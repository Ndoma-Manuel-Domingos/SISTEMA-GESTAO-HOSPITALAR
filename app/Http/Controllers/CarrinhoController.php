<?php

namespace App\Http\Controllers;

use App\Models\ContaBancaria;
use App\Models\Caixa;
use App\Models\CartaoConsumo;
use App\Models\CartaoConsumoHistorico;
use App\Models\CartaoConsumoMovimento;
use App\Models\Cliente;
use App\Models\Entidade;
use App\Models\Estoque;
use App\Models\ItemVenda;
use App\Models\Loja;
use App\Models\Lote;
use App\Models\Mesa;
use App\Models\Movimento;
use App\Models\Quarto;
use App\Models\OperacaoFinanceiro;
use App\Models\Produto;
use App\Models\Receita;
use App\Models\Registro;
use App\Models\Subconta;
use App\Models\User;
use App\Models\UserLoja;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

use phpseclib\Crypt\RSA;

class CarrinhoController extends Controller
{

    //
    use TraitChavesSaft;
    use TraitHelpers;

    // Exibe a página do carrinho
    public function index()
    {
        $carrinho = Session::get('carrinho', []);
        $total = $this->calcularTotal($carrinho);
        $loja = ['moeda' => 'AOA'];  // Exemplo de dados da loja
        return view('carrinho.index', compact('carrinho', 'total', 'loja'));
    }

    // Adiciona um produto ao carrinho
    public function adicionar(Request $request)
    {
        $produtoId = $request->input('produto_id');
        $quantidade = $request->input('quantidade', 1);
        $preco = $request->input('preco');
        $nome = $request->input('nome');
        $stock = $request->input('stock');

        $carrinho = Session::get('carrinho', []);

        if (isset($carrinho[$produtoId])) {

            // Atualiza a quantidade
            $carrinho[$produtoId]['quantidade'] += $quantidade;

            if ($carrinho[$produtoId]['quantidade'] > $stock) {
                $carrinho[$produtoId]['quantidade'] = $stock;
            }

            // Recalcula o valor a pagar
            $carrinho[$produtoId]['valor_pagar'] = $carrinho[$produtoId]['quantidade'] * $carrinho[$produtoId]['preco'];
        } else {

            if ($quantidade > $stock) {
                $quantidade = $stock;
            }

            // Adiciona novo produto ao carrinho com preço unitário
            $carrinho[$produtoId] = [
                'produto_id' => $produtoId,
                'nome' => $nome,
                'quantidade' => $quantidade,
                'preco' => $preco,  // Armazena o preço unitário
                'valor_pagar' => $preco * $quantidade
            ];
        }

        // Salva o carrinho na sessão
        Session::put('carrinho', $carrinho);

        // Calcula o total do carrinho
        $total = $this->calcularTotal($carrinho);

        // Retorna o carrinho atualizado como resposta JSON
        return response()->json(['carrinho' => $carrinho, 'total' => $total, 'message' => 'Produto adicionado ao carrinho com sucesso!']);
    }

    // Adiciona um produto ao carrinho
    public function codigo_barra(Request $request)
    {

        $request->validate([
            'produto_id' => 'required|exists:produtos,codigo_barra', // Validação do código de barras
        ]);

        $codigoBarra = $request->input('produto_id');
        $produto = Produto::where('codigo_barra', $codigoBarra)->first();

        $quantidade = $request->input('quantidade');

        if (!$produto) {
            return response()->json([
                'success' => false,
                'message' => 'Produto não encontrado.',
            ], 404);
        }

        $stock = $produto->total_produto_loja_activa();

        $carrinho = Session::get('carrinho', []);
        if (isset($carrinho[$produto->id])) {
            // Atualiza a quantidade

            $carrinho[$produto->id]['quantidade'] += $quantidade;

            if ($carrinho[$produto->id]['quantidade'] > $stock) {
                $carrinho[$produto->id]['quantidade'] = $stock;
            }

            // Recalcula o valor a pagar
            $carrinho[$produto->id]['valor_pagar'] = $carrinho[$produto->id]['quantidade'] * $carrinho[$produto->id]['preco'];
        } else {

            if ($quantidade > $stock) {
                $quantidade = $stock;
            }

            // Adiciona novo produto ao carrinho com preço unitário
            $carrinho[$produto->id] = [
                'produto_id' => $produto->id,
                'nome' => $produto->nome,
                'quantidade' => $quantidade,
                'preco' => $produto->preco_venda,  // Armazena o preço unitário
                'valor_pagar' => $produto->preco_venda * $quantidade
            ];
        }
        // Salva o carrinho na sessão
        Session::put('carrinho', $carrinho);

        // Calcula o total do carrinho
        $total = $this->calcularTotal($carrinho);

        // Retorna o carrinho atualizado como resposta JSON
        return response()->json(['success' => true, 'carrinho' => $carrinho, 'total' => $total, 'message' => 'Produto adicionado ao carrinho com sucesso!']);
    }

    // Adiciona um produto ao carrinho
    public function codigo_barra_grelha(Request $request)
    {

        $request->validate([
            'produto_id' => 'required|exists:produtos,codigo_barra', // Validação do código de barras
        ]);

        $codigoBarra = $request->input('produto_id');
        $produto = Produto::where('codigo_barra', $codigoBarra)->first();

        $quantidade = $request->input('quantidade');

        if (!$produto) {
            return response()->json([
                'success' => false,
                'message' => 'Produto não encontrado.',
            ], 404);
        }

        $stock = $produto->total_produto_loja_activa();

        $carrinho = [];

        if ($quantidade > $stock) {
            $quantidade = $stock;
        }
        // Adiciona novo produto ao carrinho com preço unitário
        $carrinho = [
            'id' => $produto->id,
            'nome' => $produto->nome,
            'quantidade' => $quantidade,
            'preco' => $produto->preco_venda,  // Armazena o preço unitário
            'taxa' => $produto->taxa
        ];

        // Retorna o carrinho atualizado como resposta JSON
        return response()->json(['success' => true, 'carrinho' => $carrinho, 'message' => 'Produto adicionado ao carrinho com sucesso!']);
    }

    // Remove um produto do carrinho
    public function remover(Request $request)
    {
        $produtoId = $request->input('produto_id');

        $carrinho = Session::get('carrinho', []);

        if (isset($carrinho[$produtoId])) {
            unset($carrinho[$produtoId]);
            Session::put('carrinho', $carrinho);
        }

        // Calcula o total do carrinho
        $total = $this->calcularTotal($carrinho);

        // Retorna o carrinho atualizado como resposta JSON
        return response()->json(['carrinho' => $carrinho, 'total' => $total, 'message' => 'Produto removido do carrinho com sucesso!']);
    }

