<?php

namespace App\Http\Controllers;

use App\Models\BackupSetting;
use App\Models\Entidade;
use App\Models\Funcao;
use App\Models\HashLicenca;
use App\Models\Membro;
use App\Models\Municipio;
use App\Models\Plano;
use App\Models\Profissao;
use App\Models\Provincia;
use App\Models\TipoEntidade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class InscricaoEmpresaController extends Controller
{

    use TraitChavesSaft;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();

        $tipos_entidade = TipoEntidade::where('status', 'activo')->get();
        $provincias = Provincia::get();
        $municipios = Municipio::get();

        $funcoes = Funcao::get();
        $profissoes = Profissao::get();
        $planos = Plano::get();

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),

            "tipos_entidade" => $tipos_entidade,
            "provincias" => $provincias,
            "municipios" => $municipios,
            "funcoes" => $funcoes,
            "profissoes" => $profissoes,
            "planos" => $planos,
        ];

        return view('admin.inscricoes.create', $head);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:membros,email',
            'bilhete' => 'required|string|unique:membros,documento',
        ]);

        $dados = $request->all();

        try {
            DB::beginTransaction();

            if ($request->hasFile('foto')) {
                $dados['foto'] = $request->file('foto')->store('images', 'public');
            }

            $token = Str::random(64);
            // Gerar uma sigla única
            $sigla = Entidade::generateUniqueSigla();


            $entidade = Entidade::create([
                'nome' => $dados['empresa'] ?? "",
                'sigla' => $sigla,
                'nif' => $dados['nif'] ?? "",
                'tipo_id' => $dados['tipo_negocio'] ?? 1,
                'tipo_empresa' => "Juridica",
                'morada' => $dados['residente'] ?? "",
                'status' => "desactivo",
                'codigo_postal' => NULL,
                'cidade' => NULL,
                'conservatoria' => NULL,
                'capital_social' => NULL,
                'nome_comercial' => NULL,
                'slogan' => NULL,
                'plano_id' => $request->plano_id,
                'membro_id' => $request->membro_id,
                'logotipo' => $dados['foto'] ?? NULL,
                'municipio_id' => $dados['municipio_id'] ?? "",
                'provincia_id' => $dados['provincia_id'] ?? "",
                'pais' => NULL,
                'moeda' => NULL,
                'taxa_iva' => NULL,
                'motivo_isencao' => NULL,
                "email" => $dados['email_empresa'],
                'imposto_id' => NULL,
                'motivo_id' => NULL,
                'telefone' => $dados['telefone_empresa'],
                'website' => NULL,
                'promocoes_email' => false,
                'novidade_email' => false,
            ]);

            HashLicenca::create(['hash' => $this->getMachineFingerprint()]);

            $user = User::create([
                "name" => $dados['nome'],
                "email" => $dados['email'],
                "is_admin" => true,
                "password" => Hash::make("123456789"),
                "entidade_id" => $entidade->id,
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
                'entidade_id' => $entidade->id
            ]);

            if (!$request->membro_id) {
                Membro::create([
                    'nome' => $request->nome,
                    'email' => $request->email,
                    'telefone' => $request->telefone,
                    'genero' => $request->genero,
                    'nacionalidade' => $request->nacionalidade,
                    'documento' => $request->bilhete,
                    'profissao_id' => $request->profissao_id,
                    'funcao_id' => $request->funcao_id,
                    'data_ingresso' => now(),
                    'status' => 'activo',
                    'photo' => NULL,
                    'user_id' => $user->id,
                    'endereco' => $request->residente
                ]);
            }

            //******************************************** */
            $role = Role::create(['name' => "{$entidade->sigla} - Administrador Geral", 'entidade_id' => $entidade->id]);
            // $permission = Permission::findByName("controle permissoes", "web");
            $permissions = Permission::get();
            foreach ($permissions as $permiss) {
                $role->givePermissionTo($permiss);
            }
            $user->roles()->attach($role);

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
    }
}
