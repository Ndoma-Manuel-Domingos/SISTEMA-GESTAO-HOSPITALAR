<?php

namespace App\Http\Controllers;

use App\Models\EncomendaFornecedore;
use App\Models\Entidade;
use App\Models\Estoque;
use App\Models\FacturaEncomendaFornecedor;
use App\Models\Fornecedore;
use App\Models\Imposto;
use App\Models\ItensEncomenda;
use App\Models\ItensRequisicao;
use App\Models\Loja;
use App\Models\LojaProduto;
use App\Models\Motivo;
use App\Models\Produto;
use App\Models\Registro;
use App\Models\RegistroMovimento;
use App\Models\Requisicao;
use App\Models\User;
use App\Models\UserLoja;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;

class RequisacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar requisacao')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $requisicoes = Requisicao::with(['items'])->when($request->tipo_documento, function ($query, $value) {
            $query->where('status', '=', $value);
        })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('created_at', '>=', Carbon::parse($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('created_at', '<=', Carbon::parse($value));
            })
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => "Requisições",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "requisicoes" => $requisicoes,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
            "requests" => $request->all("tipo_documento", "data_inicio", "data_final"),
        ];

        return view('dashboard.requisacoes.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar requisacao')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $items = ItensRequisicao::where('user_id', '=', Auth::user()->id)
            ->where('status', '=', 'em processo')
            ->where('code', '=', NULL)
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->with('produto.taxa_imposto')
            ->get();

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

        $produtos = Produto::whereIn("id", $meus_produtos)
            ->where('status', '=', 'activo')
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $fornecedores = Fornecedore::where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $totalEncomendas = ItensRequisicao::where([
            ['user_id', '=', Auth::user()->id],
            ['status', '!=', 'em processo'],
            ['code', '!=', NULL],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->count();

        $resultado = $totalEncomendas + 1;


        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();


        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produtos" => $produtos,
            "fornecedores" => $fornecedores,
            "items" => $items,
            "motivos" => Motivo::get(),
            "impostos" => Imposto::get(),
            "lojas" => $lojas,
            "totalRequisicao" =>  $resultado . "-" . date('y') . "" . date('m') . "" . date('d') . "/R",
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.requisacoes.create', $head);
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

        if (!$user->can('criar todos') && !$user->can('criar requisacao')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $request->validate(
            ['numero' => 'required'],
            ['numero.required' => 'O número é um campo obrigatório']
        );


        try {
            // Inicia a transação
            DB::beginTransaction();

            foreach ($request->ids as $id) {
                $update = ItensRequisicao::findOrFail($id);
                $update->quantidade = $request->input("quantidade{$id}");
                $update->loja_id = $request->loja_id;
                $update->update();
            }

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);


            $totalQuantidade = ItensRequisicao::where([
                ['user_id', '=', Auth::user()->id],
                ['status', '=', 'em processo'],
                ['code', '=', NULL],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
                ->with(['produto'])
                ->sum('quantidade');

            // dd($request->numero, $request->loja_id);

            $code = uniqid(time());
            $create = Requisicao::create([
                'status' => 'pendente',
                'numero' => $request->numero,
                'loja_id' => $request->loja_id,
                'data_emissao' => date('Y-m-d'),
                'observacao' => $request->observacao,
                'code' => $code,
                'quantidade' => $totalQuantidade,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);

            $items = ItensRequisicao::where([
                ['user_id', '=', Auth::user()->id],
                ['status', '=', 'em processo'],
                ['code', '=', NULL],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
                ->with(['produto'])
                ->get();

            foreach ($items as $value) {
                $update = ItensRequisicao::findOrFail($value->id);
                $update->code = $code;
                $update->requisicao_id = $create->id;
                $update->status = 'pendente';
                $update->update();
            }
            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            Alert::warning('Error', $e->getMessage());
        }

        Alert::success('Sucesso', 'Requisição realizada com sucesso!');
        return redirect()->route('requisacoes.show', $create->id);
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

        if (!$user->can('listar todos') && !$user->can('listar requisacao')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $requisicao = Requisicao::with(['items.produto.taxa_imposto', 'items.produto.categoria', 'items.produto.variacao', 'items.produto.marca', 'aprovador', 'user', 'loja'])->findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $items = ItensRequisicao::where([
            ['code', '=', $requisicao->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->with(['produto'])
            ->get();

        $head = [
            "titulo" => "Visualizar Requisição {$requisicao->numero}",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "requisicao" => $requisicao,
            "items" => $items,
            "loja" => $entidade,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.requisacoes.show', $head);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar requisacao')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        //
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $requisicao = Requisicao::with(['items.produto.taxa_imposto', 'items.produto.categoria', 'items.produto.variacao', 'items.produto.marca', 'aprovador', 'user', 'loja'])->findOrFail($id);

        $items = ItensRequisicao::where([
            ['code', '=', $requisicao->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->with('produto')
            ->with('loja')
            ->get();

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

        $produtos = Produto::whereIn("id", $meus_produtos)->where([
            ['status', '=', 'activo'],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->get();

        $fornecedores = Fornecedore::where("entidade_id", $entidade->empresa->id)->get();


        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();


        $head = [
            "titulo" => "Adicionar Encomenda",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produtos" => $produtos,
            "requisicao" => $requisicao,
            "fornecedores" => $fornecedores,
            "items" => $items,
            "lojas" => $lojas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.requisacoes.edit', $head);
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

        if (!$user->can('editar todos') && !$user->can('editar requisacao')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $requisicao = Requisicao::findOrFail($id);
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        try {
            // Inicia a transação
            DB::beginTransaction();

            foreach ($request->ids as $id) {
                $update = ItensRequisicao::findOrFail($id);
                $update->quantidade = $request->input("quantidade{$id}");
                $update->loja_id = $request->loja_id;
                $update->update();
            }

            $totalQuantidade = ItensRequisicao::where([
                ['code', '=', $requisicao->code],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
                ->with(['produto'])
                ->sum('quantidade');

            $updated = ItensRequisicao::findOrFail($requisicao->id);
            $updated->status = $updated->status;
            $updated->loja_id = $request->loja_id;
            $updated->data_emissao = date('Y-m-d');
            $updated->quantidade = $totalQuantidade;
            $updated->update();

            $items = ItensRequisicao::where([
                ['code', '=', $requisicao->code],
                ['entidade_id', '=', $entidade->empresa->id],
            ])
                ->with(['produto'])
                ->get();

            foreach ($items as $value) {
                $update = ItensRequisicao::findOrFail($value->id);
                $update->status = $updated->status;
                $update->update();
            }


            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            Alert::warning('Error', $e->getMessage());
        }

        Alert::success('Sucesso', 'Requisição Actualizada com sucesso!');
        return redirect()->route('requisacoes.show', $updated->id);
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

        if (!$user->can('elimnar todos') && !$user->can('elimnar requisacao')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $requisicao = Requisicao::findOrFail($id);

            $items = ItensRequisicao::where('code', '=', $requisicao->code)
                ->get();

            if ($items) {
                foreach ($items as $value) {
                    ItensRequisicao::findOrFail($value->id)->delete();
                }
            }

            $requisicao->delete();

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

    public function adicionarProduto($id)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar requisacao')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $produto = Produto::findOrFail($id);

        $verificar = ItensRequisicao::where([
            ['produto_id', '=', $produto->id],
            ['user_id', '=', Auth::user()->id],
            ['data_emissao', '=', date('Y-m-d')],
            ['status', '=',  'em processo'],
            ['code',  NULL],
            ['entidade_id', '=', $entidade->empresa->id],
        ])->first();

        if ($verificar) {
            Alert::error("Erro", "Este produto Já foi Adicionar... Pode alterar a quantidade");
            return redirect()->back();
            // return redirect()->route('fornecedores-encomendas.create');
        }

        $items = ItensRequisicao::create([
            'produto_id' => $produto->id,
            'user_id' => Auth::user()->id,
            'quantidade' => 1,
            'data_emissao' => date('Y-m-d'),
            'status' => 'em processo',
            'code' =>  NULL,
            'entidade_id' => $entidade->empresa->id,
        ]);

        if ($items->save()) {
            return redirect()->back();
        } else {
            Alert::error("Erro", "Ocorreu um erro ao tentar adicionar este produto");
            return redirect()->back();
        }
    }

    public function editarProduto($id, $requisicao)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar requisacao')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $produto = Produto::findOrFail($id);
        $request = Requisicao::findOrFail($requisicao);

        $verificar = ItensRequisicao::where([
            ['produto_id', '=', $produto->id],
            ['code', '=', $request->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])->first();

        if ($verificar) {
            Alert::error("Erro", "Este produto Já foi Adicionar... Pode alterar a quantidade");
            return redirect()->back();
        }

        $items = ItensRequisicao::create([
            'produto_id' => $produto->id,
            'loja_id' => $request->loja_id,
            'user_id' => Auth::user()->id,
            'quantidade' => 1,
            'data_emissao' => date('Y-m-d'),
            'status' => 'em processo',
            'code' =>  $request->code,
            'entidade_id' => $entidade->empresa->id,
        ]);

        if ($items->save()) {
            return redirect()->back();
        } else {
            Alert::error("Erro", "Ocorreu um erro ao tentar adicionar este produto");
            return redirect()->back();
        }
    }

    public function removerProduto($id)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar requisacao')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $delete = ItensRequisicao::findOrFail($id);
        if ($delete->delete()) {
            return redirect()->back();
        }
    }

    public function rascunho($id)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar requisacao')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $requisicao = Requisicao::findOrFail($id);
        $requisicao->status = "rascunho";
        $requisicao->update();

        $items = ItensRequisicao::where('code', '=', $requisicao->code)->get();

        if ($items) {
            foreach ($items as $item) {
                $updated = ItensRequisicao::findOrFail($item->id);
                $updated->status = 'rascunho';
                $updated->update();
            }
        }

        Alert::success('Sucesso', 'Encomenda Entregue com sucesso!');
        return redirect()->route('requisacoes.show', $requisicao->id);
    }

    public function rejeitar($id)
    {
        $user = auth()->user();

        if (!$user->can('rejeitar requisicao')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $requisicao = Requisicao::findOrFail($id);
        $requisicao->status = "rejeitada";
        $requisicao->update();

        $items = ItensRequisicao::where('code', $requisicao->code)->get();

        if ($items) {
            foreach ($items as $item) {
                $updated = ItensRequisicao::findOrFail($item->id);
                $updated->status = 'rejeitada';
                $updated->update();
            }
        }

        Alert::success('Sucesso', 'Requisição Rejeitada com sucesso!');
        return redirect()->route('requisacoes.show', $requisicao->id);
    }

    public function aprovada($id)
    {
        $user = auth()->user();

        if (!$user->can('aprovar requisicao')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $requisicao = Requisicao::findOrFail($id);

        $items = ItensRequisicao::where([
            ['code', '=', $requisicao->code]
        ])->get();

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $requisicao = Requisicao::with(['items.produto.taxa_imposto', 'items.produto.categoria', 'items.produto.variacao', 'items.produto.marca', 'aprovador', 'user', 'loja'])->findOrFail($id);

        $items = ItensRequisicao::where([
            ['code', '=', $requisicao->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->with(['produto.estoque'])
            ->with('loja')
            ->get();



        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        $head = [
            "titulo" => "Receber Ecomenda ou Produto",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "requisicao" => $requisicao,
            "items" => $items,
            "lojas" => $lojas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.requisacoes.receber', $head);
    }

    public function aprovadaStore(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('aprovar requisicao')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $requisicao = Requisicao::findOrFail($request->requisicao_id);

        try {
            // Inicia a transação
            DB::beginTransaction();
            foreach ($request->ids as $id) {
                $update = ItensRequisicao::findOrFail($id);

                $produto = Produto::findOrFail($update->produto_id);
                $loja = Loja::findOrFail($requisicao->loja_id);

                $actualizarEstoque = Estoque::where([
                    ['produto_id', '=', $produto->id],
                    ['loja_id', '=', $loja->id],
                ])->first();

                if ($actualizarEstoque) {
                    $actualizar = Estoque::findOrFail($actualizarEstoque->id);
                    $actualizar->stock = $actualizar->stock - $request->input("quantidade{$id}");
                    $actualizar->update();
                }
                    
                $total_registro = RegistroMovimento::where("entidade_id", $entidade->empresa->id)
                    ->where('tipo_documento', 'L1')
                    ->count() + 1;
    
                $sigla = $request->tipo_documento . "" . date('Y') . "/" . $total_registro;
                
                Registro::create([
                    "documento" => $sigla,
                    "registro" => "Saída de Produtos Requisição",
                    "data_registro" => date('Y-m-d'),
                    'tipo' => 'S',
                    'status' => 'E',
                    "quantidade" => $request->input("quantidade{$id}"),
                    "observacao" => $requisicao->numero,
                    "requisicao_id" => $requisicao->id,
                    "documento_id" => $requisicao->id,
                    "produto_id" => $produto->id,
                    "status" => "L1",
                    "preco_unitario" => $produto->preco_venda,
                    "loja_id" => $requisicao->loja_id,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);
            }

            $requisicao->status = "aprovada";
            $requisicao->update();

            $itemRequisicoes = ItensRequisicao::where('code', '=', $requisicao->code)->get();

            foreach ($itemRequisicoes as $item) {
                $up = ItensRequisicao::findOrFail($item->id);
                $up->status = "aprovada";
                $up->update();
            }

            $requisicao->user_aprovador_id = Auth::user()->id;
            $requisicao->update();

            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            Alert::warning('Error', $e->getMessage());
        }

        return redirect()->route('requisacoes.show', $requisicao->id);
    }

    public function imprimir($code)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar requisacao')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $requisicao = Requisicao::with(['items.produto.taxa_imposto', 'items.produto.categoria', 'items.produto.variacao', 'items.produto.marca', 'aprovador', 'user', 'loja'])->findOrFail($code);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $items = ItensRequisicao::where([
            ['code', '=', $requisicao->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->with(['produto'])
            ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Factura Pro-forma",
            "descricao" => env('APP_NAME'),
            "requisicao" => $requisicao,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "empresa" => $empresa,
            "items" => $items,

            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.requisacoes.imprimir', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function imprimir_colectiva(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar requisacao')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $requisicoes = Requisicao::with(['items'])->when($request->tipo_documento, function ($query, $value) {
            $query->where('status', '=', $value);
        })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('created_at', '>=', Carbon::parse($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('created_at', '<=', Carbon::parse($value));
            })
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Requisições",
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "requisicoes" => $requisicoes,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
            "requests" => $request->all("tipo_documento", "data_inicio", "data_final"),
        ];

        $pdf = PDF::loadView('dashboard.requisacoes.imprimir-colectiva', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function imprimir_individual($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar requisacao')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $requisicao = Requisicao::with(['items.produto.taxa_imposto', 'items.produto.categoria', 'items.produto.variacao', 'items.produto.marca', 'aprovador', 'user', 'loja'])->findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $items = ItensRequisicao::where([
            ['code', '=', $requisicao->code],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->with(['produto'])
            ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Requisição: {$requisicao->numero}",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "requisicao" => $requisicao,
            "items" => $items,
            "loja" => $entidade,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.requisacoes.imprimir-individual', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
}
