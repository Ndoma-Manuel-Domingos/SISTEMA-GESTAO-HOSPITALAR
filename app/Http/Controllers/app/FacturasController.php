<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitChavesSaft;
use App\Http\Controllers\TraitHelpers;
use App\Jobs\SubmitElectronicDocumentReciboToAgtJob;
use App\Jobs\SubmitElectronicDocumentToAgtJob;
use App\Models\Subconta;
use App\Models\Caixa;
use App\Models\Seguradora;
use App\Models\Cliente;
use App\Models\Consulta;
use App\Models\Exame;
use App\Models\PlanoTratamento;
use App\Models\ContaCliente;
use App\Models\Entidade;
use App\Models\Estoque;
use App\Models\FacturaOriginal;
use App\Models\ItemFacturaOriginal;
use App\Models\ItemNotaCredito;
use App\Models\ItemRecibo;
use App\Models\ItemVenda;
use App\Models\Loja;
use App\Models\Movimento;
use App\Models\ContaBancaria;
use App\Models\OperacaoFinanceiro;
use App\Models\MovimentoContaCliente;
use App\Models\NotaCredito;
use App\Models\Produto;
use App\Models\Receita;
use App\Models\Dispesa;
use App\Models\LojaProduto;
use App\Models\Lote;
use App\Models\Recibo;
use App\Models\Registro;
use App\Models\Serie;
use App\Models\TipoPagamento;
use App\Models\User;
use App\Models\UserLoja;
use App\Models\Venda;
use App\Traits\UsesAgtConfig;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

use phpseclib\Crypt\RSA;

use Barryvdh\DomPDF\Facade\Pdf;

class FacturasController extends Controller
{
    use TraitChavesSaft, UsesAgtConfig, TraitHelpers;

