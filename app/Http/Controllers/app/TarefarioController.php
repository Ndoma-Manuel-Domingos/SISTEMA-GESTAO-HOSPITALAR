<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Conta;
use App\Models\Imposto;
use App\Models\Marca;
use App\Models\Motivo;
use App\Models\Produto;
use App\Models\Quarto;
use App\Models\QuartoTarefario;
use App\Models\Subconta;
use App\Models\User;
use App\Models\Variacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class TarefarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar tarefario')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $tarefarios = Produto::with(['tarefarios.quarto'])->where("entidade_id", $entidade->empresa->id)
            ->where('aplicado', 'Y')
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => "Tarifários",
            "descricao" => env('APP_NAME'),
            "tarefarios" => $tarefarios,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.tarefarios.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar tarefario')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $quartos = Quarto::with(['tipo','andar'])->where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "motivos" => Motivo::get(),
            "impostos" => Imposto::get(),
            "quartos" => $quartos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.tarefarios.create', $head);
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

        if (!$user->can('criar todos') && !$user->can('criar tarefario')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            "valor" => "required|string",
            "modo_tarefario" => "required|string",
            "tipo_cobranca" => "required|string",
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $code = uniqid(time());
            $nova_conta = "";
            $conta = Conta::where("conta", "62")->where("entidade_id", $entidade->empresa->id)->first();
            $serie = "62.1.1";


            $subc_ = Subconta::where("numero", "like", $serie . "%")->where("entidade_id", $entidade->empresa->id)->count();
            $numero =  $subc_ + 1;

            $nova_conta = $serie . "." . $numero;

            $subconta = Subconta::create([
                "entidade_id" => $entidade->empresa->id,
                "numero" => $nova_conta,
                "nome" => $request->nome,
                "tipo_conta" => "M",
                "code" => $code,
                "status" => $conta->status,
                "conta_id" => $conta->id,
                "user_id" => Auth::user()->id,
            ]);

            $motivo = Motivo::findOrFail($request->motivo_isencao ?? $entidade->empresa->motivo_id);
            $imposto = Imposto::findOrFail($request->imposto ?? $entidade->empresa->imposto_id);

            $categoria = Categoria::where("entidade_id", $entidade->empresa->id)->where("categoria", "-- Sem Categoria --")->first();
            $marca = Marca::where("entidade_id", $entidade->empresa->id)->where("nome", "-- Sem Marca --")->first();
            $variacao = Variacao::where("entidade_id", $entidade->empresa->id)->where("nome", "-- Sem Variação --")->first();

            $tarefario = Produto::create([
                "nome" => $request->nome,
                "codigo_barra" => time(),
                "referencia" => time(),
                "conta" => $nova_conta,
                "code" => $code,

                "modo_tarefario" => $request->modo_tarefario,
                "tipo_cobranca" => $request->tipo_cobranca,

                "descricao" => $request->nome,
                "variacao_id" => $variacao->id ?? NULL,
                "categoria_id" => $categoria->id ?? NULL,
                "marca_id" => $marca->id ?? NULL,

                "imposto_id" => $imposto->id,
                "tipo" => "S",
                "unidade" => "uni",
                "imposto" => $imposto->codigo,
                "taxa" => $imposto->valor,
                "motivo_isencao" => $motivo->codigo,
                "motivo_id" => $motivo->id,
                "preco_custo" => $request->valor,
                "preco" => $request->valor,
                "margem" => 0,
                "preco_venda" => $request->valor_com_iva,
                "controlo_stock" => "Não",
                "tipo_stock" => "M",
                "aplicado" => "Y",
                "disponibilidade" => NULL,
                "status" => $request->status ?? "activo",
                "subconta_id" => $subconta->id ?? 1,
                "user_id" => Auth::user()->id,
                "entidade_id" =>  $entidade->empresa->id,
            ]);

            if (!empty($request->quarto_id) && isset($request->quarto_id)) {
                foreach ($request->quarto_id as $key) {
                    $verificar = QuartoTarefario::where('quarto_id', $key)->where('tarefario_id', $tarefario->id)->first();
                    if (!$verificar) {
                        QuartoTarefario::create([
                            'quarto_id' => $key,
                            'tarefario_id' => $tarefario->id,
                            'entidade_id' => $entidade->empresa->id,
                            'user_id' => Auth::user()->id,
                        ]);
                    }
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

        return response()->json(['message' => "Dados salvos com sucesso", 'success' => true]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function associar($id)
    {
        //
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar tarefario')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $quartos = Quarto::where('entidade_id', $entidade->empresa->id)->get();

        $tarefario = Produto::with(['tarefarios.quarto'])->findOrFail($id);

        $head = [
            "titulo" => "Associar Quartos",
            "descricao" => env('APP_NAME'),
            "tarefario" => $tarefario,
            "quartos" => $quartos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.tarefarios.associar-quartos', $head);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function associar_store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar tarefario')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'tarefario_id' => 'required|string',
            'quarto_id' => 'required|array', // Garante que quarto_id é um array
            'quarto_id.*' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $tarefario = Produto::with(['tarefarios.quarto'])->findOrFail($request->tarefario_id);

            if (!empty($request->quarto_id) && isset($request->quarto_id)) {
                foreach ($request->quarto_id as $key) {
                    $verificar = QuartoTarefario::where('quarto_id', $key)->where('tarefario_id', $tarefario->id)->first();
                    if (!$verificar) {
                        QuartoTarefario::create([
                            'quarto_id' => $key,
                            'tarefario_id' => $tarefario->id,
                            'entidade_id' => $entidade->empresa->id,
                            'user_id' => Auth::user()->id,
                        ]);
                    }
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

        return response()->json(['message' => "Dados salvos com sucesso", 'success' => true]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function desassociar($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar tarefario')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $tarefario = QuartoTarefario::findOrFail($id);
            $tarefario->delete();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }


        return response()->json(['message' => "Dados salvos com sucesso", 'success' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activar($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar tarefario')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $tarefario = Produto::findOrFail($id);
        $tarefario->status = 'activo';
        $tarefario->update();

        return redirect()->back()->with("success", "tarefario activado com sucesso!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function desactivar($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar tarefario')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $tarefario = Produto::findOrFail($id);
        $tarefario->status = 'desactivo';
        $tarefario->update();

        return redirect()->back()->with("success", "tarefario desactivado com sucesso!!");
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

        if (!$user->can('listar todos') && !$user->can('listar tarefario')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $tarefario = Produto::with(['tarefarios.quarto'])->findOrFail($id);

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "tarefario" => $tarefario,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.tarefarios.show', $head);
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

        if (!$user->can('editar todos') && !$user->can('editar tarefario')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $tarefario = Produto::findOrFail($id);

        $head = [
            "titulo" => __('messages.editar'),
            "descricao" => env('APP_NAME'),
            "motivos" => Motivo::get(),
            "impostos" => Imposto::get(),
            "tarefario" => $tarefario,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.tarefarios.edit', $head);
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
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $user = auth()->user();

            if (!$user->can('editar todos') && !$user->can('editar tarefario')) {
                
                return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            }

            $request->validate([
                'valor' => 'required|string',
                'modo_tarefario' => 'required|string',
                'tipo_cobranca' => 'required|string',
            ]);

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $tarefario = Produto::findOrFail($id);

            $subconta = Subconta::findOrFail($tarefario->subconta_id);
            $subconta->nome = $request->nome;
            $subconta->update();

            $motivo = Motivo::findOrFail($request->motivo_isencao ?? $entidade->empresa->motivo_id);
            $imposto = Imposto::findOrFail($request->imposto ?? $entidade->empresa->imposto_id);

            $tarefario->nome = $request->nome;
            $tarefario->modo_tarefario = $request->modo_tarefario;
            $tarefario->tipo_cobranca = $request->tipo_cobranca;
            $tarefario->descricao = $request->nome;
            $tarefario->imposto_id = $imposto->id;
            $tarefario->imposto = $imposto->codigo;
            $tarefario->taxa = $imposto->valor;
            $tarefario->motivo_isencao = $motivo->codigo;
            $tarefario->motivo_id = $motivo->id;
            $tarefario->preco_custo = $request->valor;
            $tarefario->preco = $request->valor;
            $tarefario->preco_venda = $request->valor_com_iva;
            $tarefario->status = $request->status ?? "activo";

            $tarefario->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => "Dados salvos com sucesso", 'success' => true]);
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

        if (!$user->can('eliminar todos') && !$user->can('eliminar tarefario')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $tarefario = Produto::findOrFail($id);
            $tarefario->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => "Dados excluído com sucesso", 'success' => true]);
    }
}
