<?php

namespace App\Http\Controllers;

use App\Models\ClienteContrato;
use App\Models\ContratoPosto;
use App\Models\Entidade;
use App\Models\Equipa;
use App\Models\EquipamentoActivo;
use App\Models\Ocorrencia;
use App\Models\TipoOcorrencia;
use App\Models\TipoPosto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class OcorrenciaController extends Controller
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
 
        $ocorrencias = Ocorrencia::with(["posto", "tipo_ocorrencia", "registrado_por", "user","entidade"])->where("entidade_id", $entidade->empresa->id)
            ->orderBy("created_at", "desc")
        ->get();
        
        $equipas = Equipa::where("entidade_id", $entidade->empresa->id)->get();
        
        $empresa = Entidade::with(["variacoes", "clientes", "marcas","categorias"])->findOrFail($entidade->empresa->id);
      
        $head = [
            "titulo" => "Ocorrências",
            "descricao" => env("APP_NAME"),
            "ocorrencias" => $ocorrencias,
            "empresa" => $empresa,
            "equipas" => $equipas,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.clientes.contratos.ocorrencias.index", $head);
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
        $tipos_ocorrencias = TipoOcorrencia::where('entidade_id', $entidade->empresa->id)->get();
        $postos = ContratoPosto::where('entidade_id', $entidade->empresa->id)->get();
        $posto = ContratoPosto::find($request->posto_id);
        
        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "user" => Auth::user(),
            "tipos_ocorrencias" => $tipos_ocorrencias,
            "posto" => $posto,
            "postos" => $postos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.contratos.ocorrencias.create', $head);
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
            'posto_id' => 'required|string',
            'tipo_ocorrencia_id' => 'required|string',
            'data_at' => 'required|string',
            'hora_at' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
            
            $ocorrencias = Ocorrencia::where("entidade_id", $entidade->empresa->id)->count() + 1;

            Ocorrencia::create([
                'posto_id' => $request->posto_id,
                'tipo_ocorrencia_id' => $request->tipo_ocorrencia_id,
                'data_at' => $request->data_at,
                'descricao' => $request->descricao,
                'hora_at' => $request->hora_at,
                'numero' => date("y") . "" . date("m") . "" . date("d") . "/". $ocorrencias,
                'entidade_id' => $entidade->empresa->id,
                'user_id' => Auth::id(),
                'registrado_por_id' => Auth::id(),
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

        $ocorrencia = Ocorrencia::findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", 'clientes', "marcas", "categorias"])->findOrFail($entidade->empresa->id);

        $tipos_ocorrencias = TipoOcorrencia::where('entidade_id', $entidade->empresa->id)->get();
        $postos = ContratoPosto::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => __('messages.editar'),
            "tipos_ocorrencias" => $tipos_ocorrencias,
            "postos" => $postos,
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "ocorrencia" => $ocorrencia,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.contratos.ocorrencias.edit', $head);
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
   
        $ocorrencia = Ocorrencia::with(["posto", "tipo_ocorrencia", "registrado_por"])->findOrFail($id);
        
        $head = [
            "titulo" =>  __('messages.mais_detalhes'),
            "descricao" => env("APP_NAME"),
            "ocorrencia" => $ocorrencia,
            "loja" => User::with(["empresa"])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.clientes.contratos.ocorrencias.show", $head);
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
            'posto_id' => 'required|string',
            'tipo_ocorrencia_id' => 'required|string',
            'data_at' => 'required|string',
            'hora_at' => 'required|string',
        ]);

        $ocorrencia = Ocorrencia::findOrFail($id);
            
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $ocorrencia->posto_id = $request->posto_id;
            $ocorrencia->tipo_ocorrencia_id = $request->tipo_ocorrencia_id;
            $ocorrencia->data_at = $request->data_at;
            $ocorrencia->hora_at = $request->hora_at;
            $ocorrencia->descricao = $request->descricao;
            $ocorrencia->update();

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

            $posto = Ocorrencia::findOrFail($id);
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
