<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Imports\EstoqueImport;
use App\Models\Cliente;
use App\Models\Entidade;
use App\Models\Estoque;
use App\Models\Fornecedore;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PDF;
use RealRashid\SweetAlert\Facades\Alert;

class EstoqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $estoque = Estoque::where("entidade_id", $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        $head = [
            "titulo" => "Stock",
            "descricao" => env('APP_NAME'),
            "estoques" => $estoque,
            'lojas' => $lojas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.estoques.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_import()
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes", "categorias", "marcas")->findOrFail($entidade->empresa->id);

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        $fornecedores = Fornecedore::where("entidade_id", $entidade->empresa->id)->get();
        $clientes = Cliente::where("entidade_id", $entidade->empresa->id)->get();

        $head = [
            "titulo" => __('messages.novo'),
            "lojas" => $lojas,
            "clientes" => $clientes,
            "fornecedores" => $fornecedores,
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.estoques.create-import', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store_import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            try {
                Excel::import(new EstoqueImport($request->all()), $request->file('file'));
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                return back()->withErrors($e->failures());
            } catch (\Exception $e) {
                return back()->withErrors($e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Dados importados com sucesso!');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);


        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        $fornecedores = Fornecedore::where("entidade_id", $entidade->empresa->id)->get();
        $clientes = Cliente::where("entidade_id", $entidade->empresa->id)->get();

        $produtos = Produto::whereIn("id", $meus_produtos)->where('tipo', 'P')
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $head = [
            "titulo" => "Actualizar Stock",
            "descricao" => env('APP_NAME'),
            "lojas" => $lojas,
            "produtos" => $produtos,
            "clientes" => $clientes,
            "fornecedores" => $fornecedores,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.estoques.create', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'loja_id' => 'required',
            'operacao' => 'required',
        ]);

        $data = json_decode($request->input('itens'), true);

        try {
            DB::beginTransaction();
            //
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            if ($request->operacao == "Saída de Stock") {
                $tipo = "S";
            } else {
                $tipo = "E";
            }

            $total_registro = RegistroMovimento::where("entidade_id", $entidade->empresa->id)
                ->where('tipo_documento', $request->tipo_documento)
                ->count() + 1;

            $sigla = $request->tipo_documento . "" . date('Y') . "/" . $total_registro;
            
            $code_ = time();

            $registro = RegistroMovimento::create([
                "operacao" => $request->operacao,
                "tipo" => $request->tipo_documento,
                "numero" => $total_registro,
                "codigo" => $code_,
                "sigla" => $sigla,
                "data_at" => date("Y-m-d"),
                "observacao" => $request->observacao,
                "loja_id" => $request->loja_id,
                "cliente_id" => $request->cliente_id,
                "fornecedor_id" => $request->fornecedor_id,
                "tipo_documento" => $request->tipo_documento,
                "user_id" => Auth::user()->id,
                "entidade_id" => $entidade->empresa->id,
            ]);

            if ($request->operacao == "Saída de Stock") {
            
                foreach ($data as $item) {
                
                    $produto = Produto::findOrFail($item['produto_id']);
                    
                    $qts = 0;
                    
                    if($produto->tipo_stock == "P") {
                        $qts = UnitConverter::converterParaBase($item['quantidade'], $produto->unidade);
                    }else {
                        $qts = $item['quantidade'];
                    }
                    
                    Registro::create([
                        "registro" => $request->operacao,
                        "documento" => $sigla,
                        "documento_id" => $registro->id,
                        "data_registro" => date('Y-m-d'),
                        "tipo" => $tipo,
                        'status' => 'D',
                        "preco_unitario" => $item['preco'],
                        "quantidade" => $qts,
                        "produto_id" => $item['produto_id'],
                        "observacao" => $request->observacao,
                        "loja_id" => $request->loja_id,
                        "lote_id" => $item['lote_id'],
                        "user_id" => Auth::user()->id,
                        "entidade_id" => $entidade->empresa->id,
                    ]);

                    $verificarEstoque = Estoque::where("lote_id", $item['lote_id'])
                        ->where("entidade_id", $entidade->empresa->id)
                        ->where("produto_id", $item['produto_id'])
                        ->where("loja_id", $request->loja_id)
                        ->first();

                    if ($verificarEstoque) {
                        // Nao informou o lote
                        if (!$item['lote_id'] == NULL) {
                            $produtos_lotes = Lote::findOrFail($item['lote_id']);
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
            }
            
            if ($request->operacao == "Entrada de Stock") {
                // Nao informou o lote
                foreach ($data as $item) {
                    $produto = Produto::findOrFail($item['produto_id']);
                    
                    $qts = 0;
                    
                    if($produto->tipo_stock == "P") {
                        $qts = UnitConverter::converterParaBase($item['quantidade'], $produto->unidade);
                    }else {
                        $qts = $item['quantidade'];
                    }
                                   
                    ## DEFINIR PRECO CUSTO EM MEDIA

                    ## ANTIGO
                    $TOTAL_CUSTO_ANTIGO = $produto->preco_custo * $produto->total_produto_loja_activa;
                    $TOTAL_CUSTO_NOVO = $item['preco'] * $qts;

                    $TOTAL_CUSTO = $TOTAL_CUSTO_ANTIGO + $TOTAL_CUSTO_NOVO;

                    $TOTAL_QUANTIDADE_FINAL = $produto->total_produto_loja_activa + $qts;

                    $CUSTO_MEDICO = $TOTAL_CUSTO / $TOTAL_QUANTIDADE_FINAL;

                    $produto->disponibilidade = $produto->preco_custo;
                    $produto->preco = $CUSTO_MEDICO;
                    $produto->preco_custo = $item['preco'];
                    $produto->preco_venda = $item['preco_venda'];
                    $produto->update();

                    Registro::create([
                        "registro" => $request->operacao,
                        "data_registro" => date('Y-m-d'),
                        "tipo" => $tipo,
                        "documento" => $sigla,
                        "documento_id" => $registro->id,
                        'status' => 'E',
                        "preco_unitario" => $item['preco'],
                        "quantidade" => $qts,
                        "produto_id" => $item['produto_id'],
                        "observacao" => $request->observacao,
                        "loja_id" => $request->loja_id,
                        "lote_id" => $item['lote_id'],
                        "user_id" => Auth::user()->id,
                        "entidade_id" => $entidade->empresa->id,
                    ]);
                    
                    if (!$item['lote_id'] == NULL) {
                        $produtos_lotes = Lote::findOrFail($item['lote_id']);
                        
                        if($produtos_lotes->data_validade != NULL && $produtos_lotes->data_validade <= date("Y-m-d")) {
                            return response()->json(['success' => true, 'message' => "O lote produto: {$produto->nome} encontra-se expirado, não podemos actualizar o stock!"], 404);
                        }
                        
                        $produtos_lotes->stock_total = $produtos_lotes->stock_total + $qts;
                        $produtos_lotes->entrada = $produtos_lotes->entrada + $qts;
                        $produtos_lotes->update();
                    }

                    $verificarEstoque_ = Estoque::where("entidade_id", $entidade->empresa->id)
                        ->where("produto_id", $item['produto_id'])
                        ->where("loja_id", $request->loja_id)
                        ->first();

                    if ($verificarEstoque_) {
                        $update = Estoque::findOrFail($verificarEstoque_->id);
                        $update->stock = $update->stock + $qts;
                        $update->update();
                    } else {
                        Estoque::create([
                            "loja_id" => $request->loja_id,
                            "lote_id" => $item['lote_id'],
                            "produto_id" => $item['produto_id'],
                            "user_id" => Auth::user()->id,
                            "data_operacao" => date('Y-m-d'),
                            "stock" => $qts,
                            "operacao" => $request->operacao,
                            "observacao" => $request->observacao,
                            "entidade_id" => $entidade->empresa->id,
                        ]);
                    }
                    
                    $existe = LojaProduto::where('produto_id', $produto->id)
                        ->where('loja_id', $request->loja_id)
                        ->where('entidade_id', $entidade->empresa->id)
                    ->exists();
                    
                    if (!$existe) {
                        LojaProduto::create([
                            'produto_id' => $produto->id,
                            'loja_id' => $request->loja_id,
                            'entidade_id' => $entidade->empresa->id,
                        ]);
                    }
                }
            }

            if ($request->operacao == "Actualizar de Stock") {
                foreach ($data as $item) {
                
                    $produto = Produto::findOrFail($item['produto_id']);
                    $qts = 0;
                    if($produto->tipo_stock == "P") {
                        $qts = UnitConverter::converterParaBase($item['quantidade'], $produto->unidade);
                    }else {
                        $qts = $item['quantidade'];
                    }

                    Registro::create([
                        "registro" => $request->operacao,
                        "data_registro" => date('Y-m-d'),
                        "tipo" => $tipo,
                        'status' => 'A',
                        "documento" => $sigla,
                        "documento_id" => $registro->id,
                        "preco_unitario" => $item['preco'],
                        "quantidade" => $qts,
                        "produto_id" => $item['produto_id'],
                        "observacao" => $request->observacao,
                        "loja_id" => $request->loja_id,
                        "lote_id" => $item['lote_id'],
                        "user_id" => Auth::user()->id,
                        "entidade_id" => $entidade->empresa->id,
                    ]);

                    $verificarEstoque = Estoque::where("lote_id", $item['lote_id'])
                        ->where("entidade_id", $entidade->empresa->id)
                        ->where("produto_id", $item['produto_id'])
                        ->where("loja_id", $request->loja_id)
                        ->first();

                    // Nao informou o lote
                    if (!$item['lote_id'] == NULL) {
                        $produtos_lotes = Lote::findOrFail($item['lote_id']);
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
            }

            $total = 0;

            foreach ($data as $item) {
                
                $produt = Produto::findOrFail($item['produto_id']);
            
                $qts = 0;
                
                if($produt->tipo_stock == "P") {
                    $qts = UnitConverter::converterParaBase($item['quantidade'], $produto->unidade);
                }else {
                    $qts = $item['quantidade'];
                }
            
                RegistroMovimentoItem::create([
                    'registro_id' => $registro->id,
                    'codigo' => $code_,
                    'produto_id' => $item['produto_id'],
                    'quantidade' => $qts,
                    'preco_custo' => $item['preco'],
                    'preco_venda' => $item['preco_venda'],
                    'lote_id' => $item['lote_id'],
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);
                $total += $item['preco'] * $item['quantidade'];
            }

            $registro->total = $total;
            $registro->update();
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!", "registro" => $registro]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    public function resumoRelatorio(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $dataActual = date("Y-m-d");

        if ($request->periodo == "1_mes") {
            $data_create = date("Y-m-d", strtotime($dataActual . "-1months"));
        } else if ($request->periodo == "7_dias") {
            $data_create = date("Y-m-d", strtotime($dataActual . "-7days"));
        } else if ($request->periodo == "21_dias") {
            $data_create = date("Y-m-d", strtotime($dataActual . "-21days"));
        } else if ($request->periodo == "2_meses") {
            $data_create = date("Y-m-d", strtotime($dataActual . "-2months"));
        } else if ($request->periodo == "3_meses") {
            $data_create = date("Y-m-d", strtotime($dataActual . "-3months"));
        } else if ($request->periodo == "6_meses") {
            $data_create = date("Y-m-d", strtotime($dataActual . "-6months"));
        } else if ($request->periodo == "1_ano") {
            $data_create = date("Y-m-d", strtotime($dataActual . "-1years"));
        }


        $estoque = Estoque::when($request->loja_id, function ($query, $value) {
            $query->where("loja_id", $value);
        })->where('entidade_id', '=', $entidade->empresa->id)
            ->where('data_operacao', '>=', $data_create)
            ->where('data_operacao', '<=', date('Y-m-d'))
            ->with('produto')
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => "Relatório de Análise de Stock",
            "descricao" => env('APP_NAME'),
            "resultados" => $estoque,
            "empresa" => $entidade,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.estoques.resumo', $head);
    }

    public function imprimirResumoRelatorio()
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $dataActual = date("Y-m-d");

        $data_create = date("Y-m-d", strtotime($dataActual . "-7days"));

        $estoque = Estoque::where('entidade_id', '=', $entidade->empresa->id)
            ->where('data_operacao', '>=', $data_create)
            ->where('data_operacao', '<=', date('Y-m-d'))
            ->with('produto')
            ->orderBy('created_at', 'desc')
            ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Relatório de Análise de Stock",
            "descricao" => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "resultados" => $estoque,
            "loja" => $entidade,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.estoques.resumo-relatorio-pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }


    public function estoqueProduto(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["caixas", "users"])->findOrFail($entidade->empresa->id);

        $estoques = Lote::with(["registros" => function ($query) use ($request) {
            $query->when($request->loja_id, function ($q, $v) {
                $q->where("loja_id", $v);
            });
        }, "produto"]) // carregando os relacionamentos
            ->when($request->status, function ($query, $value) {
                $query->where("status", $value);
            })
            ->when($request->produto_id, function ($query, $value) {
                $query->where("produto_id", $value);
            })
            ->where("entidade_id", $entidade->empresa->id)
            ->get();

        $produtos = Produto::where("tipo", "P")
            ->where("entidade_id", $entidade->empresa->id)
            ->get();

        $lojas = Loja::where("entidade_id", $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Produto de Stock",
            "descricao" => env("APP_NAME"),
            "empresa" => $empresa,
            "estoques" => $estoques,
            "produtos" => $produtos,
            "lojas" => $lojas,
            "requests" => $request->all("status", "produto_id", "loja_id"),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.estoques.dashboard', $head);
    }

    public function imprimirEstoqueProduto(Request $request)
    {

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["caixas", "users"])->findOrFail($entidade->empresa->id);

        $estoques = Lote::with([
            "registros" => function ($query) use ($request) {
                $query->when($request->loja_id, function ($q, $v) {
                    $q->where("loja_id", $v);
                });
            },
            "produto"
        ]) // carregando os relacionamentos
            ->when($request->status, function ($query, $value) {
                $query->where("status", $value);
            })
            ->when($request->produto_id, function ($query, $value) {
                $query->where("produto_id", $value);
            })
            ->where("entidade_id", $entidade->empresa->id)
            ->get();

        $produto = Produto::find($request->produto_id);
        $loja = Loja::find($request->loja_id);


        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Produto de Stock",
            "descricao" => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "empresa" => $empresa,
            "estoques" => $estoques,
            "produto" => $produto,
            "loja" => $loja,
            "requests" => $request->all('status'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];


        $pdf = PDF::loadView('dashboard.estoques.stock-relatorio-pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
}
