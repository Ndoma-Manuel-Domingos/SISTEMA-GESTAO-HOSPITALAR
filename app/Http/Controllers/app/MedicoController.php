<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\Conta;
use App\Models\Distrito;
use App\Models\Entidade;
use App\Models\Especialidade;
use App\Models\EstadoCivil;
use App\Models\Funcionario;
use App\Models\Medico;
use App\Models\Municipio;
use App\Models\Provincia;
use App\Models\Seguradora;
use App\Models\Subconta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;

class MedicoController extends Controller
{
    use TraitHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $medicos = Medico::with(['funcionario', 'especialidade'])->where("entidade_id", $entidade->empresa->id)
            ->where('tipo', 'Medico')
            ->orderBy('created_at', 'desc')
            ->get();

        $empresa = Entidade::with(['variacoes', 'clientes', 'marcas', 'categorias'])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Médicos",
            "descricao" => env('APP_NAME'),
            "medicos" => $medicos,
            "empresa" => $empresa,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.medicos.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'clientes', 'marcas', 'categorias'])->findOrFail($entidade->empresa->id);

        $especialidades = Especialidade::where('entidade_id', '=', $entidade->empresa->id)->get();

        $roles = Role::where("entidade_id", $entidade->empresa->id)->get();

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "user" => Auth::user(),
            "estados_civils" => EstadoCivil::get(),
            "provincias" => Provincia::get(),
            "municipios" => Municipio::get(),
            "distritos" => Distrito::get(),
            "especialidades" => $especialidades,
            "roles" => $roles,
            "seguradores" => Seguradora::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.medicos.create', $head);
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
            "nome" => "required|string",
            "nif" => "required|string",
            "tipo" => "required|string",
            // "numero_cedula" => "required|string|unique:medicos,numero_cedula",
            // "data_emissao_cedula" => "required|string",
            // "status_profissional" => "required|string",
            // "especialidade_id" => "required|string",
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $verificar_numero_mecanografico = Funcionario::where('entidade_id', $entidade->empresa->id)->where('numero_mecanografico', $request->numero_mecanografico)->first();

            if ($verificar_numero_mecanografico) {
                return response()->json(['message' => "Número Mecanografico já existe!"], 404);
            }

            $verificar_numero_nif = Funcionario::where('entidade_id', $entidade->empresa->id)->where('nif', $request->nif)->first();

            if ($verificar_numero_nif) {
                return response()->json(['message' => "Número NIF já existe!"], 404);
            }

            $code = uniqid(time());
            $nova_conta = "";

            $serie = "";

            $conta = Conta::where('conta', '36')->where('entidade_id', $entidade->empresa->id)->first();

            if ($request->categoria == "Orgão Sociais") {
                $serie =  "36.1.1.";
            }
            if ($request->categoria == "Empregados") {
                $serie =  "36.1.2.";
            }
            if ($request->categoria == "Pessoal") {
                $serie =  "36.1.2.";
            }

            $subc_ = Subconta::where('numero', 'like', "{$serie}%")->where('entidade_id', $entidade->empresa->id)->count() + 1;
            $nova_conta =  $serie . "{$subc_}";

            $subconta = Subconta::create([
                'entidade_id' => $entidade->empresa->id,
                'numero' => $nova_conta,
                'nome' => $request->nome,
                'tipo_conta' => 'M',
                'code' => $code,
                'status' => $conta->status,
                'conta_id' => $conta->id,
                'user_id' => Auth::user()->id,
            ]);

            $user = null;

            if ($request->tipo_acesso != null) {
                $role = Role::findOrFail($request->tipo_acesso);
                $email = $request->email ?? $this->gerarEmailCliente($request->nome);
                $user = User::create([
                    "name" => $request->nome,
                    "email" => $email,
                    "is_admin" => false,
                    "type_user" => "Admin",
                    "status" => true,
                    "level" => 1,
                    "login_access" => false,
                    "password" => Hash::make("123456789"),
                    "entidade_id" => $entidade->empresa->id,
                ]);
                $user->assignRole($role);
            }

            $funcionario = Funcionario::create([
                "conta" => $nova_conta,
                "nif" => $request->nif,
                "code" => $code,
                "numero_mecanografico" => $request->numero_mecanografico,
                "nome" => $request->nome,
                "pais" => $request->pais,
                "status" => true,
                "gestor_conta" => $user->id ?? NULL,
                "codigo_postal" => $request->codigo_postal ?? NULL,
                "localidade" => $request->localidade ?? NULL,
                "telefone" => $request->telefone ?? NULL,
                "telemovel" => $request->telemovel ?? NULL,

                "numero_bilhete" => $request->numero_bilhete ?? NULL,
                "local_emissao_bilhete" => $request->local_emissao_bilhete ?? NULL,
                "data_emissao_bilhete" => $request->data_emissao_bilhete ?? NULL,
                "validade_bilhete" => $request->validade_bilhete ?? NULL,
                "numero_passaporte" => $request->numero_passaporte ?? NULL,
                "local_emissao_passaporte" => $request->local_emissao_passaporte ?? NULL,
                "data_emissao_passaporte" => $request->data_emissao_passaporte ?? NULL,
                "validade_passaporte" => $request->validade_passaporte ?? NULL,

                "nome_do_pai" => $request->nome_do_pai ?? NULL,
                "nome_da_mae" => $request->nome_da_mae ?? NULL,
                "data_nascimento" => $request->data_nascimento ?? NULL,
                "genero" => $request->genero ?? NULL,
                "estado_civil_id" => $request->estado_civil_id ?? NULL,
                "seguradora_id" => $request->seguradora_id ?? NULL,
                "provincia_id" => $request->provincia_id ?? NULL,
                "municipio_id" => $request->municipio_id ?? NULL,
                "distrito_id" => $request->distrito_id ?? NULL,

                "vencimento" => $request->vencimento ?? NULL,
                "email" => $email ?? NULL,
                "website" => $request->website ?? NULL,
                "referencia_externa" => $request->referencia_externa ?? NULL,
                "categoria" => $request->categoria ?? NULL,
                "user_id" => Auth::user()->id,
                "entidade_id" => $entidade->empresa->id,
                "subconta_id" => $subconta->id,
            ]);

            $medico = Medico::create([
                "numero_cedula" => $request->numero_cedula,
                "entidade_registradora" => $request->entidade_registradora,
                "provincia_registro" => $request->provincia_registro,
                "data_emissao_cedula" => $request->data_emissao_cedula,
                "data_validade_cedula" => $request->data_validade_cedula,
                "status_profissional" => $request->status_profissional,
                "tipo" => $request->tipo,
                "gestor_conta" => $user->id ?? NULL,
                "user_id" => Auth::user()->id,
                "funcionario_id" => $funcionario->id,
                "especialidade_id" => $request->especialidade_id,
                "entidade_id" => $entidade->empresa->id,
            ]);

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
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
        $medico = Medico::with([])->findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'clientes', 'marcas', 'categorias'])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Médico",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "medico" => $medico,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.medicos.show', $head);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $medico = Medico::with(['funcionario', 'especialidade'])
            ->findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'clientes', 'marcas', 'categorias'])->findOrFail($entidade->empresa->id);
        $especialidades = Especialidade::where('entidade_id', '=', $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Médicos",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "medico" => $medico,
            "estados_civils" => EstadoCivil::get(),
            "provincias" => Provincia::get(),
            "municipios" => Municipio::get(),
            "distritos" => Distrito::get(),
            "seguradores" => Seguradora::get(),
            "especialidades" => $especialidades,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.medicos.edit', $head);
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
        $request->validate([
            "nome" => "required|string",
            "nif" => "required|string",
            "numero_cedula" => "required|string",
            "data_emissao_cedula" => "required|string",
            "status_profissional" => "required|string",
            "especialidade_id" => "required|string",
        ]);


        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $medico = Medico::findOrFail($id);
        $funcionario = Funcionario::findOrFail($medico->funcionario_id);

        try {
            DB::beginTransaction();

            $code = uniqid(time());
            $nova_conta = "";
            $conta = Conta::where('conta', '36')->where('entidade_id', $entidade->empresa->id)->first();

            if ($request->categoria == "Orgão Sociais") {
                $serie =  "36.1.1.";
            }
            if ($request->categoria == "Empregados") {
                $serie =  "36.1.2.";
            }
            if ($request->categoria == "Pessoal") {
                $serie =  "36.1.2.";
            }

            if ($funcionario->code == NULL) {
                $subc_ = Subconta::where('numero', 'like', "{$serie}%")->where('entidade_id', $entidade->empresa->id)->count() + 1;
                $nova_conta =  $serie . "{$subc_}";

                $subconta = Subconta::create([
                    'entidade_id' => $entidade->empresa->id,
                    'numero' => $nova_conta,
                    'nome' => $request->nome,
                    'tipo_conta' => 'M',
                    'code' => $code,
                    'status' => $conta->status,
                    'conta_id' => $conta->id,
                    'user_id' => Auth::user()->id,
                ]);

                $funcionario->subconta_id = $subconta->id;
                $funcionario->conta = $nova_conta;
                $funcionario->code = $code;
            } else {

                if ($request->categoria != $funcionario->categoria) {
                    $subconta = Subconta::where('code', $funcionario->code)->where('entidade_id', $entidade->empresa->id)->first();
                    $nova_conta = $funcionario->conta;
                    if ($subconta) {
                        $subc_up = Subconta::findOrFail($funcionario->subconta_id);
                        $subc_up->numero = $nova_conta;
                        $subc_up->code = $code;
                        $subc_up->nome = $request->nome;
                        $subc_up->update();
                    }

                    $funcionario->conta = $nova_conta;
                    $funcionario->code = $code;
                }
                ## continuição para edição das categorias
            }

            $funcionario->nif = $request->nif;
            $funcionario->numero_mecanografico = $request->numero_mecanografico;
            $funcionario->nome = $request->nome;

            $funcionario->pais = $request->pais;
            $funcionario->gestor_conta = $request->gestor_conta;
            $funcionario->codigo_postal = $request->codigo_postal;
            $funcionario->localidade = $request->localidade;
            $funcionario->telefone = $request->telefone;
            $funcionario->telemovel = $request->telemovel;

            $funcionario->numero_bilhete = $request->numero_bilhete;
            $funcionario->local_emissao_bilhete = $request->local_emissao_bilhete;
            $funcionario->data_emissao_bilhete = $request->data_emissao_bilhete;
            $funcionario->validade_bilhete = $request->validade_bilhete;
            $funcionario->numero_passaporte = $request->numero_passaporte;
            $funcionario->local_emissao_passaporte = $request->local_emissao_passaporte;
            $funcionario->data_emissao_passaporte = $request->data_emissao_passaporte;
            $funcionario->validade_passaporte = $request->validade_passaporte;
            $funcionario->tipo_funcionario_id = $request->tipo_funcionario_id;

            $funcionario->nome_do_pai = $request->nome_do_pai;
            $funcionario->nome_da_mae = $request->nome_da_mae;
            $funcionario->data_nascimento = $request->data_nascimento;
            $funcionario->genero = $request->genero;
            $funcionario->estado_civil_id = $request->estado_civil_id;
            $funcionario->seguradora_id = $request->seguradora_id;
            $funcionario->provincia_id = $request->provincia_id;
            $funcionario->municipio_id = $request->municipio_id;
            $funcionario->distrito_id = $request->distrito_id;

            $funcionario->vencimento = $request->vencimento;
            $funcionario->email = $request->email;
            $funcionario->website = $request->website;
            $funcionario->referencia_externa = $request->referencia_externa;
            $funcionario->categoria = $request->categoria;


            $medico->numero_cedula = $request->numero_cedula;
            $medico->entidade_registradora = $request->entidade_registradora;
            $medico->provincia_registro = $request->provincia_registro;
            $medico->data_emissao_cedula = $request->data_emissao_cedula;
            $medico->data_validade_cedula = $request->data_validade_cedula;
            $medico->tipo = $request->tipo;
            $medico->status_profissional = $request->status_profissional;
            $medico->especialidade_id = $request->especialidade_id;

            $funcionario->update();
            $medico->update();


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
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $medicos = Medico::findOrFail($id);
            $medicos->delete();

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
