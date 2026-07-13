<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\AnoLectivo;
use App\Models\Cliente;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Sala;
use App\Models\Turno;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;

class AlunoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function matriculas()
    {
        //
        $user = auth()->user();
        
        if(!$user->can('listar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        
        $matriculas = Matricula::with(['aluno', 'curso', 'sala', 'turno', "ano_lectivo"])
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "matriculas" => $matriculas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.alunos.matriculas', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function matriculas_create(Request $request, $id)
    {
    
        $user = auth()->user();
        
        if(!$user->can('criar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
       
        
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        
        $aluno = Cliente::findOrFail($id);
        
        $cursos = Curso::where('entidade_id', '=', $entidade->empresa->id)->get();
        $turnos = Turno::where('entidade_id', '=', $entidade->empresa->id)->get();
        $salas = Sala::where('entidade_id', '=', $entidade->empresa->id)->get();
        $anos_lectivos = AnoLectivo::where('entidade_id', '=', $entidade->empresa->id)->get();
        $roles = Role::get();

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "user" => Auth::user(),
            "cursos" => $cursos,
            "turnos" => $turnos,
            "roles" => $roles,
            "salas" => $salas,
            "anos_lectivos" => $anos_lectivos,
            "aluno" => $aluno,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.alunos.create-matricula', $head);
    }
    
        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function matriculas_post(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('criar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'curso_id' => 'required',
            'sala_id' => 'required',
            'turno_id' => 'required',
            'ano_lectivo_id' => 'required',
            'aluno_id' => 'required',
        ]);

   
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $codigo = time();
        
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
            
            $verificar_matricula = Matricula::where('status', $request->status)
            ->where('aluno_id', $request->aluno_id)
            ->where('curso_id', $request->curso_id)
            ->where('turno_id', $request->turno_id)
            ->where('sala_id', $request->sala_id)
            ->where('ano_lectivo_id', $request->ano_lectivo_id)
            ->first();
            
            if( $verificar_matricula ) {
                return response()->json(['message' => "Este estudante já tem uma matrícula neste curso!"], 404);
            }
         
            $matricula = Matricula::create([
                'status' => $request->status,
                'codigo' => $codigo,
                'valor_pagamento' => $request->valor_pagamento,
                'user_id' => Auth::user()->id, 
                'aluno_id' => $request->aluno_id,
                'curso_id' => $request->curso_id,
                'turno_id' => $request->turno_id,
                'sala_id' => $request->sala_id,
                'ano_lectivo_id' => $request->ano_lectivo_id,
                'entidade_id' => $entidade->empresa->id,
            ]);
                
            $matricula->numero = "PROC " .  $matricula->id;
            $matricula->update();
             
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function matriculas_status($id)
    {
        $user = auth()->user();
        
        if(!$user->can('editar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $matricula = Matricula::findOrFail($id);
                    
            if($matricula->status == "DESACTIVO"){
                $matricula->status = "ACTIVO";
            }elseif($matricula->status == "ACTIVO"){
                $matricula->status = "DESACTIVO";
            }
                    
            $matricula->update();
              
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
     * @param  int  $matriculas_editar
     * @return \Illuminate\Http\Response
     */
    public function matriculas_editar($id)
    {
        $user = auth()->user();
        
        if(!$user->can('editar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $matricula = Matricula::with(['aluno'])->findOrFail($id);
                
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        
        $cursos = Curso::where('entidade_id', '=', $entidade->empresa->id)->get();
        $turnos = Turno::where('entidade_id', '=', $entidade->empresa->id)->get();
        $salas = Sala::where('entidade_id', '=', $entidade->empresa->id)->get();
        $anos_lectivos = AnoLectivo::where('entidade_id', '=', $entidade->empresa->id)->get();

        $head = [
            "titulo" =>  __('messages.editar'),
            "descricao" => env('APP_NAME'),
            "cursos" => $cursos,
            "turnos" => $turnos,
            "salas" => $salas,
            "matricula" => $matricula,
            "anos_lectivos" => $anos_lectivos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.alunos.edit-matricula', $head);    
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function matriculas_editar_update(Request $request, $id)
    {
            
        $user = auth()->user();
        
        if(!$user->can('editar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'curso_id' => 'required',
            'sala_id' => 'required',
            'turno_id' => 'required',
            'ano_lectivo_id' => 'required',
        ]);

                
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
                
            $matricula = Matricula::findOrFail($id);
                
            $matricula->status = $request->status;
            $matricula->user_id = Auth::user()->id; 
            $matricula->curso_id = $request->curso_id;
            $matricula->turno_id = $request->turno_id;
            $matricula->sala_id = $request->sala_id;
            $matricula->ano_lectivo_id = $request->ano_lectivo_id;
            $matricula->update();
            
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

        $aluno = Aluno::findOrFail($id);
        if($aluno->delete()){
            return redirect()->route('alunos.index')->with("success", "Dados Excluído com Sucesso!");
        }else{
            return redirect()->route('alunos.index')->with("warning", "Erro ao tentar Excluir aluno");
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function matriculas_excluir($id)
    {     
        $user = auth()->user();
        
        if(!$user->can('eliminar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
              
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $matricula = Matricula::findOrFail($id);
            $matricula->delete();
                
        // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }


        return response()->json(['success' => true, 'message' => "Dados Exluídos com sucesso!"], 200);

    }
}
