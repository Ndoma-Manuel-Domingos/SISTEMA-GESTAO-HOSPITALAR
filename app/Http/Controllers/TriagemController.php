<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use App\Models\Cliente;
use App\Models\Entidade;
use App\Models\Equipa;
use App\Models\FichaTriagem;
use App\Models\Leito;
use App\Models\Medico;
use App\Models\Prioridade;
use App\Models\Produto;
use App\Models\TipoAtendimento;
use App\Models\User;
use App\Services\ConsultorioService;
use App\Services\RelatorioTriagemService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;

use Ramsey\Uuid\Uuid;

class TriagemController extends Controller
{
    use TraitHelpers;

    protected $service;

    public function __construct(RelatorioTriagemService $service)
    {
        $this->middleware('auth');
        $this->service = $service;
    }

    public function dashboard()
    {
        return response()->json([
            'triagensPeriodo' => $this->service->triagensPeriodo(),
            'prioridades' => $this->service->prioridades(),
            'profissionais' => $this->service->profissionais(),
            'queixas' => $this->service->queixas(),
            'imc' => $this->service->imc(),
            'sinaisVitais' => $this->service->sinaisVitais(),
            'status' => $this->service->status(),
            'tempoAtendimento' => $this->service->tempoAtendimento(),
            'entidades' => $this->service->entidades(),
            'cards' => $this->service->cards()
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar triagem') && !$user->can('monitoramento enfermagem triagem')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $triagens = FichaTriagem::with(["paciente", "prioridade", "profissional", "atendimento", "tipo_atendimento", "entidade"])
            ->where("entidade_id", $entidade->empresa->id)
            ->orderBy("created_at", "desc")
            ->get();

        $tipo_atentimento = TipoAtendimento::whereIn("sigla", ["Triagem"])
            ->where("entidade_id", $entidade->empresa->id)
            ->pluck("id");

        $atendimentos = Atendimento::whereIn("tipo_atendimento_id", $tipo_atentimento)
            ->where("entidade_id", $entidade->empresa->id)
            ->whereDate("data_at", date("Y-m-d"))
            ->whereIn("status", ["em atendimento"])
            ->get();


        $empresa = Entidade::with(["variacoes", "clientes", "marcas", "categorias"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Triagens",
            "descricao" => env("APP_NAME"),
            "triagens" => $triagens,
            "empresa" => $empresa,
            "atendimentos" => $atendimentos,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.triagens.index", $head);
    }

    //
    public function create(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar triagem') && !$user->can('monitoramento enfermagem triagem')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $atendimento = Atendimento::with(["paciente", "triagem"])->findOrFail($request->atendimento_id);

        $pacientes = Cliente::when($atendimento->cliente_id, function ($query, $value) {
            $query->where("id", $value);
        })
            ->where("entidade_id", $entidade->entidade_id)
            ->get();

        $leitos = Leito::where("status", "livre")
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $produtos = Produto::where("tipo", "S")
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $equipas = Equipa::whereIn("status", ["desactiva"])
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $prioridades = Prioridade::where("entidade_id", $entidade->entidade_id)->get();
        $tipos_atendimentos = TipoAtendimento::where("entidade_id", $entidade->entidade_id)->get();
        $medicos = Medico::with(["funcionario"])->where("entidade_id", $entidade->entidade_id)->get();

        $head = [
            "titulo" => "Colectar dados clinico: {$atendimento->paciente->nome}",
            "descricao" => env('APP_NAME'),
            "pacientes" => $pacientes,
            "prioridades" => $prioridades,
            "atendimento" => $atendimento,
            "tipos_atendimentos" => $tipos_atendimentos,
            "medicos" => $medicos,
            "leitos" => $leitos,
            "produtos" => $produtos,
            "equipas" => $equipas,
            "user" => Auth::user(),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.triagens.create', $head);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ConsultorioService $service)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar triagem') && !$user->can('monitoramento enfermagem triagem')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            "paciente_id" => "required|string",
            "atendimento_id" => "required|string",
            "queixa_principal" => "required|string",
            "prioridade_id" => "required|string",
        ]);

        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $tipo_atendimento = TipoAtendimento::where("sigla", $request->tipo_atendimento_id)->first();

            $triagem = FichaTriagem::updateOrCreate(
                [
                    'atendimento_id' => $request->atendimento_id,
                    'paciente_id' => $request->paciente_id,
                    "entidade_id" => $entidade->empresa->id,
                ],
                [
                    "pressao" => $request->pressao,
                    "peso" => $request->peso,
                    "altura" => $request->altura,
                    "temperatura" => $request->temperatura,
                    "freq_respiratoria" => $request->freq_respiratoria,

                    "estado_consciencia" => $request->estado_consciencia,
                    "pressao_diatolica" => $request->pressao_diatolica,
                    "saturacao_oxigenio" => $request->saturacao_oxigenio,
                    "escala_dor" => $request->escala_dor,
                    "circunferencia_abdominal" => $request->circunferencia_abdominal,
                    "glicemia_capilar" => $request->glicemia_capilar,
                    "gravidez" => $request->gravidez,

                    "freq_cardiaca" => $request->freq_cardiaca,
                    "imc" => $this->calcularIMC($request->peso ?? 1, $request->altura ?? 1)['imc'],
                    "imc_classificacao" => $this->calcularIMC($request->peso ?? 1, $request->altura ?? 1)['classificacao'],
                    "observacoes" => $request->observacoes,
                    "queixa_principal" => $request->queixa_principal,
                    "profissional_id" => $request->profissional_id,
                    "prioridade_id" => $request->prioridade_id,
                    "tipo_atendimento_id" => $tipo_atendimento ? $tipo_atendimento->id : null,
                    "status" => "CONCLUIDO",
                    "user_id" => Auth::id(),
                    "entidade_id" => $entidade->empresa->id,
                ]
            );

            // Defina o intervalo de hoje
            $inicioDoDia = Carbon::today();
            $fimDoDia = Carbon::today()->endOfDay();

            $tipo_atendimento = TipoAtendimento::findOrFail($tipo_atendimento->id);

            $total_atendimentos = Atendimento::whereBetween('created_at', [$inicioDoDia, $fimDoDia])->where("tipo_atendimento_id", $tipo_atendimento->id ?? "")->where('entidade_id', $entidade->empresa->id)->count();
            $total_atendimentos = $total_atendimentos  + 1;

            if ($tipo_atendimento && isset($tipo_atendimento) && !empty($tipo_atendimento)) {
                $atendimento = Atendimento::findOrFail($request->atendimento_id);
                $atendimento->status = "aguardando";
                $atendimento->prioridade_id = $request->prioridade_id;
                $atendimento->numero = "{$tipo_atendimento->sigla} - {$total_atendimentos}";
                $atendimento->profissional_id = $request->profissional_id;
                $atendimento->tipo_atendimento_id = $tipo_atendimento ? $tipo_atendimento->id : NULL;
                $atendimento->update();

                $service->createConsultaAutomatic($atendimento, $user);
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

    public function calcularIMC($peso, $altura)
    {
        if ($peso <= 0 || $altura <= 0) {
            return "Peso e altura devem ser maiores que zero.";
        }

        $imc = $peso / ($altura * $altura);
        $classificacao = "";

        if ($imc < 18.5) {
            $classificacao = "Abaixo do peso";
        } else if ($imc >= 18.5 && $imc < 24.9) {
            $classificacao = "Peso normal";
        } else if ($imc >= 25 && $imc < 29.9) {
            $classificacao = "Sobrepeso";
        } else if ($imc >= 30 && $imc < 34.9) {
            $classificacao = "Obesidade grau 1";
        } else if ($imc >= 35 && $imc < 39.9) {
            $classificacao = "Obesidade grau 2";
        } else {
            $classificacao = "Obesidade grau 3";
        }
        return [
            "imc" => $imc,
            "classificacao" => $classificacao
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function imprimir_ficha(string $id)
    {
        $triagem = FichaTriagem::with([
            "paciente",
            "prioridade",
        ])->findOrFail($id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Ficha de Triagem",
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "descricao" => env('APP_NAME'),
            "triagem" => $triagem,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.triagens.imprimir-ficha', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar triagem') && !$user->can('monitoramento enfermagem triagem')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $triagens = FichaTriagem::with(["paciente", "prioridade", "profissional", "tipo_atendimento", "atendimento", "entidade"])->findOrFail($id);
        $empresa = Entidade::with(["variacoes", "clientes", "marcas", "categorias"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Triagens",
            "descricao" => env("APP_NAME"),
            "triagem" => $triagens,
            "empresa" => $empresa,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.triagens.show", $head);
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

        if (!$user->can('editar todos') && !$user->can('editar triagem') && !$user->can('monitoramento enfermagem triagem')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $triagem = FichaTriagem::with(["paciente", "prioridade", "profissional", "tipo_atendimento", "entidade"])
            ->findOrFail($id);
        //
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $prioridades = Prioridade::where("entidade_id", $entidade->entidade_id)->get();
        $tipos_atendimentos = TipoAtendimento::where("entidade_id", $entidade->entidade_id)->get();
        $medicos = Medico::with(["funcionario"])->where("entidade_id", $entidade->entidade_id)->get();

        $head = [
            "titulo" => "Colectar dados clinico: {$triagem->paciente->nome}",
            "descricao" => env('APP_NAME'),
            "triagem" => $triagem,
            "prioridades" => $prioridades,
            "tipos_atendimentos" => $tipos_atendimentos,
            "medicos" => $medicos,
            "user" => Auth::user(),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.triagens.edit', $head);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar triagem') && !$user->can('monitoramento enfermagem triagem')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $ficha = FichaTriagem::findOrFail($id);

            $ficha->pressao = $request->pressao;
            $ficha->peso = $request->peso;
            $ficha->altura = $request->altura;
            $ficha->temperatura = $request->temperatura;
            $ficha->freq_respiratoria = $request->freq_respiratoria;
            $ficha->freq_cardiaca = $request->freq_cardiaca;
            $ficha->imc = $this->calcularIMC($request->peso ?? 1, $request->altura ?? 1)['imc'];
            $ficha->imc_classificacao = $this->calcularIMC($request->peso ?? 1, $request->altura ?? 1)['classificacao'];
            $ficha->observacoes = $request->observacoes;
            $ficha->profissional_id = $request->profissional_id;
            $ficha->prioridade_id = $request->prioridade_id;
            $ficha->tipo_atendimento_id = $request->tipo_atendimento_id;
            $ficha->status = "CONCLUIDO";
            $ficha->update();

            // Defina o intervalo de hoje
            $inicioDoDia = Carbon::today();
            $fimDoDia = Carbon::today()->endOfDay();

            $tipo_atendimento = TipoAtendimento::findOrFail($request->tipo_atendimento_id);

            $total_atendimentos = Atendimento::whereBetween('created_at', [$inicioDoDia, $fimDoDia])->where("tipo_atendimento_id", $tipo_atendimento->id ?? "")->where('entidade_id', $entidade->empresa->id)->count();
            $total_atendimentos = $total_atendimentos  + 1;

            Atendimento::create([
                'status' => "aguardando",
                'numero' => "{$tipo_atendimento->sigla} - {$total_atendimentos}",
                'cliente_id' => $ficha->paciente_id,
                'data_at' => date("Y-m-d"),
                'prioridade_id' => $request->prioridade_id,
                'tipo_atendimento_id' => $request->tipo_atendimento_id,
                'profissional_id' => $request->profissional_id,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning("Informação", $e->getMessage());
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

        if (!$user->can('eliminar todos') && !$user->can('eliminar triagem')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $ficha = FichaTriagem::findOrFail($id);
            $ficha->delete();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Excluídos com sucesso!"], 200);
    }
}
