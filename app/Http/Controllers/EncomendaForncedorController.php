<?php

namespace App\Http\Controllers;

use App\Models\EncomendaFornecedore;
use App\Models\Entidade;
use App\Models\Estoque;
use App\Models\FacturaEncomendaFornecedor;
use App\Models\Fornecedore;
use App\Models\Imposto;
use App\Models\ItensEncomenda;
use App\Models\Loja;
use App\Models\LojaProduto;
use App\Models\Lote;
use App\Models\Motivo;
use App\Models\Movimento;
use App\Models\Produto;
use App\Models\Registro;
use App\Models\Subconta;
use App\Models\User;
use App\Models\UserLoja;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;

class EncomendaForncedorController extends Controller
{

    use TraitHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $encomendas = EncomendaFornecedore::when($request->status, function ($query, $value) {
            $query->where('status', '=', $value);
        })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('created_at', '>=', Carbon::parse($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('created_at', '<=', Carbon::parse($value));
            })
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->with('fornecedor')
            ->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "Encomendas Listagem",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "encomendas" => $encomendas,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
            "requests" => $request->all("status", "data_inicio", "data_final"),
        ];

        return view('dashboard.fornecedores.encomendas.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $items = ItensEncomenda::where([
            ['user_id', '=', Auth::user()->id],
            ['status', '=', 'em processo'],
            ['code', '=', NULL],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->with('produto.taxa_imposto')
            ->get();

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $loja = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        $produtos = Produto::whereIn("id", $meus_produtos)
            ->where('status', '=', 'activo')
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->where('tipo', 'P')
            ->get();

        $fornecedores = Fornecedore::where("entidade_id", $entidade->empresa->id)
            ->get();

        $totalEncomendas = EncomendaFornecedore::where([
            ['user_id', '=', Auth::user()->id],
            ['status', '!=', 'em processo'],
            ['code', '!=', NULL],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->count();

        $resultado = $totalEncomendas + 1;

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $loja = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        $head = [
            "titulo" => "Adicionar Encomenda",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produtos" => $produtos,
            "fornecedores" => $fornecedores,
            "items" => $items,
            "motivos" => Motivo::get(),
            "impostos" => Imposto::get(),
            "lojas" => $loja,
            "totalEncomendas" =>  $resultado . "-" . date('y') . "" . date('m') . "" . date('d') . "/ENC",
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.encomendas.create', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'data_previsao' => 'required',
                'fornecedor_selecionado' => 'required',
            ]
        );

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $code = uniqid(time());
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $fornecedor = Fornecedore::findOrFail($request->input("fornecedor_selecionado"));

            $subconta_fornecedor = Subconta::findOrFail($fornecedor->subconta_id);
            $subconta_compra_mercadoria = Subconta::where('entidade_id', $entidade->empresa->id)->where('numero', ENV('COMPRA_MERCADORIA'))->first();
            $subconta_mercadoria = Subconta::where('entidade_id', $entidade->empresa->id)->where('numero', ENV('MERCADORIA'))->first();
            $subconta_desconto_comercial_compra = Subconta::where('entidade_id', $entidade->empresa->id)->where('numero', ENV('DESCONTO_COMERCIAL_COMPRA'))->first();
            $subconta_desconto_financeiro_compra = Subconta::where('entidade_id', $entidade->empresa->id)->where('numero', ENV('DESCONTO_FINANCEIRO_COMPRA'))->first();

            $subconta_iva = Subconta::where('numero', ENV('IVA_DEDUTIVO'))->first();

            $desconto_global = 0;

            if ($request->has('toggle_desconto_comercial')) {
                $desconto_global = $request->input('desconto_comercial', 0);
                if ($desconto_global < 0 || $desconto_global > 100) {
                    return response()->json(['success' => false, 'message' => "O desconto deve estar entre 0% e 100%."], 404);
                }
            }

            if ($request->has('toggle_desconto_financeiro')) {
                $desconto_global = $request->input('desconto_financeiro', 0);
                if ($desconto_global < 0 || $desconto_global > 100) {
                    return response()->json(['success' => false, 'message' => "O desconto deve estar entre 0% e 100%."], 404);
                }
            }

            $carrinho = json_decode($request->carrinho_encomenda, true);

            if (!is_array($carrinho) && !empty($carrinho)) {
                return response()->json(['success' => false, 'message' => "Não existe produtos adicionados, para serem encomendados, informe os produtos que pretendes encomendar por favor."], 404);
            }

            foreach ($carrinho as $car) {

                $produto = Produto::findOrFail($car['produto_id']);

                $QUANTIDADE_ = $car['quantidade'] ?? 1;
                $IVA_ = $car['taxa'] ?? 0;
                $IVA_VALOR_PERCENTAGEM = $IVA_ / 100;
                $PRECO_CUSTO_ = $car['preco'] ?? 0;
                $MARGEM_LUCRO_ = $produto->margem ?? 0;
                $DESCONTO_ = $car['desconto'];
                $DESCONTO_ = ($DESCONTO_ !== null && $DESCONTO_ > 0) ? $DESCONTO_ : $desconto_global;
                $DESCONTO_VALOR = ($PRECO_CUSTO_ * ($DESCONTO_ / 100)) * $QUANTIDADE_;
                $DESCONTO_VALOR_ = ($PRECO_CUSTO_ * ($DESCONTO_ / 100));

                $VALOR_MARGEM_LUCRO = $PRECO_CUSTO_ * ($MARGEM_LUCRO_ / 100);
                $VALOR_IVA_CUSTO = $PRECO_CUSTO_ * ($IVA_ / 100);
                $PRECO_VANDA = (($PRECO_CUSTO_ + $VALOR_MARGEM_LUCRO) - $DESCONTO_VALOR_) + $VALOR_IVA_CUSTO;

                // TATAL DE FACTURA // VALOR DO IVA GERAL
                $valorIVa = ($PRECO_CUSTO_ * $QUANTIDADE_) * $IVA_VALOR_PERCENTAGEM;
                // TOTAL DE FACTURA // VALOR GERAL COM IVA 
                $totalComIva = ($PRECO_CUSTO_ * $QUANTIDADE_) + $valorIVa;
                // TOTAL DE FACTURA // VALOR GERAL SEM IVA 
                $totalSemIva = $PRECO_CUSTO_ * $QUANTIDADE_;

                $TOTAL = $totalComIva - $DESCONTO_VALOR;


                if ($request->has('toggle_desconto_comercial')) {
                    #CREDITAMOS NA CONTA 21.8
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta_desconto_comercial_compra->id,
                        'status' => true,
                        'movimento' => 'S',
                        'credito' => $DESCONTO_VALOR,
                        'debito' => 0,
                        'observacao' => $request->observacao,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'code' => $code,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);
                }

                if ($request->has('toggle_desconto_financeiro')) {
                    #CREDITAMOS NA CONTA 66.3
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta_desconto_financeiro_compra->id,
                        'status' => true,
                        'movimento' => 'S',
                        'credito' => $DESCONTO_VALOR,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'debito' => 0,
                        'observacao' => $request->observacao,
                        'code' => $code,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);
                }

                #DEBITAMOS NA CONTA 21 (COMPRAS O VALOR DESTE COMPA UNITARIA)
                Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $subconta_compra_mercadoria->id,
                    'status' => true,
                    'movimento' => 'E',
                    'credito' => 0,
                    'debito' => $TOTAL,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'observacao' => $request->observacao,
                    'code' => $code,
                    'data_at' => date("Y-m-d"),
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                ## CREDITAMOS FORNECEDOR - AUMENTAR NOSSA DIVIDA COM O FORNECEDORES
                Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $subconta_fornecedor->id,
                    'status' => true,
                    'movimento' => 'S',
                    'credito' => $TOTAL,
                    'debito' => 0,
                    'observacao' => $request->observacao,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'code' => $code,
                    'data_at' => date("Y-m-d"),
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                // CASO FOR TIPO INVENTARIO PERMANENTE TEMOS QUE ANULAR A cONTA 21 (COMPRAS)
                if ($entidade->empresa->tipo_inventario == "PERMANENTE") {

                    // CREDITAR
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta_compra_mercadoria->id,
                        'status' => true,
                        'movimento' => 'S',
                        'credito' => $TOTAL,
                        'debito' => 0,
                        'observacao' => $request->observacao,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'code' => $code,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);

                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $produto->subconta_id,
                        'status' => true,
                        'movimento' => 'E',
                        'credito' => 0,
                        'debito' => $TOTAL,
                        'observacao' => $request->observacao,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'code' => $code,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);
                }

                ItensEncomenda::create([
                    "quantidade" => $QUANTIDADE_,
                    "quantidade_recebida" => 0,
                    "iva" => $IVA_,
                    "custo" => $PRECO_CUSTO_,
                    "preco_venda" => $PRECO_VANDA,
                    "imposto_valor" => $VALOR_IVA_CUSTO,
                    "produto_id" => $produto->id,
                    "margem" => $produto->margem,
                    "code" => NULL,
                    "status" => "em processo",
                    "data_emissao" => date("Y-m-d"),
                    "desconto_valor" => $DESCONTO_VALOR,
                    "totalCiva" => $totalComIva,
                    "totalSiva" => $totalSemIva,
                    "valorIva" => $valorIVa,
                    "fornecedor_id" => $request->input("fornecedor_selecionado"),
                    "desconto" => $DESCONTO_,
                    "total" => $TOTAL,
                    "loja_id" => $request->loja_id,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);
            }

            $queryBase = ItensEncomenda::where('fornecedor_id', $request->input("fornecedor_selecionado"))
                ->where('user_id', Auth::id())
                ->whereIn('status', ['em processo'])
                ->whereNull('code')
                ->where('entidade_id', $entidade->empresa->id)
                ->with(['produto']);

            $totalValorSiva   = (clone $queryBase)->sum('totalSiva');
            $totalValorCiva   = (clone $queryBase)->sum('totalCiva');
            $totalQuantidade  = (clone $queryBase)->sum('quantidade');
            $totalDesconto    = (clone $queryBase)->sum('desconto_valor');
            $totalGeral  = (clone $queryBase)->sum('total');
            $totalProduto     = (clone $queryBase)->count(); // conta os registros
            $imposto          = (clone $queryBase)->sum('imposto_valor');

            ## OUTRA PARTE DOS CUSTOS - START

            //valor total dos custos da mercadoria sem desconto
            $valor_total_custo_sem_desconto = ($request->custo_transporte + $request->custo_manuseamento + $request->outros_custos);
            $desconto_custo_transporte = $request->custo_transporte - ($request->custo_transporte * ($desconto_global / 100));
            $desconto_custo_manuseamento = $request->custo_manuseamento - ($request->custo_manuseamento * ($desconto_global / 100));
            $desconto_outros_custos = $request->outros_custos - ($request->outros_custos * ($desconto_global / 100));

            //valor total dos custos da mercadoria com desconto
            $valor_total_custo_com_desconto = $desconto_custo_transporte + $desconto_custo_manuseamento + $desconto_outros_custos;

            $valor_do_desconto_em_custos = $valor_total_custo_sem_desconto * $desconto_global / 100;

            $totalDesconto += $valor_do_desconto_em_custos;
            $totalGeral += $valor_total_custo_com_desconto;

            $code = uniqid(time());

            $totalEncomendas = EncomendaFornecedore::where('user_id', Auth::user()->id)
                ->where('status', '!=', 'em processo')
                ->where('code', '!=', NULL)
                ->where('entidade_id', $entidade->empresa->id)
                ->count();

            $resultado = $totalEncomendas + 1;
            if ($valor_total_custo_com_desconto > 0) {

                // ADICIONAR OUTROS CUSTOS 
                // DEBITAMOS
                Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $subconta_compra_mercadoria->id,
                    'status' => true,
                    'movimento' => 'E',
                    'credito' => 0,
                    'debito' => $valor_total_custo_com_desconto,
                    'observacao' => $request->observacao,
                    'code' => $code,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'data_at' => date("Y-m-d"),
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                ## CREDITAMOS FORNECEDOR - AUMENTAR NOSSA DIVIDA COM O FORNECEDORES
                Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $subconta_fornecedor->id,
                    'status' => true,
                    'movimento' => 'S',
                    'credito' => $valor_total_custo_com_desconto,
                    'debito' => 0,
                    'observacao' => $request->observacao,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'code' => $code,
                    'data_at' => date("Y-m-d"),
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);
            }

            /** START - CONTABILIDADE METODO INVENTARIO PERMANENTE */
            if ($entidade->empresa->tipo_inventario == "PERMANENTE") {

                if ($valor_total_custo_com_desconto > 0) {
                    // REMOVER OUTROS CUSTOS 
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta_mercadoria->id,
                        'status' => true,
                        'movimento' => 'E',
                        'credito' => 0,
                        'debito' => $valor_total_custo_com_desconto,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'observacao' => $request->observacao,
                        'code' => $code,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);

                    // CREDITAR
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta_compra_mercadoria->id,
                        'status' => true,
                        'movimento' => 'S',
                        'credito' => $valor_total_custo_com_desconto,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'debito' => 0,
                        'observacao' => $request->observacao,
                        'code' => $code,
                        'data_at' => date("Y-m-d"),
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);
                }
            }
            /**END -CONTABILIDADE METODO INVENTARIO PERMANENTE */
            if ($request->has('toggle_desconto_comercial')) {
                $tipo_desconto = "C";
            }
            if ($request->has('toggle_desconto_financeiro')) {
                $tipo_desconto = "F";
            } else {
                $tipo_desconto = "P";
            }

            $create = EncomendaFornecedore::create([
                'status' => 'pendente',
                'numero' => $resultado,
                'factura' => $request->numero,
                'fornecedor_id' => $request->fornecedor_selecionado,
                'loja_id' => $request->loja_id,
                'data_emissao' => date('Y-m-d'),
                'previsao_entrega' => $request->data_previsao,
                'observacao' => $request->observacao,
                'custo_transporte' => $request->custo_transporte,
                'custo_manuseamento' => $request->custo_manuseamento,
                'outros_custos' => $request->outros_custos,
                'code' => $code,
                'tipo_desconto' => $tipo_desconto,
                'descontado' => 0,
                'imposto' => $imposto,
                'quantidade' => $totalQuantidade,
                'total_produto' => $totalProduto,
                'total_sIva' => $totalValorSiva,
                'total_cIVa' =>  $totalValorCiva,
                'tota_pago' => 0,
                'total_a_pagar' => $totalGeral,
                'total' => $totalGeral + $totalDesconto,
                'desconto' => $desconto_global,
                'desconto_valor' => $totalDesconto,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);

            $items = ItensEncomenda::where('fornecedor_id', $request->input("fornecedor_selecionado"))
                ->where('user_id', Auth::user()->id)
                ->whereIn('status', ['em processo'])
                ->where('code', NULL)
                ->where('entidade_id', $entidade->empresa->id)
                ->with(['produto'])
                ->get();

            foreach ($items as $value) {
                $update = ItensEncomenda::findOrFail($value->id);
                $update->code = $code;
                $update->status = 'pendente';
                $update->update();
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

        Alert::success('Sucesso', 'Encomenda realizada com sucesso!');
        return redirect()->route('fornecedores-encomendas.show', $create->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $encomenda = EncomendaFornecedore::with('fornecedor', 'user')->findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $facturas = FacturaEncomendaFornecedor::where('encomenda_id', $encomenda->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $facturasPagas = FacturaEncomendaFornecedor::where('encomenda_id', $encomenda->id)
            ->where('status', true)
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->whereIn('status2', ['concluido'])
            ->get();

        /********************************************** */
        $totalFactura = FacturaEncomendaFornecedor::where('encomenda_id', $encomenda->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->count();

        $totalValorFactura = FacturaEncomendaFornecedor::where('encomenda_id', $encomenda->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->sum('valor_pago');

        $totalPagoFactura = FacturaEncomendaFornecedor::where('encomenda_id', $encomenda->id)
            ->where('status', false)
            ->where('entidade_id', $entidade->empresa->id)
            ->whereIn('status3', ['original'])
            ->whereIn('status2', ['nao concluido'])
            ->sum('total_pago');

        $totalValorFacturaSaldo = FacturaEncomendaFornecedor::where('encomenda_id', $encomenda->id)
            ->where('status', false)
            ->where('entidade_id', $entidade->empresa->id)
            ->whereIn('status3', ['original'])
            ->whereIn('status2', ['nao concluido'])
            ->sum('valor_divida');

        $totalValorPago = FacturaEncomendaFornecedor::where('encomenda_id', $encomenda->id)
            ->where('status', true)
            ->where('entidade_id', $entidade->empresa->id)
            ->whereIn('status2', ['concluido'])
            ->sum('valor_pago');

        /********************************************** */


        /********************************************** */
        $totalFacturaPaga = FacturaEncomendaFornecedor::where('encomenda_id', $encomenda->id)
            ->where('status', true)
            ->where('entidade_id', $entidade->empresa->id)
            ->whereIn('status2', ['concluido'])
            ->count();

        $totalValorFacturaNaoPaga = FacturaEncomendaFornecedor::where('encomenda_id', $encomenda->id)
            ->where('status', false)
            ->where('entidade_id', $entidade->empresa->id)
            ->whereIn('status3', ['original'])
            ->whereIn('status2', ['nao concluido'])
            ->sum('valor_factura');

        $totalValorFacturaSaldoNaoPaga = FacturaEncomendaFornecedor::where('encomenda_id', $encomenda->id)
            ->where('status', false)
            ->where('entidade_id', $entidade->empresa->id)
            ->whereIn('status3', ['original'])
            ->whereIn('status2', ['nao concluido'])
            ->sum('valor_divida');

        $totalValorPagoNaoPaga = FacturaEncomendaFornecedor::where('encomenda_id', $encomenda->id)
            ->where('status', false)
            ->where('entidade_id', $entidade->empresa->id)
            ->whereIn('status3', ['original'])
            ->whereIn('status2', ['nao concluido'])
            ->sum('valor_pago');

        /********************************************** */

        $items = ItensEncomenda::where('code', $encomenda->code)
            ->where('entidade_id', $entidade->empresa->id)
            ->with(['produto'])
            ->get();

        $head = [
            "titulo" => "Visualizar Encomenda {$encomenda->factura}",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "encomenda" => $encomenda,
            "facturas" => $facturas,
            "facturasPagas" => $facturasPagas,
            "items" => $items,
            "loja" => $entidade,

            "totalPagoFactura" => $totalPagoFactura,

            "totalValorFactura" => $totalValorFactura,
            "totalValorPago" => $totalValorPago,
            "totalFactura" => $totalFactura,
            "totalValorFacturaSaldo" => $totalValorFacturaSaldo,

            "totalValorFacturaNaoPaga" => $totalValorFacturaNaoPaga,
            "totalValorPagoNaoPaga" => $totalValorPagoNaoPaga,
            "totalFacturaPaga" => $totalFacturaPaga,
            "totalValorFacturaSaldoNaoPaga" => $totalValorFacturaSaldoNaoPaga,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.encomendas.show', $head);
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
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $encomenda = EncomendaFornecedore::with('fornecedor', 'loja', 'user')->findOrFail($id);

        $items = ItensEncomenda::where([
            ['fornecedor_id', '=', $encomenda->fornecedor_id],
            ['code', '=', $encomenda->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->with('produto')
            ->with('loja')
            ->get();

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $loja = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        $produtos = Produto::whereIn("id", $meus_produtos)
            ->where('status', 'activo')
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $fornecedores = Fornecedore::where([
            ['entidade_id', $entidade->empresa->id],
        ])->get();


        $head = [
            "titulo" => "Adicionar Encomenda",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produtos" => $produtos,
            "encomenda" => $encomenda,
            "fornecedores" => $fornecedores,
            "items" => $items,
            "lojas" => $loja,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.encomendas.edit', $head);
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

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $encomenda = EncomendaFornecedore::findOrFail($id);

            if ($encomenda->status == "pendente" && $encomenda->status_pagamento == true) {
                return response()->json(['message' => 'Essa encomenda não de ser editar por já foi entregue ou já foi pago!']);
            }
            dd($encomenda);

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
            $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

            foreach ($request->ids as $id) {
                $update = ItensEncomenda::findOrFail($id);
                $update->quantidade = $request->input("quantidade{$id}");
                $update->iva = $request->input("iva{$id}");
                $update->custo = $request->input("custo{$id}");
                $update->preco_venda = $request->input("custo{$id}") + ($request->input("custo{$id}") * ($request->input("iva{$id}") / 100));

                $update->imposto_valor = (($request->input("custo{$id}") * $request->input("quantidade{$id}")) * ($request->input("iva{$id}") / 100));

                if ($request->input("desonto{$id}") >= 1 && $request->input("desonto{$id}") <= 100) {
                    $totalComIva = ($request->input("custo{$id}") * $request->input("quantidade{$id}")) + (($request->input("custo{$id}") * $request->input("quantidade{$id}")) * ($request->input("desonto{$id}") / 100));
                    $totalSemIva = ($request->input("custo{$id}") * $request->input("quantidade{$id}")) - (($request->input("custo{$id}") * $request->input("quantidade{$id}")) * ($request->input("desonto{$id}") / 100));
                    $valorIVa = ($request->input("custo{$id}") * $request->input("quantidade{$id}")) * ($request->input("desonto{$id}") / 100);

                    $update->desconto_valor = ($request->input("custo{$id}")) - (($request->input("custo{$id}")) * ($request->input("desonto{$id}") / 100));
                } else {
                    $totalComIva = $request->input("custo{$id}") * $request->input("quantidade{$id}");
                    $totalSemIva = $request->input("custo{$id}") * $request->input("quantidade{$id}");
                    $valorIVa =
                        ($request->input("custo{$id}") * $request->input("quantidade{$id}")) -
                        (($request->input("custo{$id}") * $request->input("quantidade{$id}")) *
                            ($request->input("iva{$id}") / 100));

                    $update->desconto_valor = 0;
                }

                $update->totalCiva = $totalComIva;
                $update->totalSiva = $totalSemIva;
                $update->valorIva = $valorIVa;
                $update->fornecedor_id = $request->input("fornecedor_selecionado");

                $update->desconto = $request->input("desonto{$id}");

                $update->total = $request->input("custo{$id}") * $request->input("quantidade{$id}");

                $update->loja_id = $request->loja_id;

                $update->update();
            }

            $totalValorSiva = ItensEncomenda::where([
                ['fornecedor_id', '=', $encomenda->fornecedor_id],
                ['code', '=', $encomenda->code],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
                ->with('produto')
                ->sum('totalSiva');

            $totalValorCiva = ItensEncomenda::where([
                ['fornecedor_id', '=', $encomenda->fornecedor_id],
                ['code', '=', $encomenda->code],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
                ->with('produto')
                ->sum('totalCiva');

            $totalDesconto = ItensEncomenda::where([
                ['fornecedor_id', '=', $encomenda->fornecedor_id],
                ['code', '=', $encomenda->code],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
                ->with('produto')
                ->sum('desconto_valor');

            $totalQuantidade = ItensEncomenda::where([
                ['fornecedor_id', '=', $encomenda->fornecedor_id],
                ['code', '=', $encomenda->code],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
                ->with('produto')
                ->sum('quantidade');


            $totalProduto = ItensEncomenda::where([
                ['fornecedor_id', '=', $encomenda->fornecedor_id],
                ['code', '=', $encomenda->code],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
                ->with('produto')
                ->count();

            $imposto = ItensEncomenda::where([
                ['fornecedor_id', '=', $encomenda->fornecedor_id],
                ['code', '=', $encomenda->code],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
                ->with('produto')
                ->sum('imposto_valor');

            $updated = EncomendaFornecedore::findOrFail($encomenda->id);
            $updated->status = 'pendente';
            $updated->fornecedor_id = $request->fornecedor_selecionado;
            $updated->loja_id = $request->loja_id;
            $updated->data_emissao = date('Y-m-d');
            $updated->previsao_entrega = $request->data_previsao;
            $updated->observacao = $request->observacao;
            $updated->imposto = $imposto;
            $updated->quantidade = $totalQuantidade;
            $updated->total_produto = $totalProduto;
            $updated->total_sIva = $totalValorSiva;
            $updated->total_cIVa =  $totalValorCiva;
            $updated->total = ($totalValorSiva + $imposto);
            $updated->desconto =  $totalDesconto;

            if ($updated->update()) {
                $items = ItensEncomenda::where([
                    ['fornecedor_id', '=', $encomenda->fornecedor_id],
                    ['code', '=', $encomenda->code],
                    ['entidade_id', '=', $entidade->empresa->id],
                ])
                    ->with('produto')
                    ->get();

                foreach ($items as $value) {
                    $update = ItensEncomenda::findOrFail($value->id);
                    $update->status = 'pendente';
                    $update->update();
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

        Alert::success('Sucesso', 'Encomenda Actualizada com sucesso!');
        return redirect()->route('fornecedores-encomendas.show', $updated->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $encomenda = EncomendaFornecedore::findOrFail($id);
            $items = ItensEncomenda::where('code', '=', $encomenda->code)
                ->get();

            if ($items) {
                foreach ($items as $value) {
                    ItensEncomenda::findOrFail($value->id)->delete();
                }
            }

            $encomenda->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => "encomenda excluídas com sucesso", 'success' => true, 'redirect' => route('fornecedores-encomendas.index')]);
    }

    public function itemsNovaEncomandaSFornecedor($id)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $produto = Produto::findOrFail($id);

        $verificar = ItensEncomenda::where('produto_id', '=', $produto->id)
            ->where('user_id', '=', Auth::user()->id)
            ->where('data_emissao', '=', date('Y-m-d'))
            ->where('status', '=',  'em processo')
            ->where('code',  NULL)
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->first();

        if ($verificar) {
            Alert::error("Erro", "Este produto Já foi Adicionar... Pode alterar a quantidade");
            return redirect()->route('fornecedores-encomendas.create');
        }

        $items = ItensEncomenda::create([
            'produto_id' => $produto->id,
            'user_id' => Auth::user()->id,
            'quantidade' => 1,
            'desconto' => 0,
            'data_emissao' => date('Y-m-d'),
            'status' => 'em processo',
            'custo' => $produto->preco_custo,
            'margem' => $produto->margem,
            'iva' => $produto->imposto_id,
            'total' => $produto->preco_custo * 1,
            'code' =>  NULL,
            'entidade_id' => $entidade->empresa->id,
        ]);

        if ($items->save()) {
            return redirect()->route('fornecedores-encomendas.create');
        } else {
            Alert::error("Erro", "Ocorreu um erro ao tentar adicionar este produto");
            return redirect()->route('fornecedores-encomendas.create');
        }
    }

    public function itemsNovaEncomandaSFornecedorEdit($id, $encomenda)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $produto = Produto::findOrFail($id);
        $enco = EncomendaFornecedore::findOrFail($encomenda);

        $verificar = ItensEncomenda::where([
            ['produto_id', '=', $produto->id],
            ['code', '=', $enco->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])->first();

        if ($verificar) {
            Alert::error("Erro", "Este produto Já foi Adicionar... Pode alterar a quantidade");
            return redirect()->route('fornecedores-encomendas.edit', $enco->id);
        }

        $iva = "";

        if ($produto->imposto == "ISE") {
            $iva = 0;
        } else if ($produto->imposto == "RED") {
            $iva = 2;
        } else if ($produto->imposto == "INT") {
            $iva = 5;
        } else if ($produto->imposto == "OUT") {
            $iva = 7;
        } else if ($produto->imposto == "NOR") {
            $iva = 14;
        } else {
            $iva = 0;
        }

        $items = ItensEncomenda::create([
            'produto_id' => $produto->id,
            'loja_id' => $enco->loja_id,
            'fornecedor_id' => $enco->fornecedor_id,
            'user_id' => Auth::user()->id,
            'quantidade' => 1,
            'desconto' => 0,
            'data_emissao' => date('Y-m-d'),
            'status' => 'em processo',
            'custo' => $produto->preco_custo,
            'iva' => $iva,
            'total' => $produto->preco_custo * 1,
            'code' =>  $enco->code,
            'entidade_id' => $entidade->empresa->id,
        ]);

        if ($items->save()) {
            return redirect()->route('fornecedores-encomendas.edit', $enco->id);
        } else {
            Alert::error("Erro", "Ocorreu um erro ao tentar adicionar este produto");
            return redirect()->route('fornecedores-encomendas.edit', $enco->id);
        }
    }

    public function itemsNovaEncomandaRemoverSFornecedor($id)
    {
        $encomenda = ItensEncomenda::findOrFail($id);

        if ($encomenda->delete()) {
            return redirect()->back();
        }
    }

    public function marcarComoEntregue($id)
    {
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $encomenda = EncomendaFornecedore::findOrFail($id);
            $encomenda->status = "entregue";
            $encomenda->update();

            $items = ItensEncomenda::where([
                ['code', '=', $encomenda->code]
            ])->get();

            if ($items) {
                foreach ($items as $item) {
                    $updated = ItensEncomenda::findOrFail($item->id);
                    $updated->status = 'entregue';
                    $updated->loja_id = $encomenda->loja_id;
                    $updated->update();
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

        return response()->json(['message' => "Produtos entregues com sucesso", 'success' => true, 'redirect' => route('fornecedores-encomendas.show', $encomenda->id)]);
    }

    public function marcarComoCancelada($id)
    {
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $encomenda = EncomendaFornecedore::findOrFail($id);
            $encomenda->status = "cancelada";
            $encomenda->update();

            $items = ItensEncomenda::where('code', '=', $encomenda->code)->get();

            if ($items) {
                foreach ($items as $item) {
                    $updated = ItensEncomenda::findOrFail($item->id);
                    $updated->status = 'cancelada';
                    $updated->loja_id = $encomenda->loja_id;
                    $updated->update();
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

        return response()->json(['message' => "Encomendas canceladas com sucesso", 'success' => true, 'redirect' => route('fornecedores-encomendas.show', $encomenda->id)]);
    }

    public function receberProduto($id)
    {
        $encomenda = EncomendaFornecedore::findOrFail($id);

        $items = ItensEncomenda::where([
            ['code', '=', $encomenda->code]
        ])->get();

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $encomenda = EncomendaFornecedore::with('fornecedor', 'loja', 'user')->findOrFail($id);

        $items = ItensEncomenda::where([
            ['fornecedor_id', '=', $encomenda->fornecedor_id],
            ['code', '=', $encomenda->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->with('produto.estoque')
            ->with('loja')
            ->get();

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");
        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $loja = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        $produtos = Produto::whereIn("id", $meus_produtos)
            ->where('status', '=', 'activo')
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $fornecedores = Fornecedore::where("entidade_id", $entidade->empresa->id)->get();


        $head = [
            "titulo" => "Receber Ecomenda ou Produto",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produtos" => $produtos,
            "encomenda" => $encomenda,
            "fornecedores" => $fornecedores,
            "items" => $items,
            "lojas" => $loja,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.encomendas.receber', $head);
    }

    public function receberProdutoStore(Request $request)
    {
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
            $encomenda = EncomendaFornecedore::findOrFail($request->encomenda_id);

            $total_recebida = 0;

            foreach ($request->ids as $id) {
                if ($request->input("condicao{$id}") == 'sim') {
                    $update = ItensEncomenda::findOrFail($id);

                    if (($update->quantidade + $update->quantidade_recebida) >= $request->input("quantidade{$id}") && $update->quantidade != 0) {

                        $total_recebida += $request->input("quantidade{$id}");

                        $update->quantidade_recebida = $update->quantidade_recebida + $request->input("quantidade{$id}");
                        $update->quantidade = $update->quantidade - $request->input("quantidade{$id}");
                        $update->update();

                        $produto = Produto::findOrFail($update->produto_id);
                        $produto->preco_venda = $request->input("preco_venda{$id}");
                        $produto->update();

                        $loja = Loja::findOrFail($encomenda->loja_id);

                        $actualizarEstoque = Estoque::where('produto_id', '=', $produto->id)
                            ->where('loja_id', '=', $loja->id)
                            ->first();

                        $actualizar = Estoque::findOrFail($actualizarEstoque->id);
                        $actualizar->stock = $actualizar->stock + $request->input("quantidade{$id}");
                        $actualizar->update();

                        $lote = Lote::where('produto_id', $produto->id)->where('status', 'activo')->where("entidade_id", $entidade->empresa->id)->first();

                        Registro::create([
                            'tipo' => 'E',
                            'status' => 'A',
                            'documento' => $encomenda->factura,
                            "registro" => "Receção de Encomenda",
                            "data_registro" => date('Y-m-d'),
                            "quantidade" => $request->input("quantidade{$id}"),
                            "observacao" => $encomenda->factura,
                            "encomenda_id" => $encomenda->id,
                            "produto_id" => $produto->id,
                            "preco_unitario" => $produto->preco_venda_com_iva,
                            "loja_id" => $encomenda->loja_id,
                            "lote_id" => $lote->id ?? NULL,
                            "user_id" => Auth::user()->id,
                            "entidade_id" => $entidade->empresa->id,
                        ]);
                    }
                }
            }

            $total = ItensEncomenda::where('code', '=', $encomenda->code)->sum('quantidade');

            if ($total == 0) {
                $encomenda->status = "entregue";
                $encomenda->update();

                $updateItensEncomenda = ItensEncomenda::where('code', '=', $encomenda->code)->get();

                foreach ($updateItensEncomenda as $item) {
                    $up = ItensEncomenda::findOrFail($item->id);
                    $up->status = "entregue";
                    $up->update();
                }
            }

            $encomenda->quantidade_recebida = $encomenda->quantidade_recebida + $total_recebida;
            $encomenda->quantidade = $encomenda->quantidade - $total_recebida;
            $encomenda->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => "Produtos recebidos com sucesso", 'success' => true, 'redirect' => route('fornecedores-encomendas.show', $encomenda->id)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function imprimir($id)
    {

        $encomenda = EncomendaFornecedore::with('fornecedor', 'user')->findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $facturas = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->get();

        $facturasPagas = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status2', '=', 'concluido'],
            ['status', true],
        ])
            ->get();

        /********************************************** */
        $totalFactura = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->count();

        $totalValorFactura = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->sum('valor_pago');

        $totalPagoFactura = FacturaEncomendaFornecedor::where([
            ['status2', '=', 'nao concluido'],
            ['status', false],
            ['status3', '=', 'original'],
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->sum('total_pago');

        $totalValorFacturaSaldo = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status2', '=', 'nao concluido'],
            ['status3', '=', 'original'],
            ['status', false],
        ])
            ->sum('valor_divida');

        $totalValorPago = FacturaEncomendaFornecedor::where([
            ['status2', '=', 'concluido'],
            ['status', true],
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->sum('valor_pago');

        /********************************************** */


        /********************************************** */
        $totalFacturaPaga = FacturaEncomendaFornecedor::where([
            ['status2', '=', 'concluido'],
            ['status', true],
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->count();

        $totalValorFacturaNaoPaga = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status2', '=', 'nao concluido'],
            ['status3', '=', 'original'],
            ['status', false],
        ])
            ->sum('valor_factura');

        $totalValorFacturaSaldoNaoPaga = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status2', '=', 'nao concluido'],
            ['status3', '=', 'original'],
            ['status', false],
        ])
            ->sum('valor_divida');

        $totalValorPagoNaoPaga = FacturaEncomendaFornecedor::where([
            ['encomenda_id', '=', $encomenda->id],
            ['entidade_id', '=', $entidade->empresa->id],
            ['status2', '=', 'nao concluido'],
            ['status3', '=', 'original'],
            ['status', false],
        ])
            ->sum('valor_pago');

        /********************************************** */

        $items = ItensEncomenda::where('code', $encomenda->code)
            ->where('entidade_id', $entidade->empresa->id)
            ->with(['produto'])
            ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Encomenda: {$encomenda->factura}",
            "descricao" => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "empresa" => $empresa,
            "encomenda" => $encomenda,
            "facturas" => $facturas,
            "facturasPagas" => $facturasPagas,
            "items" => $items,
            "loja" => $entidade,

            "totalPagoFactura" => $totalPagoFactura,

            "totalValorFactura" => $totalValorFactura,
            "totalValorPago" => $totalValorPago,
            "totalFactura" => $totalFactura,
            "totalValorFacturaSaldo" => $totalValorFacturaSaldo,

            "totalValorFacturaNaoPaga" => $totalValorFacturaNaoPaga,
            "totalValorPagoNaoPaga" => $totalValorPagoNaoPaga,
            "totalFacturaPaga" => $totalFacturaPaga,
            "totalValorFacturaSaldoNaoPaga" => $totalValorFacturaSaldoNaoPaga,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];


        $pdf = PDF::loadView('dashboard.fornecedores.encomendas.imprimir', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function imprimir_todas(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $encomendas = EncomendaFornecedore::when($request->status, function ($query, $value) {
            $query->where('status', '=', $value);
        })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('created_at', '>=', Carbon::parse($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('created_at', '<=', Carbon::parse($value));
            })
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->with('fornecedor')
            ->orderBy('created_at', 'desc')->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => __('messages.listagem'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "encomendas" => $encomendas,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
            "requests" => $request->all("status", "data_inicio", "data_final"),
        ];

        $pdf = PDF::loadView('dashboard.fornecedores.encomendas.imprimir-todas', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
}
