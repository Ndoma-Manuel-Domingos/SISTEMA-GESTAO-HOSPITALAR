<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        
        if (!$user->can('controle permissoes')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $permissions = Permission::get();
      
        $head = [
            "titulo" => "Permissões",
            "descricao" => env('APP_NAME'),
            "permissions" => $permissions,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.permissoes.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $user = auth()->user();
        
        if (!$user->can('controle permissoes')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.permissoes.create', $head);
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
        
        if (!$user->can('controle permissoes')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'permission' => 'required|string|unique:permissions,name', // Ajuste conforme o nome da sua tabela e campo
        ]);
        
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        
        $permission = Permission::create([
            'name' => $request->permission,
        ]);
        
        Alert::success('Sucesso', "Dados Cadastrados com Sucesso!");
        return redirect()->back();

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
        
        if (!$user->can('controle permissoes')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        $permission = Permission::findOrFail($id);
        
        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "permission" => $permission,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.permissoes.show', $head);
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
        
        if (!$user->can('controle permissoes')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        //
        $permission = Permission::findOrFail($id);

        $head = [
            "titulo" => "Perfil",
            "descricao" => env('APP_NAME'),
            "permission" => $permission,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.permissoes.edit', $head);
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
        
        if (!$user->can('controle permissoes')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        $request->validate([
            'permission' => 'required|string',
        ]);

        $permission = Permission::findOrFail($id);
        $permission->name = $request->permission;

        if($permission->update()){
            Alert::success('Sucesso', "Dados Actualizados com Sucesso!");
            return redirect()->back();
        }else{
            Alert::success('Atenção', "Erro ao tentar Actualizar Permissão!");
            return redirect()->back();
        }
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
        
        if (!$user->can('controle permissoes')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        //
        $permission = Permission::findOrFail($id);
        if($permission->delete()){
            Alert::success('Sucesso', "Dados Excluído com Sucesso!");
            return redirect()->back();
        }else{
            Alert::success('Atenção', "Erro ao tentar Excluir permissão");
            return redirect()->back();
        }
    }

}