    // Processa o pagamento e limpa o carrinho
    public function pagamento(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $caixaActivo = Caixa::where('active', true)
            ->where('status', 'aberto')
            ->where('status_admin', 'liberado')
            ->where('user_open_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->first();

        $bancoActivo = ContaBancaria::where('active', true)
            ->where('status', 'aberto')
            ->where('user_open_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->first();

        if (!$caixaActivo) {
            return response()->json(['message' => 'Por favor, não podes realizar nenhuma venda sem antes abrir o caixa!'], 400);
        }

        $cliente = Cliente::findOrFail($request->cliente_id);
        $vendas_produtos = Receita::where('nome', 'Vendas')->where('type', 'R')->where('entidade_id', $entidade->empresa->id)->first();
        //$prestacao_servicos = Receita::where('nome', 'Prestações de Serviços')->where('type', 'R')->where('entidade_id', '=', $entidade->empresa->id)->first();

        $code = uniqid(time());

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $request['total_pagar'] = (float) $request['total_pagar'];
            $valor_multicaixa = 0;
            $valor_cash = 0;
            $DESCONTO_APLICADO = (float) $request->desconto;

            if ($entidade->empresa->tipo_venda !== "Normal") {
                if (!session()->has('carta_consumo_venda_2022')) {
                    return response()->json(['message' => 'Por favor, não podes realizar nenhuma venda sem antes escolher o cartão consumo!'], 400);
                }

                $cartao_consumo = session("carta_consumo_venda_2022");

                if ($cartao_consumo->saldo < $request['total_pagar']) {
                    return response()->json(['message' => 'Por favor, o saldo do cartão consumo, é insuficiente para realizar esta venda!'], 400);
                }

                $cartao = CartaoConsumo::findOrFail($cartao_consumo->id);
                $cartao->saldo -= $request['total_pagar'];

                if ($cartao->saldo <= 0) {
                    $cartao->status = "N";
                } else {
                    $cartao->status = "Y";
                }

                $cartao->save();

                CartaoConsumoHistorico::create([
                    "tipo" => "C",
                    "saldo" => $request['total_pagar'],
                    "date_at" => now(),
                    "cartao_id" => $cartao->id,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);

                CartaoConsumoMovimento::create([
                    "cartao_id" => $cartao->id,
                    "saldo" => $request['total_pagar'],
                    "descricao" => "pagamento de produtos",
                    "date_at" => now(),
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);

                Session::forget("carta_consumo_venda_2022");
            }

            if ($request->tipo_pronto_venda == "GRELHA") {
                $carrinho = $request->carrinho;
            } else {
                $carrinho = Session::get('carrinho', []);
            }

            $contarFactura = Venda::where('factura', $request->documento)
                ->where('ano_factura', $entidade->empresa->ano_factura)
                ->where('entidade_id', $entidade->empresa->id)
                ->count();

            $numeroFactura = $contarFactura + 1;

            $doc = "{$request['documento']} {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}";

            foreach ($carrinho as $key => $car) {

                $produto = Produto::with('marca', 'variacao', 'categoria', 'estoque')->findOrFail($car['produto_id']);

                // SElecinar em que lote este produto pertence para se comercializado ou reduzido naquele stock
                $lote = Lote::where("produto_id", $produto->id)
                    ->where("codigo_barra", $produto->codigo_barra)
                    ->where("entidade_id", $entidade->empresa->id)
                    ->first();

                // $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

                // $loja = Loja::where("entidade_id", $entidade->entidade_id)
                //     ->where("status", "activo")
                //     ->whereIn("id", $minhas_lojas)
                //     ->first();
                
                $loja = $this->LOJA_ACTIVA_USER();
                            
                if ($produto->tipo == "P") {

                    if ($lote && $lote->status == "expirado" && $lote->data_validade <= date("Y-m-d")) {
                        return response()->json(['message' => "O produto: { $produto->nome } parece estar expirado, por isso não é possível finalizar a venda, visando a segurança da população."], 404);
                    }

                    // verificar se este produto tem quantidade para ser vendidas no lote não expirado, isto por que  não podemos permitir o sistema vender quantidades que não existem 
                    $verificar_lote_produto = $produto->verificar_lote_produto($produto->id, $lote->id, $entidade->empresa->id);

                    if ($car['quantidade'] > $verificar_lote_produto) {
                        return response()->json(["message" => "O produto {$produto->nome} não possui quantidade disponível para comercialização, pois o sistema não conseguiu identificar a quantidade do lote referente a este produto. Por favor, verifique se existem lotes expirados. Caso contrário, ative o código de barras correspondente ao lote!"], 400);
                    }

                    if (!$loja) {
                        return response()->json(["message" => "Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto!"], 400);
                    }

                    // verificar quantidade de produto no estoque da loja
                    $verificar_quantidade = Estoque::where('loja_id', $loja->id)
                        ->where('produto_id', $produto->id)
                        ->where('stock', '>=', 0)
                        ->where('entidade_id', $entidade->empresa->id)
                        ->sum('stock');

                    $gestao_quantidade = Estoque::where('loja_id', $loja->id)
                        ->where('produto_id', $produto->id)
                        ->where('stock', '>=', 0)
                        ->where('entidade_id', $entidade->empresa->id)
                        ->first();

                    $verificar_quantidade = (float) $verificar_quantidade;

                    if ($car['quantidade'] > $verificar_quantidade) {
                        return response()->json(['message' => 'A Loja activa não têm este produto em stock para poder comercializar!'], 400);
                    }

                    if ($car['quantidade'] <= 0) {
                        return response()->json(['message' => 'Quantidade do produto não pode ser negativo, verificar a quantidade informada por favor!'], 400);
                    }
                }

                if ($request['venda_realizado'] == "CAIXA") {
                    $status_uso = "CAIXA";
                    $mesa_id = NULL;
                    $caixa_id = $caixaActivo->id;
                } else {
                    $mesa_id = $request['venda_realizado'];
                    $status_uso = "MESA";
                    $caixa_id = NULL;
                }

                if ($status_uso == "CAIXA") {
                    $verificarProdutoAdicionado = ItemVenda::where('status', '=', 'processo')
                        ->where('produto_id', '=', $produto->id)
                        ->where('caixa_id', '=', $caixa_id)
                        ->where('entidade_id', '=', $entidade->empresa->id)
                        ->where('user_id', '=', Auth::user()->id)
                        ->first();
                }

                if ($status_uso == "MESA") {
                    $verificarProdutoAdicionado = ItemVenda::where('status', '=', 'processo')
                        ->where('produto_id', '=', $produto->id)
                        ->where('mesa_id', '=', $mesa_id)
                        ->where('entidade_id', '=', $entidade->empresa->id)
                        ->where('user_id', '=', Auth::user()->id)
                        ->first();
                }

                // calcudo do total de incidencia
                //________________ valor total _____________

                // vamos encontrar o valor do IVA no proço de venda do produto
                $IVA_VALOR_VENDA_PRODUTO = $produto->preco_venda * ($produto->taxa / 100);

                // 1. proço X quantidade
                $_VALOR_PAGAR = (($car['preco'] - $IVA_VALOR_VENDA_PRODUTO) ?? $produto->preco_venda) * $car['quantidade'];

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

                if ($verificarProdutoAdicionado) {

                    $update = ItemVenda::findOrFail($verificarProdutoAdicionado->id);

                    $_VALOR_PAGAR = (($car['preco'] - $IVA_VALOR_VENDA_PRODUTO) ?? $produto->preco_venda) * ($update->quantidade + $car['quantidade']);

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

                    $update->quantidade = $update->quantidade + $car['quantidade'];
                    $update->valor_pagar = $_VALOR_TOTAL;

                    $update->custo = $produto->preco_custo * $update->quantidade;
                    $update->lucro = (((($car['preco'] - $IVA_VALOR_VENDA_PRODUTO) ?? $produto->preco_venda) - $produto->preco_custo) - $_DESCONTO) * $car['quantidade'];
                    $update->lucro_iva = (($produto->preco_venda_com_iva - $produto->preco_custo) - $_DESCONTO) * $car['quantidade'];

                    $update->desconto_aplicado = $update->desconto_aplicado;
                    $update->desconto_aplicado_valor = $_DESCONTO;

                    $update->valor_base = $_VALOR_BASE;
                    $update->valor_iva = $_VALOR_IVA;
                    $update->lote_id = $lote ? $lote->id : NULL;

                    $update->update();

                    if ($produto->tipo == "P") {

                        $update_gestao_quantidade = Estoque::find($gestao_quantidade->id);

                        if ($update_gestao_quantidade) {
                            $update_gestao_quantidade->stock = $update_gestao_quantidade->stock - $car['quantidade'];
                            $update_gestao_quantidade->update();
                        }
                    }
                } else {

                    $create = ItemVenda::create([
                        'produto_id' => $produto->id,
                        'movimento_id' => 1,
                        'quantidade' => $car['quantidade'],
                        'quantidade_devolvida' => 0,
                        'user_id' => Auth::user()->id,
                        'valor_pagar' => $_VALOR_TOTAL,
                        'total' => $_VALOR_TOTAL,
                        'retencao_fonte' => $_VALOR_RETENCAO,
                        'preco_unitario' => (($car['preco'] - $IVA_VALOR_VENDA_PRODUTO) ?? $produto->preco_venda) - $_DESCONTO,
                        'custo' => $produto->preco_custo * $car['quantidade'],
                        'lucro' => (((($car['preco'] - $IVA_VALOR_VENDA_PRODUTO) ?? $produto->preco_venda) - $produto->preco_custo) - $_DESCONTO) * $car['quantidade'],
                        'lucro_iva' => (($produto->preco_venda_com_iva - $produto->preco_custo) - $_DESCONTO) * $car['quantidade'],
                        'desconto_aplicado' => $DESCONTO_APLICADO,
                        'status' => 'processo',
                        'tipo_desconto' => 'P',
                        'valor_base' => $_VALOR_BASE,
                        'valor_iva' => $_VALOR_IVA,
                        'desconto_aplicado_valor' => $_DESCONTO,
                        'iva' => $produto->imposto,
                        'iva_taxa' => $produto->taxa,
                        'texto_opcional' => "",
                        'status_uso' => $status_uso,
                        'lote_id' => $lote ? $lote->id : NULL,
                        'caixa_id' => $caixa_id,
                        'mesa_id' => $mesa_id,
                        'code' => NULL,
                        'numero_serie' => "",
                        'entidade_id' => $entidade->empresa->id,
                    ]);

                    if ($produto->tipo == "P") {
                        $update_gestao_quantidade = Estoque::find($gestao_quantidade->id);

                        if ($update_gestao_quantidade) {
                            $update_gestao_quantidade->stock = $update_gestao_quantidade->stock - $car['quantidade'];
                            $update_gestao_quantidade->update();
                        }
                    }
                }
            }


            // verificar se selecionou um produto ou não para realizar a venda
            $movimento = ItemVenda::where('user_id', Auth::user()->id)
                ->where('code', NULL)
                ->where('status_uso', $request->venda_realizado)
                ->where('status', 'processo')
                ->where('entidade_id', $entidade->empresa->id)
                ->with(['produto'])
                ->get();

            if (count($movimento) == 0) {
                return response()->json(['message' => 'O correu um erro, não existe nenhum produto selecionado!'], 404);
            }


            $numerario = (float) ($request['valor_entregue'] ?? 0);
            $multicaixa = (float) ($request['valor_entregue_multicaixa'] ?? 0);
            $desconto   = (float) ($DESCONTO_APLICADO ?? 0);

            $valor_entregue_a_numerario  = $numerario * (1 - $desconto / 100);
            $valor_entregue_a_multicaixa = $multicaixa * (1 - $desconto / 100);

            $_VALOR_A_PAGAR_COM_DESCONTO = $request['total_pagar'] - ($request['total_pagar'] * ($DESCONTO_APLICADO ?? 0) / 100);
            $_VALOR_DESCONTO = $request['total_pagar'] * ($DESCONTO_APLICADO ?? 0) / 100;

            if ($request['pagamento'] == "NU") {
                // finanças
                OperacaoFinanceiro::create([
                    'nome' => $vendas_produtos->nome,
                    'status' => "pago",
                    'motante' => $_VALOR_A_PAGAR_COM_DESCONTO,
                    'formas' => 'C',
                    'cliente_id' => $cliente->id,
                    'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'subconta_id' => $caixaActivo->subconta_id,
                    'model_id' => $vendas_produtos->id,
                    'type' => 'R',
                    'status_pagamento' => "pago",
                    'code' => $code,
                    'descricao' => $vendas_produtos->nome,
                    'movimento' => 'E',
                    'date_at' => date("Y-m-d"),
                    'user_id' => Auth::user()->id,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'user_open_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                $valor_cash = $_VALOR_A_PAGAR_COM_DESCONTO;
                $valor_multicaixa = 0;

                $banco_id = NULL;
            } else if ($request['pagamento'] == "MB" || $request['pagamento'] == "TB" || $request['pagamento'] == "DE") {

                if (!$bancoActivo) {
                    return response()->json(['message' => "TPA não activo ou seja não existe nenhum Conta Bancaria activo, verifica e activa uma conta bancária para poder realizar uma venda via TPA.!"], 404);
                }
                // finanças
                OperacaoFinanceiro::create([
                    'nome' => $vendas_produtos->nome,
                    'status' => "pago",
                    'motante' => $_VALOR_A_PAGAR_COM_DESCONTO,
                    'formas' => 'B',
                    'cliente_id' => $cliente->id,
                    'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'subconta_id' => $bancoActivo->subconta_id,
                    'model_id' => $vendas_produtos->id,
                    'type' => 'R',
                    'status_pagamento' => "pago",
                    'code' => $code,
                    'descricao' => $vendas_produtos->nome,
                    'movimento' => 'E',
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'date_at' => date("Y-m-d"),
                    'user_id' => Auth::user()->id,
                    'user_open_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                $valor_cash = 0;
                $valor_multicaixa = $_VALOR_A_PAGAR_COM_DESCONTO;
                $banco_id = $caixaActivo->id;
            } else if ($request['pagamento'] == "OU") {

                $numerario = (float) ($request->valor_entregue_input ?? 0);
                $valor_entregue_a_numerario = $numerario * (1 - $desconto / 100);
                $multicaixa = (float) $request->valor_entregue_multicaixa_input ?? 0;
                $valor_entregue_a_multicaixa = $multicaixa * (1 - $desconto / 100);


                $valor_cash =  $valor_entregue_a_numerario;
                $valor_multicaixa = $valor_entregue_a_multicaixa;

                $banco_id = $caixaActivo->id;

                if (!$bancoActivo) {
                    return response()->json(['message' => "TPA não activo ou seja não existe nenhum Conta Bancaria activo, verifica e activa uma conta bancária para poder realizar uma venda via TPA.!"], 404);
                }

                // finanças
                OperacaoFinanceiro::create([
                    'nome' => $vendas_produtos->nome,
                    'status' => "pago",
                    'motante' => $valor_multicaixa,
                    'formas' => 'B',
                    'cliente_id' => $cliente->id,
                    'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'subconta_id' => $bancoActivo->subconta_id,
                    'model_id' => $vendas_produtos->id,
                    'type' => 'R',
                    'status_pagamento' => "pago",
                    'code' => $code,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'descricao' => $vendas_produtos->nome,
                    'movimento' => 'E',
                    'date_at' => date("Y-m-d"),
                    'user_id' => Auth::user()->id,
                    'user_open_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                OperacaoFinanceiro::create([
                    'nome' => $vendas_produtos->nome,
                    'status' => "pago",
                    'motante' => $valor_cash,
                    'formas' => 'C',
                    'cliente_id' => $cliente->id,
                    'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'subconta_id' => $caixaActivo->subconta_id,
                    'model_id' => $vendas_produtos->id,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'type' => 'R',
                    'status_pagamento' => "pago",
                    'code' => $code,
                    'descricao' => $vendas_produtos->nome,
                    'movimento' => 'E',
                    'date_at' => date("Y-m-d"),
                    'user_id' => Auth::user()->id,
                    'user_open_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);
            }


            $subconta_venda_mercadoria = Subconta::where('numero', ENV('VENDA_DE_MERCADORIA'))->first();
            $subconta_prestacao_servico = Subconta::where('numero', ENV('PRESTACAO_SERVICO'))->first();
            $subconta_custo_mercadoria = Subconta::where('numero', ENV('CUSTO_MERCADORIA_VENDIDA'))->first();

            foreach ($carrinho as $car) {
                $subconta_iva = Subconta::where('numero', ENV('IVA_LIQUIDADO'))->first();
                $produt = Produto::findOrFail($car['produto_id']);

                $_VALOR_PAGAR = ($car['preco'] ?? $produt->preco_venda) * $car['quantidade'];

                $_DESCONTO = $_VALOR_PAGAR * ($request->desconto / 100);

                $_VALOR_BASE = $_VALOR_PAGAR - $_DESCONTO;

                $_VALOR_IVA = $_VALOR_BASE * ($produt->taxa / 100);

                $_VALOR_RETENCAO = $_VALOR_BASE * ($entidade->empresa->taxa_retencao_fonte / 100);

                $_VALOR_TOTAL = ($_VALOR_BASE + $_VALOR_IVA) -  $_VALOR_RETENCAO;

                if ($produt->tipo == "P") {
                    ## creditar na conta proveito - 61 - ou seja diminuir o valor sem o iva
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta_venda_mercadoria->id,
                        'status' => true,
                        'movimento' => 'S',
                        'credito' => $_VALOR_TOTAL,
                        'debito' => 0,
                        'observacao' => $request->observacao,
                        'code' => $code,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);
                }

                if ($produt->tipo == "S") {
                    ## creditar na conta proveito - 26 - ou seja diminuir o valor sem o iva
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $produt->subconta_id,
                        'status' => true,
                        'movimento' => 'S',
                        'credito' => $_VALOR_TOTAL,
                        'debito' => 0,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'observacao' => $request->observacao,
                        'code' => $code,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);
                }

                if ($entidade->empresa->tipo_inventario == "PERMANENTE") {
                    ## creditar na conta proveito - 26 - ou seja diminuir o valor sem o iva
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $produt->subconta_id,
                        'status' => true,
                        'movimento' => 'S',
                        'credito' => $_VALOR_TOTAL,
                        'debito' => 0,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'observacao' => $request->observacao,
                        'code' => $code,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);

                    ## custo da mercadoria
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta_custo_mercadoria->id,
                        'status' => true,
                        'movimento' => 'S',
                        'credito' => 0,
                        'debito' => $_VALOR_TOTAL,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'observacao' => $request->observacao,
                        'code' => $code,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);
                }

                ## creditar e debitar na conta 31 ou seja preciso aumentar a divida do clientes e depois liquidar da mesma divida
                ## START
                Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $cliente->subconta_id,
                    'status' => true,
                    'movimento' => 'E',
                    'credito' => 0,
                    'debito' => $_VALOR_TOTAL,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'observacao' => $request->observacao,
                    'code' => $code,
                    'data_at' => date("Y-m-d"),
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $cliente->subconta_id,
                    'status' => true,
                    'movimento' => 'S',
                    'credito' => $_VALOR_TOTAL,
                    'debito' => 0,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'observacao' => $request->observacao,
                    'code' => $code,
                    'data_at' => date("Y-m-d"),
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);
            }

            if ($request['pagamento'] == "NU") {
                ## vamor aumentar o valor do caixa - 45/43
                Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $caixaActivo->subconta_id,
                    'status' => true,
                    'movimento' => 'E',
                    'credito' => 0,
                    'debito' => $_VALOR_A_PAGAR_COM_DESCONTO,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'observacao' => $request->observacao,
                    'code' => $code,
                    'data_at' => date("Y-m-d"),
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);
            } else if ($request['pagamento'] == "MB") {
                $bancoActivo = ContaBancaria::where('active', true)
                    ->where('status', '=', 'aberto')
                    ->where('user_open_id', '=', Auth::user()->id)
                    ->where('entidade_id', '=', $entidade->empresa->id)
                    ->first();

                Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $bancoActivo->subconta_id,
                    'status' => true,
                    'movimento' => 'E',
                    'credito' => 0,
                    'debito' => $_VALOR_A_PAGAR_COM_DESCONTO,
                    'observacao' => $request->observacao,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'code' => $code,
                    'data_at' => date("Y-m-d"),
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);
            } else if ($request['pagamento'] == "OU") {

                $numerario = (float) ($request->valor_entregue_input ?? 0);
                $valor_entregue_a_numerario = $numerario * (1 - $desconto / 100);
                $multicaixa = (float) $request->valor_entregue_multicaixa_input ?? 0;
                $valor_entregue_a_multicaixa = $multicaixa * (1 - $desconto / 100);

                $valor_cash =  $valor_entregue_a_numerario;
                $valor_multicaixa = $valor_entregue_a_multicaixa;

                if ($bancoActivo) {

                    $movimeto = Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $caixaActivo->subconta_id,
                        'status' => true,
                        'movimento' => 'E',
                        'credito' => 0,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'debito' => $valor_cash,
                        'observacao' => $request->observacao,
                        'code' => $code,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);

                    $movimeto = Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $bancoActivo->subconta_id ?? NULL,
                        'status' => true,
                        'movimento' => 'E',
                        'credito' => 0,
                        'debito' => $valor_multicaixa,
                        'observacao' => $request->observacao,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'code' => $code,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);
                }
            }

            $_TOTAL_VERIFICADO = $valor_entregue_a_numerario + $valor_entregue_a_multicaixa;

            if ($_VALOR_A_PAGAR_COM_DESCONTO > $_TOTAL_VERIFICADO) {
                return response()->json(['message' => 'O Valor Entregue para esta Compra é insuficiente!'], 400);
            }

            $ultimoRecibo = Venda::where([
                ['factura', '=', $request->documento],
                ['ano_factura', '=', $entidade->empresa->ano_factura],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
                ->orderBy('id', 'DESC')
                ->limit(1)
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

            $rsa = new RSA(); //Algoritimo RSA

            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();

            // Lendo a private key
            $rsa->loadKey($privatekey);

            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "{$request->documento} {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}" . ';' . number_format($request['total_pagar'], 2, ".", "") . ';' . $hashAnterior;

            /**
             * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
             * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */

            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

            // Lendo a public key
            $rsa->loadKey($publickey);

            $valor_extenso = $this->valor_por_extenso($_VALOR_A_PAGAR_COM_DESCONTO);

            if ($request->venda_realizado == "CAIXA") {
                $mesa = Caixa::find($caixaActivo->id);
            }

            if ($request->venda_realizado == "MESA") {
                $mesa = Mesa::find($request['mesa_id']);
            }

            $inicioDoDia = Carbon::parse(date("Y-m-d"))->startOfDay();
            $fimDoDia = Carbon::parse(date("Y-m-d"))->endOfDay();
            $total_atendimentos = Venda::whereBetween('created_at', [$inicioDoDia, $fimDoDia])->where('entidade_id', $entidade->empresa->id)->count() + 1;

            // nova parte lucro total e custo total
            $lucro_total = 0;
            $lucro_iva_total = 0;
            $custo_total = 0;

            if ($movimento) {
                foreach ($movimento as $movim) {
                    $lucro_total += $movim->lucro;
                    $lucro_iva_total += $movim->lucro_iva;
                    $custo_total += $movim->custo;
                }
            }

            $create = Venda::create([
                'numero_pedido_diario' => $total_atendimentos,
                'codigo_factura' =>  $numeroFactura,
                'status' => true,
                'cliente_id' => $cliente->id,
                'banco_id' => $banco_id,
                'mesa_id' => $mesa ? $mesa->id : NULL,
                'mesa_caixa' => $request['venda_realizado'],
                'status_factura' => 'pago',
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                'status_venda' => "realizado",
                'user_id' => Auth::user()->id,
                'caixa_id' => $caixaActivo->id,
                'valor_entregue' => $valor_entregue_a_numerario,
                'valor_total' => $_VALOR_A_PAGAR_COM_DESCONTO,
                'lucro_iva_total' => $lucro_iva_total,
                'lucro_total' => $lucro_total,
                'custo_total' => $custo_total,
                'valor_troco' => ($valor_entregue_a_numerario + $valor_entregue_a_multicaixa) - $request['total_pagar'],
                'code' => $code,
                'ano_factura' => $entidade->empresa->ano_factura,
                'nome_cliente' => $request->nome_cliente ?? "CONSUMIDOR FINAL",
                'documento_nif' => $request->documento_nif ?? "999999999",
                'desconto' => $_VALOR_DESCONTO,
                'desconto_percentagem' => $request->desconto,
                'entidade_id' => $entidade->empresa->id,
                'prazo' => 0,
                'data_emissao' => date("Y-m-d"),
                'data_documento' => $datactual,
                'data_vencimento' => date("Y-m-d"),
                'data_disponivel' => date("Y-m-d"),
                'pagamento' => $request['pagamento'],
                'factura' => $request['documento'],
                'factura_next' => "{$request['documento']} {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}",
                'observacao' => "venda realizada com sucesso!",
                'referencia' => "venda realizada com sucesso!",

                'retificado' => 'N',
                'convertido_factura' => 'N',
                'factura_divida' => 'N',
                'anulado' => 'N',

                'moeda' => $entidade->empresa->moeda ?? 'AOA',
                'valor_extenso' => $valor_extenso,
                'valor_cash' => $valor_cash,
                'valor_multicaixa' => $valor_multicaixa,
                'texto_hash' => $plaintext,
                'hash' => base64_encode($signaturePlaintext),
                'nif_cliente' => $cliente->nif,
            ]);

            foreach ($carrinho as $key => $car) {

                $produto = Produto::findOrFail($car['produto_id']);

                // SElecinar em que lote este produto pertence para se comercializado ou reduzido naquele stock
                $lote = Lote::where("produto_id", $produto->id)
                    ->where("codigo_barra", $produto->codigo_barra)
                    ->where("entidade_id", $entidade->empresa->id)
                    ->first();

                if ($lote && $lote->status == "expirado" && $lote->data_validade <= date("Y-m-d")) {
                    return response()->json(['message' => "O produto: { $produto->nome } parece estar expirado, por isso não é possível finalizar a venda, visando a segurança da população."], 404);
                }

                Registro::create([
                    "registro" => "Saída de Stock",
                    "documento" => $doc,
                    "documento_id" => $create->id,
                    "data_registro" => date('Y-m-d'),
                    "preco_unitario" => $car['preco'],
                    "quantidade" => $car['quantidade'],
                    "produto_id" => $produto->id,
                    "observacao" => "Saída do produto {$produto->nome} para venda",
                    "loja_id" => $loja->id,
                    "tipo" => "S",
                    "status" => "V",
                    "lote_id" => $lote ? $lote->id : NULL,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);
            }

            if ($request->venda_realizado == "CAIXA") {
                $movimentos = ItemVenda::where('user_id', '=', Auth::user()->id)
                    ->where('status', '=', 'processo')
                    ->where('caixa_id', '=', $mesa->id)
                    ->where('status_uso', '=', "CAIXA")
                    ->where('entidade_id', '=', $entidade->empresa->id)
                    ->where('code', NULL)
                    ->get();
            }
            if ($request->venda_realizado == "MESA") {
                $movimentos = ItemVenda::where('user_id', '=', Auth::user()->id)
                    ->where('mesa_id', '=', $mesa->id)
                    ->where('status_uso', '=', "MESA")
                    ->where('status', '=', 'processo')
                    ->where('entidade_id', '=', $entidade->empresa->id)
                    ->where('code', NULL)
                    ->get();
            }

            $totalValorBase = 0;
            $totalValorIva = 0;
            $totalItems = 0;

            if ($movimentos) {
                foreach ($movimentos as $value) {
                    $update = ItemVenda::findOrFail($value->id);
                    $update->code = $code;
                    $update->status = "realizado";
                    $update->factura_id = $create->id;
                    $update->banco_id = $banco_id;
                    $update->update();

                    $totalValorBase += $value->valor_base;
                    $totalValorIva += $value->valor_iva;
                    $totalItems += $value->quantidade;
                }
            }

            $create->total_iva = $totalValorIva;
            $create->total_incidencia = $totalValorBase;
            $create->quantidade = $totalItems;
            $create->save();

            if ($request['venda_realizado'] == "MESA") {
                $mesa->solicitar_ocupacao = "LIVRE";
                $mesa->update();
            }

            $vendas = Venda::with('cliente')->where('code', $create->code)->first();
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
            $items = ItemVenda::with('produto')->where('code', $vendas->code)->get();
            $factura = Venda::with('cliente')->with('caixa')->with('user')->where('code', $vendas->code)->first();
            $movimentos = ItemVenda::with('produto.motivo')->where('code', $factura->code)->where('entidade_id', $entidade->empresa->id)->get();

            if ($movimentos) {

                $total_incidencia_ise = 0;
                $total_iva_ise = 0;


                $total_incidencia_nor = 0;
                $total_iva_nor = 0;

                $total_incidencia_out = 0;
                $total_iva_out = 0;

                $motivo = "";

                foreach ($movimentos as $item) {
                    if ($item->iva == 'NOR') {
                        $total_incidencia_nor = $total_incidencia_nor + $item->valor_base;
                        $total_iva_nor = $total_iva_nor + $item->valor_iva;
                    }
                    if ($item->iva == 'ISE') {
                        $total_incidencia_ise = $total_incidencia_ise + $item->valor_base;
                        $total_iva_ise = $total_iva_ise + $item->valor_iva;

                        $motivo = $item->produto->motivo->descricao;
                    }
                    if ($item->iva == 'OUT') {
                        $total_incidencia_out = $total_incidencia_out + $item->valor_base;
                        $total_iva_out = $total_iva_out + $item->valor_iva;
                    }
                }
            }

            // Aqui você deve adicionar a lógica para processar o pagamento...
            // Por exemplo, integração com gateway de pagamento, verificação de estoque, etc.

            // Após o pagamento, limpar o carrinho
            Session::forget('carrinho');

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
          
            return response()->json(['error' => $e->getMessage()], 400);
        }

        // Após o pagamento, limpar o carrinho
        Session::forget('carrinho');

        $head = [
            'titulo' => "Movimentos do Stock",
            'descricao' => env('APP_NAME'),
            "loja" => $entidade,
            "factura" => $vendas,
            "items_facturas" => $items,

            // "items_facturas_movimentos" => $movimentos,
            "total_incidencia_nor" => $total_incidencia_nor,
            "total_iva_nor" => $total_iva_nor,

            "total_incidencia_ise" => $total_incidencia_ise,
            "total_iva_ise" => $total_iva_ise,

            "total_incidencia_out" => $total_incidencia_out,
            "total_iva_out" => $total_iva_out,
            "motivo" => $motivo,
            "venda_realizado" => $request->venda_realizado,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        // Retorna a resposta de sucesso
        return response()->json(['message' => 'Pagamento realizado com sucesso!', 'data' => $head], 200);
    }


    public function inserir_operacao($descricao, $motante, $subconta, $cliente, $model, $type, $code, $movimento, $formas = "C")
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        OperacaoFinanceiro::create([
            'nome' => $descricao,
            'status' => "pago",
            'formas' => $formas,
            'motante' => $motante,
            'subconta_id' => $subconta,
            'cliente_id' => $cliente,
            'model_id' => $model,
            'type' => $type,
            'parcelado' => "N",
            'status_pagamento' => "pago",
            'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
            'code' => $code,
            'descricao' => $descricao,
            'movimento' => $movimento,
            'date_at' => date("Y-m-d"),
            'user_id' => Auth::user()->id,
            'entidade_id' => $entidade->empresa->id,
            'exercicio_id' => $this->exercicio(),
            'periodo_id' => $this->periodo(),
        ]);
    }


    // Método auxiliar para calcular o total do carrinho
    private function calcularTotal($carrinho)
    {
        return array_reduce($carrinho, function ($carry, $item) {
            return $carry + $item['valor_pagar'];
        }, 0);
    }

    // Adiciona um produto ao carrinho
    public function adicionar_mesa(Request $request)
    {
        try {
            // Inicia a transação
            DB::beginTransaction();
            // Comita a transação se tudo estiver correto
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
            $produto = Produto::with('marca', 'variacao', 'categoria', 'estoque')->findOrFail($request->produto_id);
            $mesa = Mesa::findOrFail($request->mesa);

            // $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

            // $loja = Loja::where("entidade_id", $entidade->empresa->id)
            //     ->whereIn("id", $minhas_lojas)
            //     ->where("status", "activo")
            //     ->first();
            
            $loja = $this->LOJA_ACTIVA_USER();

            if ($produto->tipo == "P") {

                if (!$loja) {
                    return response()->json([
                        "messagem" => "Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto. Por favor activa uma loja/armazém que tem este produto!"
                    ], 404);
                }

                // verificar quantidade de produto no estoque da loja
                $verificar_quantidade = Estoque::where('loja_id', $loja->id)
                    ->where('produto_id', $produto->id)
                    ->where('entidade_id', $entidade->empresa->id)
                    ->sum('stock');

                $verificar_quantidade = (float) $verificar_quantidade;

                if ($verificar_quantidade <= 0) {
                    return response()->json([
                        'messagem' => "A Loja activa não têm este produto em stock para poder comercializar!"
                    ], 404);
                }

                if ($produto->estoque) {
                    if ($produto->estoque->stock <= $produto->estoque->stock_minimo) {
                        return response()->json([
                            'messagem' => "A quantidade deste produto em estoque está abaixo do limite crítico, impedindo a venda no momento."
                        ], 404);
                    }
                } else {
                    return response()->json([
                        'messagem' => "A quantidade deste produto em estoque está abaixo do limite crítico, impedindo a venda no momento."
                    ], 404);
                }
            }

            $caixaActivo = Caixa::where('active', true)
                ->where('status', 'aberto')
                ->where('status_admin', 'liberado')
                ->where('user_open_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();


            // SElecinar em que lote este produto pertence para se comercializado ou reduzido naquele stock
            $lote = Lote::where("produto_id", $produto->id)
                ->where("codigo_barra", $produto->codigo_barra)
                ->where("entidade_id", $entidade->empresa->id)
                ->first();

            if ($produto->tipo == "P") {

                if ($lote && $lote->status == "expirado" && $lote->data_validade <= date("Y-m-d")) {
                    return response()->json(['message' => "O produto: { $produto->nome } parece estar expirado, por isso não é possível finalizar a venda, visando a segurança da população."], 404);
                }

                // verificar se este produto tem quantidade para ser vendidas no lote não expirado, isto por que  não podemos permitir o sistema vender quantidades que não existem 
                $verificar_lote_produto = $produto->verificar_lote_produto($produto->id, $lote->id, $entidade->empresa->id);

                if ($verificar_lote_produto <= 0) {
                    return response()->json(["message" => "O produto {$produto->nome} não possui quantidade disponível para comercialização, pois o sistema não conseguiu identificar a quantidade do lote referente a este produto. Por favor, verifique se existem lotes expirados. Caso contrário, ative o código de barras correspondente ao lote!"], 400);
                }
            }


            if ($caixaActivo) {

                $status_uso = "MESA";
                $caixa_id = NULL;

                Registro::create([
                    "registro" => "Saída de Stock",
                    "data_registro" => date('Y-m-d'),
                    "quantidade" => 1,
                    "tipo" => "S",
                    "status" => "V",
                    "produto_id" => $produto->id,
                    "preco_unitario" => $produto->preco_venda_com_iva,
                    "observacao" => "Saída do produto {$produto->nome} para venda",
                    "loja_id" => $loja->id,
                    "lote_id" => $lote ? $lote->id : NULL,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);

                $verificarProdutoAdicionado = ItemVenda::where('status', 'processo')
                    ->where('produto_id', $produto->id)
                    ->where('mesa_id', $request->mesa)
                    ->where('status_uso', $status_uso)
                    ->where('entidade_id', $entidade->empresa->id)
                    ->where('user_id', Auth::user()->id)
                    ->first();

                // calcudo do total de incidencia
                //________________ valor total _____________
                $valorBase = $produto->preco_venda * $request->quantidade ?? 1;
                // calculo do iva
                $valorIva = ($produto->taxa / 100) * $valorBase;

                if ($verificarProdutoAdicionado) {
                    $update = ItemVenda::findOrFail($verificarProdutoAdicionado->id);

                    $desconto = ($produto->preco_venda * ($update->quantidade + $request->quantidade ?? 1)) * ($update->desconto_aplicado / 100);

                    $valorBase = $produto->preco_venda * ($update->quantidade + $request->quantidade ?? 1);
                    // calculo do iva
                    $valorIva = ($produto->taxa / 100) * $valorBase;

                    $update->quantidade = $update->quantidade + $request->quantidade ?? 1;
                    $update->valor_pagar = ($valorBase + $valorIva) - $desconto;

                    $update->custo = $produto->preco_custo * $update->quantidade;
                    $update->lucro = ($produto->preco_venda - $produto->preco_custo) * $update->quantidade;

                    $update->desconto_aplicado = $update->desconto_aplicado;
                    $update->desconto_aplicado_valor = $desconto;

                    $update->valor_base = $valorBase;
                    $update->valor_iva = $valorIva;

                    $update->update();


                    if ($produto->tipo == "P") {
                        $produto->estoque->stock = $produto->estoque->stock - $request->quantidade ?? 1;
                        $produto->estoque->update();
                    }
                } else {
                    $create = ItemVenda::create(
                        [
                            'produto_id' => $produto->id,
                            'quantidade' => $request->quantidade ?? 1,
                            'quantidade_devolvida' => 0,
                            'user_id' => Auth::user()->id,
                            'valor_pagar' => $valorBase + $valorIva,
                            'preco_unitario' => $produto->preco_venda,
                            'custo' => $produto->preco_custo,
                            'lucro' => ($produto->preco_venda - $produto->preco_custo) * $request->quantidade ?? 1,
                            'desconto_aplicado' => 0,
                            'status' => 'processo',
                            'valor_base' => $valorBase,
                            'valor_iva' => $valorIva,
                            'desconto_aplicado_valor' => 0,
                            'iva' => $produto->imposto,
                            'iva_taxa' => $produto->taxa,
                            'texto_opcional' => "",
                            'status_uso' => $status_uso,
                            'caixa_id' => $caixa_id,
                            'mesa_id' => $mesa->id,
                            'code' => NULL,
                            'numero_serie' => "",
                            'entidade_id' => $entidade->empresa->id,
                        ]
                    );

                    if ($produto->tipo == "P") {
                        $produto->estoque->stock = $produto->estoque->stock - $request->quantidade;
                        $produto->estoque->update();
                    }
                }
            } else {
                return response()->json([
                    'messagem' => "Verifica se tens um caixa aberto, por favor!"
                ], 404);
            }

            $movimentos = ItemVenda::where('code', NULL)
                ->where('status', 'processo')
                ->where('status_uso', 'MESA')
                ->where('mesa_id', $mesa->id)
                ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->with(['produto'])
                ->get();

            $total_pagar = ItemVenda::where('code', NULL)
                ->where('status', 'processo')
                ->where('status_uso', 'MESA')
                ->where('mesa_id', $mesa->id)
                ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->sum('valor_pagar');

            $total_produtos = ItemVenda::where('code', NULL)
                ->where('status', 'processo')
                ->where('status_uso', 'MESA')
                ->where('mesa_id', $mesa->id)
                ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->count();

            $total_unidades = ItemVenda::where('code', NULL)
                ->where('status', 'processo')
                ->where('status_uso', 'MESA')
                ->where('mesa_id', $mesa->id)
                ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->sum('quantidade');

            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger('Error', $e->getMessage());
            return redirect()->back()->with('danger', $e->getMessage());
        }

        return response()->json([
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "total_produtos" => $total_produtos,
            "total_unidades" => $total_unidades,
        ], 200);
    }

    // Adiciona um produto ao carrinho
    public function adicionar_quarto(Request $request)
    {
        try {
            // Inicia a transação
            DB::beginTransaction();
            // Comita a transação se tudo estiver correto
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
            $produto = Produto::with('marca', 'variacao', 'categoria', 'estoque')->findOrFail($request->produto_id);
            $quarto = Quarto::findOrFail($request->quarto);

            // $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

            // $loja = Loja::where("entidade_id", $entidade->empresa->id)
            //     ->whereIn("id", $minhas_lojas)->where("status", "activo")
            //     ->first();
            
            $loja = $this->LOJA_ACTIVA_USER();

            if (!$loja) {
                return response()->json([
                    'messagem' => "Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto. Por favor activa uma loja/armazém que tem este produto!"
                ], 404);
            }

            if ($produto->tipo == "P") {
                // verificar quantidade de produto no estoque da loja
                $verificar_quantidade = Estoque::where('loja_id', $loja->id)
                    ->where('produto_id', $produto->id)
                    ->where('entidade_id', $entidade->empresa->id)
                    ->sum('stock');

                $verificar_quantidade = (float) $verificar_quantidade;

                if ($verificar_quantidade <= 0) {
                    return response()->json([
                        'messagem' => "A Loja activa não têm este produto em stock para poder comercializar!"
                    ], 404);
                }

                if ($produto->estoque) {
                    if ($produto->estoque->stock <= $produto->estoque->stock_minimo) {
                        return response()->json([
                            'messagem' => "A quantidade deste produto em estoque está abaixo do limite crítico, impedindo a venda no momento."
                        ], 404);
                    }
                } else {
                    return response()->json([
                        'messagem' => "A quantidade deste produto em estoque está abaixo do limite crítico, impedindo a venda no momento."
                    ], 404);
                }
            }

            $caixaActivo = Caixa::where('active', true)
                ->where('status', 'aberto')
                ->where('status_admin', 'liberado')
                ->where('user_open_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            // SElecinar em que lote este produto pertence para se comercializado ou reduzido naquele stock
            $lote = Lote::where("produto_id", $produto->id)
                ->where("codigo_barra", $produto->codigo_barra)
                ->where("entidade_id", $entidade->empresa->id)
                ->first();


            if ($produto->status == "P") {

                if ($lote && $lote->status == "expirado" && $lote->data_validade <= date("Y-m-d")) {
                    return response()->json(['message' => "O produto: { $produto->nome } parece estar expirado, por isso não é possível finalizar a venda, visando a segurança da população."], 404);
                }

                // verificar se este produto tem quantidade para ser vendidas no lote não expirado, isto por que  não podemos permitir o sistema vender quantidades que não existem 
                $verificar_lote_produto = $produto->verificar_lote_produto($produto->id, $lote->id, $entidade->empresa->id);

                if ($verificar_lote_produto <= 0) {
                    return response()->json(["message" => "O produto {$produto->nome} não possui quantidade disponível para comercialização, pois o sistema não conseguiu identificar a quantidade do lote referente a este produto. Por favor, verifique se existem lotes expirados. Caso contrário, ative o código de barras correspondente ao lote!"], 400);
                }
            }

            if ($caixaActivo) {

                $status_uso = "QUARTO";
                $caixa_id = NULL;

                Registro::create([
                    "registro" => "Saída de Stock",
                    "data_registro" => date('Y-m-d'),
                    "quantidade" => 1,
                    'tipo' => 'S',
                    "status" => "V",
                    "produto_id" => $produto->id,
                    "preco_unitario" => $produto->preco_venda_com_iva,
                    "observacao" => "Saída do produto {$produto->nome} para venda quarto",
                    "loja_id" => $loja->id,
                    "lote_id" => $lote ? $lote->id : NULL,
                    "user_id" => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);

                $verificarProdutoAdicionado = ItemVenda::where('status', 'processo')
                    ->where('produto_id', '=', $produto->id)
                    ->where('quarto_id', '=', $request->quarto)
                    ->where('status_uso', '=', $status_uso)
                    ->where('entidade_id', '=', $entidade->empresa->id)
                    ->where('user_id', '=', Auth::user()->id)
                    ->first();

                // calcudo do total de incidencia
                //________________ valor total _____________
                $valorBase = $produto->preco_venda * $request->quantidade ?? 1;
                // calculo do iva
                $valorIva = ($produto->taxa / 100) * $valorBase;

                if ($verificarProdutoAdicionado) {
                    $update = ItemVenda::findOrFail($verificarProdutoAdicionado->id);

                    $desconto = ($produto->preco_venda * ($update->quantidade + $request->quantidade ?? 1)) * ($update->desconto_aplicado / 100);

                    $valorBase = $produto->preco_venda * ($update->quantidade + $request->quantidade ?? 1);
                    // calculo do iva
                    $valorIva = ($produto->taxa / 100) * $valorBase;

                    $update->quantidade = $update->quantidade + $request->quantidade ?? 1;
                    $update->valor_pagar = ($valorBase + $valorIva) - $desconto;

                    $update->custo = $produto->preco_custo * $update->quantidade;
                    $update->lucro = ($produto->preco_venda - $produto->preco_custo) * $update->quantidade;

                    $update->desconto_aplicado = $update->desconto_aplicado;
                    $update->desconto_aplicado_valor = $desconto;

                    $update->valor_base = $valorBase;
                    $update->valor_iva = $valorIva;

                    $update->update();

                    if ($produto->tipo == "P") {

                        $produto->estoque->stock = $produto->estoque->stock - $request->quantidade ?? 1;
                        $produto->estoque->update();
                    }
                } else {

                    $create = ItemVenda::create([
                        'produto_id' => $produto->id,
                        'quantidade' => $request->quantidade ?? 1,
                        'quantidade_devolvida' => 0,
                        'user_id' => Auth::user()->id,
                        'valor_pagar' => $valorBase + $valorIva,
                        'preco_unitario' => $produto->preco_venda,
                        'custo' => $produto->preco_custo,
                        'lucro' => ($produto->preco_venda - $produto->preco_custo) * $request->quantidade ?? 1,
                        'desconto_aplicado' => 0,
                        'status' => 'processo',
                        'valor_base' => $valorBase,
                        'valor_iva' => $valorIva,
                        'desconto_aplicado_valor' => 0,
                        'iva' => $produto->imposto,
                        'iva_taxa' => $produto->taxa,
                        'texto_opcional' => "",
                        'status_uso' => $status_uso,
                        'caixa_id' => $caixa_id,
                        'quarto_id' => $quarto->id,
                        'code' => NULL,
                        'numero_serie' => "",
                        'entidade_id' => $entidade->empresa->id,
                    ]);

                    if ($produto->tipo == "P") {

                        $produto->estoque->stock = $produto->estoque->stock - $request->quantidade;
                        $produto->estoque->update();
                    }
                }
            } else {
                return response()->json([
                    'messagem' => "Verifica se tens um caixa aberto, por favor!"
                ], 404);
            }

            $movimentos = ItemVenda::where('code', NULL)
                ->where('status', 'processo')
                ->where('status_uso', $status_uso)
                ->where('quarto_id', $quarto->id)
                ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->with(['produto'])
                ->get();

            $total_pagar = ItemVenda::where('code', NULL)
                ->where('status', 'processo')
                ->where('status_uso', $status_uso)
                ->where('quarto_id', $quarto->id)
                ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->sum('valor_pagar');

            $total_produtos = ItemVenda::where('code', NULL)
                ->where('status', 'processo')
                ->where('status_uso', $status_uso)
                ->where('quarto_id', $quarto->id)
                ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->count();

            $total_unidades = ItemVenda::where('code', NULL)
                ->where('status', 'processo')
                ->where('status_uso', $status_uso)
                ->where('quarto_id', $quarto->id)
                ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->sum('quantidade');


            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back()->with('danger', $e->getMessage());
        }

        return response()->json([
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "total_produtos" => $total_produtos,
            "total_unidades" => $total_unidades,
        ], 200);
    }

    // Remove um produto do carrinho
    public function remover_mesa(Request $request)
    {
        try {
            // Inicia a transação
            DB::beginTransaction();

            $movimento = ItemVenda::findOrFail($request->itemId);

            $produto = Produto::with('marca', 'variacao', 'categoria', 'estoque')->findOrFail($movimento->produto_id);

            $produto->estoque->stock = $produto->estoque->stock + $movimento->quantidade ?? 1;
            $produto->estoque->update();

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

            // $verifica se tem uma loja activa onde esta sendo retidados os produtos
            $loja = Loja::where("entidade_id", $entidade->empresa->id)
                ->whereIn("id", $minhas_lojas)
                ->where("status", "activo")
                ->first();

            // Selecinar em que lote este produto pertence para se comercializado ou reduzido naquele stock
            $lote = Lote::where("produto_id", $produto->id)
                ->where("codigo_barra", $produto->codigo_barra)
                ->where("entidade_id", $entidade->empresa->id)
                ->first();

            Registro::create([
                "registro" => "Entrada de Stock",
                "data_registro" => date('Y-m-d'),
                "quantidade" => $movimento->quantidade ?? 1,
                "preco_unitario" => $produto->preco_venda_com_iva,
                "produto_id" => $produto->id,
                "observacao" => "Entrada do produto {$produto->nome} para venda mesa",
                "loja_id" => $loja->id,
                "tipo" => "E",
                "status" => "R",
                "lote_id" => $lote ? $lote->id : NULL,
                "user_id" => Auth::user()->id,
                "entidade_id" => $entidade->empresa->id,
            ]);

            $status_uso = "MESA";
            $movimento->delete();

            $movimentos = ItemVenda::where('code', NULL)
                ->where('status', 'processo')
                ->where('status_uso', $status_uso)
                ->where('mesa_id', $movimento->mesa_id)
                ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->with('produto')->get();

            $total_pagar = ItemVenda::where('code', NULL)
                ->where('status', 'processo')
                ->where('status_uso', $status_uso)
                ->where('mesa_id', $movimento->mesa_id)
                ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->sum('valor_pagar');

            $total_produtos = ItemVenda::where('code', NULL)
                ->where('status', 'processo')
                ->where('status_uso', $status_uso)
                ->where('mesa_id', $movimento->mesa_id)
                ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->count();

            $total_unidades = ItemVenda::where('code', NULL)
                ->where('status', 'processo')
                ->where('status_uso', $status_uso)
                ->where('mesa_id', $movimento->mesa_id)
                ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->sum('quantidade');

            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger('Error', $e->getMessage());
            return redirect()->back()->with('danger', $e->getMessage());
        }

        return response()->json([
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "total_produtos" => $total_produtos,
            "total_unidades" => $total_unidades,
        ], 200);
    }

    // Remove um produto do carrinho
    public function remover_quarto(Request $request)
    {
        try {
            // Inicia a transação
            DB::beginTransaction();

            $movimento = ItemVenda::findOrFail($request->itemId);
            $produto = Produto::with('marca', 'variacao', 'categoria', 'estoque')->findOrFail($movimento->produto_id);

            $produto->estoque->stock = $produto->estoque->stock + $movimento->quantidade ?? 1;
            $produto->estoque->update();

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

            // $verifica se tem uma loja activa onde esta sendo retidados os produtos
            $loja = Loja::where("entidade_id", $entidade->empresa->id)
                ->where("status", "activo")
                ->whereIn("id", $minhas_lojas)
                ->first();

            // Selecinar em que lote este produto pertence para se comercializado ou reduzido naquele stock
            $lote = Lote::where("produto_id", $produto->id)
                ->where("codigo_barra", $produto->codigo_barra)
                ->where("entidade_id", $entidade->empresa->id)
                ->first();

            Registro::create([
                "registro" => "Entrada de Stock",
                "data_registro" => date('Y-m-d'),
                "quantidade" => $movimento->quantidade ?? 1,
                "preco_unitario" => $produto->preco_venda_com_iva,
                "produto_id" => $produto->id,
                "observacao" => "Entrada do produto {$produto->nome} para venda quarto",
                "loja_id" => $loja->id,
                'tipo' => 'E',
                'status' => 'R',
                "lote_id" => $lote ? $lote->id : NULL,
                "user_id" => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);

            $status_uso = "QUARTO";
            $movimento->delete();

            $movimentos = ItemVenda::where('code', NULL)
                ->where('status', 'processo')
                ->where('status_uso', $status_uso)
                ->where('quarto_id', $movimento->quarto_id)
                ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->with('produto')
                ->get();

            $total_pagar = ItemVenda::where('code', NULL)
                ->where('status', 'processo')
                ->where('status_uso', $status_uso)
                ->where('quarto_id', $movimento->quarto_id)
                ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->sum('valor_pagar');

            $total_produtos = ItemVenda::where('code', NULL)
                ->where('status', 'processo')
                ->where('status_uso', $status_uso)
                ->where('quarto_id', $movimento->quarto_id)
                ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->count();

            $total_unidades = ItemVenda::where('code', NULL)
                ->where('status', 'processo')
                ->where('status_uso', $status_uso)
                ->where('quarto_id', $movimento->quarto_id)
                ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->sum('quantidade');

            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger('Error', $e->getMessage());
            return redirect()->back()->with('danger', $e->getMessage());
        }

        return response()->json([
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "total_produtos" => $total_produtos,
            "total_unidades" => $total_unidades,
        ], 200);
    }


    public function carregar_vendas_mesas(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["caixas", "users", "variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $mesa = Mesa::findOrFail($request->mesa);

        $movimentos = ItemVenda::where('code', NULL)
            ->where('status', 'processo')
            ->where('status_uso', 'MESA')
            ->where('mesa_id', $mesa->id)
            ->where('user_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->with(['produto'])
            ->get();

        $total_pagar = ItemVenda::where('code', NULL)
            ->where('status', 'processo')
            ->where('status_uso', 'MESA')
            ->where('mesa_id', $mesa->id)
            ->where('user_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->sum('valor_pagar');

        $total_produtos = ItemVenda::where('code', NULL)
            ->where('status', 'processo')
            ->where('status_uso', 'MESA')
            ->where('mesa_id', $mesa->id)
            ->where('user_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->count();

        $total_unidades = ItemVenda::where('code', NULL)
            ->where('status', 'processo')
            ->where('status_uso', 'MESA')
            ->where('mesa_id', $mesa->id)
            ->where('user_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->sum('quantidade');


        return response()->json([
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "total_produtos" => $total_produtos,
            "total_unidades" => $total_unidades,
        ], 200);
    }

    public function carregar_vendas_quartos(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["caixas", "users", "variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $quarto = Quarto::findOrFail($request->quarto);

        $movimentos = ItemVenda::where('code', NULL)
            ->where('status', 'processo')
            ->where('status_uso', 'QUARTO')
            ->where('quarto_id', $quarto->id)
            ->where('user_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->with(['produto'])
            ->get();

        $total_pagar = ItemVenda::where('code', NULL)
            ->where('status', 'processo')
            ->where('status_uso', 'QUARTO')
            ->where('quarto_id', $quarto->id)
            ->where('user_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->sum('valor_pagar');

        $total_produtos = ItemVenda::where('code', NULL)
            ->where('status', 'processo')
            ->where('status_uso', 'QUARTO')
            ->where('quarto_id', $quarto->id)
            ->where('user_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->count();

        $total_unidades = ItemVenda::where('code', NULL)
            ->where('status', 'processo')
            ->where('status_uso', 'QUARTO')
            ->where('quarto_id', $quarto->id)
            ->where('user_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->sum('quantidade');


        return response()->json([
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "total_produtos" => $total_produtos,
            "total_unidades" => $total_unidades,
        ], 200);
    }
}
