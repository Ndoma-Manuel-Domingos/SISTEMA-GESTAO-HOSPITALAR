<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Imports\ProdutoImport;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Conta;
use App\Models\Subconta;
use App\Models\Entidade;
use App\Models\Estoque;
use App\Models\Fornecedore;
use App\Models\Imposto;
use App\Models\ItemVenda;
use App\Models\Loja;
use App\Models\LojaProduto;
use App\Models\Lote;
use App\Models\Marca;
use App\Models\Motivo;
use App\Models\Movimento;
use App\Models\Produto;
use App\Models\ProdutoGrupoPreco;
use App\Models\Registro;
use App\Models\RegistroMovimento;
use App\Models\RegistroMovimentoItem;
use App\Models\Unidade;
use App\Models\User;
use App\Models\UserLoja;
use App\Models\Variacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Barryvdh\DomPDF\Facade\Pdf;

class ProdutoController extends Controller
{
    use TraitHelpers;

    private $numero_aleatorio = [];

    public function __construct()
    {
        $this->numero_aleatorio = rand(10000, 99999);
    }

    public function getNumeros()
    {
        return $this->numero_aleatorio;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar produtos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $produtos = Produto::whereIn("id", $meus_produtos)
            ->with(['categoria', 'marca', 'taxa_imposto'])->when($request->nome_referencia, function ($query, $value) {
                $query->where('nome', 'LIKE', "%{$value}%");
                $query->orWhere('codigo_barra', 'LIKE', "%{$value}%");
            })
            ->when($request->categoria_id, function ($query, $value) {
                $query->where('categoria_id',$value);
            })
            ->when($request->tipo, function ($query, $value) {
                $query->where('tipo',$value);
            })
            ->when($request->marca_id, function ($query, $value) {
                $query->where('marca_id',$value);
            })
            ->whereNotIn('tipo_stock', ['P'])
            ->where('entidade_id',$entidade->empresa->id)
            ->orderBy('nome', 'asc')
        ->get();

        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "produtos" => $produtos,
            "empresa" => $empresa,
            "lojas" => Loja::where("entidade_id", $entidade->empresa->id)->whereIn("id", $minhas_lojas)->get(),
            "categorias" => Categoria::where('entidade_id', $entidade->empresa->id)->get(),
            "marcas" => Marca::where('entidade_id', $entidade->empresa->id)->get(),
            "requests" => $request->all('categoria_id', 'tipo', 'marca_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.produtos.index', $head);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function materia_primas(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar produtos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $produtos = Produto::whereIn("id", $meus_produtos)
            ->with(['categoria', 'marca', 'taxa_imposto'])->when($request->nome_referencia, function ($query, $value) {
                $query->where('nome', 'LIKE', "%{$value}%");
                $query->orWhere('codigo_barra', 'LIKE', "%{$value}%");
            })
            ->when($request->categoria_id, function ($query, $value) {
                $query->where('categoria_id', $value);
            })
            ->when($request->marca_id, function ($query, $value) {
                $query->where('marca_id', $value);
            })
        ->where('tipo', 'P')
        ->where('tipo_stock', 'P')
        ->where('entidade_id', $entidade->empresa->id)
        ->orderBy('nome', 'asc')
        ->get();

        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "produtos" => $produtos,
            "empresa" => $empresa,
            "lojas" => Loja::where("entidade_id", $entidade->empresa->id)->whereIn("id", $minhas_lojas)->get(),
            "categorias" => Categoria::where('entidade_id', $entidade->empresa->id)->get(),
            "marcas" => Marca::where('entidade_id', $entidade->empresa->id)->get(),
            "requests" => $request->all('categoria_id', 'tipo', 'marca_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.produtos.materia-prima', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_import()
    {

        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar produtos')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes", "categorias", "marcas")->findOrFail($entidade->empresa->id);


        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();


        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "categorias" => Categoria::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "motivos" => Motivo::get(),
            "impostos" => Imposto::get(),
            "marcas" => Marca::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "variacoes" => Variacao::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "lojas" => $lojas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.produtos.create-import', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store_import(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar produtos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $loja = Loja::where("entidade_id", $entidade->empresa->id)->first();
        $cliente = Cliente::where("entidade_id", $entidade->empresa->id)->first();
        $fornecedor = Fornecedore::where("entidade_id", $entidade->empresa->id)->first();

        $datas = [
            "operacao" => "Entrada de Stock",
            "tipo_documento" => "CN",
            "observacao" => "Importação de produtos via Excel",
            // "loja_id" => $loja ? $loja->id : null,
            "loja_id" => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
            "cliente_id" => $cliente ? $cliente->id : null,
            "fornecedor_id" => $fornecedor ? $fornecedor->id : null,
        ];

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            Excel::import(new ProdutoImport($datas), $request->file('file'));
        }

        try {
            return redirect()->back()->with('success', 'Dados importados com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao importar dados: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao importar dados: ' . $e->getMessage());
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar produtos')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes", "categorias", "marcas")->findOrFail($entidade->empresa->id);


        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();
            
        $unidades = Unidade::get();

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "referencia" => time(),
            "codigo_barra" => time(),
            "unidades" => $unidades,
            "empresa" => $empresa,
            "categorias" => Categoria::where('entidade_id', $entidade->empresa->id)->get(),
            "motivos" => Motivo::get(),
            "impostos" => Imposto::get(),
            "marcas" => Marca::where('entidade_id', $entidade->empresa->id)->get(),
            "variacoes" => Variacao::where('entidade_id', $entidade->empresa->id)->get(),
            "lojas" => $lojas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.produtos.create', $head);
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

        if (!$user->can('criar todos') && !$user->can('criar produtos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
            'tipo' => 'required|string',
            'codigo_barra' => 'required|string',
            'controlo_stock' => 'required',
            'tipo_stock' => 'required',
        ]);


        $entidade = User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id);

        $verificar_produto_codigo_barra = Produto::where('codigo_barra', $request->codigo_barra)->where('entidade_id', $entidade->empresa->id)->first();

        if ($verificar_produto_codigo_barra) {
            return response()->json(['message' => "Não se pode cadastrar dois produtos com o mesmo codigo de barra!"], 404);
        }
        $verificar_produto_codigo_barra = Produto::where('nome', $request->nome)->where('entidade_id', $entidade->empresa->id)->first();

        if ($verificar_produto_codigo_barra) {
            return response()->json(['message' => "Não se pode cadastrar dois produtos com o mesmo nome!"], 404);
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            if ($request->preco_venda == null) {
                $request->preco_venda = $request->preco;
            }

            if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {
                $requestImage = $request->imagem;
                $extension = $requestImage->extension();

                $imageName = md5($requestImage->getClientOriginalName() . strtotime("now") . "." . $extension);

                $request->imagem->move(public_path('images/produtos'), $imageName);
            } else {
                $imageName = NULL;
            }

            if ($entidade->empresa->tipo_entidade->sigla == "CFOR") {
                $request->preco_venda = $request->preco_custo;
                $request->preco = $request->preco_custo;
            }

            $code = uniqid(time());
            $nova_conta = "";

            if ($request->tipo == "S") {
                // 26.1
                $conta = Conta::where('conta', '62')->where('entidade_id', $entidade->empresa->id)->first();
                $serie = "62.1.1";

                $qtds = 0;
                $observacao = "Registro de serviço";
            } else {
                if ($request->tipo_stock == "M") {
                    // 26.1
                    $conta = Conta::where('conta', '26')->where('entidade_id', $entidade->empresa->id)->first();
                    $serie = "26.1";
                }
                if ($request->tipo_stock == "P") {
                    // 22.1
                    $conta = Conta::where('conta', '22')->where('entidade_id', $entidade->empresa->id)->first();
                    $serie = "22.1";
                }
                if ($request->tipo_stock == "P1") {
                    // 22.2
                    $conta = Conta::where('conta', '22')->where('entidade_id', $entidade->empresa->id)->first();
                    $serie = "22.2";
                }
                if ($request->tipo_stock == "P2") {
                    // 22.4
                    $conta = Conta::where('conta', '22')->where('entidade_id', $entidade->empresa->id)->first();
                    $serie = "22.4";
                }
                if ($request->tipo_stock == "A") {
                    $conta = Conta::where('conta', '24')->where('entidade_id', $entidade->empresa->id)->first();
                    $serie = "24.1";
                }
                if ($request->tipo_stock == "A1") {
                    $conta = Conta::where('conta', '24')->where('entidade_id', $entidade->empresa->id)->first();
                    $serie = "24.2";
                }
                if ($request->tipo_stock == "S") {
                    $conta = Conta::where('conta', '25')->where('entidade_id', $entidade->empresa->id)->first();
                    $serie = "25.1";
                }
                if ($request->tipo_stock == "S1") {
                    $conta = Conta::where('conta', '25')->where('entidade_id', $entidade->empresa->id)->first();
                    $serie = "25.2";
                }
                if ($request->tipo_stock == "T") {
                    $conta = Conta::where('conta', '23')->where('entidade_id', $entidade->empresa->id)->first();
                    $serie = "23";
                }
                $qtds = $request->quantidade_inicial_stock ?? 0;
                $observacao = "Entrada de Existência";
            }

            $total_registro = RegistroMovimento::where("entidade_id", $entidade->empresa->id)
                ->where('tipo_documento', "CN")
                ->count() + 1;

            $sigla = "CN" . date('Y') . "/" . $total_registro;

            $subc_ = Subconta::where('numero', 'like', $serie . "%")->where('entidade_id', $entidade->empresa->id)->count();
            $numero =  $subc_ + 1;

            $nova_conta = $serie . "." . $numero;

            $subconta = Subconta::create([
                'entidade_id' => $entidade->empresa->id,
                'numero' => $nova_conta,
                'nome' => $request->nome,
                'tipo_conta' => 'M',
                'code' => $code,
                'status' => $conta->status,
                'conta_id' => $conta->id,
                'user_id' => Auth::user()->id,
            ]);

            $movimeto = Movimento::create([
                'user_id' => Auth::user()->id,
                'subconta_id' => $subconta->id,
                'status' => true,
                'movimento' => 'E',
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                'credito' => 0,
                'debito' => $request->preco_custo * $qtds,
                'observacao' => $observacao,
                'code' => $code,
                'data_at' => date("Y-m-d"),
                'entidade_id' => $entidade->empresa->id,
                'exercicio_id' => $this->exercicio(),
                'periodo_id' => $this->periodo(),
            ]);

            $motivo = Motivo::findOrFail($request->motivo_isencao ?? $entidade->empresa->motivo_id);
            $imposto = Imposto::findOrFail($request->imposto ?? $entidade->empresa->imposto_id);

            $produto = Produto::create([
                "nome" => $request->nome,
                "codigo_barra" => $request->codigo_barra,
                "conta" => $nova_conta,
                "code" => $code,
                "descricao" => $request->descricao != "" ? $request->descricao : $request->nome,
                "imagem" => $imageName,
                "variacao_id" => $request->variacao_id,
                "categoria_id" => $request->categoria_id,
                "imposto_id" => $imposto->id,
                "marca_id" => $request->marca_id,
                "tipo" => $request->tipo,
                "peso" => $request->peso,
                "unidade_id" => $request->unidade,
                "imposto" => $imposto->codigo,
                "taxa" => $imposto->valor,
                "motivo_isencao" => $motivo->codigo,
                "motivo_id" => $motivo->id,
                "preco_custo" => $request->preco_custo ?? 0,
                "preco" => $request->preco_venda ?? 0,
                "margem" => $request->margem,
                "preco_venda" => $request->preco_venda ?? 0,
                "preco_venda_com_iva" => $request->preco_venda + ($request->preco_venda * ($imposto->valor / 100)),
                "controlo_stock" => $request->controlo_stock,
                "tipo_stock" => $request->tipo_stock,
                "disponibilidade" => $request->disponibilidade,
                "status" => $request->status,
                "subconta_id" => $subconta->id ?? 1,
                "user_id" => Auth::user()->id,
                "entidade_id" =>  $entidade->empresa->id,
            ]);

            $lote = null;

            if ($request->tipo == "P") {
                $lote = Lote::create([
                    "produto_id" => $produto->id,
                    "lote" => "RED-" . $this->getNumeros(),
                    "status" => "activo",
                    "codigo_barra" => $produto->codigo_barra,
                    "data_validade" => NULL,
                    "data_validade_vitalicio" => 1,
                    "stock_total" => 0,
                    "entidade_id" => $entidade->empresa->id,
                ]);
            }

            $lojas = Loja::when($request->disponibilidade, function ($query, $value) {
                $query->where("id", $value);
            })
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

            foreach ($lojas as $loja) {

                if ($request->quantidade_inicial_stock >= 1) {
                    $registro = RegistroMovimento::create([
                        "operacao" => $request->operacao,
                        "tipo" => "CN",
                        "numero" => $total_registro,
                        "codigo" => $code,
                        "sigla" => $sigla,
                        "data_at" => date("Y-m-d"),
                        "observacao" => $request->observacao,
                        "loja_id" => $request->loja_id,
                        "cliente_id" => $request->cliente_id,
                        "fornecedor_id" => $request->fornecedor_id,
                        "tipo_documento" => "CN",
                        "user_id" => Auth::user()->id,
                        "entidade_id" => $entidade->empresa->id,
                    ]);

                    $total = 0;

                    RegistroMovimentoItem::create([
                        'registro_id' => $registro->id,
                        'codigo' => $code,
                        'produto_id' => $produto->id,
                        'quantidade' => $request->quantidade_inicial_stock,
                        'preco_custo' => $produto->preco_custo,
                        'preco_venda' => $produto->preco_venda,
                        'lote_id' => $lote ? $lote->id : NULL,
                        "user_id" => Auth::user()->id,
                        "entidade_id" => $entidade->empresa->id,
                    ]);
                    $total += $produto->preco_custo * $request->quantidade_inicial_stock;

                    $registro->total = $total;
                    $registro->update();
                }

                LojaProduto::create([
                    'produto_id' => $produto->id,
                    'loja_id' => $loja->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);

                if ($request->tipo == "P") {
                    Registro::create([
                        "registro" => "Entrada de Stock",
                        "data_registro" => date('Y-m-d'),
                        'tipo' => 'E',
                        'status' => 'A',
                        "quantidade" => $request->quantidade_inicial_stock,
                        "produto_id" => $produto->id,
                        "observacao" => 'Entrada inicial de produtos de Stock',
                        "stock_minimo" => 5, // quantidade minima,
                        "loja_id" => $loja->id,
                        "lote_id" => $lote ? $lote->id : NULL,
                        "user_id" => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }

                Estoque::create([
                    "loja_id" => $loja->id,
                    "lote_id" => $lote ? $lote->id : NULL,
                    "produto_id" => $produto->id,
                    "user_id" => Auth::user()->id,
                    "data_operacao" => date('Y-m-d'),
                    "stock" => $request->tipo == "P" ? $request->quantidade_inicial_stock : 0,
                    "observacao" => 'Entrada inicial de produtos de Stock',
                    "stock_minimo" => 5, // quantidade minima,
                    "operacao" => "Actualizar de Stock",
                    "entidade_id" => $entidade->empresa->id,
                ]);
            }

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar produtos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $produto = Produto::with(["receitas.items.ingrediente", "variacao", "taxa_imposto", "categoria", "marca"])->findOrFail($id);

        $grupo_precos = ProdutoGrupoPreco::with(['produto'])->where('produto_id', $produto->id)->get();

        $totalStock = Estoque::where('produto_id', $produto->id)
            ->where('entidade_id', $entidade->empresa->id)
        ->sum('stock');

        $lojas = Estoque::with('loja')->where('produto_id', $produto->id)->get();

        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);
        
        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

        $materias_primas = Produto::whereIn("id", $meus_produtos)->where('tipo', 'P')
            ->where('tipo_stock', 'P')
            ->where('entidade_id', $entidade->empresa->id)
        ->get();
        
        $unidades = Unidade::get();

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "produto" => $produto,
            "empresa" => $empresa,
            "unidades" => $unidades,
            "totalStock" => $totalStock,
            "lojas" => $lojas,
            "materias_primas" => $materias_primas,
            "grupo_precos" => $grupo_precos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.produtos.show', $head);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function definir_preco_venda($grupo, $movimento)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes", "categorias", "marcas")->findOrFail($entidade->empresa->id);

        $movimento = ItemVenda::with('produto')->findOrFail($movimento);
        $grupo = ProdutoGrupoPreco::findOrFail($grupo);

        $produto = Produto::with(["estoque", "variacao", "taxa_imposto", "categoria", "marca"])->findOrFail($grupo->produto_id);

        $grupos = ProdutoGrupoPreco::where('produto_id', $produto->id)->get();

        foreach ($grupos as $item) {
            $update = ProdutoGrupoPreco::findOrFail($item->id);
            $update->status = 'desactivo';
            $update->update();
        }

        $produto->imposto_id = $grupo->id;
        $produto->preco = $grupo->preco_venda;
        $produto->imposto = $grupo->imposto;
        $produto->taxa = $grupo->taxa;
        $produto->motivo_isencao = $grupo->codigo;
        $produto->motivo_id = $grupo->id;
        $produto->preco_custo = $grupo->preco_custo;
        $produto->margem = $grupo->margem;
        $produto->preco_venda = $grupo->preco_venda;
        $produto->update();

        $grupo->status = 'activo';
        $grupo->update();


        // actualização de vendas ou seja actualizar produtos dos seus preços
        $desconto = ($produto->preco * $movimento->quantidade) * ($movimento->desconto_aplicado / 100);

        $produto->estoque->stock = ($produto->estoque->stock + $movimento->quantidade) - $movimento->quantidade;

        $valorBase = $produto->preco * $movimento->quantidade;
        // calculo do iva
        $valorIva = ($produto->taxa / 100) * $valorBase;

        $movimento->quantidade = $movimento->quantidade;
        $movimento->valor_pagar = ($valorBase + $valorIva) - $desconto;
        $movimento->preco_unitario = $produto->preco;

        $movimento->valor_base = $valorBase;
        $movimento->valor_iva = $valorIva;

        $movimento->desconto_aplicado = $movimento->desconto_aplicado;
        $movimento->desconto_aplicado_valor = $desconto;

        $movimento->iva = $movimento->iva;
        $movimento->texto_opcional = $movimento->texto_opcional;
        $movimento->numero_serie = $movimento->numero_serie;
        $movimento->update();

        return redirect()->route('actualizar-venda', [$movimento->id, "null"])->with("success", "Preços actualizados com successo!");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function definir_preco_factura($grupo, $movimento)
    {
        //
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes", "categorias", "marcas")->findOrFail($entidade->empresa->id);

        $movimento = ItemVenda::with('produto')->findOrFail($movimento);
        $grupo = ProdutoGrupoPreco::findOrFail($grupo);

        $produto = Produto::with(["estoque", "variacao", "taxa_imposto", "categoria", "marca"])->findOrFail($grupo->produto_id);

        $grupos = ProdutoGrupoPreco::where('produto_id', $produto->id)->get();

        foreach ($grupos as $item) {
            $update = ProdutoGrupoPreco::findOrFail($item->id);
            $update->status = 'desactivo';
            $update->update();
        }

        $produto->imposto_id = $grupo->id;
        $produto->preco = $grupo->preco;
        $produto->imposto = $grupo->imposto;
        $produto->taxa = $grupo->taxa;
        $produto->motivo_isencao = $grupo->codigo;
        $produto->motivo_id = $grupo->id;
        $produto->preco_custo = $grupo->preco_custo;
        $produto->margem = $grupo->margem;
        $produto->preco_venda = $grupo->preco_venda;
        $produto->update();

        $grupo->status = 'activo';
        $grupo->update();

        // actualização de vendas ou seja actualizar produtos dos seus preços
        $desconto = ($produto->preco * $movimento->quantidade) * ($movimento->desconto_aplicado / 100);

        $produto->estoque->stock = ($produto->estoque->stock + $movimento->quantidade) - $movimento->quantidade;

        $valorBase = $produto->preco * $movimento->quantidade;
        // calculo do iva
        $valorIva = ($produto->taxa / 100) * $valorBase;

        $movimento->quantidade = $movimento->quantidade;
        $movimento->valor_pagar = ($valorBase + $valorIva) - $desconto;
        $movimento->preco_unitario = $produto->preco;

        $movimento->valor_base = $valorBase;
        $movimento->valor_iva = $valorIva;

        $movimento->desconto_aplicado = $movimento->desconto_aplicado;
        $movimento->desconto_aplicado_valor = $desconto;

        $movimento->iva = $movimento->iva;
        $movimento->texto_opcional = $movimento->texto_opcional;
        $movimento->numero_serie = $movimento->numero_serie;
        $movimento->update();

        return redirect()->route('actualizar-venda-factura', $movimento->id)->with("success", "Preços actualizados com successo!");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function definir_preco($id)
    {
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
            $empresa = Entidade::with("variacoes", "categorias", "marcas")->findOrFail($entidade->empresa->id);

            $grupo = ProdutoGrupoPreco::findOrFail($id);

            $produto = Produto::with(["variacao", "taxa_imposto", "categoria", "marca"])->findOrFail($grupo->produto_id);

            $grupos = ProdutoGrupoPreco::where('produto_id', $produto->id)->get();

            foreach ($grupos as $item) {
                $update = ProdutoGrupoPreco::findOrFail($item->id);
                $update->status = 'desactivo';
                $update->update();
            }

            $produto->imposto_id = $grupo->id;
            $produto->preco = $grupo->preco;
            $produto->imposto = $grupo->imposto;
            $produto->taxa = $grupo->taxa;
            $produto->motivo_isencao = $grupo->codigo;
            $produto->motivo_id = $grupo->id;
            $produto->preco_custo = $grupo->preco_custo;
            $produto->margem = $grupo->margem;
            $produto->preco_venda = $grupo->preco_venda;
            $produto->update();

            $grupo->status = 'activo';
            $grupo->update();

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

    public function grupos_preco_delete($id)
    {
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
            $empresa = Entidade::with("variacoes", "categorias", "marcas")->findOrFail($entidade->empresa->id);

            $produto = ProdutoGrupoPreco::findOrFail($id);
            $produto->delete();

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
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function grupos_preco($id)
    {
        //
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes", "categorias", "marcas")->findOrFail($entidade->empresa->id);

        $produto = Produto::with(["variacao", "taxa_imposto", "categoria", "marca"])->findOrFail($id);

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        $head = [
            "titulo" => "Produto",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produto" => $produto,
            "lojas" => $lojas,
            "motivos" => Motivo::get(),
            "impostos" => Imposto::get(),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.produtos.grupo-precos', $head);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function grupos_preco_put(Request $request, $id)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $produto = Produto::findOrFail($id);

        // verificar se já tem produto
        $grupos = ProdutoGrupoPreco::where('produto_id', $id)->get();

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            if (count($grupos) == 0) {
                $create = ProdutoGrupoPreco::create([
                    "produto_id" => $produto->id,
                    "imposto_id" => $produto->imposto_id,
                    "preco" => $produto->preco,
                    "imposto" => $produto->imposto,
                    "taxa" => $produto->taxa,
                    "motivo_isencao" => $produto->movito_isencao,
                    "motivo_id" => $produto->motivo_id,
                    "preco_custo" => $produto->preco_custo,
                    "margem" => $produto->margem,
                    "preco_venda" => $produto->preco_venda,
                    "status" => 'activo',
                    "user_id" => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
            }

            $motivo = Motivo::findOrFail($request->motivo_isencao);

            if ($request->imposto == "5") {
                $request->taxa = 14;
            } else

            if ($request->imposto == "1") {
                $request->taxa = 0;
            } else

            if ($request->imposto == "2") {
                $request->taxa = 2;
            } else

            if ($request->imposto == "3") {
                $request->taxa = 5;
            } else

            if ($request->imposto == "4") {
                $request->taxa = 7;
            } else {
                $request->taxa = 0;
            }

            if ($request->preco == null) {
                $preco = $request->preco_venda;
                $venda = $request->preco_venda;
            } else {
                $preco = $request->preco;
                $venda = $request->preco_venda;
            }

            if ($request->preco_venda == null) {
                $preco = $request->preco;
                $venda = $request->preco;
            }

            $imposto = Imposto::where('id', $request->imposto)->first();

            $create = ProdutoGrupoPreco::create([
                "produto_id" => $produto->id,
                "imposto_id" => $imposto->id,
                "preco" => $preco,
                "imposto" => $request->imposto,
                "taxa" => $request->taxa,
                "motivo_isencao" => $motivo->codigo,
                "motivo_id" => $motivo->id,
                "preco_custo" => $request->preco_custo,
                "margem" => $request->margem,
                "preco_venda" => $venda,
                "status" => $request->status,
                "user_id" => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);

            $create->save();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }




        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
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

        if (!$user->can('editar todos') && !$user->can('editar produtos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes", "categorias", "marcas")->findOrFail($entidade->empresa->id);

        $unidades = Unidade::get();
        
        $produto = Produto::with(["variacao", "taxa_imposto", "categoria", "marca"])->findOrFail($id);

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        $lojas_produtos = LojaProduto::where('entidade_id', $entidade->empresa->id)
            ->where('produto_id', $produto->id)
            ->pluck('loja_id') // retorna apenas os IDs das lojas
            ->toArray();

        $head = [
            "titulo" => "Produto",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produto" => $produto,
            "lojas" => $lojas,
            "unidades" => $unidades,
            "lojas_produtos" => $lojas_produtos,
            "motivos" => Motivo::get(),
            "impostos" => Imposto::get(),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.produtos.edit', $head);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function recuperar_produto($id)
    {
        $produto = Produto::with(["variacao", "taxa_imposto", "categoria", "marca"])->findOrFail($id);
        return $produto;
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
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar produtos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
            'tipo' => 'required|string',
            'controlo_stock' => 'required',
            'tipo_stock' => 'required',
        ]);

        $entidade = User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {
                $requestImage = $request->imagem;
                $extension = $requestImage->extension();

                $imageName = md5($requestImage->getClientOriginalName() . strtotime("now") . "." . $extension);

                $request->imagem->move(public_path('images/produtos'), $imageName);
            } else {
                $imageName = $request->imagem_guardada;
            }

            $nova_conta = "";
            $code = uniqid(time());

            $produto = Produto::findOrFail($id);

            if ($produto->subconta_id == NULL) {

                if ($request->tipo_stock == "M") {
                    // 26.1
                    $conta = Conta::where('conta', '26')->where('entidade_id', $entidade->empresa->id)->first();
                    $serie = "26.1";
                }

                if ($request->tipo_stock == "P") {
                    // 22.1
                    $conta = Conta::where('conta', '22')->where('entidade_id', $entidade->empresa->id)->first();
                    $serie = "22.1";
                }

                if ($request->tipo_stock == "P1") {
                    // 22.2
                    $conta = Conta::where('conta', '22')->where('entidade_id', $entidade->empresa->id)->first();
                    $serie = "22.2";
                }

                if ($request->tipo_stock == "P2") {
                    // 22.4
                    $conta = Conta::where('conta', '22')->where('entidade_id', $entidade->empresa->id)->first();
                    $serie = "22.4";
                }

                if ($request->tipo_stock == "A") {
                    $conta = Conta::where('conta', '24')->where('entidade_id', $entidade->empresa->id)->first();
                    $serie = "24.1";
                }

                if ($request->tipo_stock == "A1") {
                    $conta = Conta::where('conta', '24')->where('entidade_id', $entidade->empresa->id)->first();
                    $serie = "24.2";
                }

                if ($request->tipo_stock == "S") {
                    $conta = Conta::where('conta', '25')->where('entidade_id', $entidade->empresa->id)->first();
                    $serie = "25.1";
                }

                if ($request->tipo_stock == "S1") {
                    $conta = Conta::where('conta', '25')->where('entidade_id', $entidade->empresa->id)->first();
                    $serie = "25.2";
                }

                if ($request->tipo_stock == "T") {
                    $conta = Conta::where('conta', '23')->where('entidade_id', $entidade->empresa->id)->first();
                    $serie = "23";
                }

                $subc_ = Subconta::where('numero', 'like', "{$serie}%")->where('entidade_id', $entidade->empresa->id)->count() + 1;
                $nova_conta =  $serie . "{$subc_}";

                $subconta = Subconta::create([
                    'entidade_id' => $entidade->empresa->id,
                    'numero' => $nova_conta,
                    'nome' => $request->nome,
                    'tipo_conta' => 'M',
                    'code' => $code,
                    'status' => $conta->status,
                    'conta_id' => $conta->id,
                    'user_id' => Auth::user()->id,
                ]);
            } else {

                if ($request->tipo_stock == $produto->tipo_stock) {
                    $nova_conta = $produto->conta;
                    $code = $produto->code;
                    $subc_ = Subconta::where('id', $produto->subconta_id)->where('entidade_id', $entidade->empresa->id)->first();

                    if ($subc_) {
                        $subconta = Subconta::findOrFail($subc_->id);
                        $subconta->nome = $request->nome;
                        $subconta->update();
                    } else {
                        ## depois damos outro tratamento
                    }
                } else {
                    if ($request->tipo_stock == "M") {
                        // 26
                        $conta = Conta::where('conta', '26')->where('entidade_id', $entidade->empresa->id)->first();
                        $serie = "26.1";
                    }

                    if ($request->tipo_stock == "P") {
                        // 22.1
                        $conta = Conta::where('conta', '22')->where('entidade_id', $entidade->empresa->id)->first();
                        $serie = "22.1";
                    }

                    if ($request->tipo_stock == "P1") {
                        // 22.2
                        $conta = Conta::where('conta', '22')->where('entidade_id', $entidade->empresa->id)->first();
                        $serie = "22.2";
                    }

                    if ($request->tipo_stock == "P2") {
                        // 22.4
                        $conta = Conta::where('conta', '22')->where('entidade_id', $entidade->empresa->id)->first();
                        $serie = "22.4";
                    }

                    if ($request->tipo_stock == "A") {
                        $conta = Conta::where('conta', '24')->where('entidade_id', $entidade->empresa->id)->first();
                        $serie = "24.1";
                    }

                    if ($request->tipo_stock == "A1") {
                        $conta = Conta::where('conta', '24')->where('entidade_id', $entidade->empresa->id)->first();
                        $serie = "24.2";
                    }

                    if ($request->tipo_stock == "S") {
                        $conta = Conta::where('conta', '25')->where('entidade_id', $entidade->empresa->id)->first();
                        $serie = "25.1";
                    }

                    if ($request->tipo_stock == "S1") {
                        $conta = Conta::where('conta', '25')->where('entidade_id', $entidade->empresa->id)->first();
                        $serie = "25.2";
                    }

                    if ($request->tipo_stock == "T") {
                        $conta = Conta::where('conta', '23')->where('entidade_id', $entidade->empresa->id)->first();
                        $serie = "23";
                    }
                    $subc_ = Subconta::where('numero', 'like', $serie . "%")->where('entidade_id', $entidade->empresa->id)->count();
                    $numero =  $subc_ + 1;

                    $nova_conta = $serie . "." . $numero;

                    $subconta = Subconta::create([
                        'entidade_id' => $entidade->empresa->id,
                        'numero' => $nova_conta,
                        'nome' => $request->nome,
                        'tipo_conta' => 'M',
                        'code' => $code,
                        'status' => $conta->status,
                        'conta_id' => $conta->id,
                        'user_id' => Auth::user()->id,
                    ]);
                }
            }

            $motivo = Motivo::find($request->motivo_isencao);
            $imposto = Imposto::where('id', $request->imposto)->first();

            if ($entidade->empresa->tipo_entidade->sigla == "CFOR") {
                $request->preco_venda = $request->preco_custo;
            }

            $produto->update([
                "nome" => $request->nome,
                // "codigo_barra" => $request->codigo_barra,
                "code" => $code,
                "conta" => $nova_conta,
                "descricao" => $request->descricao,
                "imagem" => $imageName,
                "imposto_id" => $imposto->id ?? $produto->imposto_id,
                "variacao_id" => $request->variacao_id,
                "categoria_id" => $request->categoria_id,
                "marca_id" => $request->marca_id,
                "tipo" => $request->tipo,
                "peso" => $request->peso,
                "unidade_id" => $request->unidade,
                "imposto" => $request->imposto ?? $produto->imposto,
                "taxa" => $imposto->valor ?? $produto->taxa,
                "motivo_isencao" => $motivo->codigo ?? $produto->motivo_isencao,
                "motivo_id" => $motivo->id ?? $produto->motivo_id,
                "preco" => $request->preco_venda,
                "preco_custo" => $request->preco_custo,
                "margem" => $request->margem,
                "preco_venda" => $request->preco_venda,
                "preco_venda_com_iva" => $request->preco_venda + ($request->preco_venda * (($imposto->valor ?? $produto->taxa) / 100)),
                "controlo_stock" => $request->controlo_stock,
                "tipo_stock" => $request->tipo_stock,
                "disponibilidade" => NULL,
                "status" => $request->status,
                "subconta_id" => $subconta->id,
            ]);

            $lojas_produtos = LojaProduto::where('entidade_id', $entidade->empresa->id)->where('produto_id', $produto->id)->get();

            foreach ($lojas_produtos as $i) {
                $i->delete();
            }

            foreach ($request->disponibilidade as $i) {
                $verificar = LojaProduto::where('loja_id', $i)->where('produto_id', $produto->id)->first();
                if (!$verificar) {
                    LojaProduto::create([
                        'loja_id' => $i,
                        'produto_id' => $produto->id,
                        'entidade_id' => $entidade->empresa->id
                    ]);
                }
            }

            $verificar_lote = Lote::where('produto_id', $produto->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            if (!$verificar_lote) {
                if ($request->tipo == "P") {
                    Lote::create([
                        'produto_id' => $produto->id,
                        'lote' => "RED-" . $this->getNumeros(),
                        'status' => "activo",
                        'codigo_barra' => $produto->codigo_barra,
                        'data_validade' => NULL,
                        'data_validade_vitalicio' => 1,
                        'stock_total' => 0,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }
            } else {
                if ($request->tipo == "P") {
                    $update_lote = Lote::findOrFail($verificar_lote->id);
                    $update_lote->codigo_barra = $produto->codigo_barra;
                    $update_lote->update();
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
        $user = auth()->user();

        if (!$user->can('eliminar todos') && !$user->can('eliminar produtos')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();

            $produto = Produto::findOrFail($id);
            $produto->delete();

            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function visualizacao_produtos_servicos(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('monitoramento de mesas')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $categorias = Categoria::with(["produtos"])->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $loja = Loja::where('status', 'activo')
            ->where('entidade_id', $entidade->empresa->id)
        ->first();
        
        $data = $request->data_at ?? date("Y-m-d");

        $head = [
            "titulo" => "Monitoramento de Produtos e Serviços",
            "descricao" => env('APP_NAME'),
            "categorias" => $categorias,
            "loja" => $loja,
            "data" => $data,
            "user_id" => $request->user_id,
            "requests" => $request->all('user_id', 'data_at'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.produtos.monitorar-produtos-servicos', $head);
    }


    public function visualizacao_produtos_servicos_pdf(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $categorias = Categoria::with(["produtos"])->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $loja = Loja::where('status', 'activo')->where('entidade_id', $entidade->empresa->id)->first();
        $data = $request->data_at ?? date("Y-m-d");

        $head = [
            "titulo" => "Monitoramento de Produtos e Serviços",
            "descricao" => env('APP_NAME'),
            "categorias" => $categorias,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "loja" => $loja,
            "data" => $data,
            "user_id" => $request->user_id,
            "requests" => $request->all('user_id', 'data_at'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = \PDF::loadView('dashboard.produtos.monitorar-produtos-servicos-pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function getLotes(string $id)
    {
        $states = Produto::findOrFail($id);

        // $lotes = Lote::where('produto_id', $states->id)
        //     ->where('status', 'activo')
        //     ->get();

        $lote = Lote::where('produto_id', $states->id)
            // ->where('status', 'activo')
        ->first();

        // $option = "<option value=''>Selecione o Lote</option>";
        // foreach ($lotes as $state) {
        //     $option = '<option value="' . $state->id . '"  data-nome="' . $state->lote . '-' . $state->codigo_barra . '" >' . $state->lote . '-' . $state->codigo_barra . '<option>';
        // }
        return ["data" => $lote, "produto" => $states, "quantidade_actual" => $states->total_produto_loja_activa()];
    }
    
    public function etiqueta(string $id)
    {
        $produto = Produto::findOrFail($id);

        return view('dashboard.produtos.etiqueta', compact('produto'));
    
    }
    
    
}
