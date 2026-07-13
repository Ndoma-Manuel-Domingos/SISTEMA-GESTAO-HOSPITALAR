<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Andar;
use App\Models\MotivoReserva;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class MotivoReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if(!$user->can('listar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        
        $motivos = MotivoReserva::where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "motivos" => $motivos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.motivos-reservas.index', $head);
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
        
        if(!$user->can('criar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.motivos-reservas.create', $head);
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
            'nome' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
            
            MotivoReserva::create([
                'entidade_id' => $entidade->empresa->id, 
                'nome' => $request->nome,
                'status' => $request->status,
                'user_id' => Auth::user()->id,
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
        
        return response()->json(['message' => "Dados salvos com sucesso", 'success' => true]);
      
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
        
        if(!$user->can('listar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $motivo = MotivoReserva::findOrFail($id);
            
            if($motivo->status == 'activo'){
                $status = "desactivo";
            }else {
                $status = "activo";
            }
            
            $motivo->status = $status;
            $motivo->update();
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
    public function show($id)
    {
        $user = auth()->user();
        
        if(!$user->can('listar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $motivo = MotivoReserva::findOrFail($id);
        
        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "motivo" => $motivo,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.motivos-reservas.show', $head);

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
        
        $motivo = MotivoReserva::findOrFail($id);

        $head = [
            "titulo" => __('messages.editar'),
            "descricao" => env('APP_NAME'),
            "motivo" => $motivo,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.motivos-reservas.edit', $head);
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
            
            if(!$user->can('editar todos')){
                
                return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            }
            
            $request->validate([
                'nome' => 'required|string',
            ]);
    
            $motivo = MotivoReserva::findOrFail($id);
            $motivo->update($request->all());
            
            $motivo->update();
            
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
        
        if(!$user->can('eliminar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $motivo = MotivoReserva::findOrFail($id);
            $motivo->delete();
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