    public function __construct()
    {
        $this->loadAgtConfig();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar facturas')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $facturas = Venda::with(['cliente', 'user'])
            ->when($request->tipo_documento, function ($query, $value) {
                $query->where('factura', $value);
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('data_emissao', '>=', Carbon::parse($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('data_emissao', '<=', Carbon::parse($value));
            })
            ->when($request->cliente_id, function ($query, $value) {
                $query->where('cliente_id', $value);
            })
            ->when($request->user_id, function ($query, $value) {
                $query->where('user_id', $value);
            })
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->selectRaw("
                id,
                factura_next,
                cliente_id,
                user_id,
                valor_total,
                valor_divida,
                valor_pago,
                quantidade,
                data_documento,
                data_emissao,

                retificado,
                convertido_factura,
                factura_divida,
                anulado,
                code,
                status_factura,
                pagamento,

                documento_nif,
                nome_cliente,

                factura,
                entidade_id,
                created_at
            ");

        $recibos = Recibo::with(['cliente', 'user'])
            ->when($request->tipo_documento, function ($query, $value) {
                $query->where('factura', $value);
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('data_emissao', '>=', Carbon::parse($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('data_emissao', '<=', Carbon::parse($value));
            })
            ->when($request->cliente_id, function ($query, $value) {
                $query->where('cliente_id', $value);
            })
            ->when($request->user_id, function ($query, $value) {
                $query->where('user_id', $value);
            })
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->selectRaw("
                id,
                factura_next,
                cliente_id,
                user_id,
                valor_total,
                valor_divida,
                valor_pago,
                quantidade,
                data_documento,
                data_emissao,


                documento_nif,
                nome_cliente,

                retificado,
                convertido_factura,
                factura_divida,
                anulado,
                code,
                status_factura,
                pagamento,

                factura,
                entidade_id,
                created_at
            ");

        $notasCredito = NotaCredito::with(['cliente', 'user'])
            ->when($request->tipo_documento, function ($query, $value) {
                $query->where('factura', $value);
            })
            ->when($request->cliente_id, function ($query, $value) {
                $query->where('cliente_id', $value);
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('data_emissao', '>=', Carbon::parse($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('data_emissao', '<=', Carbon::parse($value));
            })
            ->when($request->user_id, function ($query, $value) {
                $query->where('user_id', $value);
            })
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->selectRaw("
                id,
                factura_next,
                cliente_id,
                user_id,
                valor_total,
                valor_divida,
                valor_pago,
                quantidade,
                data_documento,
                data_emissao,


                documento_nif,
                nome_cliente,

                retificado,
                convertido_factura,
                factura_divida,
                anulado,
                code,
                status_factura,
                pagamento,

                factura,
                entidade_id,
                created_at
            ");

        $documentos = $facturas
            ->union($recibos)
            ->union($notasCredito)
            ->orderBy('created_at', 'desc')
            ->get();

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        $clientes = Cliente::where('entidade_id', $entidade->empresa->id)->get();
        $users = User::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "caixa" => Caixa::where('active', true)
                ->where('entidade_id', $entidade->empresa->id)
                ->where('status_admin', 'liberado')
                ->first(),
            "documentos" => $documentos,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "lojas" => $lojas,
            "clientes" => $clientes,
            "users" => $users,
            'requests' => $request->all('data_inicio', 'data_final', 'loja_id', 'tipo_documento', 'cliente_id', 'user_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.facturas.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();

        if (!$user->can('criar facturas')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $movimentos = NULL;
        $total_pagar = NULL;
        $total_unidades = NULL;
        $total_produtos = NULL;

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $movimentos = ItemVenda::where('code', NULL)
            ->where('status', 'processo')
            ->where('user_id', Auth::user()->id)
            ->where('status_uso', "CAIXA")
            ->where('entidade_id', $entidade->empresa->id)
            ->with(['produto'])->get();

        $total_pagar = ItemVenda::where('code', NULL)
            ->where('status', 'processo')
            ->where('status_uso', "CAIXA")
            ->where('user_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->sum('valor_pagar');

        $total_retencao = ItemVenda::where('code', NULL)
            ->where('status_uso', "CAIXA")
            ->where('status', 'processo')
            ->where('user_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id)->sum('retencao_fonte');

        $total_produtos = ItemVenda::where('code', NULL)
            ->where('status', 'processo')
            ->where('status_uso', "CAIXA")
            ->where('user_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id)->count();

        $total_unidades = ItemVenda::where('code', NULL)
            ->where('status_uso', "CAIXA")
            ->where('status', 'processo')
            ->where('user_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id)->sum('quantidade');

        $seguradoras = Seguradora::where('entidade_id', $entidade->empresa->id)->get();

        $caixas = Caixa::where('entidade_id', $entidade->empresa->id)
            ->where('status_admin', 'liberado')->get();
        $bancos = ContaBancaria::where('entidade_id', $entidade->empresa->id)->get();
        $receitas = Receita::where('type', 'R')->where('entidade_id', $entidade->empresa->id)->get();

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "caixas" => $caixas,
            "bancos" => $bancos,
            "receitas" => $receitas,
            "forma_pagmento" => TipoPagamento::get(),

            "caixa" => Caixa::where('active', true)
                ->where('entidade_id', $entidade->empresa->id)
                ->where('status_admin', 'liberado')
                ->first(),

            "clientes" => Cliente::where('entidade_id', $entidade->empresa->id)->get(),
            "parentes" => Cliente::where('parent_id', null)->where('entidade_id', $entidade->empresa->id)->get(),

            "produtos" => Produto::with(['taxa_imposto'])->where("aplicado", "N")
                ->whereIn("id", $meus_produtos)
                ->where('entidade_id', $entidade->empresa->id)
                ->get(),

            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "seguradoras" => $seguradoras,
            "total_unidades" => $total_unidades,
            "total_produtos" => $total_produtos,
            "total_retencao" => $total_retencao,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.facturas.create', $head);
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

        if (!$user->can('criar facturas')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(["empresa.tipo_entidade"])->findOrFail(Auth::user()->id);

        if ($entidade->empresa->tipo_entidade->sigla === 'HOSP') {
            if ($request->quem_vai_cubrir == "P") {
                $request->validate([
                    'parent_id' => 'required|exists:clientes,parent_id',
                ]);
            }
            if ($request->quem_vai_cubrir == "S") {
                $request->validate([
                    'seguradora_id' => 'required|exists:seguradoras,id',
                ]);
            }
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $vendas_produtos = Receita::whereIn("nome", ["Vendas", "Vendas de Produtos"])->where("type", "R")->where("entidade_id", $entidade->empresa->id)->first();
            $prestacao_servicos = Receita::whereIn("nome", ["Prestações de Serviços"])->where("type", "R")->where("entidade_id", $entidade->empresa->id)->first();

            $subconta_compra_mercadoria = Subconta::where('entidade_id', $entidade->empresa->id)->where('numero', ENV('COMPRA_MERCADORIA'))->first();
            $subconta_mercadoria = Subconta::where('entidade_id', $entidade->empresa->id)->where('numero', ENV('MERCADORIA'))->first();
            $subconta_desconto_comercial_venda = Subconta::where('entidade_id', $entidade->empresa->id)->where('numero', ENV('DESCONTO_COMERCIAL_VENDA'))->first();
            $subconta_desconto_financeiro_venda = Subconta::where('entidade_id', $entidade->empresa->id)->where('numero', ENV('DESCONTO_FINANCEIRO_VENDA'))->first();

            $caixaActivo = Caixa::where("active", true)
                ->where("status", "aberto")
                ->where('status_admin', 'liberado')
                ->where("user_open_id", Auth::user()->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->first();

            $movimentos = ItemVenda::where("code", NULL)
                ->where("entidade_id", $entidade->empresa->id)
                ->where("status", "processo")
                ->where("status_uso", "CAIXA")
                ->where("user_id", Auth::user()->id)
                ->get();

            if (count($movimentos) == 0) {
                return response()->json(["error" => true, "message" => "Por favor, selecione itens para esta documentos!"], 404);
            }

            $code = uniqid(time());

            $totalValorBase = 0;
            $totalValorIva = 0;
            $totalItems = 0;
            $totalDesconto = 0;
            $totalRetencao = 0;
            $totalPagar = 0;
            $totalValorItem = 0;

            $lucro_total = 0;
            $custo_total = 0;

            if ($movimentos) {
                foreach ($movimentos as $value) {
                    $update = ItemVenda::findOrFail($value->id);
                    $produto = Produto::with('estoque')->findOrFail($update->produto_id);
                    $desconto = 0;

                    if ($request->tipo_desconto == "F" && $request->desconto_percentagem != 0) {
                        $desconto = ($update->preco_unitario * $update->quantidade) * ($request->desconto_percentagem ?? 0) / 100;

                        #DEBITAMOS NA CONTA 76.3
                        Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta_desconto_financeiro_venda->id,
                            'status' => true,
                            'movimento' => 'S',
                            'credito' => 0,
                            'debito' => $desconto,
                            'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'observacao' => $request->observacao,
                            'code' => $code,
                            'data_at' => date("Y-m-d"),
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $this->exercicio(),
                            'periodo_id' => $this->periodo(),
                        ]);
                    } else if ($request->tipo_desconto == "C" && $request->desconto_percentagem != 0) {
                        $desconto = ($update->preco_unitario * $update->quantidade) * ($request->desconto_percentagem ?? 0) / 100;

                        #DEBITAMOS NA CONTA 61.8
                        Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta_desconto_comercial_venda->id,
                            'status' => true,
                            'movimento' => 'S',
                            'credito' => 0,
                            'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'debito' => $desconto,
                            'observacao' => $request->observacao,
                            'code' => $code,
                            'data_at' => date("Y-m-d"),
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $this->exercicio(),
                            'periodo_id' => $this->periodo(),
                        ]);
                    }

                    if (in_array($update->tipo_desconto, ['F', 'C']) && $update->desconto_aplicado != 0) {
                        $desconto = $update->desconto_aplicado_valor;
                    }

                    $update->desconto_aplicado = $request->desconto_percentagem;
                    $update->desconto_aplicado_valor = $desconto;
                    $update->tipo_desconto = $request->tipo_desconto;
                    $update->custo = $produto->preco_custo;
                    $update->total = $update->valor_pagar;
                    $update->valor_pagar -= $desconto; // menos o desconto
                    $update->lucro = (($produto->preco_venda - $produto->preco_custo) - $desconto) * $update->quantidade;

                    $totalValorItem += $update->total;
                    $totalPagar += $update->valor_pagar;
                    $lucro_total += $update->lucro;
                    $custo_total += $update->custo;
                    $totalValorBase += $value->valor_base;
                    $totalValorIva += $value->valor_iva;
                    $totalItems += $value->quantidade;
                    $totalDesconto += $update->desconto_aplicado_valor;
                    $totalRetencao += $update->retencao_fonte;
                }
            }

            $total_prestacao_servico = 0;
            $total_vendas = 0;

            // $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

            // $loja = Loja::where("status", "activo")
            //     ->whereIn("id", $minhas_lojas)
            //     ->where("entidade_id", $entidade->empresa->id)
            //     ->first();

            $loja = $this->LOJA_ACTIVA_USER();

            if (!$loja) {
                return response()->json(["error" => true, "message" => "Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto. Por favor activa uma loja/armazém que tem este produto!"], 404);
            }

            foreach ($movimentos as $item) {
                $produt = Produto::findOrFail($item->produto_id);

                if ($produt->tipo == "P") {
                    $total_vendas += ($item->valor_pagar - $item->desconto_aplicado_valor);
                }

                if ($produt->tipo == "S") {
                    $total_prestacao_servico += ($item->valor_pagar - $item->desconto_aplicado_valor);
                }
            }

            $cliente = Cliente::findOrFail($request->cliente_id);

            $subconta_cliente = Subconta::find($cliente->subconta_id);

            $contarFactura = Venda::where("factura", $request->factura)
                ->where("ano_factura", $entidade->empresa->ano_factura)
                ->where("entidade_id", $entidade->empresa->id)
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

            if ($request->factura == "FR") {

                // ESTOQUE
                foreach ($movimentos as $item) {
                    $produt = Produto::findOrFail($item->produto_id);

                    if ($produt->tipo == "P") {

                        $gestao_quantidade = Estoque::where("loja_id", $loja->id)
                            ->where("produto_id", $produt->id)
                            ->where("stock", ">=", 0)
                            ->where("entidade_id", $entidade->empresa->id)
                            ->first();

                        $verificar_quantidade = (float) $produt->total_produto_loja_activa();

                        if ($verificar_quantidade <= 0) {
                            return response()->json(["error" => true, "message" => "A loja ativa não tem este produto em estoque para comercialização."], 404);
                        }

                        if ($verificar_quantidade <= $produt->total_produto_minimo_loja_activa()) {
                            return response()->json(["error" => true, "message" => "A quantidade deste produto em estoque está abaixo do limite crítico, impossibilitando a venda no momento."], 404);
                        }

                        if ($produt->total_produto_loja_activa() <= $produt->total_produto_minimo_loja_activa()) {
                            return response()->json(["error" => true, "message" => "Stock insuficiente para o produto: {$produt->nome}."], 404);
                        }

                        $update_gestao_quantidade = Estoque::find($gestao_quantidade->id);

                        if ($update_gestao_quantidade) {
                            $update_gestao_quantidade->stock = $update_gestao_quantidade->stock - $item->quantidade;
                            $update_gestao_quantidade->update();
                        }
                    }
                }

                if ($request->observacao == null || $request->observacao == "") {
                    $request->observacao = "Pagamento referente a factura: {$codigo_designacao_factura}";
                }

                if ($request->forma_de_pagamento == null) {
                    return response()->json(["message" => "Por ser uma factura recibo, precisas escolar a forma de pagamento, isto em pagamentos!"], 404);
                }

                if ($request->forma_de_pagamento == "NU") {

                    if ($request->caixa_id == "") {
                        return response()->json(["message" => "Deves selecionar o caixa onde será retirado o valor para o pagamento da factura!"], 404);
                    }

                    $caixa = Caixa::findOrFail($request->caixa_id);

                    $valor_cash = $request->valor_entregue;
                    $valor_multicaixa = 0;
                    $request->total_pagar = $request->valor_entregue;

                    // contabilidade  DEBITAR CAIXAR
                    Movimento::create([
                        "user_id" => Auth::user()->id,
                        "subconta_id" => $caixa->subconta_id,
                        "exercicio_id" => $this->exercicio(),
                        "periodo_id" => $this->periodo(),
                        "status" => true,
                        "movimento" => "E",
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        "observacao" => $request->observacao,
                        "credito" => 0,
                        "debito" => $request->total_pagar,
                        "code" => $code,
                        "data_at" => $request->data_emissao,
                        "entidade_id" => $entidade->empresa->id,
                    ]);

                    // CREDITAR CLIENTE
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $cliente->subconta_id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                        'status' => true,
                        'movimento' => 'S',
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'observacao' => $request->observacao,
                        'credito' => $request->total_pagar + $totalDesconto,
                        'debito' => 0,
                        'code' => $code,
                        'data_at' => $request->data_emissao,
                        'entidade_id' => $entidade->empresa->id,
                    ]);

                    // finanças
                    if ($total_vendas != 0) {
                        OperacaoFinanceiro::create([
                            'nome' => $vendas_produtos->nome,
                            'status' => "pago",
                            'motante' => $total_vendas,
                            'formas' => 'C',
                            'cliente_id' => $cliente->id,
                            'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : null,
                            'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                            'subconta_id' => $caixa->subconta_id,
                            'model_id' => $vendas_produtos->id,
                            'type' => 'R',
                            'status_pagamento' => "pago",
                            'code' => $code,
                            'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'descricao' => $request->observacao,
                            'movimento' => 'E',
                            'date_at' => $request->data_emissao,
                            'user_id' => Auth::user()->id,
                            'user_open_id' => Auth::user()->id,
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $this->exercicio(),
                            'periodo_id' => $this->periodo(),
                        ]);
                    }

                    if ($total_prestacao_servico != 0) {
                        OperacaoFinanceiro::create([
                            'nome' => $prestacao_servicos->nome,
                            'status' => "pago",
                            'motante' => $total_prestacao_servico,
                            'formas' => 'C',
                            'cliente_id' => $cliente->id,
                            'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                            'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                            'subconta_id' => $caixa->subconta_id,
                            'model_id' => $prestacao_servicos->id,
                            'type' => 'R',
                            'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'status_pagamento' => "pago",
                            'code' => $code,
                            'descricao' => $request->observacao,
                            'movimento' => 'E',
                            'date_at' => $request->data_emissao,
                            'user_id' => Auth::user()->id,
                            'user_open_id' => Auth::user()->id,
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $this->exercicio(),
                            'periodo_id' => $this->periodo(),
                        ]);
                    }
                }

                if ($request->forma_de_pagamento == "MB" || $request->forma_de_pagamento == "TE" || $request->forma_de_pagamento == "DE") {

                    if ($request->banco_id == "") {
                        return response()->json(['message' => 'Deves selecionar o banco onde será retirado o valor para o pagamento da factura!'], 404);
                    }

                    if ($request->numero_operacao_finanaceira == "") {
                        return response()->json(['message' => 'Deves informar o numero de transação do comprovativo!'], 404);
                    }

                    $verificarNumeroComprovativo = Venda::where(
                        'numero_operacao_finanaceira',
                        $request->numero_operacao_finanaceira
                    )->first();

                    if ($verificarNumeroComprovativo) {
                        return response()->json([
                            'message' => 'Não foi possível concluir a operação. O número da transação ou do comprovativo informado já se encontra registado no sistema.'
                        ], 404);
                    }

                    $valor_cash = 0;
                    $valor_multicaixa = $request->valor_entregue_multicaixa;
                    $request->total_pagar = $request->valor_entregue_multicaixa;

                    $banco = ContaBancaria::findOrFail($request->banco_id);

                    // contabilidade  DEBITAR BANCO
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $banco->subconta_id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                        'status' => true,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'movimento' => 'E',
                        'observacao' => $request->observacao,
                        'credito' => 0,
                        'debito' => $request->total_pagar,
                        'code' => $code,
                        'data_at' => $request->data_emissao,
                        'entidade_id' => $entidade->empresa->id,
                    ]);

                    // CREDITAR CLIENTE
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $cliente->subconta_id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                        'status' => true,
                        'movimento' => 'S',
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'observacao' => $request->observacao,
                        'credito' => $request->total_pagar + $totalDesconto,
                        'debito' => 0,
                        'code' => $code,
                        'data_at' => $request->data_emissao,
                        'entidade_id' => $entidade->empresa->id,
                    ]);

                    // finanças

                    if ($total_vendas != 0) {
                        OperacaoFinanceiro::create([
                            'nome' => $vendas_produtos->nome,
                            'status' => "pago",
                            'motante' => $total_vendas,
                            'formas' => 'B',
                            'cliente_id' => $cliente->id,
                            'code_caixa' => $caixaActivo->code_caixa ?? NULL,
                            'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                            'subconta_id' => $banco->subconta_id,
                            'model_id' => $vendas_produtos->id,
                            'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'type' => 'R',
                            'status_pagamento' => "pago",
                            'code' => $code,
                            'descricao' => $request->observacao,
                            'movimento' => 'E',
                            'date_at' => $request->data_emissao,
                            'user_id' => Auth::user()->id,
                            'user_open_id' => Auth::user()->id,
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $this->exercicio(),
                            'periodo_id' => $this->periodo(),
                        ]);
                    }

                    if ($total_prestacao_servico != 0) {
                        OperacaoFinanceiro::create([
                            'nome' => $prestacao_servicos->nome,
                            'status' => "pago",
                            'motante' => $total_prestacao_servico,
                            'formas' => 'B',
                            'cliente_id' => $cliente->id,
                            'code_caixa' => $caixaActivo->code_caixa ?? NULL,
                            'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                            'subconta_id' => $banco->subconta_id,
                            'model_id' => $prestacao_servicos->id,
                            'type' => 'R',
                            'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'status_pagamento' => "pago",
                            'code' => $code,
                            'descricao' => $request->observacao,
                            'movimento' => 'E',
                            'date_at' => $request->data_emissao,
                            'user_id' => Auth::user()->id,
                            'user_open_id' => Auth::user()->id,
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $this->exercicio(),
                            'periodo_id' => $this->periodo(),
                        ]);
                    }
                }

                if ($request->forma_de_pagamento == "OU") {

                    if ($request->caixa_id == "") {
                        return response()->json(['message' => 'Deves selecionar o caixa onde será retirado o valor para o pagamento da factura!'], 404);
                    }

                    $valor_cash =  $request->valor_entregue;
                    $valor_multicaixa = $request->valor_entregue_multicaixa_input;

                    $caixa = Caixa::findOrFail($request->caixa_id);

                    // contabilidade  DEBITAR CAIXAR
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $caixa->subconta_id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                        'status' => true,
                        'movimento' => 'E',
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'observacao' => $request->observacao,
                        'credito' => 0,
                        'debito' => $request->valor_entregue,
                        'code' => $code,
                        'data_at' => $request->data_emissao,
                        'entidade_id' => $entidade->empresa->id,
                    ]);

                    // CREDITAR CLIENTE
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $cliente->subconta_id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                        'status' => true,
                        'movimento' => 'S',
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'observacao' => $request->observacao,
                        'credito' => $request->valor_entregue,
                        'debito' => 0,
                        'code' => $code,
                        'data_at' => $request->data_emissao,
                        'entidade_id' => $entidade->empresa->id,
                    ]);

                    // finanças
                    OperacaoFinanceiro::create([
                        'nome' => $prestacao_servicos->nome,
                        'status' => "pago",
                        'motante' => $request->valor_entregue,
                        'formas' => 'C',
                        'cliente_id' => $cliente->id,
                        'code_caixa' => $caixaActivo->code_caixa ?? NULL,
                        'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                        'subconta_id' => $caixa->subconta_id,
                        'model_id' => $prestacao_servicos->id,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'type' => 'R',
                        'status_pagamento' => "pago",
                        'code' => $code,
                        'descricao' => $request->observacao,
                        'movimento' =>  'E',
                        'date_at' => $request->data_emissao,
                        'user_id' => Auth::user()->id,
                        'user_open_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);

                    if ($request->banco_id == "") {
                        // return redirect()->back()->with('danger', 'Deves selecionar o banco onde será retirado o valor para o pagamento da factura!');
                        return response()->json(['message' => 'Deves selecionar o banco onde será retirado o valor para o pagamento da factura!'], 404);
                    }

                    $banco = ContaBancaria::findOrFail($request->banco_id);

                    // contabilidade  DEBITAR BANCO
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $banco->subconta_id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                        'status' => true,
                        'movimento' => 'E',
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'observacao' => $request->observacao,
                        'credito' => 0,
                        'debito' => $request->valor_entregue_multicaixa,
                        'code' => $code,
                        'data_at' => $request->data_emissao,
                        'entidade_id' => $entidade->empresa->id,
                    ]);

                    // CREDITAR CLIENTE
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $cliente->subconta_id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                        'status' => true,
                        'movimento' => 'S',
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'observacao' => $request->observacao,
                        'credito' => $request->valor_entregue_multicaixa,
                        'debito' => 0,
                        'code' => $code,
                        'data_at' => $request->data_emissao,
                        'entidade_id' => $entidade->empresa->id,
                    ]);

                    // CREDITAR CLIENTE - VALOR DO dESCONTO PAR ENCERRAR A CONTA
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $cliente->subconta_id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                        'status' => true,
                        'movimento' => 'S',
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'observacao' => $request->observacao,
                        'credito' => $totalDesconto,
                        'debito' => 0,
                        'code' => $code,
                        'data_at' => $request->data_emissao,
                        'entidade_id' => $entidade->empresa->id,
                    ]);

                    OperacaoFinanceiro::create([
                        'nome' => $prestacao_servicos->nome,
                        'status' => "pago",
                        'motante' => $request->valor_entregue_multicaixa,
                        'formas' => 'B',
                        'cliente_id' => $cliente->id,
                        'code_caixa' => $caixaActivo->code_caixa ?? NULL,
                        'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                        'subconta_id' => $banco->subconta_id,
                        'model_id' => $prestacao_servicos->id,
                        'type' => 'R',
                        'status_pagamento' => "pago",
                        'code' => $code,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'descricao' => $request->observacao,
                        'movimento' => 'E',
                        'date_at' => $request->data_emissao,
                        'user_id' => Auth::user()->id,
                        'user_open_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);
                }
            } else {
                $request->forma_de_pagamento =  ($request->forma_de_pagamento == "" ?? "NU");
            }

            $ultimoRecibo = Venda::where('factura', $request->factura)
                ->where('ano_factura', '=', $entidade->empresa->ano_factura)
                ->where('entidade_id', '=', $entidade->empresa->id)
                ->orderBy('id', 'DESC')
                ->first();

            if ($ultimoRecibo && $ultimoRecibo->created_at->gt(Carbon::now())) {
                return response()->json([
                    'message' => 'Não podemos concluir a criação deste documento porque a data do seu computador não está certa.
                    Acerta a data e hora do seu computador para continuar, ou entra em contacto com os administradores do sistema!'
                ], 404);
            }

            if (!$ultimoRecibo) {
                $hashAnterior = "";
            } else {
                $hashAnterior = $ultimoRecibo->hash;
            }

            $dias = 0;
            if ($request->data_vencimento == 0) {
                $dias = 0;
            } else if ($request->data_vencimento == 15) {
                $dias = 15;
            } else if ($request->data_vencimento == 30) {
                $dias = 30;
            } else if ($request->data_vencimento == 45) {
                $dias = 45;
            } else if ($request->data_vencimento == 60) {
                $dias = 60;
            } else if ($request->data_vencimento == 90) {
                $dias = 90;
            }

            $cliente = Cliente::findOrFail($request->cliente_id);

            $request->data_emissao = $request->data_emissao . " " . date('H:i:s');

            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', $request->data_emissao);

            $rsa = new RSA(); //Algoritimo RSA

            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();

            // Lendo a private key
            $rsa->loadKey($privatekey);

            /**
             * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
             * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */

            $tot___ = $request->total_retencao + $request->total_pagar;

            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ";{$codigo_designacao_factura};" . number_format($tot___, 2, ".", "") . ';' . $hashAnterior;

            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

            // Lendo a public key
            $rsa->loadKey($publickey);

            $request->data_vencimento = date('Y-m-d', strtotime($request->data_emissao . ' + ' . $dias . ' days'));

            $statusFactura = "";

            if ($request->factura == "FR") {
                $statusFactura = "pago";
                $retificado = "N";
                $convertido_factura = "N";
                $factura_divida = "N";
                $anulado = "N";
            } else {
                $statusFactura = "por pagar";
                $retificado = "N";
                $convertido_factura = "N";
                $factura_divida = "Y";
                $anulado = "N";
            }

            if ($request->factura == "FR") {
                if ($request->forma_de_pagamento == "NU") {
                    $valor_cash = $request->total_pagar;
                    $valor_multicaixa = 0;
                } else if ($request->forma_de_pagamento == "MB") {
                    $valor_cash = 0;
                    $valor_multicaixa = $request->total_pagar;
                } else {
                    $valor_cash = $request->valor_entregue;
                    $valor_multicaixa = $request->valor_entregue_multicaixa;
                }
            } else {
                $valor_cash = 0;
                $valor_multicaixa = 0;
            }

            $verificar_factura = Venda::where('factura_next', $codigo_designacao_factura)
                ->where('ano_factura', $entidade->empresa->ano_factura)
                ->where('entidade_id', $entidade->empresa->id)
                ->get();

            if (count($verificar_factura) != 0) {
                Alert::success('Sucesso', "Não pode concluir essa factura, parece que a mesma tentou ser duplicada, por favor verifica-se ja tens uma factura com esta referência: {$codigo_designacao_factura} !");
                return redirect()->route('facturas.create')->with('danger', "Não pode concluir essa factura, parece que a mesma tentou ser duplicada, por favor verifica-se ja tens uma factura com esta referência: {$codigo_designacao_factura} !");
            }


            if ($movimentos) {
                foreach ($movimentos as $value) {
                    $update = ItemVenda::findOrFail($value->id);
                    $update->code = $code;
                    $update->status = "realizado";
                    $update->update();
                }
            }

            $valor_extenso = $this->valor_por_extenso(number_format($request->total_pagar, 0));

            if ($entidade->empresa->tipo_entidade->sigla === 'HOSP') {
                if ($request->quem_vai_cubrir !== "P" && $request->quem_vai_cubrir !== "S") {
                    $request->parent_id = null;
                    $request->seguradora_id = null;
                }

                if ($request->quem_vai_cubrir === "P") {
                    $request->seguradora_id = null;
                    $request->parent_id = $request->parent_id;
                }

                if ($request->quem_vai_cubrir === "S") {
                    $request->parent_id = null;
                    $request->seguradora_id = $request->seguradora_id;
                }
            } else {
                $request->parent_id = null;
                $request->seguradora_id = null;
            }

            $create_factura = Venda::create([
                'codigo_factura' => $numeroFactura,
                'status' => true,
                'status_venda' => "realizado",
                'status_factura' => $statusFactura,
                'parent_id' => $request->parent_id ?? null,
                'seguradora_id' => $request->seguradora_id ?? null,
                'user_id' => Auth::user()->id,
                'cliente_id' => $request->cliente_id,
                'valor_entregue' => 0,
                'valor_total' => $request->total_pagar - $totalDesconto,
                'lucro_total' => $lucro_total,
                'custo_total' => $custo_total,
                'valor_divida' => $request->factura == "FR" ? 0 : ($request->factura == "PP" ? 0 : $request->total_pagar),
                'total_retencao_fonte' => $totalRetencao,
                'valor_pago' => 0,
                'ano_factura' => $entidade->empresa->ano_factura,
                'prazo' => $dias,
                'valor_troco' => $request->total_pagar - $request->total_pagar,
                'data_emissao' => $request->data_emissao,
                'data_documento' => $datactual,
                'data_vencimento' => $request->data_vencimento,
                'data_disponivel' => $request->data_disponivel,
                'code' => $code,
                'desconto_percentagem' => $request->desconto_percentagem,
                'desconto' => $totalDesconto,
                'pagamento' => $request->forma_de_pagamento,
                'factura' => $request->factura,
                'factura_next' => $codigo_designacao_factura,
                'observacao' => $request->observacao,
                'referencia' => $request->referencia,
                'entidade_id' => $entidade->empresa->id,
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                'nome_cliente' => $cliente->nome,
                'documento_nif' => $cliente->nif,

                'retificado' => $retificado,
                'convertido_factura' => $convertido_factura,
                'factura_divida' => $factura_divida,
                'anulado' => $anulado,

                'moeda' => $entidade->empresa->moeda ?? 'AKZ',
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

            $movimentos = ItemVenda::with(['produto.taxa_imposto'])->where('code', $code)->get();

            foreach ($movimentos as $item) {

                $produt = Produto::findOrFail($item->produto_id);
                // SElecinar em que lote este produto pertence para se comercializado ou reduzido naquele stock
                $lote = Lote::where("produto_id", $produt->id)
                    ->where("codigo_barra", $produt->codigo_barra)
                    ->where("entidade_id", $entidade->empresa->id)
                    ->first();

                if ($lote && $lote->status == "expirado" && $lote->data_validade <= date("Y-m-d")) {
                    return response()->json(["error" => true, "message" => "O produto: { $produt->nome } parece estar expirado, por isso não é possível finalizar a venda, visando a segurança da população."], 404);
                }

                Registro::create([
                    "documento" => $codigo_designacao_factura,
                    "registro" => "Saída de Stock",
                    "data_registro" => date("Y-m-d"),
                    "documento_id" => $create_factura->id ?? NULL,
                    "preco_unitario" => $item->preco_unitario,
                    "quantidade" => $item->quantidade,
                    "tipo" => "S",
                    'status' => 'V',
                    "produto_id" => $produt->id,
                    "observacao" => "Saída do produto {$produt->nome} para venda",
                    "loja_id" => $loja->id,
                    "lote_id" => $lote ? $lote->id : NULL,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);
            }

            if ($movimentos) {
                foreach ($movimentos as $item) {

                    $subconta_iva = Subconta::where('numero', ENV('IVA_LIQUIDADO'))->first();
                    $subconta_venda_mercadoria = Subconta::where('numero', ENV('VENDA_DE_MERCADORIA'))->first();
                    $subconta_prestacao_servico = Subconta::where('numero', ENV('PRESTACAO_SERVICO'))->first();
                    $subconta_custo_mercadoria = Subconta::where('numero', ENV('CUSTO_MERCADORIA_VENDIDA'))->first();

                    $produt = Produto::findOrFail($item->produto_id);

                    if ($request->factura == "FT" || $request->factura == "FR") {

                        if ($produt->tipo == "P") {
                            ## creditar na conta proveito - 61/62/63/65 - ou seja diminuir o valor sem o iva
                            $movimeto = Movimento::create([
                                'user_id' => Auth::user()->id,
                                'subconta_id' => $subconta_venda_mercadoria->id,
                                'status' => true,
                                'movimento' => 'S',
                                'credito' => ($item->valor_pagar ?? 0) + ($item->desconto_aplicado_valor ?? 0),
                                'debito' => 0,
                                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                                'observacao' => $request->observacao,
                                'code' => $code,
                                'data_at' => $request->data_emissao,
                                'entidade_id' => $entidade->empresa->id,
                                'exercicio_id' => $this->exercicio(),
                                'periodo_id' => $this->periodo(),
                            ]);
                        }

                        if ($produt->tipo == "S") {
                            ## creditar na conta proveito - 61/62/63/65 - ou seja diminuir o valor sem o iva
                            $movimeto = Movimento::create([
                                'user_id' => Auth::user()->id,
                                'subconta_id' => $produt->subconta_id,
                                'status' => true,
                                'movimento' => 'S',
                                'credito' => ($item->valor_pagar ?? 0) + ($item->desconto_aplicado_valor ?? 0),
                                'debito' => 0,
                                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                                'observacao' => $request->observacao,
                                'code' => $code,
                                'data_at' => $request->data_emissao,
                                'entidade_id' => $entidade->empresa->id,
                                'exercicio_id' => $this->exercicio(),
                                'periodo_id' => $this->periodo(),
                            ]);
                        }

                        if ($entidade->empresa->tipo_inventario == "PERMANENTE") {
                            ## creditar na conta proveito - 26 - ou seja diminuir o valor sem o iva
                            $movimeto = Movimento::create([
                                'user_id' => Auth::user()->id,
                                'subconta_id' => $produt->subconta_id,
                                'status' => true,
                                'movimento' => 'S',
                                'credito' => ($produt->preco_custo ?? 0) * $item->quantidade,
                                'debito' => 0,
                                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                                'observacao' => $request->observacao,
                                'code' => $code,
                                'data_at' => $request->data_emissao,
                                'entidade_id' => $entidade->empresa->id,
                                'exercicio_id' => $this->exercicio(),
                                'periodo_id' => $this->periodo(),
                            ]);

                            ## custo de mercadoria
                            $movimeto = Movimento::create([
                                'user_id' => Auth::user()->id,
                                'subconta_id' => $subconta_custo_mercadoria->id,
                                'status' => true,
                                'movimento' => 'S',
                                'credito' => 0,
                                'debito' => ($produt->preco_custo ?? 0) * $item->quantidade,
                                'observacao' => $request->observacao,
                                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                                'code' => $code,
                                'data_at' => $request->data_emissao,
                                'entidade_id' => $entidade->empresa->id,
                                'exercicio_id' => $this->exercicio(),
                                'periodo_id' => $this->periodo(),
                            ]);
                        }

                        ## creditar e debitar na conta 31 ou seja preciso aumentar a divida do clientes e depois liquidar da mesma divida
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta_cliente->id,
                            'status' => true,
                            'movimento' => 'E',
                            'credito' => 0,
                            'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'debito' => ($item->valor_pagar ?? 0) + ($item->desconto_aplicado_valor ?? 0),
                            'observacao' => $request->observacao,
                            'code' => $code,
                            'data_at' => $request->data_emissao,
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $this->exercicio(),
                            'periodo_id' => $this->periodo(),
                        ]);
                    }

                    $update = ItemVenda::findOrFail($item->id);
                    $update->factura_id = $create_factura->id;
                    $update->update();
                }
            }

            if ($statusFactura == "por pagar") {
                $cartao = ContaCliente::where('cliente_id', $request->cliente_id)->firstOrFail();

                MovimentoContaCliente::create([
                    "user_id" => Auth::user()->id,
                    "documento" => $codigo_designacao_factura,
                    "conta_id" => $cartao->id,
                    "observacao" => $request->observacao,
                    "montante" => $request->total_pagar,
                    "cliente_id" => $request->cliente_id,
                    "data_emissao" => $request->data_emissao,
                    "tipo_movimento" => -1,
                    "entidade_id" => $entidade->empresa->id,
                ]);

                $cartao->saldo += $request->total_pagar;
                $cartao->divida_corrente += $request->montante;
                $cartao->save();
            }

            /***************************************************** */
            /*************** FACTURAÇÂO ELECTRONICA ************** */
            /***************************************************** */

            if ($entidade->empresa->tipo_facturacao != "saft") {
                if ($create_factura['factura'] != "FP" || $create_factura['factura'] != "PF") { //Proforma
                    dispatch(new SubmitElectronicDocumentToAgtJob(
                        $create_factura['id']
                    ));
                }
            }

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            dd($e->getMessage());
            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        // Alert::success('Sucesso', 'Venda realizada com sucesso!');
        // return redirect()->route('facturas.index');
        return response()->json(['success' => true, 'factura' => $create_factura]);
    }

    public function factura_adicionar_produto(string $id)
    {
        $user = auth()->user();

        if (!$user->can('criar facturas')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            // Inicia a transação
            DB::beginTransaction();

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $produto = Produto::with('marca', 'variacao', 'categoria', 'estoque')->findOrFail($id);
            $loja = Loja::where("entidade_id", $entidade->entidade_id)->where("status", "activo")->first();

            $lote = Lote::where("produto_id", $produto->id)
                ->where("codigo_barra", $produto->codigo_barra)
                ->where("entidade_id", $entidade->empresa->id)
                ->first();

            if ($produto->tipo == "P") {
                $gestao_quantidade = Estoque::where('loja_id', $loja->id)
                    ->where('produto_id', $produto->id)
                    ->where('stock', '>=', 1)
                    ->where('entidade_id', $entidade->empresa->id)
                    ->first();
            }

            $DESCONTO_APLICADO = 0;

            // 1. proço X quantidade
            $_VALOR_PAGAR = $produto->preco_venda * 1;

            $_DESCONTO = $_VALOR_PAGAR * ($DESCONTO_APLICADO / 100);

            $_VALOR_BASE = $_VALOR_PAGAR - $_DESCONTO;

            $_VALOR_IVA = $_VALOR_BASE * ($produto->taxa / 100);

            $_VALOR_RETENCAO = 0;

            if ($produto->tipo == "S") {
                if ($produto->preco_venda_com_iva >= $entidade->empresa->valor_taxa_retencao_fonte) {
                    $_VALOR_RETENCAO = $_VALOR_BASE * ($entidade->empresa->taxa_retencao_fonte / 100);
                }
            } else {
                $_VALOR_RETENCAO = 0;
            }

            $_VALOR_TOTAL = ($_VALOR_BASE + $_VALOR_IVA) -  $_VALOR_RETENCAO;


            $verificarProdutoAdicionado = ItemVenda::where("status", "processo")
                ->where("produto_id", $produto->id)
                ->where("user_id", Auth::user()->id)
                ->where("status_uso", "CAIXA")
                ->where("entidade_id", $entidade->empresa->id)
                ->first();

            if ($verificarProdutoAdicionado) {

                $update_item = ItemVenda::findOrFail($verificarProdutoAdicionado->id);

                $newQuantid = $update_item->quantidade + 1;

                $_VALOR_PAGAR = $produto->preco_venda * $newQuantid;

                $_DESCONTO = $_VALOR_PAGAR * ($DESCONTO_APLICADO / 100);

                $_VALOR_BASE = $_VALOR_PAGAR - $_DESCONTO;

                $_VALOR_IVA = $_VALOR_BASE * ($produto->taxa / 100);

                $_VALOR_RETENCAO = 0;

                if ($produto->tipo == "S") {
                    if ($produto->preco_venda_com_iva >= $entidade->empresa->valor_taxa_retencao_fonte) {
                        $_VALOR_RETENCAO = $_VALOR_BASE * ($entidade->empresa->taxa_retencao_fonte / 100);
                    }
                } else {
                    $_VALOR_RETENCAO = 0;
                }


                $_VALOR_TOTAL = ($_VALOR_BASE + $_VALOR_IVA) -  $_VALOR_RETENCAO;


                $update_item->valor_pagar = $_VALOR_TOTAL;
                $update_item->total = $_VALOR_TOTAL;

                $update_item->custo = $produto->preco_custo * $newQuantid;
                $update_item->lucro = (($produto->preco_venda - $produto->preco_custo) - $_DESCONTO) * $newQuantid;
                $update_item->lucro_iva = (($produto->preco_venda_com_iva - $produto->preco_custo) - $_DESCONTO) * $newQuantid;

                $update_item->desconto_aplicado = $update_item->desconto_aplicado;
                $update_item->desconto_aplicado_valor = $_DESCONTO;

                $update_item->valor_base = $_VALOR_BASE;
                $update_item->valor_iva = $_VALOR_IVA;
                $update_item->lote_id = $lote ? $lote->id : NULL;

                $update_item->retencao_fonte = $_VALOR_RETENCAO;

                if ($produto->tipo == "P") {
                    $update_gestao_quantidade = Estoque::find($gestao_quantidade->id);

                    if ($update_gestao_quantidade) {
                        $update_gestao_quantidade->stock = $update_gestao_quantidade->stock + $update_item->quantidade;
                        $update_gestao_quantidade->update();
                        $update_gestao_quantidade->stock = $update_gestao_quantidade->stock - $newQuantid;
                        $update_gestao_quantidade->update();
                    }
                }
                $update_item->quantidade = $newQuantid;
                $update_item->update();
            } else {

                ItemVenda::create([
                    "produto_id" => $produto->id,
                    'movimento_id' => 1,
                    "quantidade" => 1,
                    'tipo_desconto' => 'P',
                    'quantidade_devolvida' => 0,
                    'valor_pagar' => $_VALOR_TOTAL,
                    'total' => $_VALOR_TOTAL,
                    'preco_unitario' => $produto->preco_venda,
                    'custo' => $produto->preco_custo * 1,
                    'lucro_iva' => (($produto->preco_venda_com_iva - $produto->preco_custo) - $_DESCONTO) * 1,
                    'lucro' => (($produto->preco_venda - $produto->preco_custo) - $_DESCONTO) * 1,
                    'desconto_aplicado' => $DESCONTO_APLICADO,
                    "status" => "processo",
                    'valor_base' => $_VALOR_BASE,
                    'valor_iva' => $_VALOR_IVA,
                    'retencao_fonte' => $_VALOR_RETENCAO,
                    'desconto_aplicado_valor' => $_DESCONTO,
                    'iva' => $produto->imposto,
                    'iva_taxa' => $produto->taxa,
                    "texto_opcional" => "",
                    "status_uso" => "CAIXA",
                    "code" => NULL,
                    'lote_id' => $lote ? $lote->id : NULL,
                    "numero_serie" => "",
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);

                if ($produto->tipo == "P") {
                    $update_gestao_quantidade = Estoque::find($gestao_quantidade->id);
                    if ($update_gestao_quantidade) {
                        $update_gestao_quantidade->stock = $update_gestao_quantidade->stock - 1;
                        $update_gestao_quantidade->update();
                    }
                }
            }

            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger('Error', $e->getMessage());
            return redirect()->back()->with('danger', $e->getMessage());
        }

        return redirect()->route('facturas.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar facturas')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        if ($request->tipo_documentos == "FT") {
            $factura = Venda::with(['cliente', 'user'])->findOrFail($id);
        }
        if ($request->tipo_documentos == "FR") {
            $factura = Venda::with(['cliente', 'user'])->findOrFail($id);
        }
        if ($request->tipo_documentos == "PP") {
            $factura = Venda::with(['cliente', 'user'])->findOrFail($id);
        }
        if ($request->tipo_documentos == "RG") {
            $factura = Recibo::with(['cliente', 'user'])->findOrFail($id);
        }
        if ($request->tipo_documentos == "NC") {
            $factura = NotaCredito::with(['cliente', 'user'])->findOrFail($id);
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        if ($request->tipo_documentos == "FT" || $request->tipo_documentos == "FR" || $request->tipo_documentos == "PP") {
            $movimentos = ItemVenda::where('code', $factura->code)
                // ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->with(['produto'])->get();

            $total_retencao = ItemVenda::where('code', $factura->code)
                // ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->sum('retencao_fonte');

            $total_pagar = ItemVenda::where('code', $factura->code)
                // ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->sum('valor_pagar');
        }

        if ($request->tipo_documentos == "RG") {
            $movimentos = ItemRecibo::where('code', $factura->code)
                // ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->with(['produto'])->get();

            $total_retencao = ItemRecibo::where('code', $factura->code)
                // ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->sum('retencao_fonte');

            $total_pagar = ItemRecibo::where('code', $factura->code)
                // ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->sum('valor_pagar');
        }

        if ($request->tipo_documentos == "NC") {

            $movimentos = ItemNotaCredito::where('code', $factura->code)
                // ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->with(['produto'])->get();

            $total_retencao = ItemNotaCredito::where('code', $factura->code)
                // ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->sum('retencao_fonte');

            $total_pagar = ItemNotaCredito::where('code', $factura->code)
                // ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->sum('valor_pagar');
        }

        $total_pagar = $factura->valor_divida;

        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $caixas = Caixa::where('entidade_id', $entidade->empresa->id)
            ->where('status_admin', 'liberado')->get();
        $bancos = ContaBancaria::where('entidade_id', $entidade->empresa->id)->get();


        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "factura" => $factura,
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "total_retencao" => $total_retencao,
            "caixas" => $caixas,
            "bancos" => $bancos,
            "forma_pagmento" => TipoPagamento::get(),
            "caixa" => Caixa::where('active', true)
                ->where('entidade_id', $entidade->empresa->id)
                ->where('status_admin', 'liberado')
                ->first(),
            "clientes" => Cliente::where([
                ['entidade_id', $entidade->empresa->id],
            ])->get(),
            "produtos" => Produto::where('entidade_id', $entidade->empresa->id)
                ->whereIn("id", $meus_produtos)
                ->get(),
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),

            "empresa" => $empresa,
        ];

        return view('dashboard.facturas.show', $head);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar facturas')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $factura = Venda::with('cliente')->findOrFail($id);
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $movimentos = ItemVenda::where([
            ['entidade_id', '=', $entidade->empresa->id],
            ['code', '=', $factura->code],
            ['user_id', '=', Auth::user()->id],
        ])->with('produto')->get();

        $total_pagar = ItemVenda::where([
            ['code', '=', $factura->code],
            ['user_id', '=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])->sum('valor_pagar');


        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

        $head = [
            "titulo" => __('messages.editar'),
            "descricao" => env('APP_NAME'),
            "factura" => $factura,
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "caixa" => Caixa::where('active', true)
                ->where('entidade_id', $entidade->empresa->id)
                ->where('status_admin', 'liberado')
                ->first(),
            "clientes" => Cliente::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "produtos" => Produto::where('entidade_id', '=', $entidade->empresa->id)
                ->whereIn("id", $meus_produtos)
                ->get(),
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.facturas.edit', $head);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar facturas')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            // Inicia a transação
            DB::beginTransaction();
            $venda = Venda::findOrFail($id);
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $caixaActivo = Caixa::where('active', true)
                ->where('status', 'aberto')
                ->where('status_admin', 'liberado')
                ->where('user_open_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            /** dados da nova factura */
            $contarFacturaNovo = 0;

            if ($venda->factura != $request->factura) {
                $contarFacturaNovo = Venda::where([
                    ['entidade_id', '=', $entidade->empresa->id],
                    ['factura', '=', $request->factura],
                    ['user_id', '=', Auth::user()->id],
                    ['ano_factura', '=', $entidade->empresa->ano_factura],
                ])->count();
            } else {
                $contarFacturaNovo = 0;
            }

            $anoNovo = date("Y");
            $numeroFacturaNovo = $contarFacturaNovo + 1;
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
            //
            /***
             * registrar o pagamento original
             */
            $factura_original = FacturaOriginal::create([
                'status' => $venda->status,
                'status_venda' => $venda->status_venda,
                'status_factura' => $venda->status_factura,
                'user_id' => $venda->user_id,
                'caixa_id' => $venda->caixa_id,
                'factura_id' => $venda->id,
                'data_disponivel' => $venda->data_disponivel,
                'cliente_id' => $venda->cliente_id,
                'loja_id' => $venda->loja_id,
                'valor_entregue' => $venda->valor_entregue,
                'valor_total' => $venda->valor_total,
                'data_emissao' => $venda->data_emissao,
                'data_documento' => $datactual,
                'data_vencimento' => $venda->data_vencimento,
                'valor_troco' => $venda->valor_troco,
                'code' => $venda->code,
                'pagamento' => $venda->pagamento,
                'factura' => $venda->factura,
                'factura_next' => $venda->factura_next,
                'codigo_factura' => $venda->codigo_factura,
                'ano_factura' => $venda->ano_factura,
                'prazo' => $venda->prazo,
                'desconto' => $venda->desconto,
                'retificado' => $venda->retificado,
                'convertido_factura' => $venda->convertido_factura,
                'factura_divida' => $venda->factura_divida,
                'anulado' => $venda->anulado,
                'quantidade' => $venda->quantidade,

                'total_iva' => $venda->total_iva,
                'valor_cash' => $venda->valor_cash,
                'valor_multicaixa' => $venda->valor_multicaixa,

                'numeracao_proforma' => $venda->numeracao_proforma,
                'moeda' => $venda->moeda,
                'total_incidencia' => $venda->total_incidencia,
                'valor_extenso' => $venda->valor_extenso,
                'texto_hash' => $venda->texto_hash,
                'hash' => $venda->hash,
                'conta_corrente_cliente' => $venda->conta_corrente_cliente,
                'nif_cliente' => $venda->nif_cliente,
                'desconto_percentagem' => $venda->desconto_percentagem,
                'observacao' => $venda->observacao,
                'referencia' => $venda->referencia,
                'entidade_id' => $venda->entidade_id,
            ]);

            if ($factura_original->save()) {
                $movimentos = ItemVenda::where([
                    ['code', "=", $venda->code],
                ])->get();

                if ($movimentos) {
                    foreach ($movimentos as $movimento) {
                        ItemFacturaOriginal::create([
                            'produto_id' => $movimento->produto_id,
                            'factura_id' => $factura_original->id,
                            'movimento_id' => $movimento->movimento_id,
                            'user_id' => $movimento->user_id,
                            'quantidade' => $movimento->quantidade,
                            'status' => $movimento->status,
                            'valor_iva' => $movimento->valor_iva,
                            'valor_base' => $movimento->valor_base,
                            'valor_pagar' => $movimento->valor_pagar,
                            'preco_unitario' => $movimento->preco_unitario,
                            'desconto_aplicado' => $movimento->desconto_aplicado,
                            'desconto_aplicado_valor' => $movimento->desconto_aplicado_valor,
                            'iva' => $movimento->iva,
                            'iva_taxa' => $movimento->iva_taxa,
                            'texto_opcional' => $movimento->texto_opcional,
                            'code' => $movimento->code,
                            'numero_serie' => $movimento->numero_serie,
                            'entidade_id' => $movimento->entidade_id,
                            'user_id' => $movimento->user_id,
                        ]);
                    }
                }
            }

            /**
             * end registro factura items
             */

            // criar nota de credito
            $contarFactura = NotaCredito::where([
                ['factura', '=', 'NC'],
                ['ano_factura', '=', $entidade->empresa->ano_factura],
                ['entidade_id', '=', $entidade->empresa->id],
            ])->count();

            $ultimoRecibo = NotaCredito::where([
                ['factura', '=',  'NC'],
                ['ano_factura', '=', $entidade->empresa->ano_factura],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
                ->orderBy('id', 'DESC')
                ->first();


            if ($ultimoRecibo && $ultimoRecibo->created_at->gt(Carbon::now())) {

                Alert::success('Success', 'Não podemos concluir a criação deste documento porque a data do seu computador não está certa.
                    Acerta a data e hora do seu computador para continuar, ou entra em contacto com os administradores do sistema!');

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

            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

            $numeroFactura = $contarFactura + 1;

            $rsa = new RSA(); //Algoritimo RSA

            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();

            // Lendo a private key
            $rsa->loadKey($privatekey);

            $codigo_designacao_factura = "NC {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}";

            /**
             * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
             * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */

            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "{$codigo_designacao_factura}" . ';' . number_format($venda->valor_total, 2, ".", "") . ';' . $hashAnterior;
            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

            // Lendo a public key
            $rsa->loadKey($publickey);

            $nota = NotaCredito::create([
                'status' => true,
                'status_factura' => 'anulada',
                'status_venda' => "anulada",
                'user_id' => $venda->user_id,
                'caixa_id' => $venda->caixa_id,
                'cliente_id' => $venda->cliente_id,
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                'factura_id' => $venda->id,
                'valor_entregue' => $venda->valor_entregue,
                'valor_total' => $venda->valor_total,

                'prazo' => 0,
                'data_emissao' => date("y-m-d"),
                'data_vencimento' => date("y-m-d"),
                'data_disponivel' => date("y-m-d"),
                'data_documento' => $datactual,

                'valor_troco' => $venda->valor_troco,
                'code' => $venda->code,
                'pagamento' => $venda->pagamento,
                'factura' => 'NC',
                'codigo_factura' =>  $numeroFactura,
                'factura_next' => "{$codigo_designacao_factura}",
                'ano_factura' => date('Y'),
                'desconto' => $venda->desconto,

                'retificado' => $venda->retificado,
                'convertido_factura' => $venda->convertido_factura,
                'factura_divida' => $venda->factura_divida,
                'anulado' => 'Y',

                'quantidade' => $venda->quantidade,

                'total_iva' => $venda->total_iva,
                'valor_cash' => $venda->valor_cash,
                'valor_multicaixa' => $venda->valor_multicaixa,

                'numeracao_proforma' => $venda->factura_next,
                'moeda' => $venda->moeda,
                'total_incidencia' => $venda->total_incidencia,
                'valor_extenso' => $venda->valor_extenso,
                'texto_hash' => $plaintext,
                'hash' => base64_encode($signaturePlaintext),
                'conta_corrente_cliente' => $venda->conta_corrente_cliente,
                'nif_cliente' => $venda->nif_cliente,
                'desconto_percentagem' => $venda->desconto_percentagem,
                'observacao' => $venda->observacao,
                'referencia' => $venda->referencia,
                'entidade_id' => $venda->entidade_id,
            ]);

            $movimentos = ItemVenda::where([
                ['code', '=', $venda->code],
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get();

            if ($movimentos) {
                foreach ($movimentos as $items) {
                    ItemNotaCredito::create([
                        'produto_id' => $items->produto_id,
                        'factura_id' => $nota->id,
                        'movimento_id' => $items->movimento_id,
                        'user_id' => $items->user_id,
                        'quantidade' => $items->quantidade,
                        'status' => $items->status,
                        'valor_iva' => $items->valor_iva,
                        'valor_base' => $items->valor_base,
                        'valor_pagar' => $items->valor_pagar,
                        'preco_unitario' => $items->preco_unitario,
                        'desconto_aplicado' => $items->desconto_aplicado,
                        'desconto_aplicado_valor' => $items->desconto_aplicado_valor,
                        'iva' => $items->iva,
                        'iva_taxa' => $items->iva_taxa,
                        'texto_opcional' => $items->texto_opcional,
                        'code' => $items->code,
                        'numero_serie' => $items->numero_serie,
                        'entidade_id' => $items->entidade_id,
                        'user_id' => $items->user_id,
                    ]);
                }
            }
            /** end nota credito */

            $ultimoRecibo = Venda::where([
                ['factura', '=',  $request->factura],
                ['ano_factura', '=', $entidade->empresa->ano_factura],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
                ->orderBy('id', 'DESC')
                ->first();

            if ($ultimoRecibo && $ultimoRecibo->created_at->gt(Carbon::now())) {

                Alert::success('Success', 'Não podemos concluir a criação deste documento porque a data do seu computador não está certa.
                    Acerta a data e hora do seu computador para continuar, ou entra em contacto com os administradores do sistema!');

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

            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

            $numeroFactura = $contarFacturaNovo + 1;


            $rsa = new RSA(); //Algoritimo RSA

            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();

            // Lendo a private key
            $rsa->loadKey($privatekey);

            $codigo_designacao_factura = "{$request->factura} {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}";

            /**
             * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
             * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */

            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "{$codigo_designacao_factura}" . ';' . number_format($request->total_pagar, 2, ".", "") . ';' . $hashAnterior;
            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

            // Lendo a public key
            $rsa->loadKey($publickey);


            $dias = 0;
            if ($request->data_vencimento == 0) {
                $dias = 0;
            } else if ($request->data_vencimento == 15) {
                $dias = 15;
            } else if ($request->data_vencimento == 30) {
                $dias = 30;
            } else if ($request->data_vencimento == 45) {
                $dias = 45;
            } else if ($request->data_vencimento == 60) {
                $dias = 60;
            } else if ($request->data_vencimento == 90) {
                $dias = 90;
            }

            $request->data_vencimento = date('Y-m-d', strtotime($request->data_emissao . ' + ' . $dias . ' days'));

            $statusFactura = "";
            if ($request->data_emissao == $request->data_vencimento) {
                $statusFactura = "pago";
            } else {
                $statusFactura = "por pagar";
            }

            $venda->codigo_factura = $numeroFacturaNovo;
            $venda->status_factura = $statusFactura;
            $venda->status = true;
            $venda->status_venda = "realizado";
            $venda->status_venda = "reficada";
            $venda->cliente_id = $request->cliente_id;
            $venda->loja_id = $caixaActivo->loja_id;
            $venda->valor_entregue = $request->total_pagar;
            $venda->valor_total = $request->total_pagar;
            $venda->valor_troco = 0;
            $venda->ano_factura = $anoNovo;
            $venda->prazo = $dias;
            $venda->data_emissao = $request->data_emissao;
            $venda->data_vencimento = $request->data_vencimento;
            $venda->data_disponivel = $request->data_disponivel;
            $venda->desconto_percentagem = $request->desconto_percentagem;
            $venda->desconto = $request->desconto;
            $venda->pagamento = $request->forma_pagamento;
            $venda->factura = $request->factura;
            $venda->factura_next = "{$codigo_designacao_factura}";
            $venda->observacao = $request->observacao;
            $venda->referencia = $request->referencia;
            $venda->retificado  = 'Y';
            $venda->texto_hash = $plaintext;
            $venda->hash = base64_encode($signaturePlaintext);

            if ($venda->update()) {
                $movimentos = ItemVenda::where([
                    ['code', "=", $venda->code],
                    ['entidade_id', '=', $entidade->empresa->id],
                ])->get();

                $totalValorBase = 0;
                $totalValorIva = 0;
                $totalItems = 0;

                if ($movimentos) {
                    foreach ($movimentos as $value) {
                        $update = ItemVenda::findOrFail($value->id);
                        $update->status = "realizado";
                        $update->update();

                        $totalValorBase += $value->valor_base;
                        $totalValorIva += $value->valor_iva;
                        $totalItems += $value->quantidade;
                    }
                }
            }

            $venda->total_iva = $totalValorIva;
            $venda->total_incidencia = $totalValorBase;
            $venda->quantidade = $totalItems;

            $venda->save();

            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger('Error', $e->getMessage());
            return redirect()->back()->with('danger', $e->getMessage());
            // return Response()->json($e->getMessage());
            // Trate o erro ou exiba uma mensagem de falha
            // por exemplo: return response()->json(['message' => 'Erro ao salvar'], 500);
        }
        Alert::success('Success', 'Factura Actualizada com sucesso!');
        return redirect()->route('facturas.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = auth()->user();
        if (!$user->can('eliminar todos') && !$user->can('eliminar facturas')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function converter_factura($id)
    {
        //
        $user = auth()->user();
        if (!$user->can('editar todos') && !$user->can('editar facturas') || !$user->can('criar facturas')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $factura = Venda::with('cliente')->findOrFail($id);
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $movimentos = ItemVenda::where([
            ['entidade_id', '=', $entidade->empresa->id],
            ['code', '=', $factura->code],
            ['user_id', '=', Auth::user()->id],
        ])->with('produto')->get();

        $total_pagar = ItemVenda::where([
            ['code', '=', $factura->code],
            ['user_id', '=', Auth::user()->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])->sum('valor_pagar');

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

        $head = [
            "titulo" => "Converter factura",
            "descricao" => env('APP_NAME'),
            "factura" => $factura,
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "caixa" => Caixa::where([
                ['active', true],
                ['entidade_id', '=', $entidade->empresa->id],
            ])->where('status_admin', 'liberado')->first(),
            "clientes" => Cliente::where([
                ['entidade_id', $entidade->empresa->id],
            ])->get(),
            "produtos" => Produto::where('entidade_id', $entidade->empresa->id)
                ->whereIn("id", $meus_produtos)
                ->get(),
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.facturas.converter', $head);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function converter_factura_put(Request $request, $id)
    {

        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar facturas') || !$user->can('criar facturas')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $venda = Venda::findOrFail($id);
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);


        if ($venda->factura == $request->factura) {
            Alert::warning('Success', 'Factura não pode ser convertida, porque é do mesmo tipo com a antiga!');
            return redirect()->route('converter_factura', $id);
        }

        /** dados da nova factura */
        $contarFacturaNovo = 0;

        if ($venda->factura != $request->factura) {
            $contarFacturaNovo = Venda::where([
                ['entidade_id', '=', $entidade->empresa->id],
                ['factura', '=', $request->factura],
                ['user_id', '=', Auth::user()->id],
                ['ano_factura', '=', $entidade->empresa->ano_factura],
            ])->count();
        } else {
            $contarFacturaNovo = 0;
        }

        $numeroFacturaNovo = $contarFacturaNovo + 1;

        if ($request->factura == "RG") {
            // criar nota de credito
            $contarFactura = Recibo::where([
                ['factura', '=', 'RG'],
                ['ano_factura', '=', $entidade->empresa->ano_factura],
                ['entidade_id', '=', $entidade->empresa->id],
            ])->count();

            $ultimoRecibo = Recibo::where([
                ['factura', '=',  'RG'],
                ['ano_factura', '=', $entidade->empresa->ano_factura],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
                ->orderBy('id', 'DESC')
                ->first();


            if ($ultimoRecibo && $ultimoRecibo->created_at->gt(Carbon::now())) {

                Alert::success('Success', 'Não podemos concluir a criação deste documento porque a data do seu computador não está certa.
                    Acerta a data e hora do seu computador para continuar, ou entra em contacto com os administradores do sistema!');

                return redirect()->back();
            }

            if (!$ultimoRecibo) {
                $hashAnterior = "";
            } else {
                $hashAnterior = $ultimoRecibo->hash;
            }

            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

            $numeroFactura = $contarFactura + 1;

            $rsa = new RSA(); //Algoritimo RSA

            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();

            // Lendo a private key
            $rsa->loadKey($privatekey);

            /**
             * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
             * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */

            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "RG {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}" . ';' . number_format($venda->valor_total, 2, ".", "") . ';' . $hashAnterior;
            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

            // Lendo a public key
            $rsa->loadKey($publickey);


            $recibo = Recibo::create([
                'status' => true,
                'status_factura' => 'convertida',
                'status_venda' => "convertida",
                'user_id' => $venda->user_id,
                'caixa_id' => $venda->caixa_id,
                'cliente_id' => $venda->cliente_id,
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                'factura_id' => $venda->id,
                'valor_entregue' => $venda->valor_entregue,
                'valor_total' => $venda->valor_total,

                'prazo' => 0,
                'data_emissao' => date("y-m-d"),
                'data_vencimento' => date("y-m-d"),
                'data_disponivel' => date("y-m-d"),
                'data_documento' => $datactual,

                'valor_troco' => $venda->valor_troco,
                'code' => $venda->code,
                'pagamento' => $venda->pagamento,
                'factura' => 'RG',
                'codigo_factura' =>  $numeroFactura,
                'factura_next' => "RG {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}",
                'ano_factura' => $entidade->empresa->ano_factura,
                'desconto' => $venda->desconto,

                'retificado' => $venda->retificado,
                'convertido_factura' => "Y",
                'factura_divida' => $venda->factura_divida,
                'anulado' => $venda->anulado,

                'quantidade' => $venda->quantidade,

                'total_iva' => $venda->total_iva,
                'valor_cash' => $venda->valor_cash,
                'valor_multicaixa' => $venda->valor_multicaixa,

                'numeracao_proforma' => $venda->factura_next,
                'moeda' => $venda->moeda,
                'total_incidencia' => $venda->total_incidencia,
                'valor_extenso' => $venda->valor_extenso,
                'texto_hash' => $plaintext,
                'hash' => base64_encode($signaturePlaintext),
                'conta_corrente_cliente' => $venda->conta_corrente_cliente,
                'nif_cliente' => $venda->nif_cliente,
                'desconto_percentagem' => $venda->desconto_percentagem,
                'observacao' => $venda->observacao,
                'referencia' => $venda->referencia,
                'entidade_id' => $venda->entidade_id,
            ]);

            $movimentos = ItemVenda::where([
                ['code', '=', $venda->code],
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get();

            if ($movimentos) {
                foreach ($movimentos as $items) {
                    ItemRecibo::create([
                        'produto_id' => $items->produto_id,
                        'factura_id' => $recibo->id,
                        'movimento_id' => $items->movimento_id,
                        'user_id' => $items->user_id,
                        'quantidade' => $items->quantidade,
                        'status' => $items->status,
                        'valor_iva' => $items->valor_iva,
                        'valor_base' => $items->valor_base,
                        'valor_pagar' => $items->valor_pagar,
                        'preco_unitario' => $items->preco_unitario,
                        'desconto_aplicado' => $items->desconto_aplicado,
                        'desconto_aplicado_valor' => $items->desconto_aplicado_valor,
                        'iva' => $items->iva,
                        'iva_taxa' => $items->iva_taxa,
                        'texto_opcional' => $items->texto_opcional,
                        'code' => $items->code,
                        'numero_serie' => $items->numero_serie,
                        'entidade_id' => $items->entidade_id,
                        'user_id' => $items->user_id,
                    ]);
                }
            }

            $venda->codigo_factura = $numeroFactura;
            $venda->status_factura = "pago";
            $venda->status = true;
            $venda->status_venda = "realizado";
            $venda->status_venda = "convertida";
            $venda->factura = $request->factura;
            $venda->convertido_factura  = 'Y';

            $venda->update();

            Alert::success('Success', 'Factura Actualizada com sucesso!');
            return redirect()->route('factura-recibo-recibo', $venda->code);
        } else {
            // criar nota de credito
            $contarFactura = NotaCredito::where([
                ['factura', '=', 'NC'],
                ['ano_factura', '=', $entidade->empresa->ano_factura],
                ['entidade_id', '=', $entidade->empresa->id],
            ])->count();

            $ultimoRecibo = NotaCredito::where([
                ['factura', '=',  'NC'],
                ['ano_factura', '=', $entidade->empresa->ano_factura],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
                ->orderBy('id', 'DESC')
                ->first();

            if ($ultimoRecibo && $ultimoRecibo->created_at->gt(Carbon::now())) {

                Alert::success('Success', 'Não podemos concluir a criação deste documento porque a data do seu computador não está certa.
                    Acerta a data e hora do seu computador para continuar, ou entra em contacto com os administradores do sistema!');

                return redirect()->back();
            }

            if (!$ultimoRecibo) {
                $hashAnterior = "";
            } else {
                $hashAnterior = $ultimoRecibo->hash;
            }

            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

            $numeroFactura = $contarFactura + 1;

            $rsa = new RSA(); //Algoritimo RSA

            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();

            // Lendo a private key
            $rsa->loadKey($privatekey);
            /**
             * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
             * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */

            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "NC {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}" . ';' . number_format($venda->valor_total, 2, ".", "") . ';' . $hashAnterior;
            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

            // Lendo a public key
            $rsa->loadKey($publickey);

            $nota = NotaCredito::create([
                'status' => true,
                'status_factura' => 'convertida',
                'status_venda' => "convertida",
                'user_id' => $venda->user_id,
                'caixa_id' => $venda->caixa_id,
                'cliente_id' => $venda->cliente_id,
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                'factura_id' => $venda->id,
                'valor_entregue' => $venda->valor_entregue,
                'valor_total' => $venda->valor_total,

                'prazo' => 0,
                'data_emissao' => date("y-m-d"),
                'data_vencimento' => date("y-m-d"),
                'data_disponivel' => date("y-m-d"),
                'data_documento' => $datactual,

                'valor_troco' => $venda->valor_troco,
                'code' => $venda->code,
                'pagamento' => $venda->pagamento,
                'factura' => 'NC',
                'codigo_factura' =>  $numeroFactura,
                'factura_next' => "NC {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}",
                'ano_factura' => $entidade->empresa->ano_factura,
                'desconto' => $venda->desconto,

                'retificado' => $venda->retificado,
                'convertido_factura' => "Y",
                'factura_divida' => $venda->factura_divida,
                'anulado' => $venda->anulado,

                'quantidade' => $venda->quantidade,

                'total_iva' => $venda->total_iva,
                'valor_cash' => $venda->valor_cash,
                'valor_multicaixa' => $venda->valor_multicaixa,

                'numeracao_proforma' => $venda->factura_next,
                'moeda' => $venda->moeda,
                'total_incidencia' => $venda->total_incidencia,
                'valor_extenso' => $venda->valor_extenso,
                'texto_hash' => $plaintext,
                'hash' => base64_encode($signaturePlaintext),
                'conta_corrente_cliente' => $venda->conta_corrente_cliente,
                'nif_cliente' => $venda->nif_cliente,
                'desconto_percentagem' => $venda->desconto_percentagem,
                'observacao' => $venda->observacao,
                'referencia' => $venda->referencia,
                'entidade_id' => $venda->entidade_id,
            ]);

            $movimentos = ItemVenda::where([
                ['code', '=', $venda->code],
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get();

            if ($movimentos) {
                foreach ($movimentos as $items) {
                    ItemNotaCredito::create([
                        'produto_id' => $items->produto_id,
                        'factura_id' => $nota->id,
                        'movimento_id' => $items->movimento_id,
                        'user_id' => $items->user_id,
                        'quantidade' => $items->quantidade,
                        'status' => $items->status,
                        'valor_iva' => $items->valor_iva,
                        'valor_base' => $items->valor_base,
                        'valor_pagar' => $items->valor_pagar,
                        'preco_unitario' => $items->preco_unitario,
                        'desconto_aplicado' => $items->desconto_aplicado,
                        'desconto_aplicado_valor' => $items->desconto_aplicado_valor,
                        'iva' => $items->iva,
                        'iva_taxa' => $items->iva_taxa,
                        'texto_opcional' => $items->texto_opcional,
                        'code' => $items->code,
                        'numero_serie' => $items->numero_serie,
                        'entidade_id' => $items->entidade_id,
                        'user_id' => $items->user_id,
                    ]);
                }
            }
            /** end nota credito */

            $statusFactura = "";
            if ($request->factura == "FT") {
                $statusFactura = "pago";
            } else {
                $statusFactura = "por pagar";
            }

            $venda->codigo_factura = $numeroFacturaNovo;
            $venda->status_factura = $statusFactura;
            $venda->status = true;
            $venda->status_venda = "realizado";
            $venda->status_venda = "convertida";
            $venda->factura = $request->factura;
            $venda->convertido_factura  = 'Y';

            $venda->update();

            Alert::success('Success', 'Factura Actualizada com sucesso!');

            if ($request->factura == "FT") {
                return redirect()->route('factura-factura', $venda->code);
            }

            if ($request->factura == "FR") {
                return redirect()->route('factura-recibo', $venda->code);
            }

            if ($request->factura == "RG") {
                return redirect()->route('factura-recibo-recibo', $venda->code);
            }

            if ($request->factura == "FP") {
                return redirect()->route('factura-proforma', $venda->code);
            }

            if ($request->factura == "NC") {
                return redirect()->route('factura-nota-credito', $venda->code);
            }
        }
    }

    public function emitir_recibo(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar facturas') || !$user->can('criar facturas')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'total_pagar' => 'required',
            'factura_id' => 'required',
            'forma_de_pagamento' => 'required',
            'data_pagamento' => 'required',
        ]);

        if ($request->forma_de_pagamento == "NU") {
            $request->validate([
                'caixa_id' => 'required',
                'valor_entregue' => 'required',
            ]);
        }

        if ($request->forma_de_pagamento == "MB" || $request->forma_de_pagamento == "TE" || $request->forma_de_pagamento == "DE") {
            $request->validate([
                'banco_id' => 'required',
                'numero_operacao_finanaceira' => 'required',
                'valor_entregue_multicaixa' => 'required',
            ]);
        }

        if ($request->forma_de_pagamento == "OU") {
            $request->validate([
                'caixa_id' => 'required',
                'banco_id' => 'required',
                'valor_entregue' => 'required',
                'valor_entregue_multicaixa' => 'required',
            ]);
        }

        try {
            // Inicia a transação
            DB::beginTransaction();

            $request->valor_entregue_multicaixa = (float) $request->valor_entregue_multicaixa;
            $request->valor_entregue = (float) $request->valor_entregue;
            $request->total_pagar =  $request->valor_entregue_multicaixa + $request->valor_entregue;

            $venda = Venda::findOrFail($request->factura_id);

            $cliente = Cliente::findOrFail($venda->cliente_id);

            $code = uniqid(time());

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            if ($venda->status_factura == "pago") {
                return response()->json(['message' => 'Esta Factura já esta paga, então não podes emitir nenhum recibo!'], 404);
            }

            $caixaActivo = Caixa::where('active', true)
                ->where('status', 'aberto')
                ->where('status_admin', 'liberado')
                ->where('user_open_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            $movimentos = ItemVenda::where('code', $venda->code)
                ->where('entidade_id', $entidade->empresa->id)
                ->get();

            // $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

            // $loja = Loja::where("status", "activo")
            //     ->whereIn("id", $minhas_lojas)
            //     ->where("entidade_id", $entidade->empresa->id)
            //     ->first();

            $loja = $this->LOJA_ACTIVA_USER();

            $vendas_produtos = Receita::where('nome', 'Vendas')->where('type', 'R')->where('entidade_id', $entidade->empresa->id)->first();
            $prestacao_servicos = Receita::where('nome', 'Prestações de Serviços')->where('type', 'R')->where('entidade_id', $entidade->empresa->id)->first();

            $total_prestacao_servico = 0;
            $total_vendas = 0;

            // criar nota de credito
            $contarFactura = Recibo::where('factura', 'RG')
                ->where('ano_factura', $entidade->empresa->ano_factura)
                ->where('entidade_id', $entidade->empresa->id)
                ->count();

            foreach ($movimentos as $item) {
                $produt = Produto::findOrFail($item->produto_id);

                if ($produt->tipo == "P") {

                    if (!$loja) {
                        return response()->json(['error' => true, 'message' => "Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto. Por favor activa uma loja/armazém que tem este produto!"], 404);
                    }

                    $gestao_quantidade = Estoque::where('loja_id', $loja->id)
                        ->where('produto_id', $produt->id)
                        ->where('stock', '>=', 0)
                        ->where('entidade_id', $entidade->empresa->id)
                        ->first();

                    $verificar_quantidade = (float) $produt->total_produto_loja_activa();

                    if ($verificar_quantidade <= 0) {
                        return response()->json(['error' => true, 'message' => "A loja ativa não tem este produto em estoque para comercialização."], 404);
                    }

                    if ($verificar_quantidade <= $produt->total_produto_minimo_loja_activa()) {
                        return response()->json(['error' => true, 'message' => "A quantidade deste produto em estoque está abaixo do limite crítico, impossibilitando a venda no momento."], 404);
                    }

                    if ($produt->total_produto_loja_activa() <= $produt->total_produto_minimo_loja_activa()) {
                        return response()->json(['error' => true, 'message' => "Stock insuficiente para o produto: {$produt->nome}."], 404);
                    }

                    $cont = $contarFactura + 1;

                    Registro::create([
                        "registro" => "Saída de Stock",
                        "documento" => "RG {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$cont}",
                        "data_registro" => date('Y-m-d'),
                        "quantidade" => $item->quantidade,
                        'tipo' => 'S',
                        'status' => 'V',
                        "produto_id" => $produt->id,
                        "observacao" => "Saída do produto {$produt->nome} para venda",
                        "loja_id" => $loja->id,
                        "lote_id" => NULL,
                        "user_id" => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);

                    $update_gestao_quantidade = Estoque::find($gestao_quantidade->id);

                    if ($update_gestao_quantidade) {
                        $update_gestao_quantidade->stock = $update_gestao_quantidade->stock - $item->quantidade;
                        $update_gestao_quantidade->update();
                    }

                    $total_vendas += ($item->valor_pagar - $item->desconto_aplicado_valor);
                }

                if ($produt->tipo == "S") {
                    $total_prestacao_servico += ($item->valor_pagar - $item->desconto_aplicado_valor);
                }
            }

            if ($request->forma_de_pagamento == "NU") {
                $valor_cash = $request->total_pagar;
                $valor_multicaixa = 0;
                $venda->valor_cash += $valor_cash;

                $caixa = Caixa::findOrFail($request->caixa_id);

                #VAMOS DEBITAR NO CAIXA OU SEJA VAMOS AUMENTAR O DINHEIRO NO CAIXA SELECIONADO
                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $caixa->subconta_id,
                    'status' => true,
                    'movimento' => 'E',
                    'credito' => 0,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'debito' => $request->total_pagar,
                    'observacao' => "Pagamento da factura referente {$venda->factura_next}",
                    'code' => $code,
                    'data_at' => $request->data_pagamento,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                ## CREDITAR CLEINTE OU SENHA VAMOR REDUZIR A DIVIDA QUE O CLIENTE TEM CONNOSCO
                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $cliente->subconta_id,
                    'status' => true,
                    'movimento' => 'S',
                    'credito' => $request->total_pagar,
                    'debito' => 0,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'observacao' => "Pagamento da factura referente {$venda->factura_next}",
                    'code' => $code,
                    'data_at' => $request->data_pagamento,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                if ($total_vendas != 0) {
                    OperacaoFinanceiro::create([
                        'nome' => $vendas_produtos->nome,
                        'status' => "pago",
                        'formas' => "C",
                        'code_caixa' => $caixaActivo->code_caixa ?? NULL,
                        'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                        'user_open_id' => Auth::user()->id,
                        'motante' => $total_vendas,
                        'subconta_id' => $caixa->subconta_id,
                        'cliente_id' => $cliente->id,
                        'model_id' => $vendas_produtos->id,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'type' => "R",
                        'status_pagamento' => "pago",
                        'code' => $code,
                        'descricao' => "Pagamento da factura referente {$venda->factura_next}",
                        'movimento' => "E",
                        'date_at' => $request->data_pagamento,
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);
                }
                if ($total_prestacao_servico != 0) {
                    OperacaoFinanceiro::create([
                        'nome' => $prestacao_servicos->nome,
                        'status' => "pago",
                        'formas' => "C",
                        'code_caixa' => $caixaActivo->code_caixa ?? NULL,
                        'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                        'user_open_id' => Auth::user()->id,
                        'motante' => $total_prestacao_servico,
                        'subconta_id' => $caixa->subconta_id,
                        'cliente_id' => $cliente->id,
                        'model_id' => $prestacao_servicos->id,
                        'type' => "R",
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'status_pagamento' => "pago",
                        'code' => $code,
                        'descricao' => "Pagamento da factura referente {$venda->factura_next}",
                        'movimento' => "E",
                        'date_at' => $request->data_pagamento,
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);
                }
            }

            if ($request->forma_de_pagamento == "MB" || $request->forma_de_pagamento == "TE" || $request->forma_de_pagamento == "DE") {

                $verificarNumeroComprovativo = Venda::where(
                    'numero_operacao_finanaceira',
                    $request->numero_operacao_finanaceira
                )->first();

                if ($verificarNumeroComprovativo) {
                    return response()->json([
                        'message' => 'Não foi possível concluir a operação. O número da transação ou do comprovativo informado já se encontra registado no sistema.'
                    ], 404);
                }

                $valor_cash = 0;
                $valor_multicaixa = $request->total_pagar;
                $venda->valor_multicaixa += $valor_multicaixa;

                $banco = ContaBancaria::findOrFail($request->banco_id);

                #VAMOS DEBITAR NO BANCO OU SEJA VAMOS AUMENTAR O DINHEIRO NO BANCO SELECIONADO
                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $banco->subconta_id,
                    'status' => true,
                    'movimento' => 'E',
                    'credito' => 0,
                    'debito' => $request->total_pagar,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'observacao' => $request->observacao,
                    'code' => $code,
                    'data_at' => $request->data_pagamento,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                ## CREDITAR CLEINTE OU SENHA VAMOR REDUZIR A DIVIDA QUE O CLIENTE TEM CONNOSCO
                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $cliente->subconta_id,
                    'status' => true,
                    'movimento' => 'S',
                    'credito' => $request->total_pagar,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'debito' => 0,
                    'observacao' => "Pagamento da factura referente {$venda->factura_next}",
                    'code' => $code,
                    'data_at' => $request->data_pagamento,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                if ($total_vendas != 0) {
                    OperacaoFinanceiro::create([
                        'nome' => $vendas_produtos->nome,
                        'status' => "pago",
                        'formas' => "B",
                        'motante' => $total_vendas,
                        'subconta_id' => $banco->subconta_id,
                        'cliente_id' => $cliente->id,
                        'model_id' => $vendas_produtos->id,
                        'type' => "R",
                        'parcelado' => "N",
                        'status_pagamento' => "pago",
                        'code' => $code,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'descricao' => "Pagamento da factura referente {$venda->factura_next}",
                        'movimento' => "S",
                        'code_caixa' => $caixaActivo->code_caixa ?? NULL,
                        'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                        'user_open_id' => Auth::user()->id,
                        'date_at' => $request->data_pagamento,
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);
                }

                if ($total_prestacao_servico != 0) {
                    OperacaoFinanceiro::create([
                        'nome' => $prestacao_servicos->nome,
                        'status' => "pago",
                        'formas' => "B",
                        'motante' => $total_prestacao_servico,
                        'subconta_id' => $banco->subconta_id,
                        'cliente_id' => $cliente->id,
                        'model_id' => $prestacao_servicos->id,
                        'type' => "R",
                        'parcelado' => "N",
                        'status_pagamento' => "pago",
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'code' => $code,
                        'descricao' => "Pagamento da factura referente {$venda->factura_next}",
                        'movimento' => "S",
                        'code_caixa' => $caixaActivo->code_caixa ?? NULL,
                        'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                        'user_open_id' => Auth::user()->id,
                        'date_at' => $request->data_pagamento,
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);
                }
            }

            if ($request->forma_de_pagamento == "OU") {

                $valor_cash =  $request->valor_entregue_input;
                $valor_multicaixa = $request->valor_entregue_multicaixa_input;
                $venda->valor_cash += $valor_cash;
                $venda->valor_multicaixa += $valor_multicaixa;

                $caixa = Caixa::findOrFail($request->caixa_id);

                #VAMOS DEBITAR NO CAIXA OU SEJA VAMOS AUMENTAR O DINHEIRO NO CAIXA SELECIONADO
                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $caixa->subconta_id,
                    'status' => true,
                    'movimento' => 'E',
                    'credito' => 0,
                    'debito' => $request->valor_entregue_input,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'observacao' => "Pagamento da factura referente {$venda->factura_next}",
                    'code' => $code,
                    'data_at' => $request->data_pagamento,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);


                ## CREDITAR CLEINTE OU SENHA VAMOR REDUZIR A DIVIDA QUE O CLIENTE TEM CONNOSCO
                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $cliente->subconta_id,
                    'status' => true,
                    'movimento' => 'S',
                    'credito' => $request->valor_entregue_input,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'debito' => 0,
                    'observacao' => "Pagamento da factura referente {$venda->factura_next}",
                    'code' => $code,
                    'data_at' => $request->data_pagamento,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                OperacaoFinanceiro::create([
                    'nome' => "Pagamento da factura referente {$venda->factura_next}",
                    'status' => "pago",
                    'formas' => "C",
                    'motante' => $request->valor_entregue_input,
                    'subconta_id' => $caixa->subconta_id,
                    'cliente_id' => $cliente->id,
                    'model_id' => $prestacao_servicos->id,
                    'type' => "R",
                    'status_pagamento' => "pago",
                    'code' => $code,
                    'descricao' => "Pagamento da factura referente {$venda->factura_next}",
                    'movimento' => "E",
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'code_caixa' => $caixaActivo->code_caixa ?? NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'user_open_id' => Auth::user()->id,
                    'date_at' => $request->data_pagamento,
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                $banco = ContaBancaria::findOrFail($request->banco_id);
                #VAMOS DEBITAR NO BANCO OU SEJA VAMOS AUMENTAR O DINHEIRO NO BANCO SELECIONADO
                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $banco->subconta_id,
                    'status' => true,
                    'movimento' => 'E',
                    'credito' => 0,
                    'debito' => $request->valor_entregue_multicaixa_input,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'observacao' => $request->observacao,
                    'code' => $code,
                    'data_at' => $request->data_pagamento,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                ## CREDITAR CLEINTE OU SENHA VAMOR REDUZIR A DIVIDA QUE O CLIENTE TEM CONNOSCO
                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $cliente->subconta_id,
                    'status' => true,
                    'movimento' => 'S',
                    'credito' => $request->valor_entregue_multicaixa_input,
                    'debito' => 0,
                    'observacao' => "Pagamento da factura referente {$venda->factura_next}",
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'code' => $code,
                    'data_at' => $request->data_pagamento,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                OperacaoFinanceiro::create([
                    'nome' => "Pagamento da factura referente {$venda->factura_next}",
                    'status' => "pago",
                    'formas' => "B",
                    'motante' => $request->valor_entregue_multicaixa_input,
                    'subconta_id' => $banco->subconta_id,
                    'cliente_id' => $cliente->id,
                    'model_id' => $prestacao_servicos->id,
                    'type' => "R",
                    'parcelado' => "N",
                    'status_pagamento' => "pago",
                    'code' => $code,
                    'descricao' => "Pagamento da factura referente {$venda->factura_next}",
                    'movimento' => "S",
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'code_caixa' => $caixaActivo->code_caixa ?? NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'user_open_id' => Auth::user()->id,
                    'date_at' => $request->data_pagamento,
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);
            }

            $numeroFactura = $contarFactura + 1;

            if ($entidade->empresa->tipo_facturacao != "saft") {
                $verificarSerie = Serie::where('entidade_id', $entidade->empresa->id)
                    ->where('seriesYear', $entidade->empresa->ano_factura)
                    ->where('documentType', "RG")
                    ->first();

                if (!$verificarSerie) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Infelizmente não podemos concluir essa operação, precisas criar o solicitar uma serie para esse tipo de documento!'
                    ], 404);
                }

                $codigo_designacao_factura = "RG {$verificarSerie->seriesCode}/{$numeroFactura}";
            } else {
                $codigo_designacao_factura = "RG {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}";
            }

            $ultimoRecibo = Recibo::where('factura', '=', 'RG')
                ->where('ano_factura', '=', $entidade->empresa->ano_factura)
                ->where('entidade_id', '=', $entidade->empresa->id)
                ->orderBy('id', 'DESC')
                ->limit(1)
                ->first();

            if ($ultimoRecibo && $ultimoRecibo->created_at->gt(Carbon::now())) {
                return response()->json([
                    'message' => 'Não podemos concluir a criação deste documento porque a data do seu computador não está certa.
                    Acerta a data e hora do seu computador para continuar, ou entra em contacto com os administradores do sistema!'
                ], 404);
            }

            if (!$ultimoRecibo) {
                $hashAnterior = "";
            } else {
                $hashAnterior = $ultimoRecibo->hash;
            }

            $request->data_pagamento = $request->data_pagamento . " " . date('H:i:s');

            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', $request->data_pagamento);


            $rsa = new RSA(); //Algoritimo RSA

            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();

            // Lendo a private key
            $rsa->loadKey($privatekey);

            /**
             * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
             * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */

            $tot__ = $request->total_retencao + $request->total_pagar;

            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ";{$codigo_designacao_factura};" . number_format($tot__, 2, ".", "") . ';' . $hashAnterior;

            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

            // Lendo a public key
            $rsa->loadKey($publickey);

            $totalValorBase = 0;
            $totalValorIva = 0;
            $totalItems = 0;
            $totalIDesconto = 0;

            if ($movimentos) {
                foreach ($movimentos as $value) {
                    $totalValorBase += $value->valor_base;
                    $totalValorIva += $value->valor_iva;
                    $totalItems += $value->quantidade;
                    $totalIDesconto += $value->desconto_aplicado_valor;
                }
            }

            $code_recibo = uniqid(time());

            $valor_extenso = $this->valor_por_extenso($request->total_pagar);

            $recibo = Recibo::create([
                'status' => true,
                'status_factura' => 'pago',
                'status_venda' => "convertida",
                'user_id' => $venda->user_id,
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                'cliente_id' => $venda->cliente_id,
                'factura_id' => $venda->id,
                'valor_entregue' => $request->total_pagar,
                'valor_total' => $request->total_pagar,
                'valor_pago' => $request->total_pagar,

                'prazo' => $venda->prazo,
                'data_emissao' => $request->data_pagamento,
                'data_vencimento' => $venda->data_vencimento,
                'data_disponivel' => $venda->data_disponivel,
                'data_documento' => $datactual,

                'lucro_total' => $venda->lucro_total,
                'custo_total' => $venda->custo_total,
                'exame_id' => $venda->exame_id,
                'internamento_id' => $venda->internamento_id,
                'tratamento_id' => $venda->tratamento_id,
                'consulta_id' => $venda->consulta_id,
                'mesa_id' => $venda->mesa_id,
                'quarto_id' => $venda->quarto_id,
                'banco_id' => $venda->banco_id,
                'mesa_caixa' => $venda->mesa_caixa, // 'MESA','CAIXA','QUARTO','CONSULTA','EXAME'

                'valor_troco' => 0,
                'code' => $code_recibo,
                'pagamento' => $request->forma_de_pagamento,
                'factura' => 'RG',
                'codigo_factura' => $numeroFactura,
                'factura_next' => $codigo_designacao_factura,
                'ano_factura' => $entidade->empresa->ano_factura,
                'desconto' => $totalIDesconto,

                'retificado' => $venda->retificado,
                'convertido_factura' => "Y",
                'factura_divida' => $venda->factura_divida,
                'anulado' => $venda->anulado,

                'quantidade' => $totalItems,

                'total_iva' => $totalValorIva,
                'valor_cash' => $valor_cash,
                'valor_multicaixa' => $valor_multicaixa,

                'numeracao_proforma' => $venda->factura_next,
                'moeda' => $venda->moeda,
                'total_incidencia' => $request->total_pagar, //  $totalValorBase,
                'valor_extenso' => $valor_extenso,
                'texto_hash' => $plaintext,
                'hash' => base64_encode($signaturePlaintext),
                'conta_corrente_cliente' => $venda->conta_corrente_cliente,
                'nif_cliente' => $venda->nif_cliente,
                'desconto_percentagem' => $venda->desconto_percentagem,
                'observacao' => $venda->observacao,
                'referencia' => $venda->referencia,
                'entidade_id' => $venda->entidade_id,
            ]);

            $movimentos = ItemVenda::where([
                ['code', $venda->code],
                ['entidade_id', $entidade->empresa->id],
            ])->get();

            if ($movimentos) {
                foreach ($movimentos as $items) {
                    ItemRecibo::create([
                        'produto_id' => $items->produto_id,
                        'factura_id' => $recibo->id,
                        'movimento_id' => $items->movimento_id ?? NULL,
                        'user_id' => $items->user_id,
                        'quantidade' => $items->quantidade,
                        'status' => 'realizado',
                        'valor_iva' => $items->valor_iva,
                        'valor_base' => $items->valor_base,
                        'valor_pagar' => $items->valor_pagar,
                        'preco_unitario' => $items->preco_unitario,

                        'retencao_fonte' => $items->retencao_fonte,
                        'custo' => $items->custo,
                        'lucro' => $items->lucro,
                        'total' => $items->total,
                        'tipo_desconto' => $items->tipo_desconto, //'P','C','F'

                        'desconto_aplicado' => $items->desconto_aplicado,
                        'desconto_aplicado_valor' => $items->desconto_aplicado_valor,
                        'iva' => $items->iva,
                        'iva_taxa' => $items->iva_taxa,
                        'texto_opcional' => $items->texto_opcional,
                        'code' => $code_recibo,
                        'numero_serie' => $items->numero_serie,
                        'entidade_id' => $items->entidade_id,
                        'user_id' => $items->user_id,
                    ]);
                }
            }

            if ($request->total_pagar >= ($venda->valor_divida + $venda->valor_pago)) {
                $status = 'pago';
                $factura_divida = "N";
                $convertido_factura  = 'Y';
                $status_venda = "convertida";
                //factura já paga
            } else if ($venda->valor_pago == $venda->valor_total) {
                $status = 'pago';
                $factura_divida = "N";
                $convertido_factura  = 'Y';
                $status_venda = "convertida";
            } else {
                $status = 'por pagar';
                $factura_divida = "Y";
                $convertido_factura  = 'N';
                $status_venda = "realizado";
            }

            $venda->valor_pago += $request->total_pagar;
            $venda->valor_entregue += $request->total_pagar;
            $venda->valor_divida -= $request->total_pagar;

            if ($venda->valor_divida < 1) {
                $status = 'pago';
                $factura_divida = "N";
                $convertido_factura  = 'Y';
                $status_venda = "convertida";
                $venda->valor_divida = 0;
            }

            if ($factura_divida == "N") {
                Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $cliente->subconta_id,
                    'status' => true,
                    'movimento' => 'S',
                    'credito' => $venda->desconto,
                    'debito' => 0,
                    'observacao' => "Pagamento da factura referente {$venda->factura_next}",
                    'code' => $code,
                    'data_at' => $request->data_pagamento,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);
            }

            $venda->status_factura = $status;
            $venda->status = true;
            $venda->pagamento = $request->forma_de_pagamento;
            $venda->factura_divida = $factura_divida;
            $venda->status_venda = $status_venda;
            $venda->convertido_factura  = $convertido_factura;

            $venda->update();

            if ($venda->consulta_id) {
                $consulta = Consulta::findOrFail($venda->consulta_id);
                $consulta->pago = "PAGO";
                $consulta->update();
            }

            if ($venda->exame_id) {
                $exame = Exame::findOrFail($venda->exame_id);
                $exame->pago = "PAGO";
                $exame->update();
            }

            if ($venda->tratamento_id) {
                $tratamento = PlanoTratamento::findOrFail($venda->tratamento_id);
                $tratamento->pago = "PAGO";
                $tratamento->update();
            }

            if ($entidade->empresa->tipo_facturacao != "saft") {
                dispatch(new SubmitElectronicDocumentReciboToAgtJob(
                    $recibo['id']
                ));
            }

            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger('Error', $e->getMessage());
            return redirect()->back()->with('danger', $e->getMessage());
        }

        return response()->json(['success' => true, 'factura' => $recibo]);
    }

    public function anularFactura(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user->can('editar todos') && !$user->can('editar facturas') || !$user->can('criar facturas')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            // Inicia a transação
            DB::beginTransaction();

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $caixaActivo = Caixa::where('active', true)
                ->where('status', 'aberto')
                ->where('status_admin', 'liberado')
                ->where('user_open_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            $code = uniqid(time());

            $factura = venda::findOrFail($id);

            if ($factura->anulado == "Y") {
                return response()->json([
                    'message' => 'Não podemos concluir a com anulação deste documento porque ele já se encontra anulado!'
                ], 400);
            }

            $movimentos = ItemVenda::with(['produto'])->where('code', $factura->code)
                ->where('entidade_id', $entidade->empresa->id)
                ->get();

            // $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
            // $loja = Loja::where('status', 'activo')
            //     ->whereIn("id", $minhas_lojas)
            //     ->where('entidade_id', $entidade->empresa->id)
            //     ->first();

            $loja = $this->LOJA_ACTIVA_USER();


            // criar nota de credito
            $contarFactura = NotaCredito::where('factura', 'NC')
                ->where('ano_factura', $entidade->empresa->ano_factura)
                ->where('entidade_id', $entidade->empresa->id)
                ->count();

            $numeroFactura = $contarFactura + 1;

            // retornar os produtos no stock
            if ($factura->factura == "FR") {

                foreach ($movimentos as $item) {
                    $produt = Produto::findOrFail($item->produto_id);

                    if ($produt->tipo == "P") {

                        if (!$loja) {
                            return response()->json(['error' => true, 'message' => "Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto. Por favor activa uma loja/armazém que tem este produto!"], 404);
                        }

                        $gestao_quantidade = Estoque::where('loja_id', $loja->id)
                            ->where('produto_id', $produt->id)
                            ->where('stock', '>=', 0)
                            ->where('entidade_id', $entidade->empresa->id)
                            ->first();

                        $ct =  $contarFactura + 1;

                        Registro::create([
                            "documento" => "NC {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$ct}",
                            "data_registro" => date('Y-m-d'),
                            "quantidade" => $item->quantidade,
                            'tipo' => 'E',
                            'status' => 'D',
                            "registro" => "Entrada de Stock",
                            "observacao" => "Retorno do produto {$produt->nome} no Stock",
                            "produto_id" => $produt->id,
                            "loja_id" => $loja->id,
                            "lote_id" => NULL,
                            "user_id" => Auth::user()->id,
                            'entidade_id' => $entidade->empresa->id,
                        ]);

                        $update_gestao_quantidade = Estoque::find($gestao_quantidade->id);

                        if ($update_gestao_quantidade) {
                            $update_gestao_quantidade->stock = $update_gestao_quantidade->stock + $item->quantidade;
                            $update_gestao_quantidade->update();
                        }
                    }
                }

                $dispesa = Dispesa::where('type', 'D')->where('nome', 'Reembolso')->where('entidade_id', $entidade->empresa->id)->first();

                if ($factura->pagamento == "NU") {
                    $conta = Caixa::where('entidade_id', $entidade->empresa->id)
                        ->where('status_admin', 'liberado')->first();
                    $f = "C";
                } else {
                    $f = "B";
                    $conta = ContaBancaria::where('entidade_id', $entidade->empresa->id)->first();
                }

                OperacaoFinanceiro::create([
                    'nome' => $dispesa->nome,
                    'status' => "pago",
                    'formas' => $f,
                    'motante' => $factura->valor_total,
                    'subconta_id' => $conta->subconta_id,
                    'fornecedor_id' => $factura->cliente_id,
                    'model_id' => $dispesa->id,
                    'type' => "D",
                    'parcelado' => "N",
                    'status_pagamento' => "pago",
                    'code' => $code,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'descricao' => "REEMBOLSO DOS FACTURA ANUALADA",
                    'movimento' => "S",
                    'date_at' => date("Y-m-d"),
                    'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'user_id' => Auth::user()->id,
                    'user_open_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $request->exercicio_id ?? $this->exercicio(),
                    'periodo_id' => $request->periodo_id ?? $this->periodo(),
                ]);
            }

            if ($entidade->empresa->tipo_facturacao != "saft") {
                $verificarSerie = Serie::where('entidade_id', $entidade->empresa->id)
                    ->where('seriesYear', $entidade->empresa->ano_factura)
                    ->where('documentType', "NC")
                    ->first();

                if (!$verificarSerie) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Infelizmente não podemos concluir essa operação, precisas criar o solicitar uma serie para esse tipo de documento!'
                    ], 404);
                }
                $codigo_designacao_factura = "NC {$verificarSerie->seriesCode}/{$numeroFactura}";
            } else {
                $codigo_designacao_factura = "NC {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}";
            }


            $ultimoRecibo = NotaCredito::where('factura',  'NC')
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

            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

            // $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

            $rsa = new RSA(); //Algoritimo RSA

            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();

            // Lendo a private key
            $rsa->loadKey($privatekey);
            /**
             * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
             * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */

            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "{$codigo_designacao_factura}" . ';' . number_format($factura->valor_total, 2, ".", "") . ';' . $hashAnterior;
            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

            // Lendo a public key
            $rsa->loadKey($publickey);

            $nota = NotaCredito::create([
                'status' => true,
                'status_factura' => 'anulada',
                'status_venda' => "anulada",
                'user_id' => $factura->user_id,
                'caixa_id' => $factura->caixa_id,
                'factura_id' => $factura->id,
                'cliente_id' => $factura->cliente_id,
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                'valor_entregue' => $factura->valor_entregue,
                'valor_total' => $factura->valor_total,

                'prazo' => 0,
                'data_emissao' => date("Y-m-d"),
                'data_vencimento' => date("Y-m-d"),
                'data_disponivel' => date("Y-m-d"),
                'data_documento' => $datactual,

                'lucro_total' => $factura->lucro_total,
                'custo_total' => $factura->custo_total,
                'exame_id' => $factura->exame_id,
                'internamento_id' => $factura->internamento_id,
                'tratamento_id' => $factura->tratamento_id,
                'consulta_id' => $factura->consulta_id,
                'mesa_id' => $factura->mesa_id,
                'quarto_id' => $factura->quarto_id,
                'banco_id' => $factura->banco_id,
                'mesa_caixa' => $factura->mesa_caixa, // 'MESA','CAIXA','QUARTO','CONSULTA','EXAME'

                'valor_troco' => $factura->valor_troco,
                'code' => $factura->code,
                'pagamento' => $factura->pagamento,
                'factura' => 'NC',
                'codigo_factura' =>  $numeroFactura,
                'factura_next' => $codigo_designacao_factura,
                'ano_factura' => $entidade->empresa->ano_factura,
                'desconto' => $factura->desconto,

                'retificado' => $factura->retificado,
                'convertido_factura' => $factura->convertido_factura,
                'factura_divida' => $factura->factura_divida,
                'anulado' => 'N',

                'quantidade' => $factura->quantidade,

                'total_iva' => $factura->total_iva,
                'valor_cash' => $factura->valor_cash,
                'valor_multicaixa' => $factura->valor_multicaixa,

                'numeracao_proforma' => $factura->factura_next,
                'moeda' => $factura->moeda,
                'total_incidencia' => $factura->total_incidencia,
                'total_retencao_fonte' => $factura->total_retencao_fonte,
                'valor_extenso' => $factura->valor_extenso,
                'texto_hash' => $plaintext,
                'hash' => base64_encode($signaturePlaintext),
                'conta_corrente_cliente' => $factura->conta_corrente_cliente,
                'nif_cliente' => $factura->nif_cliente,
                'desconto_percentagem' => $factura->desconto_percentagem,
                'observacao' => $request->motivo,
                'referencia' => $factura->referencia,
                'entidade_id' => $factura->entidade_id,
            ]);

            if ($movimentos) {
                foreach ($movimentos as $items) {
                    $item = ItemNotaCredito::create([
                        'produto_id' => $items->produto_id,
                        'factura_id' => $nota->id,
                        'movimento_id' => $items->movimento_id,
                        'user_id' => $items->user_id,
                        'quantidade' => $items->quantidade,
                        'status' => $items->status,
                        'valor_iva' => $items->valor_iva,
                        'valor_base' => $items->valor_base,
                        'valor_pagar' => $items->valor_pagar,

                        'retencao_fonte' => $items->retencao_fonte,
                        'custo' => $items->custo,
                        'lucro' => $items->lucro,
                        'total' => $items->valor_pagar,
                        'tipo_desconto' => $items->tipo_desconto, //'P','C','F'

                        'preco_unitario' => $items->preco_unitario,
                        'desconto_aplicado' => $items->desconto_aplicado,
                        'desconto_aplicado_valor' => $items->desconto_aplicado_valor,
                        'iva' => $items->iva,
                        'iva_taxa' => $items->iva_taxa,
                        'texto_opcional' => $items->texto_opcional,
                        'code' => $items->code,
                        'numero_serie' => $items->numero_serie,
                        'entidade_id' => $items->entidade_id,
                        'user_id' => $items->user_id,
                    ]);

                    // ************************************************
                    $item1 = ItemVenda::findOrFail($items->id);
                    $item1->status = "anulada";
                    $item1->update();
                }
            }

            $factura->numeracao_proforma = $codigo_designacao_factura;
            $factura->anulado = "Y";
            $factura->status_venda = "anulada";
            $factura->status_factura = "anulada";
            $factura->observacao = $request->motivo;
            $factura->update();

            if ($entidade->empresa->tipo_facturacao != "saft") {
                dispatch(new SubmitElectronicDocumentToAgtJob(
                    $nota['factura_id'],
                    true
                ));
            }

            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger('Error', $e->getMessage());
            return redirect()->back()->with('danger', $e->getMessage());
        }

        return response()->json(['success' => true, 'factura' => $nota]);
    }

    public function imprimirFactura($id)
    {
        $factura = venda::with('cliente')->findOrFail($id);
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $movimentos = ItemVenda::where('entidade_id', $entidade->empresa->id)
            ->where('code', $factura->code)
            ->where('user_id', Auth::user()->id)
            ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            'titulo' => "Imprimir factura",
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            'descricao' => env('APP_NAME'),
            'factura' => $factura,
            'movimentos' => $movimentos,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.documentos.factura', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function pdf(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $facturas = Venda::with(['cliente', 'user'])
            ->when($request->tipo_documento, function ($query, $value) {
                $query->where('factura', $value);
            })
            ->when($request->cliente_id, function ($query, $value) {
                $query->where('cliente_id', $value);
            })
            ->when($request->user_id, function ($query, $value) {
                $query->where('user_id', $value);
            })
            ->when($request->data_inicio, function ($query, $value) {
                return $query->whereDate('created_at', '>=', Carbon::parse($value));
            })
            ->when($request->data_final, function ($query, $value) {
                return $query->whereDate('created_at', '<=', Carbon::parse($value));
            })
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->selectRaw("
                id,
                factura_next,
                cliente_id,
                user_id,
                valor_total,
                valor_divida,
                valor_pago,
                quantidade,
                total_incidencia,
                total_iva,

                documento_nif,
                nome_cliente,

                retificado,
                convertido_factura,
                factura_divida,
                anulado,
                code,
                status_factura,
                pagamento,

                factura,
                entidade_id,
                created_at
            ");


        $recibos = Recibo::with(['cliente', 'user'])
            ->when($request->tipo_documento, function ($query, $value) {
                $query->where('factura', $value);
            })
            ->when($request->cliente_id, function ($query, $value) {
                $query->where('cliente_id', $value);
            })
            ->when($request->user_id, function ($query, $value) {
                $query->where('user_id', $value);
            })
            ->when($request->data_inicio, function ($query, $value) {
                return $query->whereDate('created_at', '>=', Carbon::parse($value));
            })
            ->when($request->data_final, function ($query, $value) {
                return $query->whereDate('created_at', '<=', Carbon::parse($value));
            })
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->selectRaw("
                id,
                factura_next,
                cliente_id,
                user_id,
                valor_total,
                valor_divida,
                valor_pago,
                quantidade,
                total_incidencia,
                total_iva,

                documento_nif,
                nome_cliente,

                retificado,
                convertido_factura,
                factura_divida,
                anulado,
                code,
                status_factura,
                pagamento,

                factura,
                entidade_id,
                created_at
            ");

        $notasCredito = NotaCredito::with(['cliente', 'user'])
            ->when($request->tipo_documento, function ($query, $value) {
                $query->where('factura', $value);
            })
            ->when($request->cliente_id, function ($query, $value) {
                $query->where('cliente_id', $value);
            })
            ->when($request->user_id, function ($query, $value) {
                $query->where('user_id', $value);
            })
            ->when($request->data_inicio, function ($query, $value) {
                return $query->whereDate('created_at', '>=', Carbon::parse($value));
            })
            ->when($request->data_final, function ($query, $value) {
                return $query->whereDate('created_at', '<=', Carbon::parse($value));
            })
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->selectRaw("
                id,
                factura_next,
                cliente_id,
                user_id,
                valor_total,
                valor_divida,
                valor_pago,
                quantidade,
                total_incidencia,
                total_iva,
                retificado,
                convertido_factura,
                factura_divida,

                documento_nif,
                nome_cliente,

                anulado,
                code,
                status_factura,
                pagamento,
                factura,
                entidade_id,
                created_at
        ");


        $documentos = $facturas
            ->union($recibos)
            ->union($notasCredito)
            ->orderBy('created_at', 'desc')
            ->get();

        // $caixa = Caixa::find($request->caixa_id);
        $loja = Loja::find($request->loja_id);
        $cliente = Cliente::find($request->cliente_id);
        $user = User::find($request->user_id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            'titulo' => __('messages.listagem'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            'descricao' => "",
            'documentos' => $documentos,
            "cliente" => $cliente,
            "user" => $user,
            "loja" => $loja,
            "requests" => $request->all('data_inicio', 'data_final', 'caixa_id', 'user_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function retificarFactura($id)
    {
        return "retificar Factura {$id}";
    }

    public function facturaSemPagamentos(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar facturas')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        if ($request->tipo_documento == "dividas_corrente") {
            $facturas = Venda::when($request->factura, function ($query, $value) {
                $query->where('factura_next', 'like', "%{$value}%");
            })
                ->where('status_factura', '=', 'por pagar')
                ->whereDate('data_emissao', '<', date("Y-m-d"))
                ->whereDate('data_vencimento', '>', date("Y-m-d"))
                ->where('entidade_id', '=', $entidade->empresa->id)
                ->with(['cliente'])
                ->orderby('created_at', 'desc')
                ->get();

            // dividas vencidas
            $facturasVencidas = 0;
            //dividas corrente
            $facturasVencidasCorrente = Venda::when($request->factura, function ($query, $value) {
                $query->where('factura_next', 'like', "%{$value}%");
            })
                ->where('status_factura', '=', 'por pagar')
                ->whereDate('data_emissao', '<', date("Y-m-d"))
                ->whereDate('data_vencimento', '>', date("Y-m-d"))
                ->where('entidade_id', '=', $entidade->empresa->id)
                ->sum('valor_total');
        } else if ($request->tipo_documento == "dividas_vencidas") {

            $facturas = Venda::when($request->factura, function ($query, $value) {
                $query->where('factura_next', 'like', "%{$value}%");
            })
                ->where('status_factura', '=', 'por pagar')
                ->whereDate('data_vencimento', '<', date("Y-m-d"))
                ->where('entidade_id', '=', $entidade->empresa->id)
                ->with(['cliente'])
                ->orderby('created_at', 'desc')
                ->get();

            // dividas vencidas
            $facturasVencidas = Venda::when($request->factura, function ($query, $value) {
                $query->where('factura_next', 'like', "%{$value}%");
            })
                ->where('status_factura', '=', 'por pagar')
                ->whereDate('data_vencimento', '<', date("Y-m-d"))
                ->where('entidade_id', '=', $entidade->empresa->id)
                ->sum('valor_total');

            //dividas corrente
            $facturasVencidasCorrente = 0;
        } else {
            $facturas = Venda::when($request->factura, function ($query, $value) {
                $query->where('factura_next', 'like', "%{$value}%");
            })
                ->where('entidade_id', '=', $entidade->empresa->id)
                ->where('status_factura', '=', 'por pagar')
                ->with(['cliente'])
                ->orderby('created_at', 'desc')
                ->get();

            // dividas vencidas
            $facturasVencidas = Venda::when($request->factura, function ($query, $value) {
                $query->where('factura_next', 'like', "%{$value}%");
            })
                ->where('status_factura', '=', 'por pagar')
                ->whereDate('data_vencimento', '<', date("Y-m-d"))
                ->where('entidade_id', '=', $entidade->empresa->id)
                ->sum('valor_total');

            //dividas corrente
            $facturasVencidasCorrente = Venda::when($request->factura, function ($query, $value) {
                $query->where('factura_next', 'like', "%{$value}%");
            })
                ->where('status_factura', '=', 'por pagar')
                ->whereDate('data_emissao', '<', date("Y-m-d"))
                ->whereDate('data_vencimento', '>', date("Y-m-d"))
                ->where('entidade_id', '=', $entidade->empresa->id)
                ->sum('valor_total');
        }


        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();


        $head = [
            "titulo" => "Facturas sem pagamento",
            "descricao" => env('APP_NAME'),
            "caixa" => Caixa::where([
                ['active', true]
            ])
                ->where('status_admin', 'liberado')->first(),
            "facturas" => $facturas,
            "facturasVencidas" => $facturasVencidas,
            "facturasVencidasCorrente" => $facturasVencidasCorrente,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "lojas" => $lojas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.facturas.sem_pagamentos', $head);
    }

    public function facturaFacturacao(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar facturas')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $query = Venda::when($request->factura, function ($query, $value) {
            $query->where('factura_next', 'like', "%{$value}%");
        })
            ->when($request->data_inicio, function ($query, $value) {
                return $query->whereDate('created_at', '>=', Carbon::parse($value));
            })
            ->when($request->data_final, function ($query, $value) {
                return $query->whereDate('created_at', '<=', Carbon::parse($value));
            })
            ->when($request->cliente_id, function ($query, $value) {
                $query->where('cliente_id', $value);
            })
            ->with(['cliente', 'parent', 'seguradora'])
            ->where('entidade_id', $entidade->empresa->id);

        if ($request->relatorio == "contas_receber_mes") {
            $query->whereMonth('data_vencimento', now()->month)
                ->whereYear('data_vencimento', now()->year)
                ->whereIn('factura', ['FT'])
                ->whereIn('factura_divida', ['Y']);
        } else if ($request->relatorio == "contas_receber_atraso") {
            $query->where('data_vencimento', '<', now()->startOfMonth())
                ->whereIn('factura', ['FT'])
                ->whereIn('factura_divida', ['Y']);
        } else {
            $query->whereIn('factura', ['FT', 'FG', 'FR']);
        }

        $facturas = $query->orderby('created_at', 'desc')->get();

        $clientes = Cliente::where('entidade_id', '=', $entidade->empresa->id)->get();

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();


        $head = [
            "titulo" => "Facturação",
            "descricao" => env('APP_NAME'),
            "caixa" => Caixa::where('active', true)
                ->where('entidade_id', $entidade->empresa->id)
                ->where('status_admin', 'liberado')
                ->first(),
            "facturas" => $facturas,
            "clientes" => $clientes,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "lojas" => $lojas,
            'requests' => $request->all('factura', 'data_inicio', 'data_final', 'cliente_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.facturas.facturacao', $head);
    }

    public function facturaInformativo(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar facturas')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        if ($request->factura != "") {

            $facturas = Venda::where('entidade_id', '=', $entidade->empresa->id)
                ->where('factura_next', 'like', "%{$request->factura}%")
                ->whereIn('factura', ['EC', 'PF', 'FP', 'OT'])
                ->with(['cliente'])
                ->orderby('created_at', 'desc')
                ->get();
        } else {
            ####################### PADRÃO
            $facturas = Venda::where('entidade_id', '=', $entidade->empresa->id)
                ->whereIn('factura', ['EC', 'PF', 'FP', 'OT'])
                ->with(['cliente'])
                ->orderby('created_at', 'desc')
                ->get();
        }


        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();


        $head = [
            "titulo" => "Facturas informativo",
            "descricao" => env('APP_NAME'),
            "caixa" => Caixa::where([
                ['active', true],
                ['entidade_id', $entidade->empresa->id],
            ])
                ->where('status_admin', 'liberado')->first(),
            "facturas" => $facturas,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "lojas" => $lojas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.facturas.informativos', $head);
    }

    public function NotaCreditos(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar facturas')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $facturas = NotaCredito::when($request->factura, function ($query, $value) {
            $query->where('factura_next', 'like', "%{$value}%");
        })->where('entidade_id', '=', $entidade->empresa->id)
            ->with('cliente')
            ->with('facturas')
            ->orderby('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "facturas" => $facturas,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "requests" => $request->all('factura'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.facturas.notas-creditos', $head);
    }


    public function recibos(Request $request)
    {
        #RECIBO
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar facturas')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $facturas = Recibo::when($request->factura, function ($query, $value) {
            $query->where('factura_next', 'like', "%{$value}%");
        })->where('entidade_id', '=', $entidade->empresa->id)
            ->with('cliente')
            ->with('facturas')
            ->orderby('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "facturas" => $facturas,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "requests" => $request->all('factura'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.facturas.recibos', $head);
    }

    public function FacturaProforma($code, $opcao = "SEGUNDA VIA")
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $factura = Venda::with('cliente')->with('caixa')->with('user')->where('code', $code)->first();
        if (!$factura) {
            return redirect()->back();
        }

        $movimentos = ItemVenda::with('produto')->where('code', $factura->code)->where('user_id', Auth::user()->id)->where('entidade_id', $entidade->empresa->id)->get();

        if ($movimentos) {

            $total_incidencia_ise = 0;
            $total_retencao_ise = 0;
            $total_iva_ise = 0;

            $total_incidencia_nor = 0;
            $total_retencao_nor = 0;
            $total_iva_nor = 0;

            $total_incidencia_out = 0;
            $total_retencao_out = 0;
            $total_iva_out = 0;


            $total_incidencia_out_5 = 0;
            $total_iva_out_5 = 0;

            $total_incidencia_out_2 = 0;
            $total_iva_out_2 = 0;


            $motivo = "";

            foreach ($movimentos as $item) {
                if ($item->iva_taxa === 14) {
                    $total_incidencia_nor = $total_incidencia_nor + $item->valor_base;
                    $total_iva_nor = $total_iva_nor + $item->valor_iva;
                }
                if ($item->iva_taxa === 0) {
                    $total_incidencia_ise = $total_incidencia_ise + $item->valor_base;
                    $total_iva_ise = $total_iva_ise + $item->valor_iva;

                    // $motivo = $item->produto->motivo->descricao;
                }
                if ($item->iva_taxa === 7) {
                    $total_incidencia_out = $total_incidencia_out + $item->valor_base;
                    $total_iva_out = $total_iva_out + $item->valor_iva;
                }
                if ($item->iva_taxa === 2) {
                    $total_incidencia_out_2 = $total_incidencia_out_2 + $item->valor_base;
                    $total_iva_out_2 = $total_iva_out_2 + $item->valor_iva;
                }
                if ($item->iva_taxa === 5) {
                    $total_incidencia_out_5 = $total_incidencia_out_5 + $item->valor_base;
                    $total_iva_out_5 = $total_iva_out_5 + $item->valor_iva;
                }
            }
        }

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Factura Pro-forma",
            "descricao" => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "factura" => $factura,
            "items_facturas" => $movimentos,
            "loja" => $entidade,

            "total_incidencia_nor" => $total_incidencia_nor,
            "total_iva_nor" => $total_iva_nor,
            "total_retencao_nor" => $total_retencao_nor,

            "total_incidencia_ise" => $total_incidencia_ise,
            "total_iva_ise" => $total_iva_ise,
            "total_retencao_ise" => $total_retencao_ise,

            "total_incidencia_out" => $total_incidencia_out,
            "total_iva_out" => $total_iva_out,
            "total_retencao_out" => $total_retencao_out,

            "total_incidencia_out_5" => $total_incidencia_out_5,
            "total_iva_out_5" => $total_iva_out_5,

            "total_incidencia_out_2" => $total_incidencia_out_2,
            "total_iva_out_2" => $total_iva_out_2,

            "motivo" => $motivo,
            "opcao" => $opcao,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.documentos.impressao.factura-proforma', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function FacturaFactura($code, $opcao = "SEGUNDA VIA")
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $factura = Venda::with('cliente')->with('caixa')->with('user')->where('code', $code)->first();
        if (!$factura) {
            return redirect()->back();
        }

        $movimentos = ItemVenda::with('produto.motivo')->where('code', $factura->code)->where('entidade_id', $entidade->empresa->id)->get();

        if ($movimentos) {

            $total_incidencia_ise = 0;
            $total_retencao_ise = 0;
            $total_iva_ise = 0;

            $total_incidencia_nor = 0;
            $total_retencao_nor = 0;
            $total_iva_nor = 0;

            $total_incidencia_out = 0;
            $total_retencao_out = 0;
            $total_iva_out = 0;


            $total_incidencia_out_5 = 0;
            $total_iva_out_5 = 0;

            $total_incidencia_out_2 = 0;
            $total_iva_out_2 = 0;

            $motivo = "";

            foreach ($movimentos as $item) {
                if ($item->iva_taxa === 14) {
                    $total_incidencia_nor = $total_incidencia_nor + $item->valor_base;
                    $total_iva_nor = $total_iva_nor + $item->valor_iva;
                }
                if ($item->iva_taxa === 0) {
                    $total_incidencia_ise = $total_incidencia_ise + $item->valor_base;
                    $total_iva_ise = $total_iva_ise + $item->valor_iva;
                }
                if ($item->iva_taxa === 7) {
                    $total_incidencia_out = $total_incidencia_out + $item->valor_base;
                    $total_iva_out = $total_iva_out + $item->valor_iva;
                }
                if ($item->iva_taxa === 2) {
                    $total_incidencia_out_2 = $total_incidencia_out_2 + $item->valor_base;
                    $total_iva_out_2 = $total_iva_out_2 + $item->valor_iva;
                }
                if ($item->iva_taxa === 5) {
                    $total_incidencia_out_5 = $total_incidencia_out_5 + $item->valor_base;
                    $total_iva_out_5 = $total_iva_out_5 + $item->valor_iva;
                }
            }
        }

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Factura",
            "descricao" => env('APP_NAME'),
            "factura" => $factura,
            "items_facturas" => $movimentos,
            "loja" => $entidade,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),

            "total_incidencia_nor" => $total_incidencia_nor,
            "total_iva_nor" => $total_iva_nor,
            "total_retencao_nor" => $total_retencao_nor,

            "total_incidencia_ise" => $total_incidencia_ise,
            "total_iva_ise" => $total_iva_ise,
            "total_retencao_ise" => $total_retencao_ise,

            "total_incidencia_out" => $total_incidencia_out,
            "total_iva_out" => $total_iva_out,
            "total_retencao_out" => $total_retencao_out,

            "total_incidencia_out_5" => $total_incidencia_out_5,
            "total_iva_out_5" => $total_iva_out_5,

            "total_incidencia_out_2" => $total_incidencia_out_2,
            "total_iva_out_2" => $total_iva_out_2,
            "opcao" => $opcao,
            "motivo" => $motivo,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        // if ($entidade->empresa->tipo_factura == 'Normal') {
        $pdf = PDF::loadView('dashboard.facturas.documentos.impressao.factura-factura', $head);
        $pdf->setPaper('A4', 'portrait');
        // }
        // if ($entidade->empresa->tipo_factura == 'Ticket') {
        //     $pdf = PDF::loadView('dashboard.facturas.documentos.impressao.factura-factura-ticket', $head);
        //     $pdf->setPaper([0, 0, 226.77, 1000], 'portrait');
        // }

        return $pdf->stream();
    }

    public function FacturaRecibo($code, $opcao = "SEGUNDA VIA")
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $factura = Venda::with('cliente')->with('caixa')->with('user')->where('code', $code)->first();
        if (!$factura) {
            return redirect()->back();
        }

        $movimentos = ItemVenda::with('produto')->where('code', $factura->code)->where('entidade_id', $entidade->empresa->id)->get();

        if ($movimentos) {

            $total_incidencia_ise = 0;
            $total_retencao_ise = 0;
            $total_iva_ise = 0;


            $total_incidencia_nor = 0;
            $total_retencao_nor = 0;
            $total_iva_nor = 0;

            $total_incidencia_out = 0;
            $total_retencao_out = 0;
            $total_iva_out = 0;


            $total_incidencia_out_5 = 0;
            $total_iva_out_5 = 0;

            $total_incidencia_out_2 = 0;
            $total_iva_out_2 = 0;

            $motivo = "";

            foreach ($movimentos as $item) {
                if ($item->iva_taxa === 14) {
                    $total_incidencia_nor = $total_incidencia_nor + $item->valor_base;
                    $total_iva_nor = $total_iva_nor + $item->valor_iva;
                }
                if ($item->iva_taxa === 0) {
                    $total_incidencia_ise = $total_incidencia_ise + $item->valor_base;
                    $total_iva_ise = $total_iva_ise + $item->valor_iva;
                }
                if ($item->iva_taxa === 7) {
                    $total_incidencia_out = $total_incidencia_out + $item->valor_base;
                    $total_iva_out = $total_iva_out + $item->valor_iva;
                }
                if ($item->iva_taxa === 2) {
                    $total_incidencia_out_2 = $total_incidencia_out_2 + $item->valor_base;
                    $total_iva_out_2 = $total_iva_out_2 + $item->valor_iva;
                }
                if ($item->iva_taxa === 5) {
                    $total_incidencia_out_5 = $total_incidencia_out_5 + $item->valor_base;
                    $total_iva_out_5 = $total_iva_out_5 + $item->valor_iva;
                }
            }
        }

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "factura" => $factura,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "items_facturas" => $movimentos,
            "loja" => $entidade,

            "total_incidencia_nor" => $total_incidencia_nor,
            "total_iva_nor" => $total_iva_nor,
            "total_retencao_nor" => $total_retencao_nor,

            "total_incidencia_ise" => $total_incidencia_ise,
            "total_iva_ise" => $total_iva_ise,
            "total_retencao_ise" => $total_retencao_ise,

            "total_incidencia_out" => $total_incidencia_out,
            "total_iva_out" => $total_iva_out,
            "total_retencao_out" => $total_retencao_out,

            "total_incidencia_out_5" => $total_incidencia_out_5,
            "total_iva_out_5" => $total_iva_out_5,

            "total_incidencia_out_2" => $total_incidencia_out_2,
            "total_iva_out_2" => $total_iva_out_2,

            "opcao" => $opcao,
            "motivo" => $motivo,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        if ($entidade->empresa->tipo_factura == 'Normal') {
            $pdf = PDF::loadView('dashboard.facturas.documentos.impressao.factura-recibo', $head);
            $pdf->setPaper('A4', 'portrait');
        }
        if ($entidade->empresa->tipo_factura == 'Ticket') {
            $pdf = PDF::loadView('dashboard.facturas.documentos.impressao.factura-recibo-ticket', $head);
            $pdf->setPaper([0, 0, 226.77, 1000], 'portrait');
        }

        return $pdf->stream();
    }

    public function FacturaReciboRecibo($code, $opcao = "SEGUNDA VIA")
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $factura = Recibo::with('cliente')
            ->with('caixa')->with('user')
            ->with('facturas')
            ->where('code', $code)
            ->first();

        if (!$factura) {
            return redirect()->back();
        }

        $movimentos = ItemRecibo::with('produto')
            ->where('code', $factura->code)
            ->where('user_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Factura Recibo Recibo",
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "descricao" => env('APP_NAME'),
            "factura" => $factura,
            "items_facturas" => $movimentos,
            "loja" => $entidade,
            "opcao" => $opcao,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        if ($entidade->empresa->tipo_factura == 'Normal') {
            $pdf = PDF::loadView('dashboard.facturas.documentos.impressao.factura-recibo-recibo', $head);
            $pdf->setPaper('A4', 'portrait');
        }
        if ($entidade->empresa->tipo_factura == 'Ticket') {
            $pdf = PDF::loadView('dashboard.facturas.documentos.impressao.factura-recibo-recibo-ticket', $head);
            $pdf->setPaper([0, 0, 226.77, 1000], 'portrait');
        }

        return $pdf->stream();
    }

    public function FacturaNotaCredito($code, $opcao = "SEGUNDA VIA")
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $factura = NotaCredito::with('origem')->with('cliente')->with('caixa')->with('user')->where('code', $code)->first();

        if (!$factura) {
            return redirect()->back();
        }

        $movimentos = ItemNotaCredito::where('code', $factura->code)
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        if ($movimentos) {

            $total_incidencia_ise = 0;
            $total_iva_ise = 0;


            $total_incidencia_nor = 0;
            $total_iva_nor = 0;

            $total_incidencia_out = 0;
            $total_iva_out = 0;


            $total_incidencia_out_5 = 0;
            $total_iva_out_5 = 0;

            $total_incidencia_out_2 = 0;
            $total_iva_out_2 = 0;

            foreach ($movimentos as $item) {
                if ($item->iva_taxa === 14) {
                    $total_incidencia_nor = $total_incidencia_nor + $item->valor_base;
                    $total_iva_nor = $total_iva_nor + $item->valor_iva;
                }
                if ($item->iva_taxa === 0) {
                    $total_incidencia_ise = $total_incidencia_ise + $item->valor_base;
                    $total_iva_ise = $total_iva_ise + $item->valor_iva;

                    $motivo = $item->produto->motivo->descricao;
                }
                if ($item->iva_taxa === 7) {
                    $total_incidencia_out = $total_incidencia_out + $item->valor_base;
                    $total_iva_out = $total_iva_out + $item->valor_iva;
                }
                if ($item->iva_taxa === 2) {
                    $total_incidencia_out_2 = $total_incidencia_out_2 + $item->valor_base;
                    $total_iva_out_2 = $total_iva_out_2 + $item->valor_iva;
                }
                if ($item->iva_taxa === 5) {
                    $total_incidencia_out_5 = $total_incidencia_out_5 + $item->valor_base;
                    $total_iva_out_5 = $total_iva_out_5 + $item->valor_iva;
                }
            }
        }

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Factura Nota Credito",
            "descricao" => env('APP_NAME'),
            "factura" => $factura,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "items_facturas" => $movimentos,

            "total_incidencia_nor" => $total_incidencia_nor,
            "total_iva_nor" => $total_iva_nor,

            "total_incidencia_ise" => $total_incidencia_ise,
            "total_iva_ise" => $total_iva_ise,

            "total_incidencia_out" => $total_incidencia_out,
            "total_iva_out" => $total_iva_out,

            "total_incidencia_out_5" => $total_incidencia_out_5,
            "total_iva_out_5" => $total_iva_out_5,

            "total_incidencia_out_2" => $total_incidencia_out_2,
            "total_iva_out_2" => $total_iva_out_2,

            "opcao" => $opcao,
            "loja" => $entidade,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        if ($entidade->empresa->tipo_factura == 'Normal') {
            $pdf = PDF::loadView('dashboard.facturas.documentos.impressao.factura-nota-credito', $head);
            $pdf->setPaper('A4', 'portrait');
        }
        if ($entidade->empresa->tipo_factura == 'Ticket') {
            $pdf = PDF::loadView('dashboard.facturas.documentos.impressao.factura-nota-credito-ticket', $head);
            $pdf->setPaper([0, 0, 226.77, 1000], 'portrait');
        }

        return $pdf->stream();
    }

    public function GerarNotaEntrega($code)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $factura = Venda::with('cliente')->with('caixa')->with('user')->where('code', $code)->first();

        if (!$factura) {
            return redirect()->back();
        }

        $movimentos = ItemVenda::with('produto.motivo')->where('code', $factura->code)->where('entidade_id', $entidade->empresa->id)->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Nota de Ertrega",
            "descricao" => env('APP_NAME'),
            "factura" => $factura,
            "items_facturas" => $movimentos,
            "loja" => $entidade,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),

            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = Pdf::loadView('dashboard.facturas.documentos.impressao.nota-entrega', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }


    public function MovimentoPDF($code)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $movimento = Movimento::with('caixa')->with('user')->where('code', $code)->first();
        if (!$movimento) {
            return redirect()->back();
        }

        $resultado = ($movimento->movimento == "E") ? "entrada de valores" : "saídas de valores";

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);


        $head = [
            "titulo" => "Nota de {$resultado}",
            "descricao" => env('APP_NAME'),
            "movimento" => $movimento,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "loja" => $entidade,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.documentos.impressao.nota-movimento', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }


    function gerarJwtRs256(array $payload, string $privateKey): string
    {
        // 1️⃣ HEADER FIXO (RS256)
        $header = [
            "typ" => "JOSE",
            "alg" => "RS256"
        ];

        // 2️⃣ JSON → Base64URL
        $headerEncoded  = $this->base64UrlEncode(json_encode($header));
        $payloadEncoded = $this->base64UrlEncode(json_encode($payload));

        // 3️⃣ STRING A ASSINAR
        $dataToSign = $headerEncoded . "." . $payloadEncoded;

        // 4️⃣ LER CHAVE PRIVADA
        // $privateKey = file_get_contents($privateKeyPath);

        // 5️⃣ ASSINAR COM RSA + SHA256
        openssl_sign(
            $dataToSign,
            $signature,
            $privateKey,
            OPENSSL_ALGO_SHA256
        );

        // 6️⃣ ASSINATURA BASE64URL
        $signatureEncoded = $this->base64UrlEncode($signature);

        // 7️⃣ JWT FINAL
        return $dataToSign . "." . $signatureEncoded;
    }

    function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
