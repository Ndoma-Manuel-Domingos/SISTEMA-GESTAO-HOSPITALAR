<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Imports\FuncionarioImport;
use App\Models\AccessLog;
use App\Models\CartaoTemplate;
use App\Models\Conta;
use App\Models\Contrato;
use App\Models\Funcionario;
use App\Models\Distrito;
use App\Models\Entidade;
use App\Models\EstadoCivil;
use App\Models\Municipio;
use App\Models\Provincia;
use App\Models\Seguradora;
use App\Models\Subconta;
use App\Models\TipoFuncionario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;
use Spatie\Permission\Models\Role;

class FuncionarioController extends Controller
{
    use TraitHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar funcionario')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $funcionarios = Funcionario::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'tipo_funcionario'])->where("entidade_id", $entidade->empresa->id)->orderBy('created_at', 'desc')->get();

        $empresa = Entidade::with("variacoes")->with("marcas")->with("categorias")->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "funcionarios",
            "descricao" => env('APP_NAME'),
            "funcionarios" => $funcionarios,
            "empresa" => $empresa,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.funcionarios.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_import()
    {
        //
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar funcionario')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("marcas")->with("categorias")->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" =>__('messages.novo'),
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "user" => Auth::user(),
            "estados_civils" => EstadoCivil::get(),
            "provincias" => Provincia::get(),
            "municipios" => Municipio::get(),
            "distritos" => Distrito::get(),
            "seguradores" => Seguradora::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.funcionarios.create-import', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store_import(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar funcionario')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        try {
            Excel::import(new FuncionarioImport, $request->file('file'));
            return redirect()->back()->with('success', 'Dados importados com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao importar dados: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao importar dados: ' . $e->getMessage());
        }
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
        if (!$user->can('criar todos') && !$user->can('criar funcionario')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("marcas")->with("categorias")->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" =>__('messages.novo'),
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "user" => Auth::user(),
            "estados_civils" => EstadoCivil::get(),
            "provincias" => Provincia::get(),
            "municipios" => Municipio::get(),
            "distritos" => Distrito::get(),
            "seguradores" => Seguradora::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.funcionarios.create', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar funcionario')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
            'nif' => 'required|string',
            // 'email' => 'required|email',
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $verificar_numero_mecanografico = Funcionario::where('entidade_id', $entidade->empresa->id)->where('numero_mecanografico', $request->numero_mecanografico)->first();

        if ($verificar_numero_mecanografico) {
            return response()->json(['message' => "Número Mecanografico já existe!"], 404);
        }

        $verificar_numero_nif = Funcionario::where('entidade_id', $entidade->empresa->id)->where('nif', $request->nif)->first();

