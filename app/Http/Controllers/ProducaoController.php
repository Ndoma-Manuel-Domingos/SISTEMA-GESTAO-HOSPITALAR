<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\IngredienteMovimento;
use App\Models\LojaProduto;
use App\Models\Lote;
use App\Models\Producao;
use App\Models\Produto;
use App\Models\ProdutoReceita;
use App\Models\ProdutoReceitaItem;
use App\Models\Registro;
use App\Models\RegistroMovimento;
use App\Models\RegistroMovimentoItem;
use App\Models\User;
use App\Models\UserLoja;
use App\Services\IngredienteService;
use App\Services\ProducaoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ProducaoController extends Controller
{

    public IngredienteService $ingredienteService;

    public function __construct(IngredienteService $ingredienteService)
    {
        $this->ingredienteService = $ingredienteService;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user->can('controle cuzinha')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $productions = Producao::with(['receita.produto'])
            ->where("user_id", Auth::user()->id)
            ->where("entidade_id",  $entidade->empresa->id)
            ->get();

        $head = [
            "titulo" => "Produção",
            "descricao" => env('APP_NAME'),
            "productions" => $productions,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.producao.index', $head);
    }

    public function create()
    {
        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $produtos = Produto::whereIn("id", $meus_produtos)
            ->whereNotIn('tipo_stock', ['P'])
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('nome', 'asc')
        ->get();

        $head = [
            "titulo" => "Nova Produção",
            "descricao" => env('APP_NAME'),
            "produtos" => $produtos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.producao.create', $head);
    }

    public function store(Request $request, ProducaoService $service)
    {
        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'receita_id' => 'required|exists:produtos_receitas,id',
            'quantidade_desejada' => 'required|numeric|min:1',
            'fator_escala' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();

            $production = $service->create($request->all());
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json([
            'success' => true,
            'message' => 'Produção salva com sucesso!',
            'data' => $production
        ]);
    }

    private function canMove($current, $next)
    {
        $flow = [
            'PENDENTE' => 'EM_PRODUCAO',
            'EM_PRODUCAO' => 'FINALIZADO',
            'FINALIZADO' => 'CONFIRMADO',
        ];

        return isset($flow[$current]) && $flow[$current] === $next;
    }

    public function mudaStatus(Request $request)
    {
        $production = Producao::findOrFail(
            $request->production_id
        );

        try {
            DB::beginTransaction();

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            if (!$this->canMove($production->status, $request->status)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Movimento inválido'
                ], 404);
            }

            if ($request->status == "CONFIRMADO") {

                $produto = Produto::findOrFail($production->produto_id);

                $lote = Lote::where('produto_id', $produto->id)
                    ->where('status', 'activo')
                    ->where('entidade_id', $entidade->empresa->id)
                    ->first();

                $loja = LojaProduto::where('produto_id', $produto->id)
                    ->where('entidade_id', $entidade->empresa->id)
                    ->first();

                if ($lote && $loja) {

                    ## DEFINIR PRECO CUSTO EM MEDIA
                    ## ANTIGO
                    $TOTAL_CUSTO_ANTIGO = $produto->preco_custo * $produto->total_produto_loja_activa;
                    $TOTAL_CUSTO_NOVO = $produto->preco_custo * $production->quantidade_estimada;

                    $TOTAL_CUSTO = $TOTAL_CUSTO_ANTIGO + $TOTAL_CUSTO_NOVO;

                    $TOTAL_QUANTIDADE_FINAL = $produto->total_produto_loja_activa + $production->quantidade_estimada;

                    $CUSTO_MEDICO = $TOTAL_CUSTO / $TOTAL_QUANTIDADE_FINAL;

                    $produto->disponibilidade = $produto->preco_custo;
                    $produto->preco = $CUSTO_MEDICO;
                    $produto->preco_custo = $produto->preco_custo;
                    $produto->preco_venda = $produto->preco_venda;
                    $produto->update();

                    $total_registro = RegistroMovimento::where("entidade_id", $entidade->empresa->id)
                        ->where('tipo_documento', "CN")
                        ->count() + 1;

                    $sigla = "CN" . "" . date('Y') . "/" . $total_registro;

                    $code_ = time();

                    $registro = RegistroMovimento::create([
                        "operacao" => "Entrada de Stock",
                        "tipo" => "CN",
                        "numero" => $total_registro,
                        "codigo" => $code_,
                        "sigla" => $sigla,
                        "data_at" => date("Y-m-d"),
                        "observacao" => "Entrada automatica de produtos no estoque",
                        "loja_id" => $loja->loja_id,
                        "cliente_id" => $request->cliente_id,
                        "fornecedor_id" => $request->fornecedor_id,
                        "tipo_documento" => "CN",
                        "user_id" => Auth::user()->id,
                        "entidade_id" => $entidade->empresa->id,
                    ]);

                    Registro::create([
                        "registro" => "Entrada de Stock",
                        "data_registro" => date('Y-m-d'),
                        "tipo" => "E",
                        "documento" => $sigla,
                        "documento_id" => $registro->id,
                        'status' => 'E',
                        "preco_unitario" => $produto->preco_venda,
                        "quantidade" => $production->quantidade_estimada,
                        "produto_id" => $produto->id,
                        "observacao" => "Entrada automatica de produtos no estoque",
                        "loja_id" => $loja->loja_id,
                        "lote_id" => $lote->id,
                        "user_id" => Auth::user()->id,
                        "entidade_id" => $entidade->empresa->id,
                    ]);

                    $produtos_lotes = Lote::findOrFail($lote->id);
                    $produtos_lotes->stock_total = $produtos_lotes->stock_total + $production->quantidade_estimada;
                    $produtos_lotes->entrada = $produtos_lotes->entrada + $production->quantidade_estimada;
                    $produtos_lotes->update();

                    $verificarEstoque_ = Estoque::where("entidade_id", $entidade->empresa->id)
                        ->where("produto_id", $produto->id)
                        ->where("loja_id", $loja->loja_id)
                    ->first();

                    if ($verificarEstoque_) {
                        $update = Estoque::findOrFail($verificarEstoque_->id);
                        $update->stock = $update->stock + $production->quantidade_estimada;
                        $update->update();
                    } else {
                        Estoque::create([
                            "loja_id" => $loja->loja_id,
                            "lote_id" => $lote->id,
                            "produto_id" => $produto->id,
                            "user_id" => Auth::user()->id,
                            "data_operacao" => date('Y-m-d'),
                            "stock" => $production->quantidade_estimada,
                            "operacao" => "Entrada de Stock",
                            "observacao" => "Entrada automatica de produtos no estoque",
                            "entidade_id" => $entidade->empresa->id,
                        ]);
                    }

                    RegistroMovimentoItem::create([
                        'registro_id' => $registro->id,
                        'codigo' => $code_,
                        'produto_id' => $produto->id,
                        'quantidade' => $production->quantidade_estimada,
                        'preco_custo' => $produto->preco,
                        'preco_venda' => $produto->preco_venda,
                        'lote_id' => $lote->id,
                        "user_id" => Auth::user()->id,
                        "entidade_id" => $entidade->empresa->id,
                    ]);

                    $total = $produto->preco_venda * $production->quantidade_estimada;

                    $registro->total = $total;
                    $registro->update();
                }
            }

            $production->status = $request->status;

            $production->save();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        return response()->json([
            'success' => true
        ]);
    }

    public function receitas(string $id)
    {
        return ProdutoReceita::where('produto_id', $id)->get();
    }

    public function receitaItems(string $id)
    {
        return ProdutoReceitaItem::with(['ingrediente.unidade', 'unidade', 'receita.produto'])
            ->where('receita_id', $id)
            ->get();
    }
    
    
    public function producaoDia()
    {
        $dados = Producao::selectRaw(
            "DATE(created_at) data, 
                SUM(quantidade_produzida) total,
                SUM(quantidade_perdida) as perda,
                SUM(quantidade_estimada) as estimado")
            // ->where('status', 'FINALIZADA')
            ->whereBetween('created_at', [
                now()->subDays(30),
                now()
            ])
            ->groupBy('data')
            ->orderBy('data')
            ->take(30)
        ->get();
    
        return $dados->map(function ($item) {

            $eficiencia = $item->estimado > 0
                ? ($item->total / $item->estimado) * 100
                : 0;

            return [
                'data' => $item->data,
                'total' => (float) $item->total,
                'perda' => (float) $item->perda,
                'eficiencia' => round($eficiencia, 2),
            ];
        });
        
        // return response()->json($dados);
    }
}
