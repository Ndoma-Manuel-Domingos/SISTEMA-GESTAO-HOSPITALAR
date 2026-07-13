<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\SolicitarInternamento;
use App\Models\Cliente;
use App\Models\Medico;
use App\Models\Prioridade;
use App\Models\TipoAtendimento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

use Ramsey\Uuid\Uuid;

class SolicitarInternamentoController extends Controller
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

        $solicitacoes = SolicitarInternamento::with(["paciente", "prioridade", "medico"])
            ->where("entidade_id", $entidade->entidade_id)
            ->orderBy("id", "desc")
        ->get();
        
        $pacientes = Cliente::where("entidade_id", $entidade->entidade_id)->get();
        $prioridades = Prioridade::where("entidade_id", $entidade->entidade_id)->get();
        $medicos = Medico::with(["funcionario"])->where("entidade_id", $entidade->entidade_id)->get();
        
        $head = [
            "titulo" => "Solicitações de Internamentos",
            "descricao" => env("APP_NAME"),
            "solicitacoes" => $solicitacoes,
            "pacientes" => $pacientes,
            "prioridades" => $prioridades,
            "medicos" => $medicos,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.solicitacoes-internamentos.index", $head);
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
        
        if(!$user->can('criar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
      
        $request->validate([
            'paciente_id' => 'required|string',
            'tipo_internamento' => 'required|string',
            'prioridade_id' => 'required|string',
            'justificativo' => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
            
            // Defina o intervalo de hoje
            $inicioDoDia = Carbon::today();
            $fimDoDia = Carbon::today()->endOfDay();
            
            $solicitacao = SolicitarInternamento::whereBetween('created_at', [$inicioDoDia, $fimDoDia])->where('entidade_id', $entidade->empresa->id)->count();
            $solicitacao = $solicitacao  + 1;
            
            SolicitarInternamento::create([
                'status' => "aguardando",
                'numero' => "SOLIC - {$solicitacao}",
                'paciente_id' => $request->paciente_id,
                'data_at' => $request->data_at,
                'prioridade_id' => $request->prioridade_id,
                'tipo_internamento' => $request->tipo_internamento,
                'medico_id' => $request->medico_id,
                'unidate_desejada' => $request->unidate_desejada,
                'justificativo' => $request->justificativo,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id, 
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
    public function atender_paciente($id, $status)
    {
        $solicitacao = SolicitarInternamento::findOrFail($id);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            if($status == "aceitar"){
                $estado = "atendido";
            }
            if($status == "cancelar"){
                $estado = "cancelada";
            }

            $solicitacao->status = $estado;
            $solicitacao->update();
            
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
       
        return response()->json(['message' => 'Dados Actualizado com sucesso!'], 200);
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
        
        if(!$user->can('editar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $solicitacao = SolicitarInternamento::with(["paciente", "prioridade", "medico"])->findOrFail($id);

        return response()->json(['success' => true, 'data' => $solicitacao], 200);
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
        
        if(!$user->can('editar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            "paciente_id" => "required|string",
            "tipo_internamento" => "required|string",
            "prioridade_id" => "required|string",
            "justificativo" => "required",
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
      
            $atendimento = SolicitarInternamento::findOrFail($id);
            
            $atendimento->paciente_id = $request->paciente_id;
            $atendimento->data_at = $request->data_at;
            $atendimento->prioridade_id = $request->prioridade_id;
            $atendimento->tipo_internamento = $request->tipo_internamento;
            $atendimento->medico_id = $request->medico_id;
            $atendimento->unidate_desejada = $request->unidate_desejada;
            $atendimento->justificativo = $request->justificativo;
            $atendimento->user_id = Auth::user()->id;
    
            $atendimento->update();      
            
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
        
        if(!$user->can('eliminar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
      
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
                        
            $solicitacao = SolicitarInternamento::findOrFail($id);
            $solicitacao->delete();
        
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

}
