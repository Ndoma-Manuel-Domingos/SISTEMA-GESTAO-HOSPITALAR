<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\ContaBancaria;
use App\Models\Caixa;
use App\Models\CentroCusto;
use App\Models\Cliente;
use App\Models\Conta;
use App\Models\Subconta;
use App\Models\Dispesa;
use App\Models\Exercicio;
use App\Models\Fornecedore;
use App\Models\Funcionario;
use App\Models\Movimento;
use App\Models\Receita;
use App\Models\OperacaoFinanceiro;
use App\Models\Orcamento;
use App\Models\Periodo;
use App\Models\TipoPagamento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;

class OrcamentoController extends Controller
{
    use TraitHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('operacao financeira')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $orcamentos = Orcamento::where('entidade_id', '=', $entidade->empresa->id)
            ->with(['exercicio', 'periodo', 'user', 'entidade'])
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => __('messages.orcamentos'),
            "descricao" => env('APP_NAME'),
            "orcamentos" => $orcamentos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.orcamentos.index', $head);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function lixeira(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('operacao financeira')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $operacoes = OperacaoFinanceiro::when($request->data_inicio, function ($query, $value) {
            $query->whereDate('date_at', '>=', Carbon::parse($value));
        })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('date_at', '<=', Carbon::parse($value));
            })
            ->when($request->tipo_movimento, function ($query, $value) {
                $query->where('type', $value);
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status_pagamento', $value);
            })
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->whereIn('type', ['R', 'D'])
            ->with(['centro_custo', 'subconta', 'fornecedor', 'cliente', 'dispesa', 'caixa', 'contabancaria', 'receita', 'user', 'entidade'])
            ->orderBy('created_at', 'desc')
            ->onlyTrashed()
            ->get();


        $head = [
            "titulo" => "Operações Financeiras",
            "descricao" => env('APP_NAME'),
            "operacoes" => $operacoes,
            "requests" => $request->all('data_inicio', 'data_final', 'tipo_movimento', 'status'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.operacao-financeira.lixeira', $head);
    }

    public function exportar(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('operacao financeira')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $operacoes = OperacaoFinanceiro::when($request->data_inicio, function ($query, $value) {
            $query->whereDate('date_at', '>=', Carbon::parse($value));
        })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('date_at', '<=', Carbon::parse($value));
            })
            ->when($request->tipo_movimento, function ($query, $value) {
                $query->where('type', $value);
            })
            ->when($request->subconta_id, function ($query, $value) {
                $query->where('subconta_id', $value);
            })
            ->when($request->centro_custo_id, function ($query, $value) {
                $query->where('centro_custo_id', $value);
            })
            ->when($request->cliente_id, function ($query, $value) {
                $query->where('cliente_id', $value);
            })
            ->when($request->fornecedor_id, function ($query, $value) {
                $query->where('fornecedor_id', $value);
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status_pagamento', $value);
            })
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->whereIn('type', ['R', 'D'])
            ->with(['centro_custo', 'subconta', 'fornecedor', 'cliente', 'dispesa', 'caixa', 'contabancaria', 'receita', 'user', 'entidade'])
            ->orderBy('created_at', 'desc')
            ->get();

        $centroCusto = CentroCusto::find($request->centro_custo_id);
        $subconta = Subconta::find($request->subconta_id);
        $cliente = Cliente::find($request->cliente_id);
        $fornecedor = Fornecedore::find($request->fornecedor_id);


        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            'titulo' => "Operações Financeiras",
            'descricao' => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "centroCusto" => $centroCusto,
            "subconta" => $subconta,
            "cliente" => $cliente,
            "fornecedor" => $fornecedor,
            "operacoes" => $operacoes,
            "requests" => $request->all('data_inicio', 'data_final', 'tipo_movimento', 'status', "centro_custo_id"),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.operacao-financeira.exportar', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transacoes(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('operacao financeira')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $hoje = Carbon::now();

        if ($request->relatorio == "contas_receber_atraso") {
            // Contas a receber - contas_receber_atraso
            $operacoes = OperacaoFinanceiro::where('type', 'R')
                ->with(['fornecedor', 'cliente', 'dispesa', 'receita'])
                ->where('status_pagamento', 'pendente')
                ->where('entidade_id', $entidade->empresa->id)
                // ->where('date_at', '<=', $hoje)

                ->when($request->data_inicio, function ($query, $value) {
                    $query->whereDate('date_at', '>=', Carbon::parse($value));
                })
                ->when($request->data_final, function ($query, $value) {
                    $query->whereDate('date_at', '<=', Carbon::parse($value));
                })
                ->when($request->tipo_movimento, function ($query, $value) {
                    $query->where('type', $value);
                })
                ->when($request->status, function ($query, $value) {
                    $query->where('status_pagamento', $value);
                })

                ->get();
        } else if ($request->relatorio == "contas_receber_mes") {
            // contas_receber_mes
            $operacoes = OperacaoFinanceiro::where('type', 'R')
                ->with(['fornecedor', 'cliente', 'dispesa', 'receita'])
                ->where('status_pagamento', 'pendente')
                ->where('entidade_id', $entidade->empresa->id)

                ->when($request->data_inicio, function ($query, $value) {
                    $query->whereDate('date_at', '>=', Carbon::parse($value));
                })
                ->when($request->data_final, function ($query, $value) {
                    $query->whereDate('date_at', '<=', Carbon::parse($value));
                })
                ->when($request->tipo_movimento, function ($query, $value) {
                    $query->where('type', $value);
                })
                ->when($request->status, function ($query, $value) {
                    $query->where('status_pagamento', $value);
                })

                ->whereMonth('date_at', $hoje->month)
                ->whereYear('date_at', $hoje->year)
                ->get();
        } else if ($request->relatorio == "contas_pagar_atraso") {
            // Contas a pagar - contas_pagar_atraso
            $operacoes = OperacaoFinanceiro::where('type', 'D')
                ->with(['fornecedor', 'cliente', 'dispesa', 'receita'])
                ->where('status_pagamento', 'pendente')
                ->where('entidade_id', $entidade->empresa->id)
                // ->where('date_at', '<=', $hoje) 

                ->when($request->data_inicio, function ($query, $value) {
                    $query->whereDate('date_at', '>=', Carbon::parse($value));
                })
                ->when($request->data_final, function ($query, $value) {
                    $query->whereDate('date_at', '<=', Carbon::parse($value));
                })
                ->when($request->tipo_movimento, function ($query, $value) {
                    $query->where('type', $value);
                })
                ->when($request->status, function ($query, $value) {
                    $query->where('status_pagamento', $value);
                })

                ->get();
        } else if ($request->relatorio == "contas_pagar_mes") {
            // contas_pagar_mes
            $operacoes = OperacaoFinanceiro::where('type', 'D')
                ->with(['fornecedor', 'cliente', 'dispesa', 'receita'])
                ->where('status_pagamento', 'pendente')
                ->where('entidade_id', $entidade->empresa->id)

                ->when($request->data_inicio, function ($query, $value) {
                    $query->whereDate('date_at', '>=', Carbon::parse($value));
                })
                ->when($request->data_final, function ($query, $value) {
                    $query->whereDate('date_at', '<=', Carbon::parse($value));
                })
                ->when($request->tipo_movimento, function ($query, $value) {
                    $query->where('type', $value);
                })
                ->when($request->status, function ($query, $value) {
                    $query->where('status_pagamento', $value);
                })

                ->whereMonth('date_at', $hoje->month)
                ->whereYear('date_at', $hoje->year)
                ->get();
        } else if ($request->relatorio == "") {
            // contas_pagar_mes
            $operacoes = OperacaoFinanceiro::with(['fornecedor', 'cliente', 'dispesa', 'receita'])
                ->when($request->data_inicio, function ($query, $value) {
                    $query->whereDate('date_at', '>=', Carbon::parse($value));
                })
                ->when($request->data_final, function ($query, $value) {
                    $query->whereDate('date_at', '<=', Carbon::parse($value));
                })
                ->when($request->tipo_movimento, function ($query, $value) {
                    $query->where('type', $value);
                })
                ->when($request->status, function ($query, $value) {
                    $query->where('status_pagamento', $value);
                })
                ->where('entidade_id', $entidade->empresa->id)
                ->get();
        }

        // Saldo atual
        $receitasPagas = OperacaoFinanceiro::where('type', 'R')->where('entidade_id', $entidade->empresa->id)->where('status_pagamento', 'pago')->sum('motante');
        $despesasPagas = OperacaoFinanceiro::where('type', 'D')->where('entidade_id', $entidade->empresa->id)->where('status_pagamento', 'pago')->sum('motante');
        $saldoAtual = $receitasPagas - $despesasPagas;

        $head = [
            "titulo" => "Transações Financeiras",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "operacoes" => $operacoes,
            "receitasPagas" => $receitasPagas,
            "despesasPagas" => $despesasPagas,
            "saldoAtual" => $saldoAtual,
            "requests" => $request->all('data_inicio', 'data_final', 'tipo_movimento', 'status', 'relatorio'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];


        return view('dashboard.operacao-financeira.transacoes', $head);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transacoes_saldos_caixas(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('operacao financeira')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $caixas = Caixa::where('entidade_id', $entidade->empresa->id)
            ->where('status_admin', 'liberado')->pluck('subconta_id');

        $saldos_caixas = OperacaoFinanceiro::with('subconta')
            ->whereIn('subconta_id', $caixas)
            ->where('status_pagamento', 'pago')
            ->where('entidade_id', $entidade->empresa->id)
            ->selectRaw("
                subconta_id,
                SUM(CASE WHEN type = 'R' THEN motante ELSE 0 END) as receita,
                SUM(CASE WHEN type = 'D' THEN motante ELSE 0 END) as despesa
            ")
            ->groupBy('subconta_id')
            ->get();


        $head = [
            "titulo" => "Transações Financeiras",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "saldos_caixas" => $saldos_caixas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.operacao-financeira.saldos-caixas', $head);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transacoes_saldos_caixas_imprimir(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('operacao financeira')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $caixas = Caixa::where('entidade_id', $entidade->empresa->id)
            ->where('status_admin', 'liberado')->pluck('subconta_id');

        $saldos_caixas = OperacaoFinanceiro::with('subconta')
            ->whereIn('subconta_id', $caixas)
            ->where('status_pagamento', 'pago')
            ->where('entidade_id', $entidade->empresa->id)
            ->selectRaw("
                subconta_id,
                SUM(CASE WHEN type = 'R' THEN motante ELSE 0 END) as receita,
                SUM(CASE WHEN type = 'D' THEN motante ELSE 0 END) as despesa
            ")
            ->groupBy('subconta_id')
            ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);


        $head = [
            "titulo" => "Transações Financeiras",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "saldos_caixas" => $saldos_caixas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];


        $pdf = PDF::loadView('dashboard.operacao-financeira.saldos-caixas-imprimir', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transacoes_saldos_bancos(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('operacao financeira')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $bancos = ContaBancaria::where('entidade_id', $entidade->empresa->id)->pluck('subconta_id');

        $saldos_bancos = OperacaoFinanceiro::with('subconta')
            ->whereIn('subconta_id', $bancos)
            ->where('status_pagamento', 'pago')
            ->where('entidade_id', $entidade->empresa->id)
            ->selectRaw("
                subconta_id,
                SUM(CASE WHEN type = 'R' THEN motante ELSE 0 END) as receita,
                SUM(CASE WHEN type = 'D' THEN motante ELSE 0 END) as despesa
            ")
            ->groupBy('subconta_id')
            ->get();

        $head = [
            "titulo" => "Transações Financeiras",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "saldos_bancos" => $saldos_bancos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];


        return view('dashboard.operacao-financeira.saldos-bancos', $head);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transacoes_saldos_bancos_imprimir(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('operacao financeira')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $bancos = ContaBancaria::where('entidade_id', $entidade->empresa->id)->pluck('subconta_id');

        $saldos_bancos = OperacaoFinanceiro::with('subconta')
            ->whereIn('subconta_id', $bancos)
            ->where('status_pagamento', 'pago')
            ->where('entidade_id', $entidade->empresa->id)
            ->selectRaw("
                subconta_id,
                SUM(CASE WHEN type = 'R' THEN motante ELSE 0 END) as receita,
                SUM(CASE WHEN type = 'D' THEN motante ELSE 0 END) as despesa
            ")
            ->groupBy('subconta_id')
            ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Transações Financeiras",
            "descricao" => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "empresa" => $entidade,
            "saldos_bancos" => $saldos_bancos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];


        $pdf = PDF::loadView('dashboard.operacao-financeira.saldos-bancos-imprimir', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transferencia(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('operacao financeira')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $caixas = Caixa::where('entidade_id', '=', $entidade->empresa->id)
            ->where('status_admin', 'liberado')->get();
        $bancos = ContaBancaria::where('entidade_id', '=', $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Transações Financeiras",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,

            "caixas" => $caixas,
            "bancos" => $bancos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];


        return view('dashboard.operacao-financeira.transferencia', $head);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transferencia_store(Request $request)
    {
        //
        $request->validate([
            'tipo_transferencia' => 'required',
            'motante' => 'required',
        ]);

        $user = auth()->user();

        if (!$user->can('operacao financeira')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $code = uniqid(time());
        $caixaActivo = Caixa::where('active', true)
            ->where('status', 'aberto')
            ->where('status_admin', 'liberado')
            ->where('user_open_id', '=', Auth::user()->id)
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->first();

        $receita = Receita::where('nome', 'Depositos')->where('type', 'R')->where('entidade_id', '=', $entidade->empresa->id)->first();
        $dispesa = Dispesa::where('nome', 'Levantamentos')->where('type', 'D')->where('entidade_id', '=', $entidade->empresa->id)->first();

        $receita_tr = Receita::where('nome', 'Transferência')->where('type', 'R')->where('entidade_id', '=', $entidade->empresa->id)->first();
        $dispesa_tr = Dispesa::where('nome', 'Transferência')->where('type', 'D')->where('entidade_id', '=', $entidade->empresa->id)->first();

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            if ($request->tipo_transferencia == "depositos") {

                $request->validate([
                    'caixa_id' => 'required',
                    'banco_id' => 'required',
                ]);

                //origem
                $caixa = Caixa::findOrFail($request->caixa_id);
                //destino
                $banco = ContaBancaria::findOrFail($request->banco_id);

                // contabilidade      
                // DEBITAR     
                Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $banco->subconta_id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'status' => true,
                    'movimento' => 'E',
                    'observacao' => "Deposito de valores - Origem Caixa: { $caixa->conta }",
                    'credito' => 0,
                    'debito' => $request->motante,
                    'code' => $code,
                    'data_at' => $request->data_operacao,
                    'entidade_id' => $entidade->empresa->id,
                ]);

                // CREDITAR
                Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $caixa->subconta_id,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                    'status' => true,
                    'movimento' => 'S',
                    'observacao' => "Saídas de valores - Destino Banco: { $banco->conta }",
                    'credito' => $request->motante,
                    'debito' => 0,
                    'code' => $code,
                    'data_at' => $request->data_emissao,
                    'entidade_id' => $entidade->empresa->id,
                ]);


                OperacaoFinanceiro::create([
                    'nome' => $dispesa->nome,
                    'status' => 'pago',
                    'motante' => $request->motante,
                    'formas' => 'C',
                    'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'subconta_id' => $caixa->subconta_id,
                    'model_id' => $dispesa->id,
                    'type' => "D",
                    'status_pagamento' => 'pago',
                    'code' => $code,
                    'descricao' => "Saídas de valores - Destino Banco: { $banco->conta }",
                    'movimento' =>  "S",
                    'date_at' => $request->data_operacao,
                    'user_id' => Auth::user()->id,
                    'user_open_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                OperacaoFinanceiro::create([
                    'nome' => $receita->nome,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'status' => 'pago',
                    'motante' => $request->motante,
                    'formas' => 'B',
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'subconta_id' => $banco->subconta_id,
                    'model_id' => $receita->id,
                    'type' => "R",
                    'status_pagamento' => 'pago',
                    'code' => $code,
                    'descricao' => "Deposito de valores - Origem Caixa: { $caixa->conta }",
                    'movimento' =>  "E",
                    'date_at' => $request->data_operacao,
                    'user_id' => Auth::user()->id,
                    'user_open_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);
            }

            if ($request->tipo_transferencia == "levantamentos") {

                $request->validate([
                    'caixa_id' => 'required',
                    'banco_id' => 'required',
                ]);

                //origem
                $caixa = Caixa::findOrFail($request->caixa_id);
                //destino
                $banco = ContaBancaria::findOrFail($request->banco_id);

                // contabilidade      
                // DEBITAR     
                Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $caixa->subconta_id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                    'status' => true,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'movimento' => 'E',
                    'observacao' => "Deposito de valores - Origem Banco: { $banco->conta }",
                    'credito' => 0,
                    'debito' => $request->motante,
                    'code' => $code,
                    'data_at' => $request->data_operacao,
                    'entidade_id' => $entidade->empresa->id,
                ]);

                // CREDITAR
                Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $banco->subconta_id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                    'status' => true,
                    'movimento' => 'S',
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'observacao' => "Saídas de valores - Destino Caixa: { $caixa->conta }",
                    'credito' => $request->motante,
                    'debito' => 0,
                    'code' => $code,
                    'data_at' => $request->data_emissao,
                    'entidade_id' => $entidade->empresa->id,
                ]);


                OperacaoFinanceiro::create([
                    'nome' => $dispesa->nome,
                    'status' => 'pago',
                    'motante' => $request->motante,
                    'formas' => 'C',
                    'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'subconta_id' => $banco->subconta_id,
                    'model_id' => $dispesa->id,
                    'type' => "D",
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'status_pagamento' => 'pago',
                    'code' => $code,
                    'descricao' => "Saídas de valores - Destino Caixa: { $caixa->conta }",
                    'movimento' =>  "S",
                    'date_at' => $request->data_operacao,
                    'user_id' => Auth::user()->id,
                    'user_open_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                OperacaoFinanceiro::create([
                    'nome' => $receita->nome,
                    'status' => 'pago',
                    'motante' => $request->motante,
                    'formas' => 'B',
                    'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'subconta_id' => $caixa->subconta_id,
                    'model_id' => $receita->id,
                    'type' => "R",
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'status_pagamento' => 'pago',
                    'code' => $code,
                    'descricao' => "Deposito de valores - Origem Banco: { $banco->conta }",
                    'movimento' =>  "E",
                    'date_at' => $request->data_operacao,
                    'user_id' => Auth::user()->id,
                    'user_open_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);
            }

            if ($request->tipo_transferencia == "e_bancos") {
                $request->validate([
                    'banco_id' => 'required',
                    'banco_destino_id' => 'required',
                ]);

                //origem
                $banco_origem = ContaBancaria::findOrFail($request->banco_id);
                //destino
                $banco_destino = ContaBancaria::findOrFail($request->banco_destino_id);

                // contabilidade      
                // CREDITAR     
                Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $banco_origem->subconta_id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                    'status' => true,
                    'movimento' => 'E',
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'observacao' => "Destino Banco: { $banco_destino->conta }",
                    'credito' => $request->motante,
                    'debito' => 0,
                    'code' => $code,
                    'data_at' => $request->data_operacao,
                    'entidade_id' => $entidade->empresa->id,
                ]);

                // DEBITAR
                Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $banco_destino->subconta_id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                    'status' => true,
                    'movimento' => 'S',
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'observacao' => "Origem Banco: { $banco_origem->conta }",
                    'credito' => 0,
                    'debito' => $request->motante,
                    'code' => $code,
                    'data_at' => $request->data_emissao,
                    'entidade_id' => $entidade->empresa->id,
                ]);

                // SAIDA
                OperacaoFinanceiro::create([
                    'nome' => $dispesa_tr->nome,
                    'status' => 'pago',
                    'motante' => $request->motante,
                    'formas' => 'C',
                    'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'subconta_id' => $banco_origem->subconta_id,
                    'model_id' => $dispesa_tr->id,
                    'type' => "D",
                    'status_pagamento' => 'pago',
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'code' => $code,
                    'descricao' => "Transferência - Destino Banco: { $banco_destino->conta }",
                    'movimento' =>  "S",
                    'date_at' => $request->data_operacao,
                    'user_id' => Auth::user()->id,
                    'user_open_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                // ENTRADA
                OperacaoFinanceiro::create([
                    'nome' => $receita_tr->nome,
                    'status' => 'pago',
                    'motante' => $request->motante,
                    'formas' => 'C',
                    'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'subconta_id' => $banco_destino->subconta_id,
                    'model_id' => $receita_tr->id,
                    'type' => "D",
                    'status_pagamento' => 'pago',
                    'code' => $code,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'descricao' => "Transferência- Origem Banco: { $banco_origem->conta }",
                    'movimento' =>  "S",
                    'date_at' => $request->data_operacao,
                    'user_id' => Auth::user()->id,
                    'user_open_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);
            }

            if ($request->tipo_transferencia == "e_caixaas") {
                $request->validate([
                    'caixa_id' => 'required',
                    'caixa_destino_id' => 'required',
                ]);

                //origem
                $caixa_origem = Caixa::findOrFail($request->caixa_id);
                //destino
                $caixa_destino = Caixa::findOrFail($request->caixa_destino_id);

                // contabilidade      
                // CREDITAR     
                Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $caixa_origem->subconta_id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                    'status' => true,
                    'movimento' => 'E',
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'observacao' => "Destino Caixa: { $caixa_destino->conta }",
                    'credito' => $request->motante,
                    'debito' => 0,
                    'code' => $code,
                    'data_at' => $request->data_operacao,
                    'entidade_id' => $entidade->empresa->id,
                ]);

                // DEBITAR
                Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $caixa_destino->subconta_id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                    'status' => true,
                    'movimento' => 'S',
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'observacao' => "Origem Caixa: { $caixa_origem->conta }",
                    'credito' => 0,
                    'debito' => $request->motante,
                    'code' => $code,
                    'data_at' => $request->data_emissao,
                    'entidade_id' => $entidade->empresa->id,
                ]);

                // SAIDA
                OperacaoFinanceiro::create([
                    'nome' => $dispesa_tr->nome,
                    'status' => 'pago',
                    'motante' => $request->motante,
                    'formas' => 'C',
                    'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'subconta_id' => $caixa_origem->subconta_id,
                    'model_id' => $dispesa_tr->id,
                    'type' => "D",
                    'status_pagamento' => 'pago',
                    'code' => $code,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'descricao' => "Transferência - Destino Caixa: { $caixa_destino->conta }",
                    'movimento' =>  "S",
                    'date_at' => $request->data_operacao,
                    'user_id' => Auth::user()->id,
                    'user_open_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                // ENTRADA
                OperacaoFinanceiro::create([
                    'nome' => $receita_tr->nome,
                    'status' => 'pago',
                    'motante' => $request->motante,
                    'formas' => 'C',
                    'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'subconta_id' => $caixa_destino->subconta_id,
                    'model_id' => $receita_tr->id,
                    'type' => "D",
                    'status_pagamento' => 'pago',
                    'code' => $code,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'descricao' => "Transferência- Origem Caixa: { $caixa_origem->conta }",
                    'movimento' =>  "S",
                    'date_at' => $request->data_operacao,
                    'user_id' => Auth::user()->id,
                    'user_open_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);
            }
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => 'Transferência realizada com sucesso!']);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('operacao financeira')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $funcionarios = Funcionario::where('entidade_id', '=', $entidade->empresa->id)->get();
        $exercicios = Exercicio::where('entidade_id', '=', $entidade->empresa->id)->get();
        $periodios = Periodo::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "funcionarios" => $funcionarios,
            "periodos" => $periodios,
            "exercicios" => $exercicios,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.orcamentos.create', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('operacao financeira')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
            'data_inicio' => 'required|date',
            'data_final' => 'required|date',
            'status' => 'required|string',
            'tipo' => 'required|string',
        ]);

        $entidade = User::with('empresa.tipo_entidade.modulos')->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $code = uniqid(time());

            Orcamento::create([
                'nome' => $request->nome,
                'status' => $request->status,
                'tipo' => $request->tipo,
                'data_inicio' => $request->data_inicio,
                'data_final' => $request->data_final,
                'responsavel_usuario_id' => $request->responsavel_usuario_id,
                'descricao' => $request->descricao,
                'codigo' => $code,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
                'exercicio_id' => $request->exercicio_id ?? $this->exercicio(),
                'periodo_id' => $request->periodo_id ?? $this->periodo(),
            ]);

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return redirect()->back()->with("success", "Dados Cadastrados com Sucesso!");
    }


    public function graficoAnual(Request $request, $ano = null)
    {
        $anoAtual = now()->year;

        if ($ano != null) {
            dd(1);
        }

        // Inicializa os dados para cada mês
        $dadosMensais = array_fill(1, 12, [
            'receita' => 0,
            'despesa' => 0,
            'saldo' => 0,
        ]);

        $entidade = User::with('empresa.tipo_entidade.modulos')->findOrFail(Auth::user()->id);

        // Totais anuais
        $totalReceita = 0;
        $totalDespesa = 0;
        $totalSaldo = 0;

        // Recupera os dados do banco
        $operacoes = OperacaoFinanceiro::where('entidade_id', $entidade->empresa->id)->where('status_pagamento', ['pago'])->whereYear('date_at', $anoAtual)->get();

        foreach ($operacoes as $operacao) {
            $mes = Carbon::parse($operacao->date_at)->month;
            if ($operacao->type === 'R') {
                $dadosMensais[$mes]['receita'] += $operacao->motante;
                $totalReceita += $operacao->motante;
            } else if ($operacao->type === 'D') {
                $dadosMensais[$mes]['despesa'] += $operacao->motante;
                $totalDespesa += $operacao->motante;
            }

            // Calcula o saldo
            $dadosMensais[$mes]['saldo'] = $dadosMensais[$mes]['receita'] - $dadosMensais[$mes]['despesa'];
        }


        // Calcula o saldo anual
        $totalSaldo = $totalReceita - $totalDespesa;


        // Retorna os dados formatados para o frontend
        return response()->json([
            'mensal' => $dadosMensais,
            'totais' => [
                'receita' => $totalReceita,
                'despesa' => $totalDespesa,
                'saldo' => $totalSaldo,
            ],
        ]);
    }

    public function graficoReceitas(Request $requst)
    {

        $entidade = User::with('empresa.tipo_entidade.modulos')->findOrFail(Auth::user()->id);

        // Consulta as receitas e soma os valores de cada categoria
        $dados = Receita::where('entidade_id', $entidade->empresa->id)->with(['operacoes' => function ($query) {
            $query->where('status_pagamento', 'pago');
        }])->where('type', 'R')
            ->get()
            ->map(function ($receita) {
                return [
                    'nome' => $receita->nome,
                    'total' => $receita->operacoes->sum('motante'),
                ];
            });

        return response()->json($dados);
    }


    public function graficoDespesas(Request $requst)
    {

        $entidade = User::with('empresa.tipo_entidade.modulos')->findOrFail(Auth::user()->id);

        // Consulta as receitas e soma os valores de cada categoria
        $dados = Dispesa::where('entidade_id', $entidade->empresa->id)->with(['operacoes' => function ($query) {
            $query->where('status_pagamento', 'pago');
        }])->where('type', 'D')
            ->get()
            ->map(function ($dispesa) {
                return [
                    'nome' => $dispesa->nome,
                    'total' => $dispesa->operacoes->sum('motante'),
                ];
            });

        return response()->json($dados);
    }


    public function graficoSaldos(Request $requst)
    {
        $anoAtual = now()->year;

        $entidade = User::with('empresa.tipo_entidade.modulos')->findOrFail(Auth::user()->id);

        // Cria uma estrutura padrão para os meses
        $meses = collect(range(1, 12))->mapWithKeys(function ($mes) {
            return [$mes => [
                'mes' => $mes,
                'total_receita' => 0,
                'total_despesa' => 0,
                'saldo' => 0
            ]];
        });

        // Busca os dados reais agrupados por mês
        $dados = OperacaoFinanceiro::selectRaw('
            MONTH(date_at) as mes,
            SUM(CASE WHEN type = "R" THEN motante ELSE 0 END) as total_receita,
            SUM(CASE WHEN type = "D" THEN motante ELSE 0 END) as total_despesa
        ')
            ->where('entidade_id', $entidade->empresa->id)
            ->where('status_pagamento', ['pago'])
            ->whereYear('date_at', $anoAtual)
            ->groupBy('mes')
            ->get();

        // Atualiza os meses padrão com os dados reais
        $meses = $meses->map(function ($item) use ($dados) {
            $mesReal = $dados->firstWhere('mes', $item['mes']);
            if ($mesReal) {
                $item['total_receita'] = $mesReal->total_receita;
                $item['total_despesa'] = $mesReal->total_despesa;
                $item['saldo'] = $mesReal->total_receita - $mesReal->total_despesa;
            }
            return $item;
        });
        return response()->json($meses->values());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //
        $user = auth()->user();

        if (!$user->can('operacao financeira')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $operacao = OperacaoFinanceiro::with(['subconta', 'fornecedor', 'cliente', 'dispesa', 'caixa', 'contabancaria', 'receita', 'user', 'entidade'])->findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        if ($operacao->type == "R") {
            $tipos = Receita::where('type', 'R')->get();
        }

        if ($operacao->type == "D") {
            $tipos = Dispesa::where('type', 'D')->get();
        }

        $caixas = Caixa::where('entidade_id', '=', $entidade->empresa->id)->get();
        $bancos = ContaBancaria::where('entidade_id', '=', $entidade->empresa->id)->get();

        $formas_pagamentos = TipoPagamento::get();
        $clientes = Cliente::where('entidade_id', '=', $entidade->empresa->id)->get();
        $fornecedores = Fornecedore::where('entidade_id', '=', $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Registro de {$request->tipo}",
            "descricao" => env('APP_NAME'),
            "tipos" => $tipos,
            "formas_pagamentos" => $formas_pagamentos,
            "clientes" => $clientes,
            "fornecedores" => $fornecedores,
            "caixas" => $caixas,
            "bancos" => $bancos,
            "operacao" => $operacao,
            // "requests" => $request->all('tipo'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.operacao-financeira.show', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function imprimir(Request $request, $id)
    {
        //
        $user = auth()->user();

        if (!$user->can('operacao financeira')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $operacao = OperacaoFinanceiro::with(['subconta', 'fornecedor', 'cliente', 'dispesa', 'caixa', 'contabancaria', 'receita', 'user', 'entidade'])->findOrFail($id);
        $itemOperacoes = OperacaoFinanceiro::with(['subconta', 'fornecedor', 'cliente', 'dispesa', 'caixa', 'contabancaria', 'receita', 'user', 'entidade'])->where('code', $operacao->code)->get();

        if ($operacao->type == "R") {
            $titulo = "REGISTRO DE RECEITA";
        }

        if ($operacao->type == "D") {
            $titulo = "REGISTRO DE DESPESA";
        }

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            'titulo' => $titulo,
            'descricao' => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "operacao" => $operacao,
            "entidade" => $entidade,
            "itemOperacoes" => $itemOperacoes,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.operacao-financeira.imprimir', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //
        $user = auth()->user();

        if (!$user->can('operacao financeira')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $orcamento = Orcamento::findOrFail($id);
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $funcionarios = Funcionario::where('entidade_id', '=', $entidade->empresa->id)->get();
        $exercicios = Exercicio::where('entidade_id', '=', $entidade->empresa->id)->get();
        $periodios = Periodo::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "funcionarios" => $funcionarios,
            "periodos" => $periodios,
            "exercicios" => $exercicios,
            "orcamento" => $orcamento,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.orcamentos.edit', $head);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('operacao financeira')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        $request->validate([
            'nome' => 'required|string',
            'data_inicio' => 'required|date',
            'data_final' => 'required|date',
            'status' => 'required|string',
            'tipo' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $orcamento = Orcamento::findOrFail($id);

            $orcamento->nome = $request->nome;
            $orcamento->status = $request->status;
            $orcamento->responsavel_usuario_id = $request->responsavel_usuario_id;
            $orcamento->exercicio_id = $request->exercicio_id;
            $orcamento->periodo_id = $request->periodo_id;
            $orcamento->descricao = $request->descricao;
            $orcamento->data_inicio = $request->data_inicio;
            $orcamento->data_final = $request->data_final;
            $orcamento->tipo = $request->tipo;

            $orcamento->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {

            dd($e);
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return redirect()->back()->with("success", "Dados Cadastrados com Sucesso!");
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function recuperar(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('operacao financeira')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        try {
            DB::beginTransaction();

            foreach ($request->ids as $item) {
                $registro = OperacaoFinanceiro::onlyTrashed()->find($item);
                if ($registro) {
                    $registro->restore();
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados recuperados com sucesso!"], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth()->user();

        if (!$user->can('eliminar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $orcamento = Orcamento::findOrFail($id);
            $orcamento->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        return response()->json(['success' => true, 'message' => "Dados Excluídos com sucesso!"], 200);
    }
}
