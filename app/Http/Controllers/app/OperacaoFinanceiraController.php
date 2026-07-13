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
use App\Models\Fornecedore;
use App\Models\Movimento;
use App\Models\Receita;
use App\Models\OperacaoFinanceiro;
use App\Models\TipoPagamento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;

class OperacaoFinanceiraController extends Controller
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

        $conta = Conta::whereIn('conta', ['41', '42', '43', '44', '45'])->where('entidade_id', $entidade->empresa->id)->pluck('id');

        $subcontas = Subconta::whereIn('conta_id', $conta)->whereIn('tipo_conta', ['M'])->where('entidade_id', $entidade->empresa->id)->get();
        $centro_custos = CentroCusto::where('entidade_id', $entidade->empresa->id)->get();
        $clientes = Cliente::where('entidade_id', $entidade->empresa->id)->get();
        $fornecedores = Fornecedore::where('entidade_id', $entidade->empresa->id)->get();

        $operacoes = OperacaoFinanceiro::when($request->data_inicio, function ($query, $value) {
            $query->whereDate('date_at', '>=', Carbon::parse($value));
        })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('date_at', '<=', Carbon::parse($value));
            })
            ->when($request->subconta_id, function ($query, $value) {
                $query->where('subconta_id', $value);
            })
            ->when($request->cliente_id, function ($query, $value) {
                $query->where('cliente_id', $value);
            })
            ->when($request->centro_custo_id, function ($query, $value) {
                $query->where('centro_custo_id', $value);
            })
            ->when($request->fornecedor_id, function ($query, $value) {
                $query->where('fornecedor_id', $value);
            })
            ->when($request->tipo_movimento, function ($query, $value) {
                $query->where('type', $value);
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status_pagamento', $value);
            })
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->whereIn('type', ['R', 'D'])
            ->with(['centro_custo', 'fornecedor', 'cliente', 'dispesa', 'caixa', 'contabancaria', 'receita', 'subconta'])
            ->orderBy('created_at', 'desc')
            ->get();


        $head = [
            "titulo" => "Operações Financeiras",
            "descricao" => env('APP_NAME'),
            "operacoes" => $operacoes,
            "clientes" => $clientes,
            "fornecedores" => $fornecedores,
            "centro_custos" => $centro_custos,
            "subcontas" => $subcontas,
            "requests" => $request->all('data_inicio', 'data_final', 'tipo_movimento', 'status', 'subconta_id', 'cliente_id', 'centro_custo_id', 'fornecedor_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.operacao-financeira.index', $head);
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
            ->when($request->utilizador, function ($query, $value) {
                $query->where('user_open_id', $value);
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
            "centroCusto" => $centroCusto,
            "subconta" => $subconta,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
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
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "empresa" => $entidade,
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
            ->where('user_open_id', '=', Auth::user()->id)
            ->where('status_admin', 'liberado')
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
                    'status' => true,
                    'movimento' => 'E',
                    'observacao' => "Deposito de valores - Origem Caixa: { $caixa->conta }",
                    'credito' => 0,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'debito' => $request->motante,
                    'code' => $code,
                    'data_at' => $request->data_operacao,
                    'entidade_id' => $entidade->empresa->id,
                ]);

                // CREDITAR
                Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $caixa->subconta_id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                    'status' => true,
                    'movimento' => 'S',
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
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
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
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
                    'status' => 'pago',
                    'motante' => $request->motante,
                    'formas' => 'B',
                    'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'subconta_id' => $banco->subconta_id,
                    'model_id' => $receita->id,
                    'type' => "R",
                    'status_pagamento' => 'pago',
                    'code' => $code,
                    'descricao' => "Deposito de valores - Origem Caixa: { $caixa->conta }",
                    'movimento' =>  "E",
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
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
                    'movimento' => 'E',
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
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
                    'status_pagamento' => 'pago',
                    'code' => $code,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
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
                    'status_pagamento' => 'pago',
                    'code' => $code,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
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
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'status_pagamento' => 'pago',
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
                    'descricao' => "Transferência- Origem Banco: { $banco_origem->conta }",
                    'movimento' =>  "S",
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
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
                    'observacao' => "Destino Caixa: { $caixa_destino->conta }",
                    'credito' => $request->motante,
                    'debito' => 0,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
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
                    'observacao' => "Origem Caixa: { $caixa_origem->conta }",
                    'credito' => 0,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
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
                    'descricao' => "Transferência - Destino Caixa: { $caixa_destino->conta }",
                    'movimento' =>  "S",
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
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
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'code' => $code,
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

        if ($request->tipo == "receita") {
            $entrada = OperacaoFinanceiro::where('entidade_id', $entidade->empresa->id)->where('type', 'R')->count() + 1;
            $tipos = Receita::where('type', 'R')->where('entidade_id', $entidade->empresa->id)->get();
            $observacao = "NOTA DE ENTRADA Nº " . $entrada;
        }

        if ($request->tipo == "dispesa") {
            $saida = OperacaoFinanceiro::where('entidade_id', $entidade->empresa->id)->where('type', 'D')->count() + 1;
            $tipos = Dispesa::where('type', 'D')->where('entidade_id', $entidade->empresa->id)->get();
            $observacao = "NOTA DE SAÍDA Nº " . $saida;
        }

        $caixas = Caixa::where('entidade_id', '=', $entidade->empresa->id)
            ->where('status_admin', 'liberado')->get();
        $bancos = ContaBancaria::where('entidade_id', '=', $entidade->empresa->id)->get();

        $formas_pagamentos = TipoPagamento::get();
        $clientes = Cliente::where('entidade_id', '=', $entidade->empresa->id)->get();
        $fornecedores = Fornecedore::where('entidade_id', '=', $entidade->empresa->id)->get();
        $centro_custos = CentroCusto::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Registro de {$request->tipo}",
            "descricao" => env('APP_NAME'),
            "tipos" => $tipos,
            "formas_pagamentos" => $formas_pagamentos,
            "clientes" => $clientes,
            "centro_custos" => $centro_custos,
            "fornecedores" => $fornecedores,
            "caixas" => $caixas,
            "bancos" => $bancos,
            "observacao" => $observacao,
            "requests" => $request->all('tipo'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.operacao-financeira.create', $head);
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
            'tipo_id' => 'required|string',
            'referencia' => 'required|string',
            'tipo_servico_id' => 'required|string',
        ]);
        
        $comprovativo = null;
            
        // if($request->tipo_servico_id !== "receita") {
        //     $request->validate([
        //         'comprovativo' => 'nullable|mimes:jpg,jpeg,png,pdf|max:5000',
        //     ]);
        //     dd(2);
        //     if ($request->hasFile('comprovativo')) {
        //         $comprovativo = $request->file('comprovativo')->store('comprovativos', 'public');
        //     }
        // }


        $entidade = User::with('empresa.tipo_entidade.modulos')->findOrFail(Auth::user()->id);

        $motante1 = 0;
        $motante2 = 0;

        $caixaActivo = Caixa::where('active', true)
            ->where('status', 'aberto')
            ->where('status_admin', 'liberado')
            ->where('user_open_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->first();

        if ($request->tipo_servico_id === "receita") {
            $request->validate([
                'forma_recebimento_id' => 'required|string',
                'data_recebimento' => 'required|string',
            ]);

            $forma = TipoPagamento::findOrFail($request->forma_recebimento_id);

            if ($forma->tipo === "NU") {
                $request->validate([
                    'caixa_id' => 'required|string',
                    'motante' => 'required|string',
                ]);

                $motante1 = $request->motante ?? 0;
            }

            if ($forma->tipo === "MB" || $forma->tipo === "DE" || $forma->tipo === "TE") {
                $request->validate([
                    'banco_id' => 'required|string',
                    'motante_banco' => 'required|string',
                ]);

                $motante2 = $request->motante_banco ?? 0;
            }

            if ($forma->tipo === "OU") {
                $request->validate([
                    'caixa_id' => 'required|string',
                    'banco_id' => 'required|string',
                    'motante' => 'required|string',
                    'motante_banco' => 'required|string',
                ]);

                $motante1 = $request->motante ?? 0;
                $motante2 = $request->motante_banco ?? 0;
            }

            $data_at = $request->data_recebimento;
            $motante = $motante1 + $motante2;
        } else {

            $request->validate([
                'forma_pagamento_id' => 'required|string',
                'data_pagamento' => 'required|string',
            ]);

            $forma = TipoPagamento::findOrFail($request->forma_pagamento_id);

            if ($forma->tipo === "NU") {
                $request->validate([
                    'caixa_id' => 'required|string',
                    'motante' => 'required|string',
                ]);

                $motante1 = $request->motante ?? 0;
            }

            if ($forma->tipo === "MB" || $forma->tipo === "DE" || $forma->tipo === "TE") {
                $request->validate([
                    'banco_id' => 'required|string',
                    'motante_banco' => 'required|string',
                ]);

                $motante2 = $request->motante_banco ?? 0;
            }

            if ($forma->tipo === "OU") {
                $request->validate([
                    'caixa_id' => 'required|string',
                    'banco_id' => 'required|string',
                    'motante' => 'required|string',
                    'motante_banco' => 'required|string',
                ]);

                $motante1 = $request->motante ?? 0;
                $motante2 = $request->motante_banco ?? 0;
            }

            $data_at = $request->data_pagamento;
            $motante = $motante1 + $motante2;
        }


        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $code = uniqid(time());
            $formaPagamento = TipoPagamento::findOrFail($request->forma_pagamento_id ?? $request->forma_recebimento_id);

            if ($formaPagamento->tipo == "NU") {
                $caixa = Caixa::find($request->caixa_id);

                if ($request->tipo_servico_id === "dispesa") {

                    $verificar_saldo = $this->saldo_conta($caixa->subconta_id);

                    if ($request->motante > $verificar_saldo) {
                        return response()->json(['message' => "Pretende realizar o pagamento utilizando os fundos do caixa: {$caixa->conta} - {$caixa->nome}. No entanto, o saldo atual não é suficiente para cobrir essa despesa. Sugerimos adicionar fundos a este caixa para prosseguir com a transação."], 404);
                    }
                }

                // finanças
                OperacaoFinanceiro::create([
                    'nome' => $request->referencia,
                    'status' => $request->status_pagamento,
                    'motante' => $request->motante,
                    'formas' => 'C',
                    'parcelado' => $request->parcelado,
                    'parcelas' => $request->parcelas,
                    'cliente_id' => $request->cliente_id,
                    'fornecedor_id' => $request->fornecedor_id,
                    'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'subconta_id' => $caixa->subconta_id,
                    'model_id' => $request->tipo_id,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'type' => $request->tipo_servico_id == "receita" ? "R" : "D",
                    'status_pagamento' => $request->status_pagamento,
                    'centro_custo_id' => $request->centro_custo_id,
                    'code' => $code,
                    'descricao' => $request->referencia,
                    'movimento' =>  $request->tipo_servico_id == "receita" ? "E" : "S",
                    'date_at' => $data_at,
                    'user_id' => Auth::user()->id,
                    'user_open_id' => Auth::user()->id, 
                    'comprovativo' => $comprovativo,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);
            }

            if ($formaPagamento->tipo == "MB" || $formaPagamento->tipo == "DE" || $formaPagamento->tipo == "TE") {
                $banco = ContaBancaria::find($request->banco_id);

                if ($request->tipo_servico_id === "dispesa") {
                    $verificar_saldo = $this->saldo_conta($banco->subconta_id);

                    if ($request->motante_banco > $verificar_saldo) {
                        return response()->json(['message' => "Pretende realizar o pagamento utilizando os fundos da conta bancária: {$banco->conta} - {$banco->nome}. No entanto, o saldo atual não é suficiente para cobrir essa despesa. Sugerimos adicionar fundos a esta conta bancária para prosseguir com a transação."], 404);
                    }
                }

                OperacaoFinanceiro::create([
                    'nome' => $request->referencia,
                    'status' => $request->status_pagamento,
                    'motante' => $request->motante_banco,
                    'formas' => 'B',
                    'parcelado' => $request->parcelado,
                    'parcelas' => $request->parcelas,
                    'cliente_id' => $request->cliente_id,
                    'fornecedor_id' => $request->fornecedor_id,
                    'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'subconta_id' => $banco->subconta_id,
                    'model_id' => $request->tipo_id,
                    'type' => $request->tipo_servico_id == "receita" ? "R" : "D",
                    'status_pagamento' => $request->status_pagamento,
                    'centro_custo_id' => $request->centro_custo_id,
                    'code' => $code,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'descricao' => $request->referencia,
                    'movimento' =>  $request->tipo_servico_id == "receita" ? "E" : "S",
                    'date_at' => $data_at,
                    'user_id' => Auth::user()->id,
                    'user_open_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);
            }

            if ($formaPagamento->tipo == "OU") {

                $caixa = Caixa::find($request->caixa_id);
                $banco = ContaBancaria::find($request->banco_id);

                if ($request->tipo_servico_id === "dispesa") {
                    $verificar_saldo1 = $this->saldo_conta($caixa->subconta_id);

                    if ($request->motante > $verificar_saldo1) {
                        return response()->json(['message' => "Pretende realizar o pagamento utilizando os fundos do caixa: {$caixa->conta} - {$caixa->nome}. No entanto, o saldo atual não é suficiente para cobrir essa despesa. Sugerimos adicionar fundos a este caixa para prosseguir com a transação."], 404);
                    }

                    $verificar_saldo = $this->saldo_conta($banco->subconta_id);

                    if ($request->motante_banco > $verificar_saldo) {
                        return response()->json(['message' => "Pretende realizar o pagamento utilizando os fundos da conta bancária: {$banco->conta} - {$banco->nome}. No entanto, o saldo atual não é suficiente para cobrir essa despesa. Sugerimos adicionar fundos a esta conta bancária para prosseguir com a transação."], 404);
                    }
                }


                OperacaoFinanceiro::create([
                    'nome' => $request->referencia,
                    'status' => $request->status_pagamento,
                    'motante' => $request->motante,
                    'formas' => 'C',
                    'parcelado' => $request->parcelado,
                    'parcelas' => $request->parcelas,
                    'cliente_id' => $request->cliente_id,
                    'fornecedor_id' => $request->fornecedor_id,
                    'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'subconta_id' => $caixa->subconta_id,
                    'model_id' => $request->tipo_id,
                    'type' => $request->tipo_servico_id == "receita" ? "R" : "D",
                    'status_pagamento' => $request->status_pagamento,
                    'centro_custo_id' => $request->centro_custo_id,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'code' => $code,
                    'descricao' => $request->referencia,
                    'movimento' =>  $request->tipo_servico_id == "receita" ? "E" : "S",
                    'date_at' => $data_at,
                    'user_id' => Auth::user()->id,
                    'user_open_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                OperacaoFinanceiro::create([
                    'nome' => $request->referencia,
                    'status' => $request->status_pagamento,
                    'motante' => $request->motante_banco,
                    'formas' => 'B',
                    'parcelado' => $request->parcelado,
                    'parcelas' => $request->parcelas,
                    'cliente_id' => $request->cliente_id,
                    'fornecedor_id' => $request->fornecedor_id,
                    'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'subconta_id' => $banco->subconta_id,
                    'model_id' => $request->tipo_id,
                    'type' => $request->tipo_servico_id == "receita" ? "R" : "D",
                    'status_pagamento' => $request->status_pagamento,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'centro_custo_id' => $request->centro_custo_id,
                    'code' => $code,
                    'descricao' => $request->referencia,
                    'movimento' =>  $request->tipo_servico_id == "receita" ? "E" : "S",
                    'date_at' => $data_at,
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

            dd($e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return redirect()->back()->with("success", "Dados Cadastrados com Sucesso!");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function entrada_valores(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('operacao financeira')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'motante_entrada' => 'required',
            'receita_id' => 'required',
            'caixa_entrada' => 'required',
            'cliente_id' => 'required',
            'descricao_entrada' => 'required',
        ]);

        $entidade = User::with('empresa.tipo_entidade.modulos')->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $code = uniqid(time());
            $caixa = Caixa::findOrFail($request->caixa_entrada);
            // finanças

            try {
                OperacaoFinanceiro::create([
                    'nome' => $request->descricao_entrada,
                    'status' => 'pago',
                    'motante' => $request->motante_entrada,
                    'formas' => 'C',
                    'parcelado' => NULL,
                    'parcelas' => NULL,
                    'cliente_id' => $request->cliente_id,
                    'code_caixa' => $caixa ? $caixa->code_caixa : NULL,
                    'status_caixa' => $caixa ? 'pendente' : 'concluido',
                    'subconta_id' => $caixa->subconta_id,
                    'model_id' => $request->receita_id,
                    'type' => "R",
                    'status_pagamento' => 'pago',
                    'centro_custo_id' => NULL,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'code' => $code,
                    'descricao' => $request->descricao_entrada,
                    'movimento' =>  "E",
                    'date_at' => date("Y-m-d"),
                    'user_id' => Auth::user()->id,
                    'user_open_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);
            } catch (\Exception $e) {
                dd($e);
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

        return redirect()->back()->with("success", "Dados Cadastrados com Sucesso!");
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saida_valores(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('operacao financeira')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'motante_saida' => 'required',
            'dispesa_id' => 'required',
            'caixa_saida' => 'required',
            'fornecedor_id' => 'required',
            'descricao_saida' => 'required',
        ]);

        $entidade = User::with('empresa.tipo_entidade.modulos')->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $code = uniqid(time());
            $caixa = Caixa::findOrFail($request->caixa_saida);
            // finanças
            OperacaoFinanceiro::create([
                'nome' => $request->descricao_saida,
                'status' => 'pago',
                'motante' => $request->motante_saida,
                'formas' => 'C',
                'parcelado' => NULL,
                'parcelas' => NULL,
                // 'cliente_id' => $request->cliente_id,
                'fornecedor_id' => $request->fornecedor_id,
                'code_caixa' => $caixa ? $caixa->code_caixa : NULL,
                'status_caixa' => $caixa ? 'pendente' : 'concluido',
                'subconta_id' => $caixa->subconta_id,
                'model_id' => $request->dispesa_id,
                'type' => "D",
                'status_pagamento' => 'pago',
                'centro_custo_id' => NULL,
                'code' => $code,
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                'descricao' => $request->descricao_saida,
                'movimento' =>  "S",
                'date_at' => date("Y-m-d"),
                'user_id' => Auth::user()->id,
                'user_open_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
                'exercicio_id' => $this->exercicio(),
                'periodo_id' => $this->periodo(),
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

        $caixas = Caixa::where('entidade_id', $entidade->empresa->id)
            ->where('status_admin', 'liberado')->get();
        $bancos = ContaBancaria::where('entidade_id', $entidade->empresa->id)->get();

        $formas_pagamentos = TipoPagamento::get();
        $clientes = Cliente::where('entidade_id', $entidade->empresa->id)->get();
        $fornecedores = Fornecedore::where('entidade_id', $entidade->empresa->id)->get();
        

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
            "operacao" => $operacao,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
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

        $operacao = OperacaoFinanceiro::findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        if ($operacao->type == "R") {
            $tipos = Receita::where('type', 'R')->get();
        }

        if ($operacao->type == "D") {
            $tipos = Dispesa::where('type', 'D')->get();
        }

        $caixas = Caixa::where('entidade_id', $entidade->empresa->id)
            ->where('status_admin', 'liberado')->get();
        $bancos = ContaBancaria::where('entidade_id', $entidade->empresa->id)->get();

        $formas_pagamentos = TipoPagamento::get();
        $clientes = Cliente::where('entidade_id', $entidade->empresa->id)->get();
        $fornecedores = Fornecedore::where('entidade_id', $entidade->empresa->id)->get();
        $centro_custos = CentroCusto::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Registro de {$request->tipo}",
            "descricao" => env('APP_NAME'),
            "tipos" => $tipos,
            "formas_pagamentos" => $formas_pagamentos,
            "clientes" => $clientes,
            "fornecedores" => $fornecedores,
            "centro_custos" => $centro_custos,
            "caixas" => $caixas,
            "bancos" => $bancos,
            "operacao" => $operacao,
            // "requests" => $request->all('tipo'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.operacao-financeira.edit', $head);
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
            'tipo_id' => 'required|string',
            'motante' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $operacao = OperacaoFinanceiro::findOrFail($id);

            $operacao->nome = $request->referencia;
            $operacao->status = $request->status_pagamento;
            $operacao->motante = $request->motante;
            $operacao->caixa_id = $request->caixa_id;
            $operacao->banco_id = $request->banco_id;
            $operacao->cliente_id = $request->cliente_id;
            $operacao->fornecedor_id = $request->fornecedor_id;
            $operacao->model_id = $request->tipo_id;
            $operacao->status_pagamento = $request->status_pagamento;
            $operacao->centro_custo_id = $request->centro_custo_id;
            $operacao->parcelado = $request->parcelado;
            $operacao->parcelas = $request->parcelas;
            $operacao->data_recebimento = $request->data_recebimento;
            $operacao->data_pagamento = $request->data_pagamento;
            $operacao->forma_recebimento_id = $request->forma_recebimento_id;
            $operacao->forma_pagamento_id = $request->forma_pagamento_id;
            $operacao->descricao = $request->descricao;
            $operacao->date_at = $request->data_pagamento ?? $request->data_recebimento;

            $operacao->update();

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

        if (!$user->can('eliminar todos') && !$user->can('eliminar dispesa')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $operacao = OperacaoFinanceiro::findOrFail($id);
            $operacao->delete();
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
