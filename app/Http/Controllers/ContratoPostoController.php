<?php

namespace App\Http\Controllers;

use App\Models\ClienteContrato;
use App\Models\ContratoPosto;
use App\Models\Entidade;
use App\Models\Equipa;
use App\Models\EquipamentoActivo;
use App\Models\TipoPosto;
use App\Models\User;
use Illuminate\Http\Request;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ContratoPostoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);
 
        $postos = ContratoPosto::with(["contrato", "tipo_posto", "equipa.responsavel", "equipa.membros.profissional.horarios"])->where("entidade_id", $entidade->empresa->id)
            ->orderBy("created_at", "desc")
        ->get();
        
        $equipas = Equipa::where("entidade_id", $entidade->empresa->id)->get();
        
        $empresa = Entidade::with(["variacoes", "clientes", "marcas","categorias"])->findOrFail($entidade->empresa->id);
      
        $head = [
            "titulo" => "Postos de Vigilia",
            "descricao" => env("APP_NAME"),
            "postos" => $postos,
            "empresa" => $empresa,
            "equipas" => $equipas,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.clientes.contratos.postos.index", $head);
    }
 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar cliente')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "marcas", "categorias"])->findOrFail($entidade->empresa->id);
        $tipos_postos = TipoPosto::where('entidade_id', $entidade->empresa->id)->get();
        $contratos = ClienteContrato::where('entidade_id', $entidade->empresa->id)->get();
        $contrato = TipoPosto::find($request->contrato_id);
        $equipas = Equipa::where("entidade_id", $entidade->empresa->id)->get();

        
        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "user" => Auth::user(),
            "equipas" => $equipas,
            "tipos_postos" => $tipos_postos,
            "contrato" => $contrato,
            "contratos" => $contratos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.contratos.postos.create', $head);
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

        if (!$user->can('criar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'contrato_id' => 'required|string',
            'tipo_posto_id' => 'required|string',
            'nome' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            ContratoPosto::create([
                'nome' => $request->nome,
                'contrato_id' => $request->contrato_id,
                'equipa_id' => $request->equipa_id,
                'endereco' => $request->endereco,
                'uso_armas' => $request->uso_armas,
                'contacto_posto' => $request->contacto_posto,
                'representante_posto' => $request->representante_posto,
                'instrucoes_especiais' => $request->instrucoes_especiais,
                'horario_permitido' => $request->horario_permitido,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                // 'coordenadas' => new Point($request->latitude, $request->longitude), // latitude, longitude
                'tipo_posto_id' => $request->tipo_posto_id,
                'entidade_id' => $entidade->empresa->id,
                'user_id' => Auth::id(),
            ]);

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

        if (!$user->can("editar todos") || !$user->can("editar cliente")) {
            return redirect()->back()->with("danger", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $contrato = ContratoPosto::findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", 'clientes', "marcas", "categorias"])->findOrFail($entidade->empresa->id);

        $tipos_postos = TipoPosto::where('entidade_id', $entidade->empresa->id)->get();
        $equipas = Equipa::where("entidade_id", $entidade->empresa->id)->get();
                
        $head = [
            "titulo" => __('messages.editar'),
            "equipas" => $equipas,
            "descricao" => env('APP_NAME'),
            "tipos_postos" => $tipos_postos,
            "empresa" => $empresa,
            "contrato" => $contrato,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.contratos.postos.edit', $head);
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
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar cliente')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
   
        $posto = ContratoPosto::with(["contrato", "recursos.recurso.conta", "escalas", "tipo_posto", "user", "entidade", "equipa.membros.profissional", "equipa.responsavel"])->findOrFail($id);
        
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", 'clientes', "marcas", "categorias"])->findOrFail($entidade->empresa->id);
        $equipamentos = EquipamentoActivo::where("entidade_id", $entidade->empresa->id)->get();
        
        $head = [
            "titulo" =>  __('messages.mais_detalhes'),
            "descricao" => env("APP_NAME"),
            "empresa" => $empresa,
            "posto" => $posto,
            "equipamentos" => $equipamentos,
            "loja" => User::with(["empresa"])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.clientes.contratos.postos.show", $head);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('criar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'contrato_id' => 'required|string',
            'tipo_posto_id' => 'required|string',
            'nome' => 'required|string',
        ]);

        $contrato = ContratoPosto::findOrFail($id);
            
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $contrato->nome = $request->nome;
            $contrato->contrato_id = $request->contrato_id;
            $contrato->equipa_id = $request->equipa_id;
            $contrato->uso_armas = $request->uso_armas;
            $contrato->endereco = $request->endereco;
            $contrato->contacto_posto = $request->contacto_posto;
            $contrato->representante_posto = $request->representante_posto;
            $contrato->instrucoes_especiais = $request->instrucoes_especiais;
            $contrato->horario_permitido = $request->horario_permitido;
            $contrato->latitude = $request->latitude;
            $contrato->longitude = $request->longitude;
            $contrato->tipo_posto_id = $request->tipo_posto_id;
            $contrato->update();

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function atribuir_equipa(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'equipa_id' => 'required|string',
            'posto_id' => 'required|string',
        ]);

        $contrato = ContratoPosto::findOrFail($request->posto_id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $contrato->equipa_id = $request->equipa_id;
            $contrato->update();

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
        //
        $user = auth()->user();

        if (!$user->can('eliminar todos') && !$user->can('eliminar cliente')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $posto = ContratoPosto::findOrFail($id);
            $posto->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => 'Dados Excluido com sucesso!'], 200);
    }
}
