<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use App\Models\Caixa;
use App\Models\Cliente;
use App\Models\ContaBancaria;
use App\Models\ContaHospitalar;
use App\Models\ContaHospitalarMovimento;
use App\Models\ContaHospitalarPagamento;
use App\Models\ItemVenda;
use App\Models\Movimento;
use App\Models\OperacaoFinanceiro;
use App\Models\Receita;
use App\Models\Serie;
use App\Models\Subconta;
use App\Models\TipoPagamento;
use App\Models\User;
use App\Models\Venda;
use App\Services\ContaHospitalarService;
use App\Jobs\SubmitElectronicDocumentToAgtJob;
use App\Services\PagamentoContaHospitalarService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

use phpseclib\Crypt\RSA;

class ContaHospitalarController extends Controller
{
    use TraitChavesSaft;
    use TraitHelpers;

    public ContaHospitalarService $service;
    protected PagamentoContaHospitalarService $pagamentoService;

    public function __construct(ContaHospitalarService $service, PagamentoContaHospitalarService $pagamentoService)
    {
        $this->service = $service;
        $this->pagamentoService = $pagamentoService;
        $this->middleware('auth');
    }

    /**
     * Listagem das contas
     */
    public function index(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $query = ContaHospitalar::with(['paciente', 'atendimento'])
            ->where("entidade_id", $entidade->empresa->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('paciente', function ($p) use ($search) {
                    $p->where('nome', 'like', "%{$search}%")
                        ->orWhere('nif', 'like', "%{$search}%");
                })

                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhere('numero', 'like', "%{$search}%")
                    ->orWhereHas('atendimento', function ($a) use ($search) {
                        $a->where('numero', 'like', "%{$search}%");
                    });
            });
        }

        $contas = $query->orderByDesc('id')->get();

        return view("dashboard.atendimentos.contas.index", [
            "titulo" => "Conta Hospitalar",
            "descricao" => env("APP_NAME"),
            "contas" => $contas,
            "empresa_logada" => $entidade,
        ]);
    }
    /**
     * Formulário para criar conta
     */
    public function create()
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $pacientes = Cliente::orderBy('nome')->get();

        $atendimentos = Atendimento::whereDoesntHave('contaHospitalar')
            ->orderByDesc('id')
            ->where("entidade_id", $entidade->empresa->id)
            ->get();

        return view('contas.create', compact('pacientes', 'atendimentos'));
    }

    /**
     * Gravar conta hospitalar
     */
    public function store(Request $request)
    {

        $request->validate([
            'paciente_id'      => 'required|exists:pacientes,id',
            'atendimento_id'   => 'required|exists:atendimentos,id',
            'observacao'       => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {

            $conta = $this->service->create($request);

            DB::commit();

            return redirect()
                ->route('contas.show', $conta->id)
                ->with('success', 'Conta criada com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors([
                    'erro' => $e->getMessage()
                ]);
        }
    }


    /**
     * Exibir uma conta hospitalar
     */
    public function show(string $id)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $conta = ContaHospitalar::with(['paciente', 'atendimento', 'itens', 'pagamentos.recebido_por', 'movimentos.user'])
            ->where("entidade_id", $entidade->empresa->id)
            ->findOrFail($id);

        $forma_pagaments = TipoPagamento::get();

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env("APP_NAME"),
            'conta' => $conta,
            'forma_pagaments' => $forma_pagaments,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.atendimentos.contas.show", $head);
    }

    /**
     * Formulário de edição
     */
    public function edit(string $id)
    {
        $conta = ContaHospitalar::findOrFail($id);
        if ($conta->status == 'PAGA') {
            return redirect()
                ->route('contas.show', $conta->id)
                ->withErrors([
                    'erro' => 'Uma conta paga não pode ser editada.'
                ]);
        }

        return view('contas.edit', compact('conta'));
    }

    /**
     * Atualizar dados da conta
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'observacao' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {

            $conta = ContaHospitalar::findOrFail($id);

            if ($conta->status == 'PAGA') {
                return back()->withErrors([
                    'erro' => 'Conta paga não pode ser alterada.'
                ]);
            }

            $conta->update([
                'observacao' => $request->observacao,
            ]);

            ContaHospitalarMovimento::create([
                'conta_hospitalar_id' => $conta->id,
                'tipo' => 'ALTERACAO',
                'descricao' => 'Dados da conta alterados.',
                'user_id' => Auth::id()
            ]);

            DB::commit();
            return redirect()
                ->route('contas.show', $conta->id)
                ->with('success', 'Conta atualizada com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors([
                    'erro' => $e->getMessage()
                ]);
        }
    }

    /**
     * Cancelar conta
     */
    public function destroy(string $id)
    {

        DB::beginTransaction();

        try {

            $conta = ContaHospitalar::with('pagamentos')->findOrFail($id);

            if ($conta->pagamentos()->count() > 0) {
                return back()->withErrors([
                    'erro' => 'Esta conta possui pagamentos e não pode ser excluída.'
                ]);
            }

            if ($conta->status == 'PAGA') {
                return back()->withErrors([
                    'erro' => 'Não é permitido excluir contas pagas.'
                ]);
            }

            $conta->status = 'CANCELADA';

            $conta->save();

            ContaHospitalarMovimento::create([
                'conta_hospitalar_id' => $conta->id,
                'tipo' => 'CANCELAMENTO',
                'descricao' => 'Conta cancelada pelo utilizador.',
                'user_id' => Auth::id()
            ]);

            $conta->delete();

            DB::commit();

            return redirect()
                ->route('contas.index')
                ->with('success', 'Conta cancelada.');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->withErrors([
                'erro' => $e->getMessage()
            ]);
        }
    }

    /**
     * Adicionar um item na conta
     */
    public function adicionarItem(Request $request, string $id)
    {
        $request->validate([
            'quantidade'      => 'required|numeric|min:1',
            'preco_unitario'  => 'required',
            'produto_id'       => 'nullable'
        ]);


        DB::beginTransaction();

        try {

            $conta = $this->service->adicionarItem($request, $id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item adicionado.',
                'total' => $conta->fresh()->total
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function removerItem(string $id)
    {
        DB::beginTransaction();

        try {

            $conta = $this->service->removerItem($id);

            DB::commit();
            return response()->json([
                'success' => true,
                'total' => $conta->fresh()->total
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function pagar(Request $request, string $id)
    {
        $request->validate([
            'tipo'              => 'required|in:PACIENTE,SEGURADORA',
            'forma_pagamento'   => 'required|string|max:50',
            'valor'             => 'required|numeric|min:0.01',
            'referencia' => 'nullable|string|max:255|unique:vendas,referencia',
            'payment_token'     => 'nullable|string|max:100'
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        DB::beginTransaction();

        try {

            $caixaActivo = Caixa::where('active', true)
                ->where('status', 'aberto')
                ->where('status_admin', 'liberado')
                ->where('user_open_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            if (!$caixaActivo) {
                return response()->json([
                    'success' => false,
                    'message' => 'É necessário que exista uma caixa aberta para emitir a fatura.'
                ], 400);

                // $caixaActivo = Caixa::where('entidade_id', $entidade->empresa->id)->where('status_admin', 'liberado')->first();
            }

            $bancoActivo = ContaBancaria::where('active', true)
                ->where('status', 'aberto')
                ->where('user_open_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            if (!$bancoActivo) {
                return response()->json([
                    'success' => false,
                    'message' => 'É necessário que exista uma caixa aberta para emitir a fatura.'
                ], 400);
                // $bancoActivo = ContaBancaria::where('entidade_id', $entidade->empresa->id)->first();
            }

            $conta = ContaHospitalar::lockForUpdate()->findOrFail($id);

            if (!in_array($conta->status, ['ABERTA', 'EM_ANDAMENTO', 'FECHADA'])) {
                throw new \Exception('Esta conta não pode receber pagamentos.');
            }

            /* Recalcula antes de validar o saldo */

            $this->service->recalcularTotais($conta->fresh());

            $conta->refresh();

            /* Evita pagamento duplicado  */
            if ($request->filled('payment_token')) {
                $duplicado = ContaHospitalarPagamento::where('payment_token', $request->payment_token)->exists();
                if ($duplicado) {
                    throw new \Exception('Este pagamento já foi processado.');
                }
            }

            /* Saldo disponível */
            if ($request->tipo == 'PACIENTE') {
                $saldoDisponivel = $conta->saldo_paciente;
            } else {
                $saldoDisponivel = $conta->saldo_seguradora;
            }

            if ($saldoDisponivel <= 0) {
                throw new \Exception('Não existe saldo pendente para este tipo de pagamento.');
            }

            if ($request->valor > $saldoDisponivel) {
                throw new \Exception(
                    'O valor informado (' . number_format($request->valor, 2, ",", ".") . ') é superior ao saldo disponível (' . number_format($saldoDisponivel, 2, ",", ".") . ').'
                );
            }

            /*  Grava pagamento */
            $pagamento = ContaHospitalarPagamento::create([
                'conta_hospitalar_id' => $conta->id,
                'tipo' => $request->tipo,
                'forma_pagamento' => $request->forma_pagamento,
                'valor' => $request->valor,
                'referencia' => $request->referencia,
                'payment_token' => $request->payment_token,
                'user_id'   => Auth::id(),
                'entidade_id' => $entidade->empresa->id,
            ]);

            /*  Movimento financeiro */
            ContaHospitalarMovimento::create([
                'conta_hospitalar_id' => $conta->id,
                'tipo' => 'PAGAMENTO',
                'descricao' => 'Pagamento de ' . number_format($pagamento->valor, 2, ",", ".") .  ' recebido de ' . strtolower($pagamento->tipo),
                'user_id'   => Auth::id(),
                'entidade_id' => $entidade->empresa->id,
            ]);

            /* Recalcula novamente */
            $this->service->recalcularTotais($conta->fresh());

            $conta = ContaHospitalar::with(['itens.servico'])->findOrFail($id);

            if ($conta->status !== 'PAGA' && $conta->saldo_paciente <= 0 && $conta->saldo_seguradora <= 0) {
                $conta->status = 'PAGA';
                $conta->fechada_em = now();
                $conta->fechada_por = Auth::id();
                $conta->save();
            }


            $create_factura = null;


            if ($request->tipo == "PACIENTE") {

                $cliente = Cliente::findOrFail($conta->paciente_id);
                $receita = Receita::where('nome', 'Prestações de Serviços')->where('entidade_id', $entidade->empresa->id)->first();
                $tipo_pagamento = TipoPagamento::findOrFail($request->forma_pagamento ?? 1);

                $caixa_id = $caixaActivo->id;
                $mesa_id = NULL;
                $status_uso = "SERVICO_HOSPITALAR";

                $code = uniqid(time());

                if ($request->tipo_documento == "FR") {
                    if ($tipo_pagamento->tipo == "NU") {
                        // finanças
                        OperacaoFinanceiro::create([
                            'nome' => $receita->nome,
                            'status' => "pago",
                            'motante' => $conta->total,
                            'formas' => 'C',
                            'cliente_id' => $cliente->id,
                            'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                            'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                            'subconta_id' => $caixaActivo->subconta_id,
                            'model_id' => $receita->id,
                            'type' => 'R',
                            'status_pagamento' => "pago",
                            'code' => $code,
                            'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'descricao' => $receita->nome,
                            'movimento' => 'E',
                            'date_at' => date("Y-m-d"),
                            'user_id' => Auth::user()->id,
                            'user_open_id' => Auth::user()->id,
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $this->exercicio(),
                            'periodo_id' => $this->periodo(),
                        ]);

                        // contabilidade  DEBITAR CAIXAR
                        Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $caixaActivo->subconta_id,
                            'exercicio_id' => $this->exercicio(),
                            'periodo_id' => $this->periodo(),
                            'status' => true,
                            'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'movimento' => 'E',
                            'observacao' => "pagamento {$receita->nome}",
                            'credito' => 0,
                            'debito' => $conta->total,
                            'code' => $code,
                            'data_at' => date("Y-m-d"),
                            'entidade_id' => $entidade->empresa->id,
                        ]);

                        $valor_cash = $conta->total;
                        $valor_multicaixa = 0;
                    } else if ($tipo_pagamento->tipo == "MB" || $tipo_pagamento->tipo == "TB" || $tipo_pagamento->tipo == "DE") {

                        // finanças
                        OperacaoFinanceiro::create([
                            'nome' => $receita->nome,
                            'status' => "pago",
                            'motante' => $conta->total,
                            'formas' => 'B',
                            'cliente_id' => $cliente->id,
                            'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                            'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                            'subconta_id' => $bancoActivo->subconta_id,
                            'model_id' => $receita->id,
                            'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'type' => 'R',
                            'status_pagamento' => "pago",
                            'code' => $code,
                            'descricao' => $receita->nome,
                            'movimento' => 'E',
                            'date_at' => date("Y-m-d"),
                            'user_id' => Auth::user()->id,
                            'user_open_id' => Auth::user()->id,
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $this->exercicio(),
                            'periodo_id' => $this->periodo(),
                        ]);

                        // contabilidade  DEBITAR CAIXAR
                        Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $bancoActivo->subconta_id,
                            'exercicio_id' => $this->exercicio(),
                            'periodo_id' => $this->periodo(),
                            'status' => true,
                            'movimento' => 'E',
                            'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'observacao' => "pagamento {$receita->nome}",
                            'credito' => 0,
                            'debito' => $conta->total,
                            'code' => $code,
                            'data_at' => date("Y-m-d"),
                            'entidade_id' => $entidade->empresa->id,
                        ]);

                        $valor_cash = 0;
                        $valor_multicaixa = $conta->total;
                    }

                    // CREDITAR CLIENTE
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $cliente->subconta_id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                        'status' => true,
                        'movimento' => 'S',
                        'observacao' => "pagamento {$receita->nome}",
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'credito' => $conta->total,
                        'debito' => 0,
                        'code' => $code,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                } else {
                    $tipo_pagamento->tipo = ($tipo_pagamento->tipo == "" ?? "NU");
                }

                $subconta_prestacao_servico = Subconta::where('numero', ENV('PRESTACAO_SERVICO'))->first();

                foreach ($conta->itens as $item) {

                    $DESCONTO_APLICADO = 0;

                    // 1. proço X quantidade
                    $_VALOR_PAGAR = $item->servico->preco_venda * 1;

                    $_DESCONTO = $_VALOR_PAGAR * ($DESCONTO_APLICADO / 100);

                    $_VALOR_BASE = $_VALOR_PAGAR - $_DESCONTO;

                    $_VALOR_IVA = $_VALOR_BASE * ($item->servico->taxa / 100);

                    $_VALOR_RETENCAO = 0;

                    if ($item->servico->tipo == "S") {
                        if ($item->servico->preco_venda_com_iva >= $entidade->empresa->valor_taxa_retencao_fonte) {
                            $_VALOR_RETENCAO = $_VALOR_BASE * ($entidade->empresa->taxa_retencao_fonte / 100);
                        }
                    } else {
                        $_VALOR_RETENCAO = 0;
                    }

                    $_VALOR_TOTAL = ($_VALOR_BASE + $_VALOR_IVA) -  $_VALOR_RETENCAO;

                    ItemVenda::create([
                        'produto_id' => $item->servico->id,
                        'movimento_id' => 1,
                        'quantidade' => 1,
                        'quantidade_devolvida' => 0,
                        'user_id' => Auth::user()->id,

                        'valor_pagar' => $_VALOR_TOTAL,
                        'total' => $_VALOR_TOTAL,
                        'retencao_fonte' => $_VALOR_RETENCAO,
                        'preco_unitario' => $item->servico->preco_venda - $_DESCONTO,
                        'custo' => $item->servico->preco_custo * 1,
                        'lucro' => (($item->servico->preco_venda - $item->servico->preco_custo) - $_DESCONTO) * 1,
                        'lucro_iva' => (($item->servico->preco_venda_com_iva - $item->servico->preco_custo) - $_DESCONTO) * 1,
                        'desconto_aplicado' => $DESCONTO_APLICADO,
                        'status' => 'processo',
                        'tipo_desconto' => 'P',
                        'valor_base' => $_VALOR_BASE,
                        'valor_iva' => $_VALOR_IVA,
                        'desconto_aplicado_valor' => $_DESCONTO,

                        'iva' => $item->servico->imposto,
                        'iva_taxa' => $item->servico->taxa,
                        'texto_opcional' => "",
                        'status_uso' => $status_uso,
                        'caixa_id' => $caixa_id,
                        'mesa_id' => $mesa_id,
                        'code' => NULL,
                        'numero_serie' => "",
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }

                $tot___ = $conta->total;

                if ($request->tipo_documento == "FR") {
                    if ($tipo_pagamento->tipo == "NU") {
                        $valor_cash = $tot___;
                        $valor_multicaixa = 0;
                    } else if ($tipo_pagamento->tipo == "MB") {
                        $valor_cash = 0;
                        $valor_multicaixa = $tot___;
                    } else {
                        $valor_cash = $tot___;
                        $valor_multicaixa = 0;
                    }
                } else {
                    $valor_cash = 0;
                    $valor_multicaixa = 0;
                }

                $movimentos = ItemVenda::where('code', NULL)
                    ->where('entidade_id', '=', $entidade->empresa->id)
                    ->where('status', '=', 'processo')
                    ->where('user_id', '=', Auth::user()->id)
                    ->get();

                $totalValorBase = 0;
                $totalValorIva = 0;
                $totalItems = 0;
                $totalDesconto = 0;
                $totalRetencao = 0;

                $lucro_total = 0;
                $custo_total = 0;

                if ($movimentos) {
                    foreach ($movimentos as $value) {
                        $update = ItemVenda::findOrFail($value->id);
                        $update->code = $code;
                        $update->status = "realizado";
                        $update->update();

                        $lucro_total += $value->lucro;
                        $custo_total += $value->custo;

                        $totalValorBase += $value->valor_base;
                        $totalValorIva += $value->valor_iva;
                        $totalItems += $value->quantidade;
                        $totalDesconto += $value->desconto_aplicado_valor;
                        $totalRetencao += $value->retencao_fonte;
                    }
                }

                $contarFactura = Venda::where('factura', $request->tipo_documento)
                    ->where('ano_factura', $entidade->empresa->ano_factura)
                    ->where('entidade_id', $entidade->empresa->id)
                    ->count();

                $numeroFactura = $contarFactura + 1;

                if ($entidade->empresa->tipo_facturacao != "saft") {

                    $verificarSerie = Serie::where('entidade_id', $entidade->empresa->id)
                        ->where('seriesYear', $entidade->empresa->ano_factura)
                        ->where('documentType', $request->factura)
                        ->first();

                    if (!$verificarSerie) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Infelizmente não podemos concluir essa operação, precisas criar o solicitar uma serie para esse tipo de documento!'
                        ], 404);
                    }

                    $codigo_designacao_factura = "{$request->factura} {$verificarSerie->seriesCode}/{$numeroFactura}";
                } else {

                    $codigo_designacao_factura = "{$request->factura} {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}";
                }

                $ultimoRecibo = Venda::where('factura', $request->tipo_documento)
                    ->where('ano_factura', $entidade->empresa->ano_factura)
                    ->where('entidade_id', $entidade->empresa->id)
                    ->orderBy('id', 'DESC')
                    ->first();


                if ($ultimoRecibo && $ultimoRecibo->created_at->gt(Carbon::now())) {
                    return response()->json([
                        'message' => 'Não podemos concluir a criação deste documento porque a data do seu computador não está certa.
                            Acerta a data e hora do seu computador para continuar, ou entra em contacto com os administradores do sistema!'
                    ], 400);
                }

                if (!$ultimoRecibo) {
                    $hashAnterior = "";
                } else {
                    $hashAnterior = $ultimoRecibo->hash;
                }

                $data_emissao = date("Y-m-d") . " " . date('H:i:s');
                //Manipulação de datas: data actual
                $datactual = Carbon::createFromFormat('Y-m-d H:i:s', $data_emissao);

                $rsa = new RSA(); //Algoritimo RSA

                $privatekey = $this->pegarChavePrivada();
                $publickey = $this->pegarChavePublica();

                // Lendo a private key
                $rsa->loadKey($privatekey);

                /**
                 * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
                 * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */

                $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ";{$codigo_designacao_factura};" . number_format($tot___, 2, ".", "") . ';' . $hashAnterior;

                // HASH
                $hash = 'sha1'; // Tipo de Hash
                $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

                //ASSINATURA
                $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

                // Lendo a public key
                $rsa->loadKey($publickey);

                $valor_extenso = $this->valor_por_extenso(number_format($tot___, 0));

                if ($entidade->empresa->tipo_entidade->sigla === 'HOSP') {
                    $conta_hospitalar = $conta->id;
                } else {
                    $conta_hospitalar = null;
                }

                if ($request->tipo_documento == "FR") {
                    $statusFactura = "pago";
                    $retificado = "N";
                    $convertido_factura = "N";
                    $factura_divida = "N";
                    $anulado = "N";
                    $valor_divida = 0;
                } else {
                    $statusFactura = "por pagar";
                    $retificado = "N";
                    $convertido_factura = "N";
                    $factura_divida = "Y";
                    $anulado = "N";
                    $valor_divida = $tot___;
                }

                $create_factura = Venda::create([
                    'codigo_factura' => $numeroFactura,
                    'status' => true,
                    'status_venda' => "realizado",
                    'status_factura' => $statusFactura,
                    'user_id' => Auth::user()->id,
                    'cliente_id' => $cliente->id,
                    'conta_hospotalar_id' => $conta_hospitalar,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'valor_entregue' => 0,
                    'valor_total' => $tot___,
                    'lucro_total' => $lucro_total,
                    'custo_total' => $custo_total,
                    'valor_divida' => $valor_divida,
                    'total_retencao_fonte' => $totalRetencao,
                    'valor_pago' => 0,
                    'ano_factura' => $entidade->empresa->ano_factura,
                    'prazo' => 0,
                    'valor_troco' => $tot___ - $tot___,
                    'data_emissao' => $request->data_emissao,
                    'data_documento' => $datactual,
                    'data_vencimento' => $request->data_emissao,
                    'data_disponivel' => $request->data_emissao,
                    'code' => $code,
                    'desconto_percentagem' => 0,
                    'desconto' => $totalDesconto,
                    'pagamento' => $tipo_pagamento->tipo ?? "NU",
                    'factura' => $request->tipo_documento,
                    'factura_next' => $codigo_designacao_factura,
                    'observacao' => "prestação de serviços hospitalares",
                    'referencia' => $request->referencia ?? NULL,
                    'entidade_id' => $entidade->empresa->id,

                    'nome_cliente' => $cliente->nome,
                    'documento_nif' => $cliente->nif,

                    'retificado' => $retificado,
                    'convertido_factura' => $convertido_factura,
                    'factura_divida' => $factura_divida,
                    'anulado' => $anulado,

                    'moeda' => $entidade->empresa->moeda ?? 'AOA',
                    'valor_extenso' => $valor_extenso,
                    'valor_cash' => $valor_cash,
                    'valor_multicaixa' => $valor_multicaixa,
                    'texto_hash' => $plaintext,
                    'hash' => base64_encode($signaturePlaintext),
                    'nif_cliente' => $cliente->nif,

                    'total_iva' => $totalValorIva,
                    'total_incidencia' => $totalValorBase,
                    'quantidade' => $totalItems,
                ]);

                $conta->factura_id = $create_factura->id;
                $conta->save();

                $movimentos = ItemVenda::where('code', $code)->get();

                if ($movimentos) {
                    foreach ($movimentos as $item) {
                        $subconta_prestacao_servico = Subconta::where('numero', ENV('PRESTACAO_SERVICO'))->first();
                        ## creditar na conta proveito - 61/62/63/65 - ou seja diminuir o valor sem o iva
                        Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta_prestacao_servico->id,
                            'status' => true,
                            'movimento' => 'S',
                            'credito' => $tot___,
                            'debito' => 0,
                            'observacao' => "prestação de serviços hospitalares",
                            'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'code' => $code,
                            'data_at' => $request->data_emissao,
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $this->exercicio(),
                            'periodo_id' => $this->periodo(),
                        ]);

                        $update = ItemVenda::findOrFail($item->id);
                        $update->factura_id = $create_factura->id;
                        $update->update();
                    }
                }

                if ($entidade->empresa->tipo_facturacao != "saft") {
                    if ($create_factura['factura'] != "FP" || $create_factura['factura'] != "PF") { //Proforma
                        dispatch(new SubmitElectronicDocumentToAgtJob(
                            $create_factura['id']
                        ));
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pagamento registado com sucesso.',
                'conta' => $conta,
                'factura' => $create_factura
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function fecharConta(string $id)
    {
        DB::beginTransaction();

        try {

            $this->service->fecharConta($id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Conta fechada com sucesso.'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function reabrirConta(string $id)
    {
        DB::beginTransaction();

        try {

            $this->service->reabrirConta($id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Conta reaberta com sucesso.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function cancelarConta(string $id)
    {
        DB::beginTransaction();

        try {

            $this->service->cancelarConta($id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Conta cancelada com sucesso.'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
