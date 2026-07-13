<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use App\Models\CIDS;
use App\Models\Cliente;
use App\Models\Consulta;
use App\Models\ConsultaItem;
use App\Models\Entidade;
use App\Models\Medico;
use App\Models\Prioridade;
use App\Models\Produto;
use App\Models\ResultadoConsulta;
use App\Models\ResultadoConsultaParamentro;
use App\Models\ResultadoConsultaParamentroImagem;
use Illuminate\Http\Request;
use App\Models\TipoAtendimento;
use App\Models\User;
use App\Services\ConsultorioService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ConsultorioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user->can('monitoramento consultorio') && !$user->can('consultorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $consultas = Consulta::with(["paciente", "cids", "exames", "paciente", "items", "medico", "entidade", "user"])->where([
            ["entidade_id", "=", $entidade->empresa->id],
        ])->orderBy("created_at", "desc")->get();

        $tipo_atentimento = TipoAtendimento::whereIn("sigla", ["Consulta"])
            ->where("entidade_id", $entidade->empresa->id)
            ->pluck("id");

        $atendimentos = Atendimento::whereIn("tipo_atendimento_id", $tipo_atentimento)
            ->where("entidade_id", $entidade->empresa->id)
            ->whereDate("data_at", date("Y-m-d"))
            ->whereIn("status", ["em atendimento"])
            ->get();

        $empresa = Entidade::with(["variacoes", "clientes", "marcas", "categorias"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Consultas",
            "descricao" => env("APP_NAME"),
            "consultas" => $consultas,
            "empresa" => $empresa,
            "atendimentos" => $atendimentos,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.consultas.consultorio", $head);
    }


    public function create(Request $request)
    {
        $user = auth()->user();
        if (!$user->can('monitoramento consultorio') && !$user->can('consultorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $origem = null;

        if ($request->origem == "atendimento") {
            $origem = Atendimento::with(["consultas.items", "consultas.items.paramentos_consultas.paramentro", "consultas.items.paramentos_consultas_imagem.paramentro", "triagem"])->find($request->atendimento_id);
        }

        $tipos_atendimentos = TipoAtendimento::where("entidade_id", $entidade->entidade_id)->get();
        $cids = CIDS::where("entidade_id", $entidade->entidade_id)->get();

        $prioridades = Prioridade::where('entidade_id', $entidade->empresa->id)->get();

        $medicos = Medico::with(["funcionario"])->where("tipo", "Medico")->where('entidade_id', $entidade->empresa->id)->orderBy('created_at', 'desc')->get();

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
            "titulo" => "Começar com a Consulta",
            "descricao" => env('APP_NAME'),
            "entidade" => $entidade,
            "medicos" => $medicos,
            "origem" => $origem,
            "request_ordem" => $request->origem,
            "tipos_atendimentos" => $tipos_atendimentos,
            "prioridades" => $prioridades,
            "cids" => $cids,
            "pacientes" => $pacientes,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.consultas.consultorio-create', $head);
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

        if (!$user->can('monitoramento consultorio') && !$user->can('consultorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            "cliente_id" => "required|string",
            "tipo_atendimento_id" => "required|string",
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

            // Defina o intervalo de hoje
            $inicioDoDia = Carbon::today();
            $fimDoDia = Carbon::today()->endOfDay();

            // Destino
            $tipo_atendimento = TipoAtendimento::findOrFail($request->tipo_atendimento_id);

            $atendimento = Atendimento::findOrFail($request->origem_id);

            if ($tipo_atendimento->sigla == "Casa") {
                $atendimento->status = "atendido";
                $atendimento->update();
            }
            if ($tipo_atendimento->sigla == "Consulta" || $tipo_atendimento->sigla == "consulta") {
                $atendimento->status = "atendido";
                $atendimento->update();
            } else {
                $total_atendimentos = Atendimento::whereBetween('created_at', [$inicioDoDia, $fimDoDia])->where("tipo_atendimento_id", $tipo_atendimento->id ?? "")->where('entidade_id', $entidade->empresa->id)->count();
                $total_atendimentos = $total_atendimentos  + 1;

                $atendimento->status = "aguardando";
                $atendimento->numero = "{$tipo_atendimento->sigla} - {$total_atendimentos}";
                $atendimento->tipo_atendimento_id = $tipo_atendimento ? $tipo_atendimento->id : NULL;
                $atendimento->update();
            }

            $consulta = Consulta::where('atendimento_id', $atendimento->id)->first();

            if ($consulta) {

                $consulta->update([
                    "queixa_principal" => $request->queixa_principal,
                    "historia_doenca_actual" => $request->historia_doenca_actual,
                    "historico_medico" => $request->historico_medico,
                    "data_consulta" => date("Y-m-d"),
                    "hora_consulta" => date("H:m:i"),
                    "exame_medico" => $request->exame_medico,
                    "alergias_conhecidas" => $request->alergias_conhecidas,
                    "anotacoes_gerais" => $request->anotacoes_gerais,
                    "avaliado" => $request->avaliado,
                    "diagnosticado" => $request->diagnosticado,
                    "cids_id" => $request->cids_id,
                    "paciente_id" => $request->cliente_id,
                    "atendimento_id" => $atendimento ? $atendimento->id : NULL,
                    "status" => "CONCLUIDO",
                ]);

                $resultado_consulta = ResultadoConsulta::where('consulta_id', $consulta->id)->first();

                if ($resultado_consulta) {
                    $resultado = ResultadoConsulta::findOrFail($resultado_consulta->id);
                    $resultado->observacoes_resultado = $request->diagnosticado;
                    $resultado->status = 'concluido';
                    $resultado->data_realizacao = date("Y-m-d");
                    $resultado->hora_realizacao = date("h:i:s");
                    $resultado->update();
                }
            } else {

                $consulta = Consulta::create([
                    "queixa_principal" => $request->queixa_principal,
                    "historia_doenca_actual" => $request->historia_doenca_actual,
                    "historico_medico" => $request->historico_medico,
                    "data_consulta" => date("Y-m-d"),
                    "hora_consulta" => date("H:m:i"),
                    "exame_medico" => $request->exame_medico,
                    "alergias_conhecidas" => $request->alergias_conhecidas,
                    "anotacoes_gerais" => $request->anotacoes_gerais,
                    "avaliado" => $request->avaliado,
                    "diagnosticado" => $request->diagnosticado,
                    "cids_id" => $request->cids_id,
                    "paciente_id" => $request->cliente_id,
                    "atendimento_id" => $atendimento ? $atendimento->id : NULL,
                    "status" => "CONCLUIDO",
                    "user_id" => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);

                $resultado_consulta = ResultadoConsulta::where('consulta_id', $consulta->id)->first();

                if ($resultado_consulta) {
                    $resultado = ResultadoConsulta::findOrFail($resultado_consulta->id);
                    $resultado->observacoes_resultado = $request->diagnosticado;
                    $resultado->status = 'concluido';
                    $resultado->data_realizacao = date("Y-m-d");
                    $resultado->hora_realizacao = date("h:i:s");
                    $resultado->update();
                }
            }

            // CAMPOS DINÂMICOS
            if ($request->has('campos')) {
                foreach ($request->campos ?? [] as $paramentroId => $valor) {
                    ResultadoConsultaParamentro::where('id', $paramentroId)
                        ->update([
                            'valor' => $valor
                        ]);
                }
            }

            if ($_FILES) {
                foreach ($request->file('imagens') ?? [] as $paramentroId => $arquivos) {
                    $ficheiros = [];
                    foreach ($arquivos as $arquivo) {
                        $name = time() . '_' . uniqid() . '.' . $arquivo->getClientOriginalExtension();
                        $destinationPath = public_path('resultados/consultas');
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0777, true);
                        }
                        $arquivo->move($destinationPath, $name);
                        $ficheiros[] = 'resultados/consultas/' . $name;
                    }
                    ResultadoConsultaParamentroImagem::where('id', $paramentroId)
                        ->update([
                            'ficheiro' => json_encode($ficheiros)
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


        return response()->json(["message" => "Consulta registrada com sucesso!", "consulta" => $consulta, "destino" => $tipo_atendimento->sigla]);
    }


    public function verificarPaciente()
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $tipo_atendimento = TipoAtendimento::where('sigla', 'Consulta')->where('entidade_id', $entidade->empresa->id)->first();

        $atendimento = Atendimento::with(['paciente', 'consultas.items.produto'])
            ->where('tipo_atendimento_id', $tipo_atendimento->id)
            ->whereDate("data_at", date("Y-m-d"))
            ->whereIn("status", ["em atendimento"])
            ->where('entidade_id', $entidade->empresa->id)
            ->first();

        if ($atendimento) {

            $especialidades = $atendimento->consultas
                ->flatMap(function ($consulta) {
                    return $consulta->items;
                })
                ->map(function ($item) {
                    return optional($item->produto)->nome;
                })
                ->filter()
                ->unique()
                ->values();

            return response()->json([
                'status' => true,
                'id' => $atendimento->id,
                'paciente' => $atendimento->paciente->nome,
                'processo' => $atendimento->numero,
                'especialidade' => $especialidades
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }
}
