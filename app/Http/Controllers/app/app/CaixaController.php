<?php

namespace App\Http\Controllers\app\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\Caixa;
use App\Models\CartaoConsumo;
use App\Models\Categoria;
use App\Models\Classe;
use App\Models\Cliente;
use App\Models\Conta;
use App\Models\ContaBancaria;
use App\Models\ContaCliente;
use App\Models\ContaFornecedore;
use App\Models\Entidade;
use App\Models\Exercicio;
use App\Models\Contrapartida;
use App\Models\Desconto;
use App\Models\Dispesa;
use App\Models\Fornecedore;
use App\Models\Loja;
use App\Models\Marca;
use App\Models\Mesa;
use App\Models\Movimento;
use App\Models\SessaoCaixa;
use App\Models\OperacaoFinanceiro;
use App\Models\Periodo;
use App\Models\PeriodoRendimento;
use App\Models\Produto;
use App\Models\Receita;
use App\Models\Sala;
use App\Models\Subconta;
use App\Models\Subsidio;
use App\Models\TaxaIRT;
use App\Models\TipoContrato;
use App\Models\TipoCredito;
use App\Models\TipoProcessamento;
use App\Models\User;
use App\Models\UserLoja;
use App\Models\Variacao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;


use PDF;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CaixaController extends Controller
{
    //
    use TraitHelpers;

    public function caixasCreateUpdate(Request $request)
    {

        $request->validate([
            'caixa' => 'required',
        ]);

        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar caixa')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa.tipo_entidade'])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            // entidade ou empresa logada

            $v_caixa = Caixa::findOrFail($request->caixa);

            if ($v_caixa && $v_caixa->active == true && $v_caixa->status == 'aberto') {
                return response()->json(['message' => 'Não é possível abrir este caixa, pois ele já está em uso!'], 404);
            }

            $code = uniqid(time());

            // contabilidade
            Movimento::create([
                'user_id' => Auth::user()->id,
                'subconta_id' => $v_caixa->subconta_id,
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                'exercicio_id' => $this->exercicio(),
                'periodo_id' => $this->periodo(),
                'status' => true,
                'movimento' => 'E',
                'observacao' => 'Abertura do caixa',
                'credito' => 0,
                'debito' => 0,
                'code' => $code,
                'data_at' => date("Y-m-d"),
                'entidade_id' => $entidade->empresa->id,
            ]);

            // finanças
            OperacaoFinanceiro::create([
                'nome' => $v_caixa->nome,
                'status' => "pago",
                'motante' => 0,
                'formas' => 'C',
                'code_caixa' => $v_caixa->code_caixa,
                'status_caixa' => 'pendente',
                'subconta_id' => $v_caixa->subconta_id,
                'model_id' => $this->receita_padrao(),
                'type' => 'R',
                'status_pagamento' => "pago",
                'code' => $code,
                'descricao' => $v_caixa->nome,
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                'movimento' => 'E',
                'date_at' => date("Y-m-d"),
                'user_id' => Auth::user()->id,
                'user_open_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
                'exercicio_id' => $this->exercicio(),
                'periodo_id' => $this->periodo(),
            ]);

            $caixas = Caixa::where('active', true)
                ->where('user_open_id', Auth::user()->id)
                ->where('status_admin', 'liberado')
                ->where('entidade_id', '=', $entidade->empresa->id)
                ->get();

            foreach ($caixas as $item) {
                $v = Caixa::findOrFail($item->id);
                $v->active = false;
                $v->continuar_apos_login = false;
                $v->status = "fachado";
                $v->update();
            }

            $v_caixa->active = true;
            $v_caixa->status = "aberto";
            $v_caixa->continuar_apos_login = true;
            $v_caixa->user_id = Auth::user()->id;
            $v_caixa->update();


            SessaoCaixa::create([
                'user_id' => Auth::user()->id,
                'caixa_id' => $request->caixa,
                'status' => 1,
                'data_abertura' => date("Y-m-d"),
                'hora_abertura' => date("H:i:s"),
                'user_fecho' => NULL,
                'hora_fecho' => NULL,
                'data_fecho' => NULL,
                'entidade_id' => $entidade->empresa->id,
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

        if ($entidade->empresa->tipo_entidade->sigla == "REST") {
            if ($entidade->empresa->tipo_entidade->tipo_venda == "Normal") {
                return response()->json(['message' => "Caixa Aberto com sucesso", 'success' => true, 'redirect' => route('pronto-venda-mesas')]);
            } else {
                return response()->json(['message' => "Caixa Aberto com sucesso", 'success' => true, 'redirect' => route('pronto-venda')]);
            }
        } else {
            return response()->json(['message' => "Caixa Aberto com sucesso", 'success' => true, 'redirect' => route('pronto-venda')]);
        }
    }


    public function monitoramentoCaixa()
    {

        $user = auth()->user();

        if (!$user->can('abertura do caixa')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $caixaAberto = Caixa::where('active', true)
            ->where('status', 'aberto')
            ->where('status_admin', 'liberado')
            ->where('user_open_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->first();

        $caixas = Caixa::with(['user_open'])->where('status_admin', 'liberado')
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $data = date("Y-m-d");

        $movimentos = NULL;

        $entradas = 0;
        $saidas = 0;
        $saldoAtual = $entradas - $saidas;

        if ($caixaAberto) {
            $movimentos = OperacaoFinanceiro::where('entidade_id', $entidade->empresa->id)
                ->when($data, function ($query, $value) {
                    $query->whereDate('date_at', '>=', Carbon::createFromDate($value));
                })
                ->when($data, function ($query, $value) {
                    $query->whereDate('date_at', '<=', Carbon::createFromDate($value));
                })
                ->where('user_open_id', Auth::user()->id)
                ->where('code_caixa', $caixaAberto->code_caixa)
                ->where('status_caixa', 'pendente')
                ->get();


            // =====================================
            // SOMAR ENTRADAS
            // =====================================

            $entradas = $movimentos->where('type', 'R')->sum('motante');

            // =====================================
            // SOMAR SAÍDAS
            // =====================================

            $saidas = $movimentos->where('type', 'D')->sum('motante');

            // =====================================
            // SALDO FINAL
            // =====================================

            $saldoAtual = $entradas - $saidas;
        }

        $head = [
            "titulo" => "Monitormanto de caixas",
            "descricao" => env('APP_NAME'),
            "caixas" => $caixas,
            "caixaAberto" => $caixaAberto,
            "entradas" => $entradas ?? 0,
            "saidas" => $saidas ?? 0,
            "saldoAtual" => $saldoAtual ?? 0,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.caixas.monitoriamento', $head);
    }


    public function abertura_caixa()
    {
        $user = auth()->user();

        if (!$user->can('abertura do caixa')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $caixaActivo = Caixa::where('active', true)
            ->where('status', 'aberto')
            ->where('status_admin', 'liberado')
            ->where('user_open_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->first();

        if ($caixaActivo) {
            Alert::error('Erro', 'Não podes ter duas contas aberta no mesmo instante!');
            return redirect()->back();
        }

        $caixas = Caixa::where('active', false)
            ->where('status', 'fechado')
            ->where('status_admin', 'liberado')
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $head = [
            "titulo" => "Abertura de Caixa",
            "descricao" => env('APP_NAME'),
            "caixas" => $caixas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.vendas.caixas.abertura', $head);
    }

    public function abertura_caixa_create(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('abertura do caixa')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa.tipo_entidade'])->findOrFail(Auth::user()->id);

        $request->validate([
            'valor' => 'required|string',
            'caixa_id' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $code = uniqid(time());
            $caixaActivo = Caixa::findOrFail($request->caixa_id);
            if ($caixaActivo->status == "aberto") {
                SessaoCaixa::updateOrCreate(
                    [
                        'caixa_id' => $caixaActivo->id,
                        'entidade_id' => $entidade->empresa->id
                    ],
                    [
                        'status' => 1,
                        'user_id' => Auth::user()->id
                    ]
                );
                $caixaActivo->user_open_id = Auth::user()->id;
                $caixaActivo->save();
            } else {

                $caixaActivo->status = "aberto";
                $caixaActivo->active = true;
                $caixaActivo->user_open_id = Auth::user()->id;
                $caixaActivo->continuar_apos_login = true;
                $caixaActivo->update();

                // contabilidade
                Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $caixaActivo->subconta_id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                    'status' => true,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'movimento' => 'E',
                    'observacao' => 'Abertura do caixa',
                    'credito' => 0,
                    'debito' => $request->valor,
                    'code' => $code,
                    'data_at' => date("Y-m-d"),
                    'entidade_id' => $entidade->empresa->id,
                ]);

                // finanças
                OperacaoFinanceiro::create([
                    'nome' => $caixaActivo->nome,
                    'status' => "pago",
                    'motante' => $request->valor,
                    'formas' => 'C',
                    'code_caixa' => $caixaActivo->code_caixa ?? NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'subconta_id' => $caixaActivo->subconta_id,
                    'model_id' => $this->receita_padrao(),
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'type' => 'R',
                    'status_pagamento' => "pago",
                    'code' => $code,
                    'descricao' => $caixaActivo->nome,
                    'movimento' => 'E',
                    'date_at' => date("Y-m-d"),
                    'user_id' => Auth::user()->id,
                    'user_open_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                SessaoCaixa::create([
                    'user_id' => Auth::user()->id,
                    'caixa_id' => $request->caixa_id,
                    'status' => 1,
                    'data_abertura' => date("Y-m-d"),
                    'hora_abertura' => date("H:i:s"),
                    'user_fecho' => NULL,
                    'hora_fecho' => NULL,
                    'data_fecho' => NULL,
                    'entidade_id' => $entidade->empresa->id,
                ]);

                $bancoActivo = ContaBancaria::where('status', "fechado")->where('entidade_id', $entidade->empresa->id)->first();

                if ($bancoActivo) {
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $bancoActivo->subconta_id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                        'status' => true,
                        'movimento' => 'E',
                        'observacao' => 'Abertura do TPA',
                        'credito' => 0,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'debito' => $request->valor,
                        'code' => $code,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                    ]);

                    OperacaoFinanceiro::create([
                        'nome' => $bancoActivo->nome,
                        'status' => "pago",
                        'motante' => $request->valor,
                        'formas' => 'B',
                        'code_caixa' => $caixaActivo->code_caixa ?? NULL,
                        'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                        'subconta_id' => $bancoActivo->subconta_id,
                        'model_id' => $this->receita_padrao(),
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'type' => 'R',
                        'status_pagamento' => "pago",
                        'code' => $code,
                        'descricao' => $bancoActivo->nome,
                        'movimento' => 'E',
                        'date_at' => date("Y-m-d"),
                        'user_id' => Auth::user()->id,
                        'user_open_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);

                    $bancoActivo->status = "aberto";
                    $bancoActivo->active = true;
                    $bancoActivo->user_open_id = Auth::user()->id;
                    $bancoActivo->update();
                }
            }


            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        if ($entidade->empresa->tipo_entidade->sigla == "REST") {
            return response()->json(['message' => "Caixa Aberto com sucesso", 'success' => true, 'redirect' => route('mesas.visualizacao-mesas')]);
        } else {
            if ($entidade->empresa->tipo_pronto_venda == "Lista") {
                return response()->json(['message' => "Caixa Aberto com sucesso", 'success' => true, 'redirect' => route('pos.index')]);
            }
            if ($entidade->empresa->tipo_pronto_venda == "Grelha") {
                return response()->json(['message' => "Caixa Aberto com sucesso", 'success' => true, 'redirect' => route('pronto-venda')]);
            }
        }
    }

    public function entrada_dinheiro_caixa()
    {
        $user = auth()->user();

        if (!$user->can('entrada valor no caixa')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $contas = Conta::where('conta', '45')->where('entidade_id', '=', $entidade->empresa->id)->pluck('id');
        $subcontas = Subconta::with(['conta'])->whereIn('conta_id', $contas)->get();

        $clientes = Cliente::where('entidade_id', '=', $entidade->empresa->id)->get();
        $fornecedores = Fornecedore::where('entidade_id', '=', $entidade->empresa->id)->get();
        $caixas = Caixa::where('entidade_id', $entidade->empresa->id)
            ->where('status_admin', 'liberado')->get();
        $bancos = ContaBancaria::where('entidade_id', $entidade->empresa->id)->get();


        $contas_proveitos = Conta::whereIn('conta', ['61', '62', '63'])->where('entidade_id', '=', $entidade->empresa->id)->pluck('id');
        $proveitos = Subconta::with(['conta'])->whereIn('conta_id', $contas_proveitos)->orderBy('numero', 'asc')->get();

        $contrapartia = Contrapartida::with(['subconta'])->where('entidade_id', '=', $entidade->empresa->id)->get();

        $tipos_creditos = TipoCredito::where('entidade_id', '=', $entidade->empresa->id)->get();
        // $proveitos = Receita::where('type', 'R')->where('entidade_id', '=', $entidade->empresa->id)->get();

        $exercicios = Exercicio::where('id', $this->exercicio())->get();
        $periodos = Periodo::where('exercicio_id', '=', $this->exercicio())->get();

        $head = [
            "titulo" => "Entrada de dinheiro no Caixa",
            "descricao" => env('APP_NAME'),
            "categorias" => Categoria::with('produtos.marca', 'produtos.variacao')->where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "loja" => Entidade::findOrFail($entidade->empresa->id),
            "subcontas" => $subcontas,
            "contrapartias" => $contrapartia,
            "tipos_creditos" => $tipos_creditos,
            "exercicios" => $exercicios,
            "periodos" => $periodos,
            "proveitos" => $proveitos,
            "clientes" => $clientes,
            "fornecedores" => $fornecedores,
            "caixas" => $caixas,
            "bancos" => $bancos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.vendas.caixas.entrada-dinheiro', $head);
    }

    public function entrada_dinheiro_caixa_create(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('entrada valor no caixa')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'montante' => 'required',
            'tipo_movimento_id' => 'required',
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $code = uniqid(time());
            // $tipo_proveito = Receita::findOrFail($request->tipo_proveito_id);


            if ($request->tipo_movimento_id == "C") {

                $subconta = Subconta::findOrFail($request->contrapartida_id);
                $fornecedor = Fornecedore::findOrFail($request->fornecedor_id);
                $subconta_fornecedor = Subconta::where('code', $fornecedor->code)->firstOrFail();

                if ($request->marcar_como == "sim") {
                    $status = "pago";
                } else {
                    $status = "pendente";
                }

                if ($request->forma_pagamento_id == "") {
                    return redirect()->back()->with('danger', 'Deves selecionar uma forma de pagamento da factura!');
                }

                if ($request->forma_pagamento_id == "NU") {
                    if ($request->caixa_id == "") {
                        return redirect()->back()->with('danger', 'Deves selecionar o caixa onde será retirado o valor para o pagamento da factura!');
                    }
                    $subconta_saida = Subconta::where('code', $request->caixa_id)->first();
                    $formas = "C";
                }
                if ($request->forma_pagamento_id == "MB") {
                    if ($request->banco_id == "") {
                        return redirect()->back()->with('danger', 'Deves selecionar o banco onde será retirado o valor para o pagamento da factura!');
                    }
                    $subconta_saida = Subconta::where('code', $request->banco_id)->first();
                    $formas = "B";
                }

                OperacaoFinanceiro::create([
                    'nome' => $request->observacao ?? "PAGAMENTO DE {$subconta->nome}",
                    'status' => $status,
                    'formas' => $formas,
                    'motante' => $request->montante,
                    'subconta_id' => $subconta->id,
                    'fornecedor_id' => $fornecedor->id,
                    'model_id' => 12,
                    'type' => 'D',
                    'parcelado' => "N",
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'status_pagamento' => $status,
                    'code' => $code,
                    'descricao' => $request->observacao ?? "PAGAMENTO DE {$subconta->nome}",
                    'movimento' => "S",
                    'date_at' => $request->date_at,
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $request->exercicio_id,
                    'periodo_id' => 13,
                ]);

                if ($request->operacao_id == "A") {

                    $conta_encargo_pagar = Subconta::where('numero', ENV('ENCARGOS_A_PAGAR'))->first();

                    $total_parcela = count($request->periodo_id);

                    foreach ($request->periodo_id as $item) {

                        $valor_parcela = $request->montante / $total_parcela;

                        ## - creditamos encargos a pagar
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $conta_encargo_pagar->id,
                            'status' => true,
                            'movimento' => 'S',
                            'credito' => $valor_parcela,
                            'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'debito' => 0,
                            'observacao' => $request->observacao,
                            'code' => $code,
                            'data_at' => $request->date_at,
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $request->exercicio_id,
                            'periodo_id' =>  $item,
                        ]);

                        ## debitamos na conta dos serviço a ser pago ou seja a contrapartida
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta->id,
                            'status' => true,
                            'movimento' => 'E',
                            'credito' => 0,
                            'debito' => $valor_parcela,
                            'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'observacao' => $request->observacao,
                            'code' => $code,
                            'data_at' => $request->date_at,
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $request->exercicio_id,
                            'periodo_id' =>  $item,
                        ]);
                    }

                    ## MOMENTO DO PAGAMENTO

                    ## vamos creditar no caixa onde esta sair o dinheiro
                    if ($request->marcar_como == "sim") {
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta_saida->id,
                            'status' => true,
                            'movimento' => 'S',
                            'credito' => $request->montante ?? 0,
                            'debito' => 0,
                            'observacao' => $request->observacao,
                            'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'code' => $code,
                            'data_at' => $request->date_at,
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $request->exercicio_id,
                            'periodo_id' => 13,
                        ]);
                    }

                    ## vamos anula a conta de custo ou seja encargos a pagar
                    $movimeto = Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $conta_encargo_pagar->id,
                        'status' => true,
                        'movimento' => 'E',
                        'credito' => 0,
                        'debito' => $request->montante ?? 0,
                        'observacao' => $request->observacao,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'code' => $code,
                        'data_at' => $request->date_at,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $request->exercicio_id,
                        'periodo_id' => 13,
                    ]);

                    // Regitra dados com o fornecedor - SAIDA
                    $movimeto = Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta_fornecedor->id,
                        'status' => true,
                        'movimento' => 'S',
                        'credito' => $request->montante ?? 0,
                        'debito' => 0,
                        'observacao' => $request->observacao,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'code' => $code,
                        'data_at' => $request->date_at,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $request->exercicio_id,
                        'periodo_id' => 13,
                    ]);

                    if ($request->marcar_como == "sim") {
                        // Regitra dados com o fornecedor ENTRADA
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta_fornecedor->id,
                            'status' => true,
                            'movimento' => 'E',
                            'credito' => 0,
                            'debito' => $request->montante ?? 0,
                            'observacao' => $request->observacao,
                            'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'code' => $code,
                            'data_at' => $request->date_at,
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $request->exercicio_id,
                            'periodo_id' => 13,
                        ]);
                    }
                }

                if ($request->operacao_id == "D") {

                    $subconta_ = Subconta::where('numero', ENV('ENCARGOS_A_REPARTIR_POR_PERIODO_FUTURO'))->first();

                    $total_parcela = count($request->periodo_id);

                    foreach ($request->periodo_id as $item) {

                        $valor_parcela = $request->montante / $total_parcela;

                        ## - creditamos encargos a pagar
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta_->id,
                            'status' => true,
                            'movimento' => 'S',
                            'credito' => $valor_parcela ?? 0,
                            'debito' => 0,
                            'observacao' => $request->observacao,
                            'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'code' => $code,
                            'data_at' => $request->date_at,
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $request->exercicio_id,
                            'periodo_id' => $item,
                        ]);

                        ## debitamos na conta dos serviço a ser pago ou seja a contrapartida
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta->id,
                            'status' => true,
                            'movimento' => 'E',
                            'credito' => 0,
                            'debito' => $valor_parcela ?? 0,
                            'observacao' => $request->observacao,
                            'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'code' => $code,
                            'data_at' => $request->date_at,
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $request->exercicio_id,
                            'periodo_id' => $item,
                        ]);
                    }

                    ## vamos creditar no caixa onde esta sair o dinheiro
                    $movimeto = Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $request->subconta_id,
                        'status' => true,
                        'movimento' => 'S',
                        'credito' => $request->montante ?? 0,
                        'debito' => 0,
                        'observacao' => $request->observacao,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'code' => $code,
                        'data_at' => $request->date_at,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $request->exercicio_id,
                        'periodo_id' => 13,
                    ]);

                    ## vamos anula a conta de custo ou seja Encargos a repartir por períodos futuros
                    $movimeto = Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta_->id,
                        'status' => true,
                        'movimento' => 'E',
                        'credito' => 0,
                        'debito' => $request->montante ?? 0,
                        'observacao' => $request->observacao,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'code' => $code,
                        'data_at' => $request->date_at,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $request->exercicio_id,
                        'periodo_id' => 13,
                    ]);
                }
            } else if ($request->tipo_movimento_id == "D") {

                $valor_total_factura = 0;

                foreach ($request->tipo_proveito_id as $item) {
                    $subconta_iva = Subconta::where('numero', ENV('IVA_LIQUIDADO'))->first();
                    $proveito = Subconta::findOrFail($item);
                    $produto_servico = Produto::where('code', $proveito->code)->first();

                    if ($request->operacao_id == "A") {
                    }

                    if ($request->operacao_id == "D") {

                        $subconta_ = Subconta::where('numero', ENV('PROVEITOS_A_REPARTIR_POR_PERIDOS_FUTUROS'))->first();

                        $total_parcela = count($request->periodo_id);
                    }

                    if ($produto_servico) {
                        // caso o serviço/produto cobrar IVA
                        if ($produto_servico->taxa != 0) {
                            if ($subconta_iva) {

                                ## creditar na conta proveito - 61/62/63/65 - ou seja diminuir o valor sem o iva
                                $movimeto = Movimento::create([
                                    'user_id' => Auth::user()->id,
                                    'subconta_id' => $proveito->id,
                                    'status' => true,
                                    'movimento' => 'S',
                                    'credito' => $produto_servico->preco ?? 0,
                                    'debito' => 0,
                                    'observacao' => $request->observacao,
                                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                                    'code' => $code,
                                    'data_at' => $request->date_at,
                                    'entidade_id' => $entidade->empresa->id,
                                    'exercicio_id' => $request->exercicio_id,
                                    'periodo_id' => $request->periodo_id,
                                ]);

                                ## creditar na conta do IVA LIQUIDADO - 34.5.3.1
                                $movimeto = Movimento::create([
                                    'user_id' => Auth::user()->id,
                                    'subconta_id' => $subconta_iva->id,
                                    'status' => true,
                                    'movimento' => 'S',
                                    'credito' => ($produto_servico->preco_venda ?? 0) - ($produto_servico->preco ?? 0),
                                    'debito' => 0,
                                    'observacao' => $request->observacao,
                                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                                    'code' => $code,
                                    'data_at' => $request->date_at,
                                    'entidade_id' => $entidade->empresa->id,
                                    'exercicio_id' => $request->exercicio_id,
                                    'periodo_id' => $request->periodo_id,
                                ]);

                                ## creditar e debitar na conta 31 ou seja preciso aumentar a divida do clientes e depois liquidar da mesma divida
                                ## START
                                $movimeto = Movimento::create([
                                    'user_id' => Auth::user()->id,
                                    'subconta_id' => $request->cliente_id,
                                    'status' => true,
                                    'movimento' => 'E',
                                    'credito' => 0,
                                    'debito' => $produto_servico->preco_venda ?? 0,
                                    'observacao' => $request->observacao,
                                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                                    'code' => $code,
                                    'data_at' => $request->date_at,
                                    'entidade_id' => $entidade->empresa->id,
                                    'exercicio_id' => $request->exercicio_id,
                                    'periodo_id' => $request->periodo_id,
                                ]);

                                $movimeto = Movimento::create([
                                    'user_id' => Auth::user()->id,
                                    'subconta_id' => $request->cliente_id,
                                    'status' => true,
                                    'movimento' => 'E',
                                    'credito' => $produto_servico->preco_venda ?? 0,
                                    'debito' => 0,
                                    'observacao' => $request->observacao,
                                    'code' => $code,
                                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                                    'data_at' => $request->date_at,
                                    'entidade_id' => $entidade->empresa->id,
                                    'exercicio_id' => $request->exercicio_id,
                                    'periodo_id' => $request->periodo_id,
                                ]);
                                ## - END
                                ## vamor aumentar o valor do caixa - 45/43
                                $movimeto = Movimento::create([
                                    'user_id' => Auth::user()->id,
                                    'subconta_id' => $request->subconta_id,
                                    'status' => true,
                                    'movimento' => 'E',
                                    'credito' => 0,
                                    'debito' => $produto_servico->preco_venda ?? 0,
                                    'observacao' => $request->observacao,
                                    'code' => $code,
                                    'data_at' => $request->date_at,
                                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                                    'entidade_id' => $entidade->empresa->id,
                                    'exercicio_id' => $request->exercicio_id,
                                    'periodo_id' => $request->periodo_id,
                                ]);
                            } else {
                                ## a conta do iva não esta cadastrada
                            }
                        } else {
                            ## caso o serviço/produto não cobra o iva ou

                            ## creditar na conta proveito - 61/62/63/65 - ou seja diminuir o valor sem o iva
                            $movimeto = Movimento::create([
                                'user_id' => Auth::user()->id,
                                'subconta_id' => $proveito->id,
                                'status' => true,
                                'movimento' => 'S',
                                'credito' => $produto_servico->preco ?? 0,
                                'debito' => 0,
                                'observacao' => $request->observacao,
                                'code' => $code,
                                'data_at' => $request->date_at,
                                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                                'entidade_id' => $entidade->empresa->id,
                                'exercicio_id' => $request->exercicio_id,
                                'periodo_id' => $request->periodo_id,
                            ]);

                            ## creditar e debitar na conta 31 ou seja preciso aumentar a divida do clientes e depois liquidar da mesma divida
                            ## START
                            $movimeto = Movimento::create([
                                'user_id' => Auth::user()->id,
                                'subconta_id' => $request->cliente_id,
                                'status' => true,
                                'movimento' => 'E',
                                'credito' => 0,
                                'debito' => $produto_servico->preco_venda ?? 0,
                                'observacao' => $request->observacao,
                                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                                'code' => $code,
                                'data_at' => $request->date_at,
                                'entidade_id' => $entidade->empresa->id,
                                'exercicio_id' => $request->exercicio_id,
                                'periodo_id' => $request->periodo_id,
                            ]);

                            $movimeto = Movimento::create([
                                'user_id' => Auth::user()->id,
                                'subconta_id' => $request->cliente_id,
                                'status' => true,
                                'movimento' => 'E',
                                'credito' => $produto_servico->preco_venda ?? 0,
                                'debito' => 0,
                                'observacao' => $request->observacao,
                                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                                'code' => $code,
                                'data_at' => $request->date_at,
                                'entidade_id' => $entidade->empresa->id,
                                'exercicio_id' => $request->exercicio_id,
                                'periodo_id' => $request->periodo_id,
                            ]);
                            ## - END
                            ## vamor aumentar o valor do caixa - 45/43
                            $movimeto = Movimento::create([
                                'user_id' => Auth::user()->id,
                                'subconta_id' => $request->subconta_id,
                                'status' => true,
                                'movimento' => 'E',
                                'credito' => 0,
                                'debito' => $produto_servico->preco_venda ?? 0,
                                'observacao' => $request->observacao,
                                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                                'code' => $code,
                                'data_at' => $request->date_at,
                                'entidade_id' => $entidade->empresa->id,
                                'exercicio_id' => $request->exercicio_id,
                                'periodo_id' => $request->periodo_id,
                            ]);
                        }
                        $valor_total_factura += $produto_servico->preco_venda;
                    } else {
                        ## Servico o produto não cadastrado correntamente
                    }

                    if ($produto_servico) {
                        if ($produto_servico->tipo == "S") {
                            OperacaoFinanceiro::create([
                                'nome' => "PRESTAÇÃO DE SERVIÇO",
                                'status' => "pago",
                                'motante' => $produto_servico->preco_venda,
                                'subconta_id' => $request->subconta_id,
                                'cliente_id' => $request->cliente_id,
                                'model_id' => 4,
                                'type' => 'R',
                                'parcelado' => "N",
                                'status_pagamento' => "pago",
                                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                                'code' => $code,
                                'descricao' => "PRESTAÇÃO DE SERVIÇO",
                                'movimento' => "E",
                                'date_at' => $request->date_at,
                                'user_id' => Auth::user()->id,
                                'entidade_id' => $entidade->empresa->id,
                                'exercicio_id' => $request->exercicio_id,
                                'periodo_id' => $request->periodo_id,
                            ]);
                        } else if ($produto_servico->tipo == "P") {
                            OperacaoFinanceiro::create([
                                'nome' => "VENDA DE PRODUTOS",
                                'status' => "pago",
                                'motante' => $produto_servico->preco_venda,
                                'subconta_id' => $request->subconta_id,
                                'cliente_id' => $request->cliente_id,
                                'model_id' => 3,
                                'type' => 'R',
                                'parcelado' => "N",
                                'status_pagamento' => "pago",
                                'code' => $code,
                                'descricao' => "VENDA DE PRODUTOS",
                                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                                'movimento' => "E",
                                'date_at' => $request->date_at,
                                'user_id' => Auth::user()->id,
                                'entidade_id' => $entidade->empresa->id,
                                'exercicio_id' => $request->exercicio_id,
                                'periodo_id' => $request->periodo_id,
                            ]);
                        } else {
                            OperacaoFinanceiro::create([
                                'nome' => "OUTRAS RECEITAS",
                                'status' => "pago",
                                'motante' => $produto_servico->preco_venda,
                                'subconta_id' => $request->subconta_id,
                                'cliente_id' => $request->cliente_id,
                                'model_id' => 7,
                                'type' => 'R',
                                'parcelado' => "N",
                                'status_pagamento' => "pago",
                                'code' => $code,
                                'descricao' => "OUTRAS RECEITAS",
                                'movimento' => "E",
                                'date_at' => $request->date_at,
                                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                                'user_id' => Auth::user()->id,
                                'entidade_id' => $entidade->empresa->id,
                                'exercicio_id' => $request->exercicio_id,
                                'periodo_id' => $request->periodo_id,
                            ]);
                        }
                    }
                }
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

        return redirect()->back()->with('success', "Operação realizada com sucesso!");
        // return redirect()->route('nota-de-movimento', $movimeto->code);

    }

    public function saida_dinheiro_caixa()
    {
        $user = auth()->user();

        if (!$user->can('saida valor no caixa')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $caixaActivo = Caixa::where('active', true)
            ->where('status', 'aberto')
            ->where('status_admin', 'liberado')
            ->where('user_open_id', '=', Auth::user()->id)
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->first();

        if (!$caixaActivo) {
            Alert::error('Erro', 'Verifica se tens um caixa aberto, por favor!');
            return redirect()->back();
        }

        $caixas = Caixa::where('active', true)->where('status', 'aberto')
            ->where('status_admin', 'liberado')->where('entidade_id', '=', $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Saída de dinheiro no Caixa",
            "descricao" => env('APP_NAME'),
            "categorias" => Categoria::with('produtos.marca', 'produtos.variacao')->where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "loja" => Entidade::findOrFail($entidade->empresa->id),
            "caixaActivo" => $caixaActivo,
            "caixas" => $caixas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.vendas.caixas.saida-dinheiro', $head);
    }

    public function saida_dinheiro_caixa_create(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('saida valor no caixa')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'montante' => 'required',
            'caixa_id' => 'required',
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $numero = Movimento::where('entidade_id', $entidade->empresa->id)->where('movimento', 'S')->count() + 1;

        try {
            DB::beginTransaction();

            $caixaActivo = Caixa::findOrFail($request->caixa_id);

            // Realizar operações de banco de dados aqui
            $code = uniqid(time());
            $movimeto = Movimento::create([
                'user_id' => Auth::user()->id,
                'caixa_id' => $caixaActivo->id,
                'status' => true,
                'movimento' => 'S',
                'numero' => "NOTA Nº {$numero}/{$entidade->empresa->ano_factura}",
                'credito' => $request->montante,
                'debito' => 0,
                'observacao' => $request->observacao,
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                'code' => $code,
                'code_caixa' => $caixaActivo->code,
                'status_caixa' => 1,
                'forma_movimento' => "NU",
                'data_at' => date("Y-m-d"),
                'entidade_id' => $entidade->empresa->id,
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

        return redirect()->route('nota-de-movimento', $movimeto->code);
    }

    public function movimentos_imprimir(Request $request)
    {

        $movimento = SessaoCaixa::with(['user', 'caixa'])
            ->findOrFail($request->id_imprimir);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Movimento do caixa Detalhado",
            "descricao" => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "loja" => Entidade::findOrFail($entidade->empresa->id),
            "movimento" => $movimento,
            "empresa" => $empresa,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.vendas.caixas.movimentos-detalhe-pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function fechamento_caixa($id = null)
    {
        $user = auth()->user();

        if (!$user->can('fecho do caixa')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with('empresa.tipo_entidade.modulos')->findOrFail(Auth::user()->id);

        $caixaActivo = Caixa::find($id);
        if (!$caixaActivo) {
            return response()->json(['message' => 'Verificar o caixa que pretendes fechar, por favor'], 404);
        }
        if ($caixaActivo->status == "fechado" && $caixaActivo->active == false) {
            return redirect()->route('dashboard');
        }

        $credito = 0;
        $debito = 0;

        $multicaixa = 0;
        $multicaixa_credito = 0;
        $multicaixa_debito = 0;

        $numerorio = 0;
        $numerorio_credito = 0;
        $numerorio_debito = 0;

        $duplo = 0;
        $duplo_credito = 0;
        $duplo_debito = 0;

        $movimentos = OperacaoFinanceiro::where('entidade_id', $entidade->empresa->id)
            // ->when($data, function ($query, $value) {
            //     $query->whereDate('date_at', '>=', Carbon::createFromDate($value));
            // })
            // ->when($data, function ($query, $value) {
            //     $query->whereDate('date_at', '<=', Carbon::createFromDate($value));
            // })
            ->where('user_open_id', Auth::user()->id)
            ->where('code_caixa', $caixaActivo->code_caixa)
            ->where('status_caixa', 'pendente')
            ->get();

        foreach ($movimentos as $item) {

            if ($item->formas == "C") {
                if ($item->type == "R") {
                    $numerorio_debito += $item->motante;
                }
                if ($item->type == "D") {
                    $numerorio_credito += $item->motante;
                }
            }

            if ($item->formas == "B") {

                if ($item->type == "R") {
                    $multicaixa_debito += $item->motante;
                }
                if ($item->type == "D") {
                    $multicaixa_credito += $item->motante;
                }
            }

            if ($item->formas == "O") {
                if ($item->type == "R") {
                    $duplo_debito += $item->motante;
                }
                if ($item->type == "D") {
                    $duplo_credito += $item->motante;
                }
            }


            if ($item->type == "R") {
                $debito += $item->motante;
            }

            if ($item->type == "D") {
                $credito += $item->motante;
            }
        }

        $multicaixa = $multicaixa_debito - $multicaixa_credito;
        $numerorio = $numerorio_debito - $numerorio_credito;
        $duplo = $duplo_debito - $duplo_credito;

        $head = [
            "titulo" => "Fecho caixa",
            "descricao" => env('APP_NAME'),
            "categorias" => Categoria::with('produtos.marca', 'produtos.variacao')
                ->where([
                    ['entidade_id', '=', $entidade->empresa->id],
                ])
                ->get(),
            "empresa" => Entidade::findOrFail($entidade->empresa->id),
            "caixaActivo" => $caixaActivo,
            "movimentos" => $movimentos,

            "credito" => $credito,
            "debito" => $debito,

            "multicaixa" => $multicaixa,
            "numerorio" => $numerorio,
            "duplo" => $duplo,

            "multicaixa_credito" => $multicaixa_credito,
            "multicaixa_debito" => $multicaixa_debito,
            "numerorio_credito" => $numerorio_credito,
            "numerorio_debito" => $numerorio_debito,
            "duplo_credito" => $duplo_credito,
            "duplo_debito" => $duplo_debito,

            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.vendas.caixas.fecho', $head);
    }

    public function fechamento_caixa_create(Request $request)
    {
        $user = auth()->user();
        if (!$user->can('fecho do caixa')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'caixa_id' => 'required',
        ]);

        try {
            // Inicia a transação
            DB::beginTransaction();

            $entidade = User::with('empresa.tipo_entidade.modulos')->findOrFail(Auth::user()->id);

            $caixaActivo = Caixa::findOrFail($request->caixa_id);

            if (!$caixaActivo) {
                return response()->json(['message' => "Verifica se tens um caixa aberto, por favor!"], 404);
            }

            $sessao = SessaoCaixa::where('entidade_id', $entidade->empresa->id)
                ->where('status', 1)
                ->where('user_id', Auth::user()->id)
                ->where('caixa_id', $request->caixa_id)
                ->first();

            if (!$sessao) {
                return response()->json(['message' => "Verifica se tens um caixa aberto, por favor!"], 404);
            }

            $caixa_aberto = SessaoCaixa::findOrFail($sessao->id);
            $caixa_aberto->data_fecho = date("Y-m-d");
            $caixa_aberto->hora_fecho = date("H:i:s");
            $caixa_aberto->user_fecho = Auth::user()->id;
            $caixa_aberto->update();

            $movimentos = OperacaoFinanceiro::where('entidade_id', $entidade->empresa->id)
                ->when($caixa_aberto->data_abertura, function ($query, $value) {
                    $query->whereDate('date_at', '>=', Carbon::createFromDate($value));
                })
                ->when($caixa_aberto->data_fecho, function ($query, $value) {
                    $query->whereDate('date_at', '<=', Carbon::createFromDate($value));
                })
                ->where('user_open_id', Auth::user()->id)
                ->where('code_caixa', $caixaActivo->code_caixa)
                ->where('status_caixa', 'pendente')
                ->get();

            foreach ($movimentos as $item) {
                $update = OperacaoFinanceiro::findOrFail($item->id);
                $update->status_caixa = "concluido";
                $update->update();
            }

            $statusCaixa = Caixa::findOrFail($caixaActivo->id);
            $statusCaixa->status = "fechado";
            $statusCaixa->active = false;
            $statusCaixa->user_open_id = NULL;
            $statusCaixa->user_close_id = Auth::user()->id;
            $caixaActivo->continuar_apos_login = false;
            $statusCaixa->update();

            $bancoActivo = ContaBancaria::where('active', true)
                ->where('status', 'aberto')
                ->where('user_open_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            if ($bancoActivo) {
                $bancoActivo->status = "fechado";
                $bancoActivo->active = false;
                $bancoActivo->user_open_id = NULL;
                $bancoActivo->user_close_id = Auth::user()->id;
                $bancoActivo->update();
            }

            if ($entidade->empresa->tipo_venda !== "Normal") {

                $cartoes = CartaoConsumo::where('nome', $request->pin)->where('entidade_id', $entidade->empresa->id)->get();
                foreach ($cartoes as $item) {
                    $up = CartaoConsumo::findOrFail($item->id);
                    $up->saldo = 0;
                    $up->status = "N";
                    $up->update();
                }
            }

            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger("Error", $e->getMessage());
            return redirect()->route("register")->with("danger", $e->getMessage());
        }

        #PAREI AQUI ESTA BEN
        return response()->json(['success' => true, 'redirect' => route('contabilidade-diarios-pdf')]);
    }

    public function continuar_caixa_create()
    {
        $user = auth()->user();
        if (!$user->can('fecho do caixa')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $caixaActivo = Caixa::where('active', true)
            ->where('continuar_apos_login', false)
            ->where('status', 'aberto')
            ->where('status_admin', 'liberado')
            ->where('user_open_id', '=', Auth::user()->id)
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->first();

        if ($caixaActivo) {
            $update = Caixa::findOrFail($caixaActivo->id);
            $update->continuar_apos_login = true;
            $update->update();
        }

        return response()->json(['caixaActivo' => $caixaActivo], 200);
    }

    public function aceitoConfigurarSistema(Request $request)
    {
        $entidade = User::with('empresa.tipo_entidade.modulos')->findOrFail(Auth::user()->id);

        $ano = $ano ?? date('Y');

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            //******************************************** */

            //**************CRIAR SALA AUTOMATICAMENTE ***** */

            $users = User::findOrFail(Auth::user()->id);
            $users->login_access = true;
            $users->update();

            //******************************************** */

            $role = Role::where('name', "{$entidade->empresa->sigla} - Administrador Geral")
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            if ($role) {
                $permissions = Permission::get();
                foreach ($permissions as $item) {
                    $permission = Permission::findById($item->id);
                    $role->givePermissionTo($permission);
                }
            }

            if ($entidade->empresa->tipo_entidade->sigla == 'HOSP') {
                Role::firstOrCreate(['name' => "{$entidade->empresa->sigla} - Medico", 'entidade_id', $entidade->empresa->id]);
                Role::firstOrCreate(['name' => "{$entidade->empresa->sigla} - Enfermeiro", 'entidade_id', $entidade->empresa->id]);
                Role::firstOrCreate(['name' => "{$entidade->empresa->sigla} - Tecnico", 'entidade_id', $entidade->empresa->id]);
            }

            $verificar_sala = Sala::where('nome', "Sala Principal")
                ->where('status', 'activo')
                ->where('solicitar_ocupacao', true)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            if (!$verificar_sala) {
                $criar_sala = Sala::create([
                    'nome' => "Sala Principal",
                    'status' => 'activo',
                    'solicitar_ocupacao' => true,
                    'entidade_id' => $entidade->empresa->id,
                ]);
            } else {
                $criar_sala = $verificar_sala;
            }

            //******************************************** */

            //******************************************** */
            //**************CRIAR MESAS AUTOMATICAMENTE ***** */
            for ($i = 1; $i <= 5; $i++) {
                $verificar_mesa = Mesa::where('nome', "Mesa 0{$i}")
                    ->where('sala_id', $criar_sala->id)
                    ->where('entidade_id', $entidade->empresa->id)
                    ->first();

                if (!$verificar_mesa) {
                    Mesa::create([
                        'nome' => "Mesa 0{$i}",
                        'ocupacao' => "",
                        'solicitar_ocupacao' => "LIVRE",
                        'sala_id' => $criar_sala->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }
            }

            //******************************************** */

            $verificar_categoria = Categoria::where('user_id', Auth::user()->id)
                ->where('categoria', "-- Sem Categoria --")
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            if (!$verificar_categoria) {
                Categoria::create([
                    'categoria' => "-- Sem Categoria --",
                    'status' => "activo",
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
            }

            $verificar_marca = Marca::where('user_id', Auth::user()->id)
                ->where('nome', "-- Sem Marca --")
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            if (!$verificar_marca) {
                Marca::create([
                    'nome' => "-- Sem Marca --",
                    'status' => "activo",
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
            }

            $verificar_variacao = Variacao::where('user_id', Auth::user()->id)
                ->where('nome', "-- Sem Variação --")
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            if (!$verificar_variacao) {
                Variacao::create([
                    'nome' => "-- Sem Variação --",
                    'status' => "activo",
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
            }


            // criar exercicio
            if (!Exercicio::where('nome', 'activo')->where('entidade_id', $entidade->empresa->id)->first()) {
                $exercicio = Exercicio::create([
                    'entidade_id' => $entidade->empresa->id,
                    'nome' => $ano,
                    'status' => 'activo',
                    'inicio' => date("{$ano}-01-01"),
                    'final' => date("{$ano}-12-31"),
                    'user_id' => Auth::user()->id,
                ]);

                for ($mes = 1; $mes <= 12; $mes++) {
                    // Obtém o primeiro e o último dia do mês
                    $primeiro_dia = date("Y-m-d", strtotime("$ano-$mes-01"));
                    $ultimo_dia = date("Y-m-t", strtotime($primeiro_dia));

                    // Obtém o número total de dias do mês
                    $dias_no_mes = date("t", strtotime($primeiro_dia));
                    $nome = date("F", strtotime($primeiro_dia));

                    $periodos = Periodo::create([
                        'entidade_id' => $entidade->empresa->id,
                        'nome' => $this->mes_em_portugues($nome),
                        'status' => 'activo',
                        'mes_processamento' => $mes,
                        'dias_uteis' => 22,
                        'dias_fixo' => $dias_no_mes,
                        'inicio' => $primeiro_dia,
                        'final' => $ultimo_dia,
                        'exercicio_id' => $exercicio->id,
                        'user_id' => Auth::user()->id,
                    ]);
                }
            }

            // START CONTABILIDADE
            $classe1_verificar = Classe::where('entidade_id', $entidade->empresa->id)
                ->where('nome', 'Meios Fixos e Investimentos')
                ->where('sigla', 'MFI')
                ->where('status', 'activo')
                ->where('conta', 'Classe 1')
                ->first();

            if (!$classe1_verificar) {
                $classe1 = Classe::create([
                    'entidade_id' => $entidade->empresa->id,
                    'nome' => 'Meios Fixos e Investimentos',
                    'sigla' => 'MFI',
                    'status' => 'activo',
                    'conta' => 'Classe 1',
                    'user_id' => Auth::user()->id,
                ]);
            } else {
                $classe1 = $classe1_verificar;
            }

            foreach ($this->plano_geral_contas_classe_1() as $contaNum => $contaData) {
                $conta = $this->criarConta($entidade, $classe1, $contaData['nome'], $contaNum);
                foreach ($contaData['subcontas'] as $subNum => $subNome) {
                    $this->criarSubconta($entidade, $conta, $subNum, $subNome);
                }
            }

            $classe2_verificar = Classe::where('entidade_id', $entidade->empresa->id)
                ->where('nome', 'Existências')
                ->where('sigla', 'EX')
                ->where('status', 'activo')
                ->where('conta', 'Classe 2')
                ->first();

            if (!$classe2_verificar) {
                $classe2 = Classe::create([
                    'entidade_id' => $entidade->empresa->id,
                    'nome' => 'Existências',
                    'sigla' => 'EX',
                    'status' => 'activo',
                    'conta' => 'Classe 2',
                    'user_id' => Auth::user()->id,
                ]);
            } else {
                $classe2 = $classe2_verificar;
            }

            foreach ($this->plano_geral_contas_classe_2() as $contaNum => $contaData) {
                $conta = $this->criarConta($entidade, $classe2, $contaData['nome'], $contaNum);
                foreach ($contaData['subcontas'] as $subNum => $subNome) {
                    $this->criarSubconta($entidade, $conta, $subNum, $subNome);
                }
            }


            $classe3_verificar = Classe::where('entidade_id', $entidade->empresa->id)
                ->where('nome', 'Terceiros')
                ->where('sigla', 'TER')
                ->where('status', 'activo')
                ->where('conta', 'Classe 3')
                ->first();

            if (!$classe3_verificar) {
                $classe3 = Classe::create([
                    'entidade_id' => $entidade->empresa->id,
                    'nome' => 'Terceiros',
                    'sigla' => 'TER',
                    'status' => 'activo',
                    'conta' => 'Classe 3',
                    'user_id' => Auth::user()->id,
                ]);
            } else {
                $classe3 = $classe3_verificar;
            }


            foreach ($this->plano_geral_contas_classe_3() as $contaNum => $contaData) {
                $conta = $this->criarConta($entidade, $classe3, $contaData['nome'], $contaNum);
                foreach ($contaData['subcontas'] as $subNum => $subNome) {
                    $this->criarSubconta($entidade, $conta, $subNum, $subNome);
                }
            }

            $classe4_verificar = Classe::where('entidade_id', $entidade->empresa->id)
                ->where('nome', 'Meios Monetários')
                ->where('sigla', 'MMON')
                ->where('status', 'activo')
                ->where('conta', 'Classe 4')
                ->first();

            if (!$classe4_verificar) {
                $classe4 = Classe::create([
                    'entidade_id' => $entidade->empresa->id,
                    'nome' => 'Meios Monetários',
                    'sigla' => 'MMON',
                    'status' => 'activo',
                    'conta' => 'Classe 4',
                    'user_id' => Auth::user()->id,
                ]);
            } else {
                $classe4 = $classe4_verificar;
            }


            foreach ($this->plano_geral_contas_classe_4() as $contaNum => $contaData) {
                $conta = $this->criarConta($entidade, $classe4, $contaData['nome'], $contaNum);
                foreach ($contaData['subcontas'] as $subNum => $subNome) {
                    $this->criarSubconta($entidade, $conta, $subNum, $subNome);
                }
            }

            $classe5_verificar = Classe::where('entidade_id', $entidade->empresa->id)
                ->where('nome', 'Capital e Reservas')
                ->where('sigla', 'CRE')
                ->where('status', 'activo')
                ->where('conta', 'Classe 5')
                ->first();

            if (!$classe5_verificar) {
                $classe5 = Classe::create([
                    'entidade_id' => $entidade->empresa->id,
                    'nome' => 'Capital e Reservas',
                    'sigla' => 'CRE',
                    'status' => 'activo',
                    'conta' => 'Classe 5',
                    'user_id' => Auth::user()->id,
                ]);
            } else {
                $classe5 = $classe5_verificar;
            }


            foreach ($this->plano_geral_contas_classe_5() as $contaNum => $contaData) {
                $conta = $this->criarConta($entidade, $classe5, $contaData['nome'], $contaNum);
                foreach ($contaData['subcontas'] as $subNum => $subNome) {
                    $this->criarSubconta($entidade, $conta, $subNum, $subNome);
                }
            }

            $classe6_verificar = Classe::where('entidade_id', $entidade->empresa->id)
                ->where('nome', 'Proveitos por Natureza')
                ->where('sigla', 'PR.NA')
                ->where('status', 'activo')
                ->where('conta', 'Classe 6')
                ->first();

            if (!$classe6_verificar) {
                $classe6 = Classe::create([
                    'entidade_id' => $entidade->empresa->id,
                    'nome' => 'Proveitos por Natureza',
                    'sigla' => 'PR.NA',
                    'status' => 'activo',
                    'conta' => 'Classe 6',
                    'user_id' => Auth::user()->id,
                ]);
            } else {
                $classe6 = $classe6_verificar;
            }


            foreach ($this->plano_geral_contas_classe_6() as $contaNum => $contaData) {
                $conta = $this->criarConta($entidade, $classe6, $contaData['nome'], $contaNum);
                foreach ($contaData['subcontas'] as $subNum => $subNome) {
                    $this->criarSubconta($entidade, $conta, $subNum, $subNome);
                }
            }


            //**************************************** */

            $classe7_verificar = Classe::where('entidade_id', $entidade->empresa->id)
                ->where('nome', 'Custos por Natureza')
                ->where('sigla', 'CU.NA')
                ->where('status', 'activo')
                ->where('conta', 'Classe 7')
                ->first();

            if (!$classe7_verificar) {
                $classe7 = Classe::create([
                    'entidade_id' => $entidade->empresa->id,
                    'nome' => 'Custos por Natureza',
                    'sigla' => 'CU.NA',
                    'status' => 'activo',
                    'conta' => 'Classe 7',
                    'user_id' => Auth::id(),
                ]);
            } else {
                $classe7 = $classe7_verificar;
            }


            foreach ($this->plano_geral_contas_classe_7() as $contaNum => $contaData) {
                $conta = $this->criarConta($entidade, $classe7, $contaData['nome'], $contaNum);
                foreach ($contaData['subcontas'] as $subNum => $subNome) {
                    $this->criarSubconta($entidade, $conta, $subNum, $subNome);
                }
            }

            $classe8_verificar = Classe::where('entidade_id', $entidade->empresa->id)
                ->where('nome', 'Resultados')
                ->where('sigla', 'RES')
                ->where('status', 'activo')
                ->where('conta', 'Classe 8')
                ->first();

            if (!$classe8_verificar) {
                $classe8 = Classe::create([
                    'entidade_id' => $entidade->empresa->id,
                    'nome' => 'Resultados',
                    'sigla' => 'RES',
                    'status' => 'activo',
                    'conta' => 'Classe 8',
                    'user_id' => Auth::user()->id,
                ]);
            } else {
                $classe8 = $classe8_verificar;
            }

            foreach ($this->plano_geral_contas_classe_8() as $contaNum => $contaData) {
                $conta = $this->criarConta($entidade, $classe8, $contaData['nome'], $contaNum);
                foreach ($contaData['subcontas'] as $subNum => $subNome) {
                    $this->criarSubconta($entidade, $conta, $subNum, $subNome);
                }
            }

            // END CONTABILIDADE

            // TAXAS DO IRTS
            foreach ($this->tabela_taxas_irt() as $contaNum => $contaData) {

                $veificar_taxa_irt = TaxaIRT::where('escalao', $contaData['nome'])
                    ->where('remuneracao', $contaData['remuneracao'])
                    ->where('taxa', $contaData['taxa'])
                    ->where('abatimento', $contaData['abatimento'])
                    ->where('valor_fixo', $contaData['valor_fixo'])
                    ->where('excesso', $contaData['excesso'])
                    ->where('entidade_id', $entidade->empresa->id)
                    ->where('exercicio_id', $exercicio->id)
                    ->first();

                if (!$veificar_taxa_irt) {
                    TaxaIRT::create([
                        'escalao' => $contaData['nome'],
                        'remuneracao' => $contaData['remuneracao'],
                        'taxa' => $contaData['taxa'],
                        'abatimento' => $contaData['abatimento'],
                        'valor_fixo' => $contaData['valor_fixo'],
                        'excesso' => $contaData['excesso'],
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $exercicio->id,
                    ]);
                }
            }

            //******* CRIAR LOJA AUTOMATICAMENTE */

            $verificar_loja = Loja::where("nome", "Loja Principal")
                ->where("entidade_id", $entidade->empresa->id)
                ->first();

            if (!$verificar_loja) {
                $criar_loja = Loja::create([
                    "nome" => "Loja Principal",
                    "status" => "activo",
                    "codigo_postal" => "000-000",
                    "morada" => $entidade->empresa->morada,
                    "email" => $entidade->empresa->email,
                    "nif" => $entidade->empresa->nif,
                    "provincia_id" => $entidade->empresa->provincia_id,
                    "municipio_id" => $entidade->empresa->municipio_id,
                    "ramo_actividade_id" => $entidade->empresa->tipo_id,
                    "distrito_id" => $entidade->empresa->distrito_id,
                    "modelo_factura" => $entidade->empresa->modelo_factura,
                    "logotipo" => $entidade->empresa->logotipo,
                    "telefone" => $entidade->empresa->telefone,
                    "cae" => NULL,
                    "descricao" => NULL,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);

                UserLoja::create([
                    "usuario_id" => Auth::user()->id,
                    "loja_id" => $criar_loja->id,
                    "status" => 1,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);
            } else {
                $criar_loja = $verificar_loja;
            }

            //********************************** */


            $verificar_conta_bancaria = ContaBancaria::where('entidade_id', $entidade->empresa->id)
                ->where('nome', 'Banco Angolano de Investimentos')
                ->first();

            if (!$verificar_conta_bancaria) {
                $numero0 = '43.1.1';
                $code0 = uniqid(time() - 2236);

                $conta_banco =  Conta::where('entidade_id', $entidade->empresa->id)->where('conta', '43')->first();

                $subconta_banco = Subconta::create([
                    'entidade_id' => $entidade->empresa->id,
                    'numero' => $numero0,
                    'nome' => 'Banco Angolano de Investimentos',
                    'tipo_conta' => 'M',
                    'code' => $code0,
                    'status' => 'activo',
                    'conta_id' => $conta_banco->id,
                    'user_id' => Auth::id(),
                ]);

                $banco = ContaBancaria::create([
                    'banco_id' => 1,
                    'nome' => 'Banco Angolano de Investimentos',
                    'status' => 'fechado',
                    'user_id' => Auth::user()->id,
                    'numero_conta' => NULL,
                    'tipo_banco_id' => 'DO',
                    'iban' => NULL,
                    'code' => $code0,
                    'conta' => $numero0,

                    "moeda" => 'KZ',

                    'nib' => NULL,
                    'switf' => NULL,
                    'nome_agencia' => NULL,
                    'numero_gestor' => NULL,
                    'nome_titular' => NULL,
                    'morada_titular' => NULL,
                    'local_titular' => NULL,
                    'codigo_postal_titular' => NULL,

                    "subconta_id" => $subconta_banco->id,
                    "loja_id" => $criar_loja->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
            }


            $verificar_caixa = Caixa::where('entidade_id', $entidade->empresa->id)
                ->where('nome', 'Caixa principal')
                ->where('status_admin', 'liberado')
                ->first();

            if (!$verificar_caixa) {
                $code = uniqid(time());
                $code_caixa = uniqid(time() + 202);
                $numero = '45.1.1';


                $conta_caixa =  Conta::where('entidade_id', $entidade->empresa->id)->where('conta', '45')->first();
                $subconta_caixa = Subconta::create([
                    'entidade_id' => $entidade->empresa->id,
                    'numero' => $numero,
                    'nome' => 'Caixa principal',
                    'tipo_conta' => 'M',
                    'code' => $code,
                    'status' => 'activo',
                    'conta_id' => $conta_caixa->id,
                    'user_id' => Auth::id(),
                ]);

                // ************************************************
                /// CRIAR CAIXA AUTOMATICAMENTE
                $criar_caixas = Caixa::create([
                    'nome' => "Caixa principal",
                    'status' => "fechado",
                    'conta_ordem' => 1,
                    'conta' => $numero,
                    'code' => $code,
                    'code_caixa' => $code_caixa,
                    'user_id' => Auth::user()->id,
                    'active' => false,
                    "subconta_id" => $subconta_caixa->id,
                    "loja_id" => $criar_loja->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
            }


            $verificar_fornecedore = Fornecedore::where('entidade_id', $entidade->empresa->id)
                ->where('nome', 'Fornecedor Diversos')
                ->first();

            if (!$verificar_fornecedore) {
                $code1 = uniqid(time() + 22);
                $numero1 = '32.1.2.1.1';

                $conta_fornecedor =  Conta::where('entidade_id', $entidade->empresa->id)->where('conta', '32')->first();
                $subconta_fornecedor = Subconta::create([
                    'entidade_id' => $entidade->empresa->id,
                    'numero' => $numero1,
                    'nome' => 'Fornecedor Diversos',
                    'tipo_conta' => 'M',
                    'code' => $code1,
                    'status' => 'activo',
                    'conta_id' => $conta_fornecedor->id,
                    'user_id' => Auth::id(),
                ]);

                $fornecedor = Fornecedore::create([
                    "nif" => "99999999",
                    "nome" => "Fornecedor Diversos",
                    "pais" => "AO",
                    "status" => true,
                    "conta" => $numero1,
                    "code" => $code1,
                    "codigo_postal" => "00000",
                    "localidade" => "Angola-Luanda",
                    "telefone" => "000-000-000",
                    "telemovel" => "244-000-000-000",
                    "email" => "fornecedor-diversos@gmail.com",
                    "website" => "www.fornecedor-diversos.com",
                    "observacao" => "Observação fornecedor diversos",
                    'user_id' => Auth::id(),
                    'entidade_id' => $entidade->empresa->id,
                    'subconta_id' => $subconta_fornecedor->id,
                ]);

                ContaFornecedore::create([
                    "saldo" => 0,
                    "divida_corrente" => 0,
                    "divida_vencidade" => 0,
                    'fornecedor_id' => $fornecedor->id,
                    'user_id' => Auth::id(),
                    'entidade_id' => $entidade->empresa->id,
                ]);
            }


            $verificar_cliente = Cliente::where('entidade_id', $entidade->empresa->id)
                ->where('nome', 'CONSUMIDOR FINAL')
                ->first();

            if (!$verificar_cliente) {
                $code2 = uniqid(time() + 220);
                $numero2 = '31.1.2.1.1';

                $conta_cliente =  Conta::where('entidade_id', $entidade->empresa->id)->where('conta', '31')->first();

                if ($conta_cliente) {
                    $subconta_cliente = Subconta::create([
                        'entidade_id' => $entidade->empresa->id,
                        'numero' => $numero2,
                        'nome' => 'CONSUMIDOR FINAL',
                        'tipo_conta' => 'M',
                        'code' => $code2,
                        'status' => 'activo',
                        'conta_id' => $conta_cliente->id,
                        'user_id' => Auth::id(),
                    ]);
                }

                $clientes = Cliente::create([
                    "nif" => "999999999",
                    "nome" => "CONSUMIDOR FINAL",
                    "pais" => "AO",
                    "status" => true,
                    "conta" => $numero2,
                    "code" => $code2,
                    "gestor_conta" => Auth::id(),
                    "codigo_postal" => "00346347",
                    "tipo_cliente" => "C",
                    "localidade" => "Angola-Luanda",
                    "telefone" => "999999999",
                    "telemovel" => "000-000-000",
                    "vencimento" => 0,
                    "email" => "consumidor-final@gmail.com",
                    "website" => NULL,
                    "referencia_externa" => NULL,
                    "observacao" => NULL,
                    'user_id' => Auth::id(),
                    'entidade_id' => $entidade->empresa->id,
                    'subconta_id' => $subconta_cliente->id,
                ]);

                ContaCliente::create([
                    'divida_corrente' => 0,
                    'divida_vencida' => 0,
                    'saldo' => 0,
                    'cliente_id' => $clientes->id,
                    'user_id' => Auth::id(),
                    'entidade_id' => $entidade->empresa->id,
                ]);
            }


            //////////////////////// PROVEITOS E CUSTOS

            foreach ($this->receitas_padroes() as $contaNum => $contaData) {

                $varificar_receita = Receita::where('nome', $contaData['nome'])
                    ->where('entidade_id', $entidade->empresa->id)
                    ->first();

                if (!$varificar_receita) {
                    Receita::create([
                        'nome' => $contaData['nome'],
                        'type' => $contaData['type'],
                        'status' => $contaData['status'],
                        'entidade_id' => $entidade->empresa->id,
                        'user_id' => Auth::user()->id,
                    ]);
                }
            }


            foreach ($this->dispesas_padroes() as $contaNum => $contaData) {

                $varificar_dispesa = Dispesa::where('nome', $contaData['nome'])
                    ->where('entidade_id', $entidade->empresa->id)
                    ->first();

                if (!$varificar_dispesa) {
                    Dispesa::create([
                        'nome' => $contaData['nome'],
                        'type' => $contaData['type'],
                        'status' => $contaData['status'],
                        'entidade_id' => $entidade->empresa->id,
                        'user_id' => Auth::user()->id,
                    ]);
                }
            }

            foreach ($this->tipos_processamentos() as $contaNum => $contaData) {

                $varificar_tipo_processamento = TipoProcessamento::where('nome', $contaData['nome'])
                    ->where('entidade_id', $entidade->empresa->id)
                    ->first();

                if (!$varificar_tipo_processamento) {
                    TipoProcessamento::create([
                        'nome' => $contaData['nome'],
                        'sigla' => $contaData['sigla'],
                        'status' => $contaData['status'],
                        'entidade_id' => $entidade->empresa->id,
                        'user_id' => Auth::user()->id,
                    ]);
                }
            }

            foreach ($this->tipos_contratos() as $contaNum => $contaData) {

                $varificar_tipo_contrato = TipoContrato::where('nome', $contaData['nome'])
                    ->where('entidade_id', $entidade->empresa->id)
                    ->first();

                if (!$varificar_tipo_contrato) {
                    TipoContrato::create([
                        'nome' => $contaData['nome'],
                        'status' => $contaData['status'],
                        'entidade_id' => $entidade->empresa->id,
                        'user_id' => Auth::user()->id,
                    ]);
                }
            }

            foreach ($this->periodos_rendimentos() as $contaNum => $contaData) {

                $verificar_periodo_rendimento = PeriodoRendimento::where('nome', $contaData['nome'])
                    ->where('entidade_id', $entidade->empresa->id)
                    ->first();

                if (!$verificar_periodo_rendimento) {
                    PeriodoRendimento::create([
                        'nome' => $contaData['nome'],
                        'status' => $contaData['status'],
                        'numero' => $contaData['numero'],
                        'entidade_id' => $entidade->empresa->id,
                        'user_id' => Auth::user()->id,
                    ]);
                }
            }

            foreach ($this->descontos() as $contaNum => $contaData) {

                $verificar_desconto = Desconto::where('nome', $contaData['nome'])->where('entidade_id', $entidade->empresa->id)->first();

                if (!$verificar_desconto) {
                    Desconto::create([
                        'nome' => $contaData['nome'],
                        'status' => $contaData['status'],
                        'desconto' => $contaData['desconto'],
                        'numero' => $contaData['numero'],
                        'entidade_id' => $entidade->empresa->id,
                        'user_id' => Auth::user()->id,
                    ]);
                }
            }

            foreach ($this->subsidios() as $contaNum => $contaData) {

                $verificar_subsidio = Subsidio::where('nome', $contaData['nome'])
                    ->where('entidade_id', $entidade->empresa->id)
                    ->first();

                if (!$verificar_subsidio) {
                    Subsidio::create([
                        'nome' => $contaData['nome'],
                        'status' => $contaData['status'],
                        'numero' => $contaData['numero'],
                        'entidade_id' => $entidade->empresa->id,
                        'user_id' => Auth::user()->id,
                    ]);
                }
            }

            $update = Entidade::findOrFail($entidade->empresa->id);
            $update->first_login_system = true;
            $update->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados salvos com sucesso!"], 200);
    }

    public function activar_aceitoConfigurarSistema(Request $request)
    {
        $entidade = User::with('empresa.tipo_entidade.modulos')->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $update = Entidade::findOrFail($entidade->empresa->id);
            $update->first_login_system = false;
            $update->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return redirect()->back();
    }

    // relatorio fechamento caixa
    public function relatorio_fechamento_caixa($data_inicio, $data_final, $user_id, $subconta_id)
    {
        $user = auth()->user();

        if (!$user->can('fecho do caixa')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $caixaActivo = Caixa::find($subconta_id);
        if (!$caixaActivo) {
            return redirect()->back();
        }

        $entidade = User::with('empresa.tipo_entidade.modulos')->findOrFail(Auth::user()->id);

        $credito = 0;
        $debito = 0;

        $multicaixa = 0;
        $multicaixa_credito = 0;
        $multicaixa_debito = 0;

        $numerorio = 0;
        $numerorio_credito = 0;
        $numerorio_debito = 0;

        $duplo = 0;
        $duplo_credito = 0;
        $duplo_debito = 0;

        $subconta_caixa = Caixa::findOrFail($caixaActivo->id);

        $movimentos = OperacaoFinanceiro::where('entidade_id', $entidade->empresa->id)
            ->when($data_inicio, function ($query, $value) {
                $query->whereDate('date_at', '>=', Carbon::createFromDate($value));
            })
            ->when($data_final, function ($query, $value) {
                $query->whereDate('date_at', '<=', Carbon::createFromDate($value));
            })
            ->where('user_id', $user_id)
            ->where('code_caixa', $caixaActivo->code_caixa)
            ->where('status_caixa', 'concluido')
            ->get();

        foreach ($movimentos as $item) {
            if ($item->formas == "C") {
                if ($item->type == "R") {
                    $numerorio_debito += $item->motante;
                }
                if ($item->type == "D") {
                    $numerorio_credito += $item->motante;
                }
            }
            if ($item->formas == "B") {

                if ($item->type == "R") {
                    $multicaixa_debito += $item->motante;
                }
                if ($item->type == "D") {
                    $multicaixa_credito += $item->motante;
                }
            }
            if ($item->formas == "O") {
                if ($item->type == "R") {
                    $duplo_debito += $item->motante;
                }
                if ($item->type == "D") {
                    $duplo_credito += $item->motante;
                }
            }
            if ($item->type == "R") {
                $debito += $item->motante;
            }
            if ($item->type == "D") {
                $credito += $item->motante;
            }
        }

        $multicaixa = $multicaixa_debito - $multicaixa_credito;
        $numerorio = $numerorio_debito - $numerorio_credito;
        $duplo = $duplo_debito - $duplo_credito;

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Fecho caixa",
            "descricao" => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "categorias" => Categoria::with('produtos.marca', 'produtos.variacao')
                ->where('entidade_id', '=', $entidade->empresa->id)
                ->get(),
            "empresa" => Entidade::findOrFail($entidade->empresa->id),

            "subconta" => $subconta_caixa,
            "data_inicio" => $data_inicio,
            "data_final" => $data_final,

            "credito" => $credito,
            "debito" => $debito,

            "multicaixa" => $multicaixa,
            "numerorio" => $numerorio,
            "duplo" => $duplo,

            "multicaixa_credito" => $multicaixa_credito,
            "multicaixa_debito" => $multicaixa_debito,
            "numerorio_credito" => $numerorio_credito,
            "numerorio_debito" => $numerorio_debito,
            "duplo_credito" => $duplo_credito,
            "duplo_debito" => $duplo_debito,

            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.vendas.caixas.relatorio-fecho', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function caixaDesactivar($id)
    {
        //
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar caixa')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $caixa = Caixa::findOrFail($id);

        if ($caixa->active == false) {
            $caixa->status = true;
        } else {
            $caixa->status = false;
        }

        if ($caixa->update()) {
            Alert::success("Sucesso!", "Caixa Suspendida do successo");
            return redirect()->route('lojas.index');
        } else {
            Alert::error("Erro!", "Não foi possível Suspender a Caixa");
            return redirect()->route('lojas.index');
        }
    }
}
