<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        // if (!$user->can('controle permissoes')) {
        //     return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }

        //
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $roles = Role::with('permissions')->where('entidade_id', $entidade->empresa->id)->orderBy('name', 'asc')->get();

        $head = [
            "titulo" => "Perfis",
            "descricao" => env('APP_NAME'),
            "roles" => $roles,
            "permissions" => Permission::pluck('id')->toArray(),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.roles.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();

        // if (!$user->can('controle permissoes')) {
        //     return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }

        //
        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "permissions" => Permission::get(),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.roles.create', $head);
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

        // if (!$user->can('controle permissoes')) {
        //     return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }

        $request->validate([
            'role' => 'required|string|unique:roles,name', // Ajuste conforme o nome da sua tabela e campo
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $roles = Role::create(['name' => $entidade->empresa->sigla . " - " . $request->role, 'entidade_id' => $entidade->empresa->id]);

        if ($request->permissions) {
            foreach ($request->permissions as $item) {
                $permission = Permission::findById($item);
                $roles->givePermissionTo($permission);
            }
        }

        Alert::success('Sucesso', "Dados Cadastrados com Sucesso!");
        return redirect()->route('roles.index');
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

        // if (!$user->can('controle permissoes')) {
        //     return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }

        //
        $role = Role::with('permissions')->findOrFail($id);

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "role" => $role,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.roles.show', $head);
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

        // if (!$user->can('controle permissoes')) {
        //     return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }

        //
        $role = Role::with(['permissions'])->findOrFail($id);

        $role_permissions = $role->permissions->pluck('id')->toArray();

        $head = [
            "titulo" => "Perfil",
            "descricao" => env('APP_NAME'),
            "role" => $role,
            "role_permissions" => $role_permissions,
            "permissions" => Permission::get(),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.roles.edit', $head);
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

        // if (!$user->can('controle permissoes')) {
        //     return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }

        //
        $request->validate([
            'role' => 'required|string',
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $role = Role::findOrFail($id);
        $role->name = $entidade->empresa->sigla . " - " . $request->role;

        $role->permissions()->sync($request->input('permissions', []));

        $role->update();

        Alert::success('Sucesso', "Dados Actualizados com Sucesso!");
        return redirect()->route('roles.index');
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

        // if (!$user->can('controle permissoes')) {
        //     return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }

        //
        $role = Role::findOrFail($id);
        if ($role->delete()) {
            Alert::success('Sucesso', "Dados Excluído com Sucesso!");
            return redirect()->route('roles.index');
        } else {
            Alert::success('Atenção', "Erro ao tentar Excluir perfil");
            return redirect()->route('roles.edit');
        }
    }
}
