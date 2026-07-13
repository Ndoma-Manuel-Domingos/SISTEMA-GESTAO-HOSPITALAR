<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Estoque;
use App\Models\Loja;
use App\Models\LojaProduto;
use App\Models\Lote;
use App\Models\Produto;
use App\Models\Registro;
use App\Models\RegistroMovimento;
use App\Models\RegistroMovimentoItem;
use App\Models\User;
use App\Models\UserLoja;
use App\Support\UnitConverter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class MovimentoEstoqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        // buscas loja
        
        $data_inicio = $request->data_inicio ? $request->data_inicio : Carbon::now()->startOfMonth()->format('Y-m-d');

        $movimentos = Registro::when($request->loja_id, function ($query, $value) {
            $query->where('loja_id', $value);
        })
            ->when($request->produto_id, function ($query, $value) {
                $query->where('produto_id', $value);
            })
            ->when($request->tipo, function ($query, $value) {
                $query->where('tipo', $value);
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->when($data_inicio, function ($query, $value) {
                $query->whereDate('data_registro', '>=', Carbon::parse($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('data_registro', '<=', Carbon::parse($value));
            })
        ->with('produto.unidade', 'user', 'loja')
        ->where('entidade_id', $entidade->empresa->id)
        ->orderBy('created_at', 'desc')
        ->get()
        ->sortBy(function ($prd) {
            return $prd->produto->nome ?? '';
        });

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        $head = [
            "titulo" => "Movimentos do Stock",
            "descricao" => env('APP_NAME'),
            "movimentos" => $movimentos,
            "lojas" => $lojas,
            "produtos" => Produto::whereIn("id", $meus_produtos)->where('entidade_id', '=', $entidade->empresa->id)->get(),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
            "requests" => $request->all('loja_id', 'tipo', 'status', 'produto_id', 'data_inicio', 'data_final')
        ];

        return view('dashboard.estoques-movimentos.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $estoque = Estoque::where("entidade_id", $entidade->empresa->id)
            ->with('loja', 'produto')
            ->findOrFail($id);

        $totalStock = Estoque::where('produto_id', $estoque->produto->id)
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->sum('stock');

        $lotes = Lote::where('produto_id', $estoque->produto->id)->where('status', 'activo')->get();

        $registros = Registro::where('loja_id', $estoque->loja->id)
            ->where('produto_id', $estoque->produto->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
        ->get();

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "registros" => $registros,
            "estoque" => $estoque,
            "totalStock" => $totalStock,
            "lotes" => $lotes,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.estoques-movimentos.show', $head);
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
        //
        $request->validate([
            "operacao" => "required",
            "stock" => "required",
            "lote_id" => "required",
        ]);
        
        try {
            DB::beginTransaction();
            //
            $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

            $estoque = Estoque::findOrFail($id);
            
            $estoque->lote_id =  $request->lote_id;
            
            $produto = Produto::findOrFail($estoque->produto_id);
            
            $tipo_documento = "CN";
            
            if($request->operacao == "adicionar_stock" || $request->operacao == "entrada_stock") {
                $tipo_documento = "E";
            }else if($request->operacao == "remover_stock") {
                $tipo_documento = "S";
            }
            
            $code_ = time();
            
            $total_registro = RegistroMovimento::where("entidade_id", $entidade->empresa->id)
                ->where('tipo_documento', $tipo_documento)
            ->count() + 1;

            $sigla = $tipo_documento . "" . date('Y') . "/" . $total_registro;
            
            if ($request->operacao != "alterar_minimo") {
                $registro = RegistroMovimento::create([
                    "operacao" => $request->operacao,
                    "tipo" => $tipo_documento,
                    "numero" => $total_registro,
                    "codigo" => $code_,
                    "sigla" => $sigla,
                    "data_at" => date("Y-m-d"),
                    "observacao" => $request->justificativo,
                    "loja_id" => $estoque->loja_id,
                    "cliente_id" => NULL,
                    "fornecedor_id" => NULL,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);
            }
                 
            $total = 0;   
            $qts = 0;
            
            if($produto->tipo_stock == "P") {
                $qts = UnitConverter::converterParaBase($request->stock, $produto->unidade);
            }else {
                $qts = $request->stock;
            }
            
            if ($request->operacao == "alterar_minimo") {
                $estoque->stock_minimo = $request->stock;
                $estoque->update();
            }

            if ($request->operacao == "entrada_stock") {
                
                $existe = LojaProduto::where('produto_id', $produto->id)
                    ->where('loja_id', $estoque->loja_id)
                    ->where('entidade_id', $entidade->empresa->id)
                ->exists();
                
                if (!$existe) {
                    LojaProduto::create([
                        'produto_id' => $produto->id,
                        'loja_id' => $estoque->loja_id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }

                // Nao informou o lote
                if (!$request->lote_id == NULL) {
                    $produtos_lotes = Lote::findOrFail($request->lote_id);
                    $produtos_lotes->stock_total = $produtos_lotes->stock_total + $qts;
                    $produtos_lotes->entrada = $produtos_lotes->entrada + $qts;
                    $produtos_lotes->update();
                }
                
                Registro::create([
                    "registro" => "Entrada de Stock",
                    "tipo" => $tipo_documento,
                    'status' => $tipo_documento == "E" ? 'A' : 'S',
                    "documento" => $sigla,
                    "preco_unitario" => $produto->preco_custo,
                    "documento_id" => $registro->id,
                    "data_registro" => date("Y-m-d"),
                    "quantidade" =>  $qts,
                    "produto_id" => $produto->id,
                    "observacao" => $request->justificativo,
                    "loja_id" => $estoque->loja_id,
                    "lote_id" => $request->lote_id,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);
                
                $verificarEstoque_ = Estoque::where("entidade_id", $entidade->empresa->id)
                    ->where("produto_id", $produto->id)
                    ->where("loja_id", $estoque->loja_id)
                ->first();

                if ($verificarEstoque_) {
                    $update = Estoque::findOrFail($verificarEstoque_->id);
                    $update->stock = $update->stock +  $qts;
                    $update->update();
                } else {
                    Estoque::create([
                        "loja_id" => $request->loja_id,
                        "lote_id" => $request->lote_id,
                        "produto_id" => $produto->id,
                        "user_id" => Auth::user()->id,
                        "data_operacao" => date('Y-m-d'),
                        "stock" =>  $qts,
                        "operacao" => "Entrada de Stock",
                        "observacao" => $request->justificativo,
                        "entidade_id" => $entidade->empresa->id,
                    ]);
                }
            }

            if ($request->operacao == "saida_stock") {

                // Nao informou o lote
                if (!$request->lote_id == NULL) {
                    $produtos_lotes = Lote::findOrFail($request->lote_id);
                    $produtos_lotes->stock_total = $produtos_lotes->stock_total -  $qts;
                    $produtos_lotes->entrada = $produtos_lotes->entrada -  $qts;
                    $produtos_lotes->update();
                }

                $registro = Registro::create([
                    "registro" => "Saída de Stock",
                    "documento" => $sigla,
                    "documento_id" => $registro->id,
                    "tipo" => $tipo_documento,
                    'status' => 'E',
                    "preco_unitario" => $produto->preco_custo,
                    "data_registro" => date("Y-m-d"),
                    "quantidade" =>  $qts,
                    "observacao" => $request->justificativo,
                    "produto_id" => $produto->id,
                    "loja_id" => $estoque->loja_id,
                    "lote_id" => $request->lote_id,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);
                
                
                $verificarEstoque = Estoque::where("lote_id", $request->lote_id)
                    ->where("entidade_id", $entidade->empresa->id)
                    ->where("produto_id", $produto->id)
                    ->where("loja_id", $estoque->loja_id)
                ->first();

                if ($verificarEstoque) {
                    // Nao informou o lote
                    if (!$request->lote_id == NULL) {
                        $produtos_lotes = Lote::findOrFail($request->lote_id);
                        if ($qts >= $produtos_lotes->stock_total) {
                            $produtos_lotes->stock_total = $produtos_lotes->stock_total - $qts;
                            $produtos_lotes->saida = $produtos_lotes->saida - $qts;
                            $produtos_lotes->update();
                        }
                    }
                    $saida = Estoque::findOrFail($verificarEstoque->id);
                    if ($saida->stock >= $qts) {
                        $saida->stock = $saida->stock - $qts;
                        $saida->update();
                    } else {
                        return response()->json(['message' => "Não pode retiriar mais do que a quantidade existente produto: {$produto->nome}!"], 404);
                    }
                }
                
            }

            if ($request->operacao == "actualizar_stock") {

                // Nao informou o lote
                if (!$request->lote_id == NULL) {
                    $produtos_lotes = Lote::findOrFail($request->lote_id);
                    $produtos_lotes->stock_total =  $qts;
                    $produtos_lotes->entrada =  $qts;
                    $produtos_lotes->update();
                }
                
                Registro::create([
                    "registro" => "Actualizar de Stock",
                    "data_registro" => date('Y-m-d'),
                    "tipo" => $tipo,
                    'status' => 'A',
                    "documento" => $sigla,
                    "documento_id" => $registro->id,
                    "preco_unitario" => $produto->preco_custo,
                    "quantidade" => $qts,
                    "produto_id" => $produto->id,
                    "observacao" => $request->justificativo,
                    "loja_id" => $estoque->loja_id,
                    "lote_id" => $request->lote_id,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);
                
                $verificarEstoque = Estoque::where("lote_id", $request->lote_id)
                    ->where("entidade_id", $entidade->empresa->id)
                    ->where("produto_id", $produto->id)
                    ->where("loja_id", $estoque->loja_id)
                ->first();

                // Nao informou o lote
                if (!$request->lote_id == NULL) {
                    $produtos_lotes = Lote::findOrFail($request->lote_id);
                    $produtos_lotes->stock_total = $qts;
                    $produtos_lotes->entrada = $qts - $produtos_lotes->stock;
                    $produtos_lotes->update();
                }
                if ($verificarEstoque) {
                    $saida = Estoque::findOrFail($verificarEstoque->id);
                    $saida->stock = $qts - $saida->stock;
                    $saida->update();
                }
            }
            
            if ($request->operacao != "alterar_minimo") {
            
                RegistroMovimentoItem::create([
                    "registro_id" => $registro->id,
                    "codigo" => $code_,
                    "produto_id" => $produto->id,
                    "quantidade" => $request->stock,
                    "preco_custo" => $produto->preco_custo,
                    "preco_venda" => $produto->preco_venda,
                    "lote_id" => $request->lote_id,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);
            
                $total += $produto->preco_custo * $request->stock;
    
                $registro->total = $total;
                $registro->update();
            }

            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
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
    }
}
