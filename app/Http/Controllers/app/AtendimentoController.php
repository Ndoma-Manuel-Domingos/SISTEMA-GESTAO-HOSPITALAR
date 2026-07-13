<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\Atendimento;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Medico;
use App\Models\Prioridade;
use App\Models\Produto;
use App\Models\SolicitacaoMedica;
use App\Models\TipoAtendimento;
use App\Models\User;
use App\Services\ContaHospitalarService;
use App\Services\RelatorioAtendimentoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;

use Ramsey\Uuid\Uuid;

class AtendimentoController extends Controller
{

    use TraitHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected RelatorioAtendimentoService $service;
    protected ContaHospitalarService $contaHospitalarService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RelatorioAtendimentoService $service, ContaHospitalarService $contaHospitalarService)
    {
        $this->middleware('auth');
        $this->service = $service;
        $this->contaHospitalarService = $contaHospitalarService;
    }


    public function dashboard()
    {
        return response()->json([
            'total_hoje' => $this->service->totalAtendimentosHoje(),
            'total_mes' => $this->service->totalAtendimentosMes(),
            'por_status' => $this->service->atendimentosPorStatus(),
            'por_medico' => $this->service->atendimentosPorMedico(),
            'por_tipo' => $this->service->atendimentosPorTipo(),
            'taxa_faltas' => $this->service->taxaFaltas(),
            'internamentos_mes' => $this->service->internamentosMes(),
            'tratamentos_ativos' => $this->service->tratamentosAtivos(),
            'evolucao_diaria' => $this->service->evolucaoDiaria(),
            'evolucao_mensal' => $this->service->evolucaoMensal(),
            'tempo_medio' => $this->service->tempoMedioAtendimento(),
            'tempo_medio_dia' => $this->service->tempoMedioPorDia(),
        ]);
    }


    public function index(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar atendimento') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        if (empty($request->data_at) && !isset($request->data_at)) {
            $request->data_at = date("Y-m-d");
        }

        $query = Atendimento::with(["paciente", "prioridade", "tipo"])
            ->when($request->prioridad_id, function ($query, $value) {
                $query->where('prioridade_id', $value);
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->when($request->funcionario_id, function ($query, $value) {
                $query->where('profissional_id', $value);
            })
            ->when($request->data_at, function ($query, $value) {
                $query->whereDate('data_at', $value);
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('paciente', function ($p) use ($search) {
                    $p->where('nome', 'like', "%{$search}%")
                        ->orWhere('nif', 'like', "%{$search}%");
                });
            });
        }

        $atendimentos = $query->where("entidade_id", $entidade->entidade_id)
            ->orderBy("id", "desc")
            ->get();

        $pacientes = Cliente::where("entidade_id", $entidade->entidade_id)->get();
        $prioridades = Prioridade::where("entidade_id", $entidade->entidade_id)->get();
        $tipos_atendimentos = TipoAtendimento::where("entidade_id", $entidade->entidade_id)->get();
        $medicos = Medico::with(["funcionario"])->where("entidade_id", $entidade->entidade_id)->get();

        $data = date("Y-m-d");

        $solicitacoes = SolicitacaoMedica::where("entidade_id", $entidade->entidade_id)->whereDate('created_at', $data)->count();
        $solicitacoes_pendente = SolicitacaoMedica::where('status', 'pendente')->whereDate('created_at', $data)->where("entidade_id", $entidade->entidade_id)->count();
        $solicitacoes_agendado = SolicitacaoMedica::where('status', 'agendado')->whereDate('created_at', $data)->where("entidade_id", $entidade->entidade_id)->count();
        $solicitacoes_executado = SolicitacaoMedica::where('status', 'executado')->whereDate('created_at', $data)->where("entidade_id", $entidade->entidade_id)->count();
        $solicitacoes_cancelado = SolicitacaoMedica::where('status', 'cancelado')->whereDate('created_at', $data)->where("entidade_id", $entidade->entidade_id)->count();

        $categoria = Categoria::whereIn('categoria', ['Consultas', 'Consulta', 'consultas', 'consulta'])->where('entidade_id', $entidade->empresa->id)->pluck("id");

        $produtos = Produto::whereIn('categoria_id', $categoria)->where('entidade_id', $entidade->empresa->id)
            ->get();

        $head = [
            "titulo" => "Atendimentos",
            "descricao" => env("APP_NAME"),
            "produtos" => $produtos,
            "solicitacoes" => $solicitacoes,
            "solicitacoes_pendente" => $solicitacoes_pendente,
            "solicitacoes_agendado" => $solicitacoes_agendado,
            "solicitacoes_executado" => $solicitacoes_executado,
            "solicitacoes_cancelado" => $solicitacoes_cancelado,
            "atendimentos" => $atendimentos,
            "pacientes" => $pacientes,
            "prioridades" => $prioridades,
            "tipos_atendimentos" => $tipos_atendimentos,
            "medicos" => $medicos,
            "requests" => $request->all("paciente_id", "prioridad_id", "status", "funcionario_id", "data_inicio", "data_final"),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.atendimentos.index", $head);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function imprimir(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        if (empty($request->data_inicio) && !isset($request->data_inicio)) {
            $request->data_inicio = date("Y-m-d");
        }

        $atendimentos = Atendimento::when($request->paciente_id, function ($query, $value) {
            $query->where("cliente_id", $value);
        })
            ->when($request->prioridad_id, function ($query, $value) {
                $query->where('prioridade_id', $value);
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->when($request->funcionario_id, function ($query, $value) {
                $query->where('profissional_id', $value);
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('data_at', '=', $value);
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('data_at', '<=', $value);
            })
            ->with(["paciente", "prioridade", "tipo"])
            ->where("entidade_id", $entidade->entidade_id)
            ->orderBy("id", "desc")
            ->get();

        $paciente = Cliente::find($request->paciente_id);
        $prioridade = Prioridade::find($request->prioridade_id);
        $medico = Medico::with(["funcionario"])->find($request->funcionario_id);


        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "RELATÓRIO DE ATENDIMENTOS",
            "descricao" => env("APP_NAME"),
            "atendimentos" => $atendimentos,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "paciente" => $paciente,
            "prioridade" => $prioridade,
            "medico" => $medico,
            "requests" => $request->all("status", "data_inicio", "data_final"),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.atendimentos.imprimir', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
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

        if (!$user->can('criar todos') && !$user->can('criar atendimento') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'cliente_id' => 'required|string',
            'tipo_atendimento_id' => 'required|string',
            'prioridade_id' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            // Verifica se o paciente já possui um atendimento em aberto
            $atendimentoAberto = Atendimento::where('cliente_id', $request->cliente_id)
                ->whereIn('status', ['aguardando', 'em atendimento'])
                ->exists();

            if ($atendimentoAberto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este paciente já possui um atendimento em andamento (Aguardando ou Em Atendimento).'
                ], 404);
            }

            // Defina o intervalo de hoje
            $inicioDoDia = Carbon::today();
            $fimDoDia = Carbon::today()->endOfDay();

            $tipo_atendimento = TipoAtendimento::findOrFail($request->tipo_atendimento_id);

            $total_atendimentos = Atendimento::whereBetween('created_at', [$inicioDoDia, $fimDoDia])->where("tipo_atendimento_id", $tipo_atendimento->id ?? "")->where('entidade_id', $entidade->empresa->id)->count();
            $total_atendimentos = $total_atendimentos  + 1;

            $atendimento = Atendimento::create([
                'code' => Uuid::uuid4(),
                'status' => "aguardando",
                'numero' => "{$tipo_atendimento->sigla} - {$total_atendimentos}",
                'cliente_id' => $request->cliente_id,
                'data_at' => date("Y-m-d"),
                'prioridade_id' => $request->prioridade_id,
                'tipo_atendimento_id' => $request->tipo_atendimento_id,
                'profissional_id' => $request->profissional_id,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);

            $request->merge([
                'paciente_id'   => $request->cliente_id,
                'atendimento_id' => $atendimento->id,
                'observacao'    => 'conta aberta com sucesso',
            ]);

            $this->contaHospitalarService->create($request);

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            dd('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function definir_atendido_paciente($id)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar atendimento') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $atendimento = Atendimento::findOrFail($id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $atendimento->status = "atendido";
            $atendimento->update();

            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => 'Atendimento concluído com sucesso!'], 200);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atender_paciente($id)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar atendimento') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $atendimento = Atendimento::with(['tipo'])->findOrFail($id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $tipo_atendimento = TipoAtendimento::find($atendimento->tipo_atendimento_id);

            $atendimento_verificacao = Atendimento::with(['paciente'])
                ->where('tipo_atendimento_id', $atendimento->tipo_atendimento_id)
                ->whereDate("data_at", date("Y-m-d"))
                ->whereIn("status", ["em atendimento"])
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            if ($atendimento_verificacao) {
                return response()->json([
                    'status' => false,
                    'message' => "A área de {$tipo_atendimento->nome} encontra-se ocupada no momento. Aguarde a conclusão do atendimento atual para encaminhar um novo paciente."
                ], 409);
            }


            if ($atendimento->status == "aguardando") {
                $estado = "em atendimento";
            }

            $atendimento->status = $estado;
            $atendimento->update();

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

    public function show($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar atendimento') && !$user->can('monitoramento central atendimento') && !$user->can('consultorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $atendimento = Atendimento::with([
            "receita.items",
            "planoTratamento.equipa",
            "internamento",
            'contaHospitalar.itens.servico',
            "exames",
            "consultas",
            "triagem",
            "user",
            "entidade",
            "paciente.plano.plano.seguradora",
            "prioridade",
            "tipo"
        ])->findOrFail($id);


        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $produtos = Produto::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env("APP_NAME"),
            "atendimento" => $atendimento,
            "produtos" => $produtos,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.atendimentos.show", $head);
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

        if (!$user->can('editar todos') && !$user->can('editar atendimento') && !$user->can('monitoramento central atendimento')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $atendimento = Atendimento::with(["paciente", "prioridade", "tipo"])->findOrFail($id);

        return response()->json(['success' => true, 'data' => $atendimento], 200);
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

        if (!$user->can('editar todos') && !$user->can('editar atendimento') && !$user->can('monitoramento central atendimento')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'cliente_id' => 'required|string',
            'tipo_atendimento_id' => 'required|string',
            'prioridade_id' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $atendimento = Atendimento::findOrFail($id);
            $atendimento->update($request->all());

            $atendimento->update();

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

        if (!$user->can('eliminar todos') && !$user->can('eliminar atendimento') && !$user->can('monitoramento central atendimento')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $atendimento = Atendimento::findOrFail($id);
            $atendimento->delete();

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

    public function verificarSolicitacoes()
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $solicitacao = SolicitacaoMedica::with(['paciente', 'items.produto'])
            ->whereDate('created_at', now()->toDateString())
            ->whereIn("status", ["pendente"])
            ->where('entidade_id', $entidade->empresa->id)
            ->first();

        if ($solicitacao) {

            $especialidades = collect($solicitacao->items)
                ->map(function ($item) {
                    return data_get($item, 'produto.nome');
                })
                ->filter()
                ->unique()
                ->sort()
                ->values();


            return response()->json([
                'status' => true,
                'id' => $solicitacao->id,
                'paciente' => optional($solicitacao->paciente)->nome,
                'processo' => $solicitacao->solicitacao,
                'especialidade' => $especialidades
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }
}
