<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Distrito;
use App\Models\Estoque;
use App\Models\ItensTransferencia;
use App\Models\Loja;
use App\Models\LojaProduto;
use App\Models\Lote;
use App\Models\Municipio;
use App\Models\Produto;
use App\Models\Provincia;
use App\Models\Registro;
use App\Models\Reserva;
use App\Models\TipoEntidade;
use App\Models\User;
use App\Models\UserLoja;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class LojaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar loja/armazem')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::with(["provincia", "municipio", "distrito",  "ramo"])
            ->where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->with(['caixas', 'bancos'])
            ->orderBy('created_at', 'desc')
        ->get();

        $head = [
            "titulo" => "Lojas",
            "descricao" => env('APP_NAME'),
            "lojas" => $lojas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.lojas.index', $head);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gestao_lojas_armazem()
    {
        $user = auth()->user();
        if (!$user->can('gestao loja/armazem')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::with(['caixas', 'produtos_estoques'])
            ->whereIn("id", $minhas_lojas)
            ->where("entidade_id", $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => "Lojas",
            "descricao" => env('APP_NAME'),
            "lojas" => $lojas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.lojas.gestao-lojas', $head);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gestao_lojas_armazem_detalhe($id)
    {
        //
        $user = auth()->user();

        if (!$user->can('gestao loja/armazem')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $loja = Loja::findOrFail($id);

        $estoques = Estoque::where('entidade_id', $entidade->empresa->id)
            ->where('loja_id', $loja->id)
            ->with(['produto'])
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" =>  __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "loja" => $loja,
            "estoques" => $estoques,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.lojas.gestao-lojas-detalhe', $head);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transferencia_lojas_armazem()
    {
        $user = auth()->user();

        if (!$user->can('gestao loja/armazem')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $items = ItensTransferencia::where('user_id', '=', Auth::user()->id)
            ->where('status', '=', 'em processo')
            ->where('code', '=', NULL)
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->with('produto')
            ->get();


        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->with(['caixas', 'produtos_estoques'])
            ->get();

        $produtos = Produto::whereIn("id", $meus_produtos)->where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('nome', 'asc')
            ->get();

        $head = [
            "titulo" => "Transferências de Produtos Lojas/Armazém",
            "descricao" => env('APP_NAME'),
            "lojas" => $lojas,
            "produtos" => $produtos,
            "items" => $items,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.lojas.transferencia-lojas', $head);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transferencia_lojas_armazem_remover_item($id)
    {
        $user = auth()->user();

        if (!$user->can('gestao loja/armazem')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $transferencia = ItensTransferencia::findOrFail($id);

        if ($transferencia->delete()) {
            return redirect()->back();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transferencia_lojas_armazem_item($id)
    {
        //
        $user = auth()->user();

        if (!$user->can('gestao loja/armazem')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $produto = Produto::findOrFail($id);
        $verificar = ItensTransferencia::where([
            'produto_id' => $produto->id,
            'user_id' => Auth::user()->id,
            'data_emissao' => date('Y-m-d'),
            'status' => 'em processo',
            'code' =>  NULL,
            'entidade_id' => $entidade->empresa->id,
        ])->first();

        if ($verificar) {
            Alert::error("Erro", "Este produto Já foi Adicionar... Pode alterar a quantidade");
            return redirect()->back();
        }

        $items = ItensTransferencia::create([
            'code' => NULL,
            'produto_id' => $produto->id,
            'armazem_origem_id' => NULL,
            'armazem_destino_id' => NULL,
            'quantidade' => 0,
            'quantidade_anterior' => 0,
            'status' => 'em processo',
            'user_id' => Auth::user()->id,
            'data_emissao' => date('Y-m-d'),
            'entidade_id' => $entidade->empresa->id,
        ]);

        if ($items->save()) {
            return redirect()->back();
        } else {
            Alert::error("Erro", "Ocorreu um erro ao tentar adicionar este produto");
            return redirect()->back();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transferencia_lojas_armazem_store(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('gestao loja/armazem')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            "loja_origem_id" => 'required',
            "loja_destino_id" => 'required',
        ]);

        try {
            // Inicia a transação
            DB::beginTransaction();

            if ($request->loja_origem_id == $request->loja_destino_id) {
                return redirect()->back()->with("warning", "Não podes fazer a transferência de Stocks na mesma Loja!");
            }

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            foreach ($request->ids as $item) {

                $actualizar_a_quantidade = ItensTransferencia::findOrFail($item);

                $estoque_origem = Estoque::where('loja_id', $request->loja_origem_id)
                    ->where('produto_id', $actualizar_a_quantidade->produto_id)
                    ->where('entidade_id', $entidade->empresa->id)
                    ->first();

                $estoque_destino = Estoque::where('loja_id', $request->loja_destino_id)
                    ->where('produto_id', $actualizar_a_quantidade->produto_id)
                    ->where('entidade_id', $entidade->empresa->id)
                    ->first();

                if ($estoque_origem) {
                    $estoque_origem_update = Estoque::findOrFail($estoque_origem->id);
                    $estoque_origem_update->stock = $estoque_origem_update->stock - $request->input("quantidade{$item}");

                    $estoque_origem_update->update();

                    Registro::create([
                        "registro" => "Saída de Stock",
                        'tipo' => 'S',
                        'status' => 'T',
                        "data_registro" => date('Y-m-d'),
                        "quantidade" => $request->input("quantidade{$item}"),
                        "produto_id" => $estoque_origem_update->produto_id,
                        "observacao" => "Transferência de Stocks do Armazém - Saída",
                        "loja_id" => $estoque_origem_update->loja_id,
                        "lote_id" => $estoque_origem_update->lote_id,
                        "user_id" => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                } else {

                    $estoque_origem_dados = Estoque::findOrFail($request->loja_origem_id);
                    $estoque_destino_dados = Estoque::findOrFail($request->loja_destino_id);

                    $verificarEstoque_ = Estoque::where("entidade_id", $entidade->empresa->id)
                        ->where("produto_id", $actualizar_a_quantidade->produto_id)
                        ->where("loja_id", $request->loja_origem_id)
                        ->first();

                    if ($verificarEstoque_) {
                        $update = Estoque::findOrFail($verificarEstoque_->id);
                        $update->stock = $update->stock + $request->input("quantidade{$item}");
                        $update->update();
                    } else {
                        Estoque::create([
                            "loja_id" => $request->loja_origem_id,
                            "lote_id" => NULL,
                            "produto_id" => $actualizar_a_quantidade->produto_id,
                            "user_id" => Auth::user()->id,
                            "data_operacao" => date('Y-m-d'),
                            "stock" => $request->input("quantidade{$item}"),
                            "operacao" => 'Transferenca de Stock',
                            "observacao" => "Transferência de Stocks do Armazém: {$estoque_origem_dados->nome} para armazém: {$estoque_destino_dados->nome}",
                            'entidade_id' => $entidade->empresa->id,
                        ]);
                    }

                    Registro::create([
                        "registro" => "Saída de Stock",
                        'tipo' => 'S',
                        'status' => 'T',
                        "data_registro" => date('Y-m-d'),
                        "quantidade" => $request->input("quantidade{$item}"),
                        "produto_id" => $actualizar_a_quantidade->produto_id,
                        "observacao" => "Transferência de Stocks do Armazém - Saída",
                        "loja_id" => $estoque_origem_dados->loja_id,
                        "lote_id" => $estoque_origem_dados->lote_id,
                        "user_id" => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }

                if ($estoque_destino) {
                    $estoque_destino_update = Estoque::findOrFail($estoque_destino->id);
                    $estoque_destino_update->stock = $estoque_destino_update->stock + $request->input("quantidade{$item}");

                    $estoque_destino_update->update();

                    Registro::create([
                        "registro" => "Entrada de Stock",
                        'tipo' => 'E',
                        'status' => 'T',
                        "data_registro" => date('Y-m-d'),
                        "quantidade" => $request->input("quantidade{$item}"),
                        "produto_id" => $estoque_destino_update->produto_id,
                        "observacao" => "Transferência de Stocks do Armazém - Entrada",
                        "loja_id" => $estoque_destino_update->loja_id,
                        "lote_id" => $estoque_destino_update->lote_id,
                        "user_id" => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                } else {

                    $estoque_origem_dados = Estoque::findOrFail($request->loja_origem_id);
                    $estoque_destino_dados = Estoque::findOrFail($request->loja_destino_id);

                    $verificarEstoque_ = Estoque::where("entidade_id", $entidade->empresa->id)
                        ->where("produto_id", $actualizar_a_quantidade->produto_id)
                        ->where("loja_id", $request->loja_destino_id)
                        ->first();

                    if ($verificarEstoque_) {
                        $update = Estoque::findOrFail($verificarEstoque_->id);
                        $update->stock = $update->stock + $request->input("quantidade{$item}");
                        $update->update();
                    } else {
                        Estoque::create([
                            "loja_id" => $request->loja_destino_id,
                            "lote_id" => NULL,
                            "produto_id" => $actualizar_a_quantidade->produto_id,
                            "user_id" => Auth::user()->id,
                            "data_operacao" => date('Y-m-d'),
                            "stock" => $request->input("quantidade{$item}"),
                            "operacao" => 'Transferenca de Stock',
                            "observacao" => "Transferência de Stocks do Armazém: {$estoque_origem_dados->nome} para armazém: {$estoque_destino_dados->nome}",
                            'entidade_id' => $entidade->empresa->id,
                        ]);
                    }

                    Registro::create([
                        "registro" => "Saída de Stock",
                        'tipo' => 'S',
                        'status' => 'T',
                        "data_registro" => date('Y-m-d'),
                        "quantidade" => $request->input("quantidade{$item}"),
                        "produto_id" => $actualizar_a_quantidade->produto_id,
                        "observacao" => "Transferência de Stocks do Armazém - Saída",
                        "loja_id" => $estoque_origem_dados->loja_id,
                        "lote_id" => $estoque_origem_dados->lote_id,
                        "user_id" => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }

                $actualizar_a_quantidade->armazem_destino_id = $request->loja_destino_id;
                $actualizar_a_quantidade->armazem_origem_id = $request->loja_origem_id;
                $actualizar_a_quantidade->quantidade = $request->input("quantidade{$item}");
                $actualizar_a_quantidade->status = 'realizada';

                $actualizar_a_quantidade->update();
            }

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

        return redirect()->back()->with("success", "Produto transferido com sucesso!");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar loja/armazem')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $provincias = Provincia::get();
        $municipios = Municipio::get();
        $distritos = Distrito::get();
        $ramos = TipoEntidade::where('status', 'activo')->get();

        $head = [
            "titulo" => __('messages.novo'),
            "provincias" => $provincias,
            "municipios" => $municipios,
            "distritos" => $distritos,
            "ramos" => $ramos,
            "descricao" => env('APP_NAME'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.lojas.create', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar loja/armazem')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $request->validate([
            'nome' => 'required|string',
            'status' => 'required|string',
            'logotipo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        if ($request->hasFile('logotipo') && $request->file('logotipo')->isValid()) {
            $image = $request->file('logotipo');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images/empresa'), $imageName);
        } else {
            $imageName = $request->logotipo;
        }
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $loja = Loja::create([
                'nome' => $request->nome,
                'status' => 'desactivo',
                'codigo_postal' => $request->codigo_postal,
                'morada' => $request->morada,
                'nif' => $request->nif ?? "",
                'logotipo' => $imageName ?? "",
                'telefone' => $request->telefone,
                'modelo_factura' => $request->modelo_factura,
                'ramo_actividade_id' => $request->ramo_actividade_id,
                'provincia_id' => $request->provincia_id,
                'municipio_id' => $request->municipio_id,
                'distrito_id' => $request->distrito_id,
                'email' => $request->email,
                'cae' => $request->cae,
                'descricao' => $request->descricao,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);

            UserLoja::create([
                "usuario_id" => Auth::user()->id,
                "loja_id" => $loja->id,
                "status" => 1,
                "user_id" => Auth::user()->id,
                "entidade_id" => $entidade->empresa->id,
            ]);

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados salvos com sucesso!"], 200);
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

        if (!$user->can('listar todos') && !$user->can('listar loja/armazem')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $loja = Loja::with(['caixas', 'bancos', "ramo"])->findOrFail($id);
        $entidade = User::with(["empresa.lojas"])->findOrFail(Auth::user()->id);

        $vendas = Venda::select(
            DB::raw("SUM(valor_total) as total_vendas"),
            DB::raw("SUM(quantidade) as total_quantidade")
        )
        ->where("entidade_id", $entidade->empresa->id)
            ->where("loja_id", $loja->id)
            ->whereIn("status_factura", ["pago"])
        ->first();

        $total_estoque_activo = Lote::join("registros", "lotes_validade_produtos.id", "=", "registros.lote_id")
            ->where("registros.entidade_id", $entidade->empresa->id)
            ->where("registros.loja_id", $loja->id)
            ->where("lotes_validade_produtos.status", "activo")
            ->selectRaw("SUM(CASE WHEN registros.tipo = 'E' THEN registros.quantidade ELSE 0 END) - SUM(CASE WHEN registros.tipo = 'S' THEN registros.quantidade ELSE 0 END) as total_estoque")
        ->first();

        $total_estoque_expirado = Lote::join("registros", "lotes_validade_produtos.id", "=", "registros.lote_id")
            ->where("lotes_validade_produtos.status", "expirado")
            ->where("registros.loja_id", $loja->id)
            ->where("registros.entidade_id", $entidade->empresa->id)
            ->selectRaw("SUM(CASE WHEN registros.tipo = 'E' THEN registros.quantidade ELSE 0 END) - SUM(CASE WHEN registros.tipo = 'S' THEN registros.quantidade ELSE 0 END) as total_estoque")
        ->first();
        
        
        $hoje = Carbon::today()->toDateString(); // formato YYYY-MM-DD
        
        $totalReservas = Reserva::where("entidade_id", $entidade->empresa->id)->count();

        $totalReservasCheckOut = Reserva::where("data_final", "=", date("Y-m-d"))
            ->whereIn("status", ["SUCESSO", "EM USO"])
            ->where("loja_id", $loja->id)
            ->where("entidade_id", $entidade->empresa->id)
            ->count();

        $totalReservasCheckIn = Reserva::whereIn("status", ["PENDENTE", "EM USO"])
            ->where("data_inicio", "=", date("Y-m-d"))
            ->where("loja_id", $loja->id)
            ->where("entidade_id", $entidade->empresa->id)
            ->count();
        $totalReservasFeitasHoje = Reserva::whereDate("created_at", $hoje)
            ->where("loja_id", $loja->id)
            ->where("entidade_id", $entidade->empresa->id)
            ->count();
            
        $reservasEmUso = Reserva::where("status", "EM USO")
            ->where("entidade_id", $entidade->empresa->id)
            ->where("loja_id", $loja->id)
            ->count();

        $head = [
            "titulo" => "Mais detalhes da Loja",
            "descricao" => env('APP_NAME'),
            "vendas" => $vendas,
            "totalReservas" => $totalReservas,
            "totalReservasCheckOut" => $totalReservasCheckOut,
            "totalReservasCheckIn" => $totalReservasCheckIn,
            "totalReservasFeitasHoje" => $totalReservasFeitasHoje,
            "reservasEmUso" => $reservasEmUso,
            "total_estoque_activo" => $total_estoque_activo->total_estoque,
            "total_estoque_expirado" => $total_estoque_expirado->total_estoque,
            "loja" => $loja,
            "empresa_logada" => User::with(["empresa.lojas", "empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.lojas.show', $head);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function mudar_status($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar loja/armazem')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $loja = Loja::findOrFail($id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            if ($loja->status == "desactivo") {
                $loja->status = 'activo';
            } else {
                $loja->status = 'desactivo';
            }

            $loja->update();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados actualizados com sucesso!"], 200);
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

        if (!$user->can('editar todos') && !$user->can('editar loja/armazem')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $loja = Loja::findOrFail($id);

        $provincias = Provincia::get();
        $municipios = Municipio::get();
        $distritos = Distrito::get();
        $ramos = TipoEntidade::where('status', 'activo')->get();

        $head = [
            "titulo" => "Loja",
            "provincias" => $provincias,
            "municipios" => $municipios,
            "ramos" => $ramos,
            "distritos" => $distritos,
            "descricao" => env('APP_NAME'),
            "loja" => $loja,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.lojas.edit', $head);
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
        $request->validate([
            'nome' => 'required|string',
            'status' => 'required|string',
            'logotipo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);
        
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar loja/armazem')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
       try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $loja = Loja::findOrFail($id);
    
            if ($request->hasFile('logotipo') && $request->file('logotipo')->isValid()) {
                $image = $request->file('logotipo');
                $imageName = time() . '.' . $image->extension();
                $image->move(public_path('images/empresa'), $imageName);
            } else {
                $imageName = $loja->logotipo;
            }

            $loja->nome = $request->nome;
            $loja->status = 'desactivo';
            $loja->codigo_postal = $request->codigo_postal;
            $loja->morada = $request->morada;
            $loja->nif = $request->nif ?? "";
            $loja->logotipo = $imageName ?? "";
            $loja->telefone = $request->telefone;
            $loja->modelo_factura = $request->modelo_factura;
            
            $loja->ramo_actividade_id = $request->ramo_actividade_id;
            $loja->provincia_id = $request->provincia_id;
            $loja->municipio_id = $request->municipio_id;
            $loja->distrito_id = $request->distrito_id;
            
            $loja->email = $request->email;
            $loja->cae = $request->cae;
            $loja->descricao = $request->descricao;
            $loja->user_id = Auth::user()->id;
            $loja->entidade_id = $entidade->empresa->id;
            
            $loja->save();
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados actualizados com sucesso!"], 200);
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

        if (!$user->can('eliminar todos') && !$user->can('eliminar loja/armazem')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            Loja::findOrFail($id)->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Excluídos com sucesso!"], 200);
    }
}
