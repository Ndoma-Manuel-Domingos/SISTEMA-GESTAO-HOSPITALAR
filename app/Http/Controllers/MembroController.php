<?php

namespace App\Http\Controllers;

use App\Models\BackupSetting;
use App\Models\Caixa;
use App\Models\Entidade;
use App\Models\Funcao;
use App\Models\Membro;
use App\Models\Profissao;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MembroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $membros = Membro::with(['profissao', 'funcao'])->get();

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "membros" => $membros,
        ];

        return view('admin.membros.index', $head);
    }

    public function buscarPorBilhete(Request $request)
    {
        $membro = Membro::where('documento', 'like', "%" . $request->bilhete . "%")->first();

        if (!$membro) {
            return response()->json([
                'found' => false
            ]);
        }

        return response()->json([
            'found' => true,
            'membro' => $membro
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $funcoes = Funcao::get();
        $profissoes = Profissao::get();

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),

            "funcoes" => $funcoes,
            "profissoes" => $profissoes,
        ];

        return view('admin.membros.create', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string',
            'email' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            //
            
            $token = Str::random(64);

            $membro = Membro::create([
                'nome' => $request->nome,
                'email' => $request->email,
                'telefone' => $request->telefone,
                'documento' => $request->bilhete,
                'profissao_id' => $request->profissao_id,
                'genero' => $request->genero,
                'nacionalidade' => $request->nacionalidade,
                'funcao_id' => $request->funcao_id,
                'data_ingresso' => now(),
                'status' => 'activo',
                'photo' => NULL,
                'endereco' => $request->residente
            ]);
            
            $user = User::create([
                "name" => $membro->nome,
                "email" => $membro->email,
                "is_admin" => true,
                "password" => Hash::make("123456789"),
                "verification_token" => $token,
            ]);
            
            $membro->user_id = $user->id;
            $membro->save();

            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            
            dd($e->getMessage());
            
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
        $membro = Membro::findOrFail($id);

        $entidades = Entidade::where('membro_id', NULL)->get();

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "membro" => $membro,
            "entidades" => $entidades,
        ];

        return view('admin.membros.show', $head);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $funcoes = Funcao::get();
        $profissoes = Profissao::get();

        $membro = Membro::with(['empresas.caixas', 'facturamentos'])->findOrFail($id);

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),

            "funcoes" => $funcoes,
            "profissoes" => $profissoes,
            "membro" => $membro,
        ];

        return view('admin.membros.edit', $head);
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
        $membro = Membro::findOrFail($id);

        try {
            DB::beginTransaction();
            //

            $membro->update([
                'nome' => $request->nome,
                'email' => $request->email,
                'telefone' => $request->telefone,
                'documento' => $request->bilhete,
                'profissao_id' => $request->profissao_id,
                'funcao_id' => $request->funcao_id,
                'nacionalidade' => $request->nacionalidade,
                'genero' => $request->genero,
                'data_ingresso' => now(),
                'status' => 'activo',
                'photo' => NULL,
                'endereco' => $request->residente
            ]);

            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
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
        try {
            DB::beginTransaction();
            //
            $membro = Membro::findOrFail($id);
            $membro->delete();

            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
    }

    public function toggleStatus(Request $request)
    {
        $caixa = Caixa::findOrFail($request->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            // alternar status
            $caixa->status_admin = $caixa->status_admin == 'liberado' ? 'bloqueado' : 'liberado';
            $caixa->save();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }


        return response()->json([
            'success' => true,
            'status' => $caixa->status_admin
        ]);
    }


    public function removerEmpresa(Request $request)
    {
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $empresa = Entidade::findOrFail($request->empresa_id);
            $empresa->membro_id = NULL;
            $empresa->save();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function addMembro(Request $request)
    {
        $token = Str::random(64);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $empresa = Entidade::findOrFail($request->entidade_id);

            $membro = Membro::findOrFail($request->membro_id);

            if ($membro->user_id == NULL) {

                if (!User::where('type_user', 'Admin')->where("entidade_id", $empresa->id)->where('is_admin', true)->exists()) {
                    $user = User::create([
                        "name" => $membro->nome,
                        "email" => $membro->email,
                        "is_admin" => true,
                        "password" => Hash::make("123456789"),
                        "entidade_id" => $empresa->id,
                        "verification_token" => $token,
                    ]);

                    $setting = BackupSetting::create([
                        'user_id' => $user->id,
                        'folder_path' => null,
                        'enabled' => 0,
                        'retain' => 24,
                        'frequency_minutes' => 120,
                        'last_run_at' => null,
                        'tipo_mysql' => "padrao",
                        'entidade_id' => $empresa->id
                    ]);

                    $role = Role::create(['name' => "{$empresa->sigla} - Administrador Geral", 'entidade_id' => $empresa->id]);
                    // $permission = Permission::findByName("controle permissoes", "web");
                    $permissions = Permission::get();
                    foreach ($permissions as $permiss) {
                        $role->givePermissionTo($permiss);
                    }
                    $user->roles()->attach($role);
                }
            }

            $empresa->membro_id = $request->membro_id;
            $empresa->save();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json([
            'success' => true
        ]);
    }
}
