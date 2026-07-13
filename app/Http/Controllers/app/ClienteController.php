<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Imports\ClienteImport;
use App\Models\AnoLectivo;
use App\Models\Atendimento;
use App\Models\Cliente;
use App\Models\Conta;
use App\Models\ContaCliente;
use App\Models\Curso;
use App\Models\Distrito;
use App\Models\Entidade;
use App\Models\EstadoCivil;
use App\Models\ItemVenda;
use App\Models\Matricula;
use App\Models\MovimentoContaCliente;
use App\Models\Municipio;
use App\Models\Prioridade;
use App\Models\Provincia;
use App\Models\Sala;
use App\Models\SeguradoraPlanoBeneficiador;
use App\Models\Seguradora;
use App\Models\SeguradoraPlano;
use App\Models\Subconta;
use App\Models\TipoAtendimento;
use App\Models\Turno;
use App\Models\User;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;
use Spatie\Permission\Models\Role;

class ClienteController extends Controller
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

        if (!$user->can('listar todos') && !$user->can('listar cliente')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $clientes = Cliente::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'filhos', 'parent'])
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('conta', 'asc')
            ->get();

        $empresa = Entidade::with("variacoes")->with('clientes')->with("marcas")->with("categorias")->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "clientes" => $clientes,
            "empresa" => $empresa,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.index', $head);
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

        if (!$user->can('criar todos') && !$user->can('criar cliente')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with('clientes')->with("marcas")->with("categorias")->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => __('messages.novo'),
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

        return view('dashboard.clientes.create-import', $head);
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

        if (!$user->can('criar todos') && !$user->can('criar cliente')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        try {
            Excel::import(new ClienteImport, $request->file('file'));
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
    public function create(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar cliente')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with('clientes')->with("marcas")->with("categorias")->findOrFail($entidade->empresa->id);

        $cursos = Curso::where('entidade_id', '=', $entidade->empresa->id)->get();
        $turnos = Turno::where('entidade_id', '=', $entidade->empresa->id)->get();
        $salas = Sala::where('entidade_id', '=', $entidade->empresa->id)->get();
        $anos_lectivos = AnoLectivo::where('entidade_id', '=', $entidade->empresa->id)->get();

        $tipos_atendimentos = TipoAtendimento::where('entidade_id', '=', $entidade->empresa->id)->get();
        $prioridades = Prioridade::where('entidade_id', '=', $entidade->empresa->id)->get();

        $planos = SeguradoraPlano::with(['seguradora'])->where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "user" => Auth::user(),
            "estados_civils" => EstadoCivil::get(),
            "provincias" => Provincia::get(),
            "municipios" => Municipio::get(),
            "distritos" => Distrito::get(),

            "tipos_atendimentos" => $tipos_atendimentos,
            "prioridades" => $prioridades,
            "planos" => $planos,

            "cursos" => $cursos,
            "turnos" => $turnos,
            "salas" => $salas,
            "anos_lectivos" => $anos_lectivos,

            "parent_id" => $request->parent_id ?? null,

            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.create', $head);
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

        if (!$user->can('criar todos') && !$user->can('criar cliente')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
            'nif' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa.tipo_entidade'])->findOrFail(Auth::user()->id);

            $code = uniqid(time());
            $nova_conta = "";


            $conta = Conta::where('conta', '31')->where('entidade_id', $entidade->empresa->id)->first();

            if ($request->tipo_cliente == "C") {
                if ($request->pais == "AO") {
                    $serie = "31.1.2.1.";
                } else {
                    $serie = "31.1.2.2.";
                }
            }
            if ($request->tipo_cliente == "TR") {
                if ($request->pais == "AO") {
                    $serie = "31.2.2.1.";
                } else {
                    $serie = "31.2.2.2.";
                }
            }
            if ($request->tipo_cliente == "TD") {
                if ($request->pais == "AO") {
                    $serie = "31.3.2.1.";
                } else {
                    $serie = "31.3.2.2.";
                }
            }
            if ($request->tipo_cliente == "CD") {
                if ($request->pais == "AO") {
                    $serie = "31.8.1.1.";
                } else {
                    $serie = "31.8.1.2.";
                }
            }
            if ($request->tipo_cliente == "SC") {
                if ($request->pais == "AO") {
                    $serie = "31.9.1.1.";
                } else {
                    $serie = "31.9.1.2.";
                }
            }

            $subc_ = Subconta::where('numero', 'like', "{$serie}%")->where('entidade_id', $entidade->empresa->id)->count() + 1;
            $nova_conta =  $serie . "{$subc_}";

            $subconta = Subconta::create([
                "entidade_id" => $entidade->empresa->id,
                "numero" => $nova_conta,
                "nome" => $request->nome,
                "tipo_conta" => "M",
                "code" => $code,
                "status" => $conta->status,
                "conta_id" => $conta->id,
                "user_id" => Auth::user()->id,
            ]);

            $clientes = Cliente::create([
                "nif" => $request->nif,
                "nome" => $request->nome,
                "conta" => $nova_conta,
                "tipo_cliente" => $request->tipo_cliente,
                "code" => $code,
                "parent_id" => $request->parent_id,
                "pais" => $request->pais,
                "status" => true,
                "gestor_conta" => NULL,
                "codigo_postal" => $request->codigo_postal,
                "localidade" => $request->localidade,
                "telefone" => $request->telefone,
                "telemovel" => $request->telemovel,
                "responsavel_nome" => $request->responsavel_nome,
                "responsavel_contacto" => $request->responsavel_contacto,

                "nome_do_pai" => $request->nome_do_pai,
                "nome_da_mae" => $request->nome_da_mae,
                "data_nascimento" => $request->data_nascimento,
                "genero" => $request->genero,
                "estado_civil_id" => $request->estado_civil_id,
                "provincia_id" => $request->provincia_id,
                "municipio_id" => $request->municipio_id,
                "distrito_id" => $request->distrito_id,

                "vencimento" => $request->vencimento,
                "email" => $request->email,
                "website" => $request->website,
                "referencia_externa" => $request->referencia_externa,
                "observacao" => $request->observacao,
                "user_id" => Auth::user()->id,
                "entidade_id" => $entidade->empresa->id,
                "subconta_id" => $subconta->id,
            ]);

            if ($request->plano_id) {
                SeguradoraPlanoBeneficiador::create([
                    'plano_id' => $request->plano_id,
                    'beneficiario_id' => $clientes->id,
                    'numero_cartao' => NULL,
                    'matricula' => NULL,
                    'data_inicio' => NULL,
                    'data_fim' => NULL,
                    'limite' => NULL,
                    'status' => "ACTIVO",
                    'observacoes' => NULL,
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
            }

            if ($clientes->parent_id == null) {
                $clientes->parent_id = $request->parent_id;
                $clientes->save();
            }

            $saldo = ContaCliente::create([
                "user_id" => Auth::user()->id,
                "divida_corrente" => 0,
                "divida_vencida" => 0,
                "saldo" => 0,
                "cliente_id" => $clientes->id,
                "entidade_id" => $entidade->empresa->id,
            ]);

            $codigo = time();

            if ($entidade->empresa->tipo_entidade->sigla == "CFOR") {
                $matricula = Matricula::create([
                    'status' => 'DESACTIVO',
                    'codigo' => $codigo,
                    'valor_pagamento' => 0,
                    'user_id' => Auth::user()->id,
                    'aluno_id' => $clientes->id,
                    'curso_id' => $request->curso_id,
                    'turno_id' => $request->turno_id,
                    'sala_id' => $request->sala_id,
                    'ano_lectivo_id' => $request->ano_lectivo_id,
                    'entidade_id' => $entidade->empresa->id,
                ]);

                $matricula->numero = "PROC " .  $matricula->id;
                $matricula->update();
            }

            if ($entidade->empresa->tipo_entidade->sigla == "HOSP") {

                if ($request->tipo_atendimento_id !== null) {
                    // Defina o intervalo de hoje
                    $inicioDoDia = Carbon::today();
                    $fimDoDia = Carbon::today()->endOfDay();

                    $tipo_atendimento = TipoAtendimento::findOrFail($request->tipo_atendimento_id);

                    $total_atendimentos = Atendimento::whereBetween('created_at', [$inicioDoDia, $fimDoDia])->where("tipo_atendimento_id", $tipo_atendimento->id ?? "")->where('entidade_id', $entidade->empresa->id)->count();
                    $total_atendimentos = $total_atendimentos  + 1;

                    Atendimento::create([
                        'numero' => "{$tipo_atendimento->sigla} - {$total_atendimentos}",
                        'status' => "aguardando",
                        'data_at' => date("Y-m-d"),
                        'code' => $code,
                        'cliente_id' => $clientes->id,
                        'prioridade_id' => $request->prioridade_id,
                        'tipo_atendimento_id' => $tipo_atendimento->id,
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }
            }

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
        //
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar cliente')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $hoje = Carbon::now()->toDateString(); // Data atual no formato YYYY-MM-DD

        $cliente = Cliente::with(["estado_civil", "plano.plano.seguradora", "provincia", "municipio", "distrito", "atendimentos", "contratos", 'filhos', 'parent'])->findOrFail($id);
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("clientes")->with("marcas")->with("categorias")->findOrFail($entidade->empresa->id);

        $conta = ContaCliente::where("cliente_id", $cliente->id)
            ->where("entidade_id", $entidade->empresa->id)
            ->first();

        $facturas = Venda::where("status_factura", "por pagar")
            ->where("cliente_id", $cliente->id)
            ->where("entidade_id", $entidade->empresa->id)
            ->with("cliente")
            ->orderby("created_at", "desc")
            ->get();

        $valorTotalCompras = Venda::where("status_factura", "pago")
            ->where("cliente_id", $cliente->id)
            ->where("entidade_id", $entidade->empresa->id)
            ->sum("valor_total");

        // dividas vencidas
        $facturasVencidas = Venda::whereDate("data_vencimento", "<=", $hoje)
            ->where("cliente_id", $cliente->id)
            ->where("status_factura", "por pagar")
            ->where("entidade_id", $entidade->empresa->id)
            ->sum("valor_total");

        //dividas corrente
        $facturasVencidasCorrente = Venda::where("cliente_id", $cliente->id)
            ->where("status_factura", "por pagar")
            ->where("entidade_id", $entidade->empresa->id)
            ->whereDate("data_vencimento", ">", $hoje)
            ->sum("valor_total");

        $matriculas = Matricula::with(["aluno", "ano_lectivo", "curso", "sala", "turno", "user"])->where("aluno_id", $cliente->id)
            ->where("entidade_id", $entidade->empresa->id)
            ->get();

        $head = [
            "titulo" =>  __('messages.mais_detalhes'),
            "descricao" => env("APP_NAME"),
            "conta" => $conta,
            "empresa" => $empresa,
            "cliente" => $cliente,
            "matriculas" => $matriculas,
            "facturas" => $facturas,
            "facturasVencidas" => $facturasVencidas,
            "facturasVencidasCorrente" => $facturasVencidasCorrente,
            "valorTotalCompras" => $valorTotalCompras,
            "loja" => User::with(["empresa"])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.clientes.show", $head);
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

        if (!$user->can("editar todos") || !$user->can("editar cliente")) {

            return redirect()->back()->with("danger", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $cliente = Cliente::with(['plano'])->findOrFail($id);

        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with("variacoes")->with("clientes")->with("marcas")->with("categorias")->findOrFail($entidade->empresa->id);

        $planos = SeguradoraPlano::with(['seguradora'])->where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => __('messages.editar'),
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "cliente" => $cliente,
            "estados_civils" => EstadoCivil::get(),
            "provincias" => Provincia::get(),
            "municipios" => Municipio::get(),
            "distritos" => Distrito::get(),
            "planos" => $planos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.edit', $head);
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

        if (!$user->can('editar todos') && !$user->can('editar cliente')) {
            return redirect()->back();
        }

        $request->validate([
            'nome' => 'required|string',
            'nif' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $code = uniqid(time());
            $nova_conta = "";
            $clientes = Cliente::findOrFail($id);

            $conta = Conta::where('conta', '31')->where('entidade_id', $entidade->empresa->id)->first();
            $serie = null;
            if ($request->tipo_cliente == "C") {
                if ($request->pais == "AO") {
                    $serie = "31.1.2.1.";
                } else {
                    $serie = "31.1.2.2.";
                }
            }
            if ($request->tipo_cliente == "TR") {
                if ($request->pais == "AO") {
                    $serie = "31.2.2.1.";
                } else {
                    $serie = "31.2.2.2.";
                }
            }
            if ($request->tipo_cliente == "TD") {
                if ($request->pais == "AO") {
                    $serie = "31.3.2.1.";
                } else {
                    $serie = "31.3.2.2.";
                }
            }
            if ($request->tipo_cliente == "CD") {
                if ($request->pais == "AO") {
                    $serie = "31.8.1.1.";
                } else {
                    $serie = "31.8.1.2.";
                }
            }
            if ($request->tipo_cliente == "SC") {
                if ($request->pais == "AO") {
                    $serie = "31.9.1.1.";
                } else {
                    $serie = "31.9.1.2.";
                }
            }


            if ($clientes->code == NULL) {

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
                dd($nova_conta, 1);
                $clientes->conta = $nova_conta;
                $clientes->code = $code;
                $clientes->subconta_id = $subconta->id;
            } else {

                if ($request->tipo_cliente != $clientes->tipo_cliente) {
                    $subconta = Subconta::where('numero', 'like', "{$serie}%")->where('entidade_id', $entidade->empresa->id)->count() + 1;
                    $nova_conta =  $serie . "{$subconta}";

                    if ($subconta) {
                        $subc_up = Subconta::findOrFail($clientes->subconta_id);
                        $subc_up->numero = $nova_conta;
                        $subc_up->code = $code;
                        $subc_up->nome = $request->nome;
                        $subc_up->update();
                    }
                } else {
                    $nova_conta =  $clientes->conta;
                }

                $clientes->conta = $nova_conta;
                $clientes->code = $code;
            }

            $clientes->nif = $request->nif;
            $clientes->nome = $request->nome;
            $clientes->tipo_cliente = $request->tipo_cliente;
            $clientes->pais = $request->pais;
            $clientes->gestor_conta = $request->gestor_conta;
            $clientes->codigo_postal = $request->codigo_postal;
            $clientes->localidade = $request->localidade;
            $clientes->telefone = $request->telefone;
            $clientes->telemovel = $request->telemovel;
            $clientes->responsavel_nome = $request->responsavel_nome;
            $clientes->responsavel_contacto = $request->responsavel_contacto;

            $clientes->nome_do_pai = $request->nome_do_pai;
            $clientes->nome_da_mae = $request->nome_da_mae;
            $clientes->data_nascimento = $request->data_nascimento;
            $clientes->genero = $request->genero;
            $clientes->estado_civil_id = $request->estado_civil_id;
            $clientes->provincia_id = $request->provincia_id;
            $clientes->municipio_id = $request->municipio_id;
            $clientes->distrito_id = $request->distrito_id;

            $clientes->vencimento = $request->vencimento;
            $clientes->email = $request->email;
            $clientes->website = $request->website;
            $clientes->referencia_externa = $request->referencia_externa;
            $clientes->observacao = $request->observacao;

            if ($request->plano_id) {
                $plano = SeguradoraPlanoBeneficiador::where('plano_id', $request->plano_id)->where('beneficiario_id', $clientes->id)->where('entidade_id', $entidade->empresa->id)->first();

                if ($plano) {
                    $p = SeguradoraPlanoBeneficiador::findOrFail($plano->id);
                    $p->plano_id = $request->plano_id;
                    $p->beneficiario_id = $clientes->id;
                    $p->update();
                } else {
                    SeguradoraPlanoBeneficiador::create([
                        'plano_id' => $request->plano_id,
                        'beneficiario_id' => $clientes->id,
                        'numero_cartao' => NULL,
                        'matricula' => NULL,
                        'data_inicio' => NULL,
                        'data_fim' => NULL,
                        'limite' => NULL,
                        'status' => "ACTIVO",
                        'observacoes' => NULL,
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }
            } else {
                SeguradoraPlanoBeneficiador::create([
                    'plano_id' => $request->plano_id,
                    'beneficiario_id' => $clientes->id,
                    'numero_cartao' => NULL,
                    'matricula' => NULL,
                    'data_inicio' => NULL,
                    'data_fim' => NULL,
                    'limite' => NULL,
                    'status' => "ACTIVO",
                    'observacoes' => NULL,
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
            }

            $clientes->update();

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

        if (!$user->can('eliminar todos') && !$user->can('eliminar cliente')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $cliente = Cliente::findOrFail($id);
            $cliente->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
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


    public function compras_clientes(Request $request, $id)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $cliente = Cliente::findOrFail($id);

        $vendas = ItemVenda::with(['factura', 'produto'])->whereHas('factura', function ($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('created_at', '>=', Carbon::parse($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('created_at', '<=', Carbon::parse($value));
            })
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $total_venda = ItemVenda::with(['factura', 'produto'])->whereHas('factura', function ($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('created_at', '>=', Carbon::parse($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('created_at', '<=', Carbon::parse($value));
            })
            ->where('entidade_id', $entidade->empresa->id)
            ->sum('valor_pagar');

        $empresa = Entidade::with(["caixas", "users", "lojas"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Compras do cliente",
            "descricao" => env('APP_NAME'),
            "vendas" => $vendas,
            "total_venda" => $total_venda,
            "empresa" => $empresa,
            "cliente" => $cliente,
            "entidade" => $entidade,
            "requests" => $request->all('data_inicio', 'data_final', 'cliente_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.compras', $head);
    }

    public function compras_pdf(Request $request)
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $cliente = Cliente::findOrFail($request->cliente_id);

        $vendas = ItemVenda::with(["factura", "produto"])->whereHas("factura", function ($query) use ($cliente) {
            $query->where("cliente_id", $cliente->id);
        })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate("created_at", ">=", Carbon::parse($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate("created_at", "<=", Carbon::parse($value));
            })
            ->where("entidade_id", $entidade->empresa->id)
            ->get();

        $total_venda = ItemVenda::with(["factura", "produto"])->whereHas("factura", function ($query) use ($cliente) {
            $query->where("cliente_id", $cliente->id);
        })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate("created_at", ">=", Carbon::parse($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate("created_at", "<=", Carbon::parse($value));
            })
            ->where("entidade_id", $entidade->empresa->id)
            ->sum("valor_pagar");

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "COMPRAR DO CLIENTE: {$cliente->nome}",
            "descricao" => env("APP_NAME"),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "vendas" => $vendas,
            "total_venda" => $total_venda,
            "cliente" => $cliente,
            "requests" => $request->all("data_inicio", "data_final", "cliente_id"),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView("dashboard.clientes.pdf", $head);
        $pdf->setPaper("A4", "portrait");

        return $pdf->stream();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ficha_cliente($id)
    {
        $user = auth()->user();
        $entidade = User::with(['empresa'])->findOrFail($user->id);

        $aluno = Cliente::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito'])->findOrFail($id);

        $matriculas = Matricula::with(['ano_lectivo', 'curso', 'sala', 'aluno', 'turno', 'entidade', 'user'])
            ->where('aluno_id', $aluno->id)
            ->get();

        $titulo = "";

        if ($entidade->empresa->tipo_entidade->sigla == "CFOR") {
            $titulo = "Ficha do Hospede";
        } else if ($entidade->empresa->tipo_entidade->sigla == "HOTL") {
            $titulo = "Ficha do Hospede";
        } else if ($entidade->empresa->tipo_entidade->sigla == "CONS") {
            $titulo = "Ficha do Paciente";
        } else if ($entidade->empresa->tipo_entidade->sigla == "HOSP") {
            $titulo = "Ficha do Paciente";
        } else if ($entidade->empresa->tipo_entidade->sigla == "CFAT") {
            $titulo = "Ficha do Cliente";
        }

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        $head = [
            "titulo" => $titulo,
            "descricao" => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "aluno" => $aluno,
            "matriculas" => $matriculas,

            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.clientes.ficha-cliente', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }


    public function creditar(Request $request)
    {
        $request->validate(['creditar_cliente_id' => 'required', 'valor' => 'required|numeric|min:0.01']);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $cartao = ContaCliente::where('cliente_id', $request->creditar_cliente_id)->firstOrFail();

            if ($request->valor > $cartao->saldo) {
                return response()->json(['success' => false, 'message' => 'Saldo insuficiente.']);
            }

            $cartao->saldo -= $request->valor;
            $cartao->save();

            MovimentoContaCliente::create([
                "observacao" => "credito",
                "documento" => "credito",
                "conta_id" => $cartao->id,
                "montante" => $request->valor,
                "cliente_id" => $request->creditar_cliente_id,
                "data_emissao" => date("Y-m-d"),
                "tipo_movimento" => "-1",
                "user_id" => Auth::user()->id,
                "entidade_id" => $entidade->empresa->id,
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

        return response()->json(['success' => true, 'novo_saldo' => number_format($cartao->saldo, 2, ',', '.')]);
    }

    public function debitar(Request $request)
    {
        $request->validate(['debitar_cliente_id' => 'required', 'valor' => 'required|numeric|min:0.01']);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $cartao = ContaCliente::where('cliente_id', $request->debitar_cliente_id)->firstOrFail();

            $cartao->saldo += $request->valor;
            $cartao->save();

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            MovimentoContaCliente::create([
                "observacao" => "debito",
                "documento" => "credito",
                "conta_id" => $cartao->id,
                "montante" => $request->valor,
                "cliente_id" => $request->debitar_cliente_id,
                "data_emissao" => date("Y-m-d"),
                "tipo_movimento" => "1",
                "user_id" => Auth::user()->id,
                "entidade_id" => $entidade->empresa->id,
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

        return response()->json(['success' => true, 'novo_saldo' => number_format($cartao->saldo, 2, ',', '.')]);
    }

    public function get_seguradora_empresa($id)
    {
        $cliente = Cliente::findOrFail($id);

        $seguradoras = Seguradora::when($cliente->seguradora_id, function ($query, $value) {
            $query->where('id', $value);
        })
            ->where('entidade_id', auth()->user()->entidade_id)
            ->get();

        $parentes = Cliente::where('id', $cliente->parent_id)
            ->where('entidade_id', auth()->user()->entidade_id)
            ->get();

        $option_seguradora = "<option value=''>Escolher</option>";
        foreach ($seguradoras as $seguradora) {
            $option_seguradora = '<option value="' . $seguradora->id . '" >' . $seguradora->nome . '<option>';
        }

        $option_parentes = "<option value=''>Escolher</option>";
        foreach ($parentes as $parente) {
            $option_parentes = '<option value="' . $parente->id . '" >' . $parente->nome . '<option>';
        }

        return ["seguradoras" => $option_seguradora, "parentes" => $option_parentes];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home(Request $request)
    {
        $paciente = Cliente::where('id', $request->paciente_id)->first();

        return Cliente::where('id', $paciente->parent_id)
            ->where('entidade_id', auth()->user()->entidade_id)
            ->select('id', 'nome')
            ->get();
    }
}
