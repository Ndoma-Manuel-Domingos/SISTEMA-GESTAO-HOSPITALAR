<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use App\Models\CatalogoExame\DuracaoPreInscricaoMedica;
use App\Models\CatalogoExame\FrequenciaPreInscricaoMedica;
use App\Models\CatalogoExame\ViaPreInscricaoMedica;
use App\Models\Categoria;
use App\Models\CIDS;
use App\Models\Cliente;
use App\Models\Consulta;
use App\Models\ConsultaItem;
use App\Models\DisponibilidadeMedica;
use App\Models\ResultadoConsulta;
use App\Models\ResultadoConsultaParamentroImagem;
use App\Models\ResultadoConsultaParamentro;
use App\Models\Entidade;
use App\Models\Internamento;
use App\Models\Medico;
use App\Models\LojaProduto;
use App\Models\Prioridade;
use App\Models\Produto;
use App\Models\ReceitaMedica;
use App\Models\ReceitaMedicaItem;
use App\Models\TipoAtendimento;
use App\Models\User;
use App\Models\UserLoja;
use App\Services\ContaHospitalarService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;

use PDF;
use phpseclib\Crypt\RSA;

class ConsultaController extends Controller
{
    use TraitChavesSaft;
    use TraitHelpers;


    protected ContaHospitalarService $contaHospitalarService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ContaHospitalarService $contaHospitalarService)
    {
        $this->middleware('auth');
        $this->contaHospitalarService = $contaHospitalarService;
    }

    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar consulta') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $consultas = Consulta::when($request->data_consulta, function ($query, $value) {
            $query->whereDate('data_consulta', $value);
        })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->when($request->medico_id, function ($query, $value) {
                $query->where('medico_id', $value);
            })
            ->when($request->paciente_id, function ($query, $value) {
                $query->where('paciente_id', $value);
            })
            ->with(['paciente', 'medico', 'entidade', 'user'])->where("entidade_id", $entidade->empresa->id)->orderBy('created_at', 'desc')->get();

        $empresa = Entidade::with(['variacoes', 'clientes', 'marcas', 'categorias'])->findOrFail($entidade->empresa->id);

        $medicos = Medico::with(["funcionario"])
            ->where("tipo", "Medico")
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $pacientes = Cliente::where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => "Consultas",
            "descricao" => env('APP_NAME'),
            "consultas" => $consultas,
            "medicos" => $medicos,
            "pacientes" => $pacientes,
            "empresa" => $empresa,
            "requests" => $request->all("status", "data_consulta", "medico_id", "paciente_id"),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.consultas.index', $head);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function imprimir_all(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $consultas = Consulta::when($request->data_consulta, function ($query, $value) {
            $query->whereDate('data_consulta', $value);
        })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->when($request->medico_id, function ($query, $value) {
                $query->where('medico_id', $value);
            })
            ->when($request->paciente_id, function ($query, $value) {
                $query->where('paciente_id', $value);
            })
            ->with(['paciente', 'medico', 'entidade', 'user', 'items.produto'])->where("entidade_id", $entidade->empresa->id)->orderBy('created_at', 'desc')->get();

        $medico = Medico::with(["funcionario"])->find($request->medico_id);

        $paciente = Cliente::find($request->paciente_id);

        $head = [
            "titulo" => "RELATÓRIO DE CONSULTAS AGENDAS",
            "descricao" => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "consultas" => $consultas,
            "medico" => $medico,
            "paciente" => $paciente,
            "requests" => $request->all("status", "data_consulta"),
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.consultas.imprimir-todas', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function receitas_imprimir($id)
    {
        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        $receita = ReceitaMedica::with(["items", "atendimento", "atendimento.paciente", "user"])->findOrFail($id);

        $head = [
            "titulo" => "Receita Médica",
            "descricao" => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "receita" => $receita,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.consultas.receita-pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }


    public function create(Request $request, $id = null)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar consulta') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $categoria = Categoria::whereIn('categoria', ['Consultas', 'Consulta', 'consultas', 'consulta'])->where('entidade_id', $entidade->empresa->id)->pluck("id");

        $produtos = Produto::whereIn('categoria_id', $categoria)->where('entidade_id', $entidade->empresa->id)
            ->get();

        $origem = null;

        if ($request->origem == "internamento") {
            $origem = Internamento::find($request->internamento_id);
        } else
        if ($request->origem == "atendimento") {
            $origem = Atendimento::find($request->atendimento_id);
        }

        $tipos_atendimentos = TipoAtendimento::where("entidade_id", $entidade->entidade_id)->get();

        $prioridades = Prioridade::where('entidade_id', $entidade->empresa->id)->get();

        $medicos = Medico::with(["funcionario"])
            ->where("tipo", "Medico")
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $pacientes = Cliente::when($origem ? $origem->cliente_id : "", function ($query, $value) {
            $query->where('id', $value);
        })
            ->when($origem ? $origem->paciente_id : "", function ($query, $value) {
                $query->where('id', $value);
            })
            ->when($request->paciente_id, function ($query, $value) {
                $query->where('id', $value);
            })
            ->where('entidade_id', $entidade->empresa->id)->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "Marcar Consultas",
            "descricao" => env('APP_NAME'),
            "entidade" => $entidade,
            "produtos" => $produtos,
            "medicos" => $medicos,
            "origem" => $origem,
            "request_ordem" => $request->origem,
            "tipos_atendimentos" => $tipos_atendimentos,
            "prioridades" => $prioridades,
            "pacientes" => $pacientes,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.consultas.create', $head);
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

        if (!$user->can('criar todos') && !$user->can('criar consulta') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            "data_consulta" => "required|date",
            "hora_consulta" => "required",
            "paciente_id" => "required",
            "prioridade_id" => "required",
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

            $items = ConsultaItem::with(["produto.categoria", "produto.paramentros_consulta"])->whereNull("consulta_id")
                ->where("user_id", Auth::user()->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->get();

            $total = 0;

            foreach ($items as $item) {
                $total = $total + $item->valor;
            }

            $atendimento = null;

            if ($request->origem_id != null) {
                $atendimento = Atendimento::findOrFail($request->origem_id);
            }

            // Data passada
            $dataHoraConsulta = Carbon::parse($request->data_consulta . ' ' . $request->hora_consulta);

            if ($dataHoraConsulta->lt(now())) {
                return response()->json(["message" => "Não é permitido agendar consultas em datas passadas."], 404);
            }

            // Médico já possui consulta neste horário
            if ($request->medico_id != null) {
                $consultaMedico = Consulta::where('medico_id', $request->medico_id)
                    ->whereDate('data_consulta', $request->data_consulta)
                    ->whereTime('hora_consulta', $request->hora_consulta)
                    ->whereNotIn('status', ['CANCELADA', 'CONCLUIDO'])
                    ->exists();

                if ($consultaMedico) {
                    return response()->json(["message" => "O médico já possui uma consulta neste horário."], 404);
                }
            }

            if ($request->paciente_id != null) {

                // Paciente já possui consulta neste horário
                $consultaPaciente = Consulta::where('paciente_id', $request->paciente_id)
                    ->whereDate('data_consulta', $request->data_consulta)
                    ->whereTime('hora_consulta', $request->hora_consulta)
                    ->whereNotIn('status', ['CANCELADA', 'CONCLUIDO'])
                    ->exists();

                if ($consultaPaciente) {
                    return response()->json(["message" => "O paciente já possui uma consulta neste horário."], 404);
                }
            }

            $consulta = Consulta::create([
                "data_consulta" => $request->data_consulta,
                "hora_consulta" => $request->hora_consulta,
                "paciente_id" => $request->paciente_id,
                "medico_id" => $request->medico_id,
                "atendimento_id" => $atendimento ? $atendimento->id : NULL,
                "status" => "AGENDADA",
                "pago" => "NAO PAGO",
                "total" => $total,
                "user_id" => Auth::user()->id,
                "entidade_id" =>  $entidade->empresa->id,
                "observacao" => $request->observacao,
                "movito_agendamento" => $request->movito_agendamento,
            ]);

            $resultado = ResultadoConsulta::create([
                "consulta_id" => $consulta->id,
                "status" => "processo",
                "referencia" => "RESULT-" . time(),
                "observacoes_resultado" => NULL,
                "data_realizacao" => NULL,
                "hora_realizacao" => NULL,
                "user_id" => Auth::user()->id,
                "entidade_id" =>  $entidade->empresa->id,
            ]);

            foreach ($items as $item) {

                if ($item->produto->paramentros_consulta) {
                    foreach ($item->produto->paramentros_consulta as $paramentro) {
                        if ($paramentro->tipo == "imagem") {
                            ResultadoConsultaParamentroImagem::create([
                                "resultado_id" => $resultado->id,
                                "parametro_id" => $paramentro->id,
                                "ficheiro" => NULL,
                                "descricao" => NULL,
                                "ordem" => NULL,
                                "item_consulta_id" => $item->id,
                                "user_id" => Auth::user()->id,
                                "entidade_id" =>  $entidade->empresa->id,
                            ]);
                        }
                        ResultadoConsultaParamentro::create([
                            "resultado_id" => $resultado->id,
                            "parametro_id" => $paramentro->id,
                            "valor" => NULL,
                            "item_consulta_id" => $item->id,
                            "user_id" => Auth::user()->id,
                            "entidade_id" =>  $entidade->empresa->id,
                        ]);
                    }
                }

                $item_ = ConsultaItem::findOrFail($item->id);
                $item_->consulta_id = $consulta->id;
                $item_->status = "concluido";
                $item_->update();
            }

            if ($atendimento) {
                $atendimento = Atendimento::findOrFail($atendimento->id);
                $atendimento->status = "atendido";
                $atendimento->update();
            } else {

                $code = uniqid(time());

                $inicioDoDia = Carbon::parse($request->data_consulta)->startOfDay();
                $fimDoDia = Carbon::parse($request->data_consulta)->endOfDay();

                $tipo_atendimento = TipoAtendimento::where("sigla", "Consulta")->where('entidade_id', $entidade->empresa->id)->first();

                $total_atendimentos = Atendimento::whereBetween('created_at', [$inicioDoDia, $fimDoDia])->where("tipo_atendimento_id", $tipo_atendimento->id ?? "")->where('entidade_id', $entidade->empresa->id)->count();
                $total_atendimentos = $total_atendimentos  + 1;

                $sigla = $tipo_atendimento ? $tipo_atendimento->sigla : NULL;

                $atendimento = Atendimento::create([
                    'status' => "aguardando",
                    'numero' => "{$sigla} - {$total_atendimentos}",
                    'cliente_id' => $request->paciente_id,
                    'prioridade_id' => $request->prioridade_id,
                    'data_at' => $request->data_consulta,
                    'code' => $code,
                    'tipo_atendimento_id' => $tipo_atendimento ? $tipo_atendimento->id : NULL,
                    'profissional_id' => $request->medico_id,
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);

                $consulta->atendimento_id = $atendimento->id;
                $consulta->update();
            }

            $request->merge([
                'atendimento_id' => $atendimento->id,
            ]);

            $conta = $this->contaHospitalarService->create($request);

            foreach ($items as $item) {
                $request->merge([
                    "produto_id" => $item->produto_id,
                    "quantidade" => 1,
                    "preco_unitario" => $item->valor,
                ]);

                $this->contaHospitalarService->adicionarItem($request, $conta->id);
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

        return response()->json(["message" => "Consulta registrada com sucesso!", "consulta" => $consulta]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function receita_medico($id)
    {
        $user = auth()->user();

        if (!$user->can('consultorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $atendimento = Atendimento::with(["receita", "paciente", "triagem", "consultas", "exames", "internamento"])->findOrFail($id);

        $cids = CIDS::where('entidade_id', $entidade->empresa->id)
            ->get();


        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

        $produtos = Produto::whereIn("id", $meus_produtos)
            ->where('tipo', 'P')
            ->whereNotIn('tipo_stock', ['P'])
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('nome', 'asc')
            ->get();

        $vias = ViaPreInscricaoMedica::get();
        $duracoes = DuracaoPreInscricaoMedica::get();
        $frequencias = FrequenciaPreInscricaoMedica::get();

        $head = [
            "produtos" => $produtos,
            "vias" => $vias,
            "duracoes" => $duracoes,
            "frequencias" => $frequencias,
            "titulo" => "Receita Médica",
            "descricao" => env('APP_NAME'),
            "cids" => $cids,
            "atendimento" => $atendimento,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.consultas.receita', $head);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function receita_medico_post(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('consultorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $receita = ReceitaMedica::create([
                "atendimento_id" => $request->atendimento_id,
                "observacoes" => $request->observacoes,
                "user_id" => Auth::user()->id,
                "entidade_id" => $entidade->empresa->id,
            ]);

            foreach ($request->medicamentos as $item) {
                ReceitaMedicaItem::create([
                    "receita_id" => $receita->id,
                    "medicamento" => $item["medicamento"],
                    "posologia" => $item["posologia"],
                    "duracao_dias" => $item["duracao"],
                    "observacoes" => $item["observacoes"] ?? null,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);
            }

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning("Informação", $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(["message" => "Consulta registrada com sucesso!", "receita" => $receita]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function imprimir(string $id)
    {
        $consulta = Consulta::with([
            "paciente",
            "items",
        ])->findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "RELATÓRIO DE CONSULTA FICHA COMPLETA",
            "descricao" => env('APP_NAME'),
            "consulta" => $consulta,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.consultas.ficha-consulta-completa', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function imprimir_individual(string $id)
    {
        $consulta = Consulta::with([
            "paciente",
            "items",
        ])->findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "FICHA DE CONSULTA INDIVIDUAL",
            "descricao" => env('APP_NAME'),
            "consulta" => $consulta,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.consultas.ficha-consulta-individual', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atestado_medico($id)
    {

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $consulta = Consulta::with(['cids', 'items', 'paciente.estado_civil', 'medico', 'entidade', 'user'])->findOrFail($id);

        $head = [
            "titulo" => "Atestado Médico",
            "descricao" => env('APP_NAME'),
            "consulta" => $consulta,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.consultas.atestado-medico', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
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

        if (!$user->can('listar todos') && !$user->can('listar consulta') && !$user->can('consultorio') && !$user->can('laboratorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $consulta = Consulta::with(["items.paramentos_consultas_imagem.paramentro", "items.paramentos_consultas.paramentro", "atendimento.exames.items", "paciente.estado_civil", "medico", "entidade", "user"])->findOrFail($id);

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env("APP_NAME"),
            "consulta" => $consulta,
            "loja" => User::with(["empresa"])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.consultas.show', $head);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizarItems(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar consulta') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $produto = Produto::findOrFail($request->exame_id);
        $consulta = Consulta::findOrFail($request->consulta_id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $verificar_items = ConsultaItem::where('produto_id', $produto->id)
                ->where('consulta_id', $consulta->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            if (!$verificar_items) {
                ConsultaItem::create([
                    'produto_id' => $produto->id,
                    'consulta_id' => $consulta->id,
                    'valor' => $produto->preco_venda,
                    'status' => "processo",
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
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

        $query = ConsultaItem::with(["produto.categoria"])
            ->where('consulta_id', $consulta->id)
            ->where('entidade_id', $entidade->empresa->id);

        $items = $query->get();
        $total = $query->sum("valor");

        return response()->json(["items" => $items, "total" => $total], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteActualizarItems($id, $consulta_id)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar consulta') && !$user->can('monitoramento central atendimento')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $consulta = ConsultaItem::findOrFail($id);
        $consulta->delete();

        $query = ConsultaItem::with(["produto.categoria"])
            ->where('consulta_id', $consulta_id)
            ->where('entidade_id', $entidade->empresa->id);

        $items = $query->get();
        $total = $query->sum("valor");

        return response()->json(["items" => $items, "total" => $total], 200);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adicionarItems(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar consulta') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $produto = Produto::findOrFail($request->exame_id);


            $verificar_items = ConsultaItem::where('produto_id', $produto->id)
                ->where('consulta_id', NULL)
                ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            if (!$verificar_items) {
                ConsultaItem::create([
                    'produto_id' => $produto->id,
                    'consulta_id' => NULL,
                    'valor' => $produto->preco_venda,
                    'status' => "processo",
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
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

        $query = ConsultaItem::with(["produto.categoria"])->whereNull('consulta_id')
            ->where('user_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id);

        $items = $query->get();
        $total = $query->sum("valor");

        return response()->json(["items" => $items, "total" => $total], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteItems($id)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar consulta') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $consulta = ConsultaItem::findOrFail($id);
        $consulta->delete();

        $query = ConsultaItem::with(["produto.categoria"])->whereNull('consulta_id')
            ->where('user_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id);

        $items = $query->get();
        $total = $query->sum("valor");

        return response()->json(["items" => $items, "total" => $total], 200);
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

        if (!$user->can('editar todos') && !$user->can('editar consulta') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $consulta = Consulta::with(["paciente", "items", "medico"])->findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'clientes', 'marcas', 'categorias'])->findOrFail($entidade->empresa->id);

        $produtos = Produto::where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $medicos = Medico::with(["funcionario"])
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $pacientes = Cliente::where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $prioridades = Prioridade::where('entidade_id', $entidade->empresa->id)
            ->get();

        $head = [
            "titulo" =>  __('messages.editar'),
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produtos" => $produtos,
            "medicos" => $medicos,
            "pacientes" => $pacientes,
            "prioridades" => $prioridades,
            "consulta" => $consulta,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.consultas.edit', $head);
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

        if (!$user->can('editar todos') && !$user->can('editar consulta') && !$user->can('monitoramento central atendimento')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            "data_consulta" => "required",
            "hora_consulta" => "required",
            "paciente_id" => "required",
            "medico_id" => "required",
        ]);

        $consulta = Consulta::findOrFail($id);
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $items = ConsultaItem::with(["produto.categoria"])->where("consulta_id", $consulta->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->get();

            $total = 0;

            foreach ($items as $item) {
                $total = $total + $item->valor;
            }

            $consulta->data_consulta = $request->data_consulta;
            $consulta->hora_consulta = $request->hora_consulta;
            $consulta->paciente_id = $request->paciente_id;
            $consulta->medico_id = $request->medico_id;
            $consulta->status = $consulta->status;
            $consulta->pago = $consulta->pago;
            $consulta->total = $total;
            $consulta->observacao = $request->observacao;
            $consulta->movito_agendamento = $request->movito_agendamento;

            foreach ($items as $item) {
                $item_ = ConsultaItem::findOrFail($item->id);
                $item_->consulta_id = $consulta->id;
                $item_->status = "concluido";
                $item_->update();
            }

            $consulta->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(["message" => "Consulta registrada com sucesso!", "consulta" => $consulta]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancelar_consulta($id)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar consulta') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $consulta = Consulta::findOrFail($id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            if ($consulta->status == "AGENDADA") {
                $estado = "CANCELADA";
            }

            if ($consulta->status == "CANCELADA") {
                $estado = "AGENDADA";
            }

            $consulta->status = $estado;
            $consulta->update();

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dar_tratamento_consulta($id)
    {
        $user = auth()->user();

        if (!$user->can('consultorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $consulta = Consulta::findOrFail($id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $estado = "EM ATENDIMENTO";

            $consulta->status = $estado;
            $consulta->update();

            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => 'Consulta em tratamento!'], 200);
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

        if (!$user->can('eliminar todos') && !$user->can('eliminar consulta')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $consulta = Consulta::findOrFail($id);
            $consulta->delete();

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


    public function medicosDisponiveis(Request $request)
    {
        $medicos = Medico::with(['funcionario'])->whereHas('disponibilidades', function ($q) use ($request) {
            $q->whereDate('data_inicio', $request->data)
                ->where('estado', 'Disponível');
        })->get();

        return response()->json($medicos);
    }

    public function horariosDisponiveis(Request $request)
    {
        $disponibilidades = DisponibilidadeMedica::where('medico_id', $request->medico_id)
            ->whereDate('data_inicio', $request->data)
            ->where('estado', 'Disponível')
            ->get();

        $horarios = [];

        foreach ($disponibilidades as $d) {
            $inicio = Carbon::parse($d->data_inicio);
            $fim = Carbon::parse($d->data_fim);

            while ($inicio < $fim) {
                $proximo = $inicio->copy()->addMinutes(30);
                $ocupado = Consulta::where('medico_id', $request->medico_id)
                    ->where('data_consulta', $inicio)
                    ->exists();

                if (!$ocupado) {
                    $horarios[] = [
                        'inicio' => $inicio->format('H:i'),
                        'fim' => $proximo->format('H:i')
                    ];
                }
                $inicio->addMinutes(30);
            }
        }

        return response()->json($horarios);
    }
}
