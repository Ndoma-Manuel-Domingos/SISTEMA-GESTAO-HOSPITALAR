<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use App\Models\Cliente;
use App\Models\Entidade;
use App\Models\Exame;
use App\Models\Medico;
use App\Models\Prioridade;
use App\Models\ResultadoExame;
use App\Models\TipoAtendimento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaboratorioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user->can('monitoramento laboratorio') && !$user->can('laboratorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $exames = Exame::with(["consulta", "prioridade", "solicitante_paciente", "solicitante_medico", "profissional", "paciente", "user", "items", "profissional_saude", "solicitante_medico", "entidade"])->where([
            ["entidade_id", "=", $entidade->empresa->id],
        ])->orderBy("created_at", "desc")->get();

        $tipo_atentimento = TipoAtendimento::whereIn("sigla", ["Exames"])
            ->where("entidade_id", $entidade->empresa->id)
            ->pluck("id");

        $atendimentos = Atendimento::whereIn("tipo_atendimento_id", $tipo_atentimento)
            ->where("entidade_id", $entidade->empresa->id)
            ->whereDate("data_at", date("Y-m-d"))
            ->whereIn("status", ["em atendimento"])
            ->get();

        $empresa = Entidade::with(["variacoes", "clientes", "marcas", "categorias"])->findOrFail($entidade->empresa->id);


        $head = [
            "titulo" => "Exames",
            "descricao" => env("APP_NAME"),
            "exames" => $exames,
            "empresa" => $empresa,
            "atendimentos" => $atendimentos,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.exames.laboratorio", $head);
    }

    public function create(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('monitoramento laboratorio') && !$user->can('laboratorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $origem = null;

        if ($request->origem == "atendimento") {
            $origem = Atendimento::with(["exames.items", "exames.items.paramentos_exames.paramentro", "exames.items.paramentos_exames_imagem.paramentro", "triagem"])->find($request->atendimento_id);
        }

        $tipos_atendimentos = TipoAtendimento::where("entidade_id", $entidade->entidade_id)->get();

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
            "titulo" => "Começar lançamento de resultados",
            "descricao" => env('APP_NAME'),
            "entidade" => $entidade,
            "medicos" => $medicos,
            "origem" => $origem,
            "request_ordem" => $request->origem,
            "tipos_atendimentos" => $tipos_atendimentos,
            "prioridades" => $prioridades,
            "pacientes" => $pacientes,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.exames.laboratorio-create', $head);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function enviar_resultados($id, Request $request)
    {
        $user = auth()->user();

        if (!$user->can('monitoramento laboratorio') && !$user->can('laboratorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            "status" => "required|string"
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $exames = Atendimento::with(['exames'])->findOrFail($id);

        try {
            DB::beginTransaction();

            $tipo_atendimento = TipoAtendimento::findOrFail($request->status);

            $inicioDoDia = Carbon::today();
            $fimDoDia = Carbon::today()->endOfDay();

            if ($tipo_atendimento->sigla == "Casa") {
                $atendimento = Atendimento::findOrFail($request->origem_id);
                $atendimento->status = "atendido";
                $atendimento->update();
            } else {
                $total_atendimentos = Atendimento::whereBetween('created_at', [$inicioDoDia, $fimDoDia])->where("tipo_atendimento_id", $tipo_atendimento->id ?? "")->where('entidade_id', $entidade->empresa->id)->count();
                $total_atendimentos = $total_atendimentos  + 1;

                $atendimento = Atendimento::findOrFail($request->origem_id);
                $atendimento->status = "aguardando";
                $atendimento->numero = "{$tipo_atendimento->sigla} - {$total_atendimentos}";
                $atendimento->tipo_atendimento_id = $tipo_atendimento ? $tipo_atendimento->id : NULL;
                $atendimento->update();
            }

            foreach ($exames->exames as $exame) {
                if ($exame->status == "CONCLUIDO") {
                    $resultado_exame = ResultadoExame::where('exame_id', $exame->id)->first();
                    if ($resultado_exame) {
                        $resultado = ResultadoExame::findOrFail($resultado_exame->id);
                        $resultado->observacoes_resultado = $request->observacao;
                        $resultado->status = 'concluido';
                        $resultado->data_realizacao = date("Y-m-d");
                        $resultado->hora_realizacao = date("h:i:s");
                        $resultado->update();
                    }
                    // Realizar operações de banco de dados aqui
                    $exame->status = "CONCLUIDO";
                    $exame->observacao = $request->observacao;
                    $exame->update();
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            dd('Informação', $e->getMessage());
            return redirect()->back();
        }

        return response()->json(['message' => 'Dados lançados com sucesso!'], 200);
    }

    public function verificarPaciente()
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $tipo_atendimento = TipoAtendimento::where('sigla', 'Exames')->where('entidade_id', $entidade->empresa->id)->first();

        $atendimento = Atendimento::with(['paciente', 'exames.items.produto'])
            ->where('tipo_atendimento_id', $tipo_atendimento->id)
            ->whereDate("data_at", date("Y-m-d"))
            ->whereIn("status", ["em atendimento"])
            ->where('entidade_id', $entidade->empresa->id)
            ->first();

        if ($atendimento) {

            $especialidades = collect($atendimento->exames)
                ->flatMap(function ($exame) {
                    return $exame->items ?? collect();
                })
                ->map(function ($item) {
                    return data_get($item, 'produto.nome');
                })
                ->filter()
                ->unique()
                ->sort()
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
