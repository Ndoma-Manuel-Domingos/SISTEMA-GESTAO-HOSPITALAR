<?php

namespace App\Http\Controllers;

use App\Models\BackupSetting;
use App\Models\Entidade;
use App\Models\Loja;
use App\Models\User;
use App\Models\UserLoja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar utilizadores')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $utilizadores = User::with(["minhas_lojas.loja"])
            ->where("entidade_id", $entidade->empresa->id)
            ->with("roles")
            ->orderBy("created_at", "desc")
            ->get();

        $roles = Role::where("entidade_id", $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Utilizadores",
            "descricao" => env('APP_NAME'),
            "utilizadores" => $utilizadores,
            "empresa" => $entidade,
            "roles" => $roles,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.utilizadores.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar utilizadores')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        $roles = Role::where("entidade_id", $entidade->empresa->id)->get();

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "roles" => $roles,
            "lojas" => $lojas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.utilizadores.create', $head);
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

        if (!$user->can('criar todos') && !$user->can('criar utilizadores')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:3|max:20|same:password',
            'password_r' => 'required|min:3|max:20',
        ]);


        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
            $roles = Role::findOrFail($request->roles);

            $create = User::create([
                "name" => $request->nome,
                "email" => $request->email,
                "is_admin" => false,
                "status" => true,
                "level" => 1,
                "login_access" => false,
                "password" => Hash::make($request->password),
                "entidade_id" => $entidade->empresa->id,
            ]);

            foreach ($request->loja_id as $value) {
                UserLoja::create([
                    "usuario_id" => $create->id,
                    "loja_id" => $value,
                    "status" => 1,
                    "entidade_id" => $entidade->empresa->id,
                    "user_id" => $user->id,
                ]);
            }

            $create->assignRole($roles);

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

        if (!$user->can('editar todos') && !$user->can('editar utilizadores')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $utilizador = User::with(["roles", "minhas_lojas"])->findOrFail($id);

        $users_roles = $utilizador->roles->pluck('id')->toArray();
        $users_lojas = $utilizador->minhas_lojas->pluck("loja_id")->toArray();
        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $user->entidade_id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        $roles = Role::where("entidade_id", $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Utilizador",
            "descricao" => env('APP_NAME'),
            "utilizador" => $utilizador,
            "roles" => $roles,
            "lojas" => $lojas,
            "users_lojas" => $users_lojas,
            "users_roles" => $users_roles,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.utilizadores.edit', $head);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function privacidade()
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $roles = Role::where("entidade_id", $entidade->empresa->id)->get();
        $utilizador = User::findOrFail(Auth::user()->id);

        $verificar_backup = BackupSetting::where('entidade_id', $entidade->id)->first();

        if (!$verificar_backup) {
            BackupSetting::create([
                'user_id' => Auth::user()->id,
                'folder_path' => null,
                'enabled' => 0,
                'retain' => 24,
                'frequency_minutes' => 120,
                'last_run_at' => null,
                'tipo_mysql' => "padrao",
                'entidade_id' => $entidade->empresa->id
            ]);
        }

        $head = [
            "titulo" => "Utilizador",
            "descricao" => env('APP_NAME'),
            "utilizador" => $utilizador,
            "roles" => $roles,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.utilizadores.privacidade', $head);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function privacidade_store(Request $request)
    {
        $request->validate([
            'senha' => 'required|string',
            'nova_senha' => 'required|min:3|max:20', //s|same:password
            'confirmar_senha' => 'required|min:3|max:20',
        ]);


        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            if (!Hash::check($request->senha, Auth::user()->password)) {
                return response()->json(['success' => true, 'message' => "Senha actual invalída!"], 404);
            }

            if ($request->nova_senha != $request->confirmar_senha) {
                return response()->json(['success' => true, 'message' => "Nova Senha e confirmação da nova senha não conferem!"], 404);
            }

            $user = User::findOrFail(Auth::user()->id);
            $user->password = Hash::make($request->nova_senha);
            $user->login_access = 1;
            $user->update();

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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar utilizadores')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $user_update = User::with(["roles", "minhas_lojas"])->findOrFail($id);
            $user_update->name = $request->nome;
            $user_update->email = $request->email;

            foreach ($user_update->roles as $role) {
                $user_update->removeRole($role);
            }


            $new_role = Role::findOrFail($request->roles);
            $user_update->assignRole($new_role);

            $user_update->update();

            $users_lojas = $user_update->minhas_lojas->pluck("id")->toArray();

            foreach ($users_lojas as $item) {
                UserLoja::findOrFail($item)->delete();
            }

            foreach ($request->loja_id as $value) {
                UserLoja::create([
                    "usuario_id" => $user_update->id,
                    "loja_id" => $value,
                    "status" => 1,
                    "entidade_id" => $user_update->entidade_id,
                    "user_id" => $user->id,
                ]);
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

        if (!$user->can('eliminar todos') && !$user->can('eliminar utilizadores')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $utilizador = User::findOrFail($id);
            $utilizador->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados eExcluído com sucesso!"], 200);
    }
}