        if ($verificar_numero_nif) {
            return response()->json(['message' => "Número NIF já existe!"], 404);
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

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
            
            if ($conta) {

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
                
            }
            
            $email = $request->email ?? $this->gerarEmailCliente($request->nome);
            $role = Role::where("name", "{$entidade->empresa->sigla} - Padrao")->first();
            
            if($role) {
                $level = 1;
                $user = User::create([
                    "name" => $request->nome,
                    "email" => $email,
                    "is_admin" => false,
                    "type_user" => "Funcionario",
                    "status" => true,
                    "level" => $level,
                    "login_access" => false,
                    "password" => Hash::make($request->nif),
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
                "gestor_conta" => $user->id,
                "codigo_postal" => $request->codigo_postal,
                "localidade" => $request->localidade,
                "telefone" => $request->telefone,
                "telemovel" => $request->telemovel,

                "numero_bilhete" => $request->numero_bilhete,
                "local_emissao_bilhete" => $request->local_emissao_bilhete,
                "data_emissao_bilhete" => $request->data_emissao_bilhete,
                "validade_bilhete" => $request->validade_bilhete,
                "numero_passaporte" => $request->numero_passaporte,
                "local_emissao_passaporte" => $request->local_emissao_passaporte,
                "data_emissao_passaporte" => $request->data_emissao_passaporte,
                "validade_passaporte" => $request->validade_passaporte,

                "nome_do_pai" => $request->nome_do_pai,
                "nome_da_mae" => $request->nome_da_mae,
                "data_nascimento" => $request->data_nascimento,
                "genero" => $request->genero,
                "estado_civil_id" => $request->estado_civil_id,
                "seguradora_id" => $request->seguradora_id,
                "provincia_id" => $request->provincia_id,
                "municipio_id" => $request->municipio_id,
                "distrito_id" => $request->distrito_id,

                "vencimento" => $request->vencimento,
                "email" => $email,
                "website" => $request->website,
                "referencia_externa" => $request->referencia_externa,
                "categoria" => $request->categoria,
                "user_id" => Auth::user()->id,
                "entidade_id" => $entidade->empresa->id,
                "subconta_id" => $subconta->id,
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar funcionario')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $funcionario = Funcionario::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito'])->findOrFail($id);
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("marcas")->with("categorias")->findOrFail($entidade->empresa->id);

        $contrato = Contrato::with(['categoria', 'subsidios_contrato.subsidio', 'descontos_contrato.desconto', 'funcionario', 'cargo', 'tipo_contrato', 'user', 'forma_pagamento'])->where('funcionario_id', $funcionario->id)->first();

        $logotipoPath = public_path("images/empresa/{$funcionario->foto}");
        $temLogotipo = File::exists($logotipoPath);
        
        
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        
        $cartao = CartaoTemplate::where('entidade_id', $entidade->empresa->id)->first() ?? CartaoTemplate::create([
            'name' => 'Default PVC',
            'width' => 540, // px
            'height' => 340, // px
            'orientation' => 'horizontal', // horizontal|vertical
            'font_family' => 'Arial',
            'font_size_title' => '14px',
            'font_size_subtitle' => '14px',
            'font_size' => '14px',
            'text_color' => '#000000',
            'background_color' => '#ffffff',
            'photo_position' => 'left', // left|right|top|bottom
            'entidade_id' => $entidade->empresa->id,
            'user_id' => Auth::user()->id,
        ]);
                
        // QR        
        $url = $funcionario->id; //route('shcools.mais-informacao-estudante', $estudante->id); // URL para abrir os detalhes do estudante
        $qrCode = QrCode::size(200)->generate($url);
        
        $head = [
            "titulo" => __('messages.editar'),
            "descricao" => env('APP_NAME'),
            "foto" => $temLogotipo ? $logotipoPath : null,
            "empresa" => $empresa,
            "funcionario" => $funcionario,
            "contrato" => $contrato,
            "template" => $cartao,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.funcionarios.show', $head, compact('qrCode'));
    }

    public function cartao_funcionario($id, $tipo = 'horizontal')
    {
        $funcionario = Funcionario::findOrFail($id);
       
        $entidade = User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id);
        
        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        
        
        $template = CartaoTemplate::where('entidade_id', $entidade->empresa->id)->first() ?? CartaoTemplate::create([
            'name' => 'Default PVC',
            'width' => 540, // px
            'height' => 340, // px
            'orientation' => 'horizontal', // horizontal|vertical
            'font_family' => 'Arial',
            'font_size_title' => '14px',
            'font_size_subtitle' => '14px',
            'font_size' => '14px',
            'text_color' => '#000000',
            'background_color' => '#ffffff',
            'photo_position' => 'left', // left|right|top|bottom
            'entidade_id' => $entidade->empresa->id,
            'user_id' => Auth::user()->id,
        ]);
        
        
        $head = [
            "template" => $template,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "funcionario" => $funcionario,
            "empresa_logada" => $entidade
        ];
   
        if ($tipo === 'vertical') {
            return view('dashboard.funcionarios.cartao.vertical', $head);
        }
        
        return view('dashboard.funcionarios.cartao.horizontal', $head);
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

        if (!$user->can('editar todos') && !$user->can('editar funcionario')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $funcionario = Funcionario::findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("marcas")->with("categorias")->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Funcionários",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "funcionario" => $funcionario,
            "estados_civils" => EstadoCivil::get(),
            "provincias" => Provincia::get(),
            "municipios" => Municipio::get(),
            "distritos" => Distrito::get(),
            "seguradores" => Seguradora::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "tipos_funcionarios" => TipoFuncionario::where('entidade_id', '=', $entidade->empresa->id)->get(),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.funcionarios.edit', $head);
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

        if (!$user->can('editar todos') && !$user->can('editar funcionario')) {
            
            return redirect()->back();
        }

        $request->validate([
            'nome' => 'required|string',
            'nif' => 'required|string',
        ]);


        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $funcionario = Funcionario::findOrFail($id);

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

            $funcionario->update();


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

        if (!$user->can('eliminar todos') && !$user->can('eliminar funcionario')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $funcionario = Funcionario::findOrFail($id);
            $funcionario->delete();

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


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ficha_funcionario($id)
    {
        $user = auth()->user();

        $funcionario = Funcionario::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito'])->findOrFail($id);

        $contrato = Contrato::with(['categoria', 'subsidios_contrato.subsidio', 'descontos_contrato.desconto', 'funcionario', 'cargo', 'tipo_contrato', 'user', 'forma_pagamento'])->where('funcionario_id', $funcionario->id)->first();
        
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        
        $head = [
            "titulo" => "Recibo",
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "descricao" => env('APP_NAME'),
            "funcionario" => $funcionario,
            "contrato" => $contrato,

            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.funcionarios.ficha-funcionario', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function carregar_foto_funcionario($id)
    {
        $user = auth()->user();
        $funcionario = Funcionario::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito'])->findOrFail($id);
        $contrato = Contrato::with(['categoria', 'subsidios_contrato.subsidio', 'descontos_contrato.desconto', 'funcionario', 'cargo', 'tipo_contrato', 'user', 'forma_pagamento'])->where('funcionario_id', $funcionario->id)->first();

        $head = [
            "titulo" => "Recibo",
            "descricao" => env('APP_NAME'),
            "funcionario" => $funcionario,
            "contrato" => $contrato,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];
        
        return view('dashboard.funcionarios.carregar-foto-funcionario', $head);

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function carregar_foto_funcionario_store(Request $request)
    {
     
        $request->validate([
            'funcionario_id' => 'required',
            'foto_base64' => 'required|string'
        ]);
        
        $funcionario = Funcionario::findOrFail($request->funcionario_id);
        
        try {
            DB::beginTransaction();
        
            if ($request->has('foto_base64') && !empty($request->foto_base64)) {
                // Decodifica o base64
                $image = str_replace('data:image/png;base64,', '', $request->foto_base64);
                $image = str_replace(' ', '+', $image);
                $imageData = base64_decode($image);
            
                // Cria um nome único para a imagem
                $imageName = uniqid() . '.png';
            
                // Salva no diretório desejado
                $path = public_path('images/funcionarios');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
            
                file_put_contents($path . '/' . $imageName, $imageData);
            } else {
                // Mantém a imagem antiga
                $imageName = $funcionario->foto;
            }
           
            // Criar funcionário
            $funcionario = Funcionario::findOrFail($request->funcionario_id);
            $funcionario->foto = $imageName;
            $funcionario->update();
       
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Foto carregada com sucesso"], 200);
        
    }
    
    // Rota que será chamada quando QR for escaneado (leitura)
    public function scan(Request $request, $id = null)
    {
        // Se veio via GET do QR
        $employeeId = $id ?? $request->input('employee_id');
        $area = $request->input('area', 'Portaria');

        $employee = Funcionario::find($employeeId);
        if (!$employee) {
            return response()->json(['status'=>'error','message'=>'Funcionário não encontrado'], 404);
        }

        $log = AccessLog::create([
            'employee_id'=> $employee->id,
            'area'=> $area,
            'method'=>'qr',
            'ip'=> $request->ip(),
            'meta'=> json_encode($request->all())
        ]);

        // resposta para o leitor / app
        return response()->json([
            'status'=>'ok',
            'employee'=>[
                'id'=>$employee->id,
                'nome'=>$employee->nome,
                'mecanografico'=>$employee->mecanografico,
                'funcao'=>$employee->funcao,
            ],
            'log_id'=>$log->id
        ]);
    }

    
}
