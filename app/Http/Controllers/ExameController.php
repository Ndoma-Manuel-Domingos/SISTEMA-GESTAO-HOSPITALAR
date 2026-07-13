<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Entidade;
use App\Models\Exame;
use App\Models\ExameItem;
use App\Models\ResultadoExame;
use App\Models\ResultadoExameSubParamentro;
use App\Models\ResultadoExameSubParamentroImagem;
use App\Models\Medico;
use App\Models\Prioridade;
use App\Models\Produto;
use App\Models\ResultadoExameParamentro;
use App\Models\TipoAtendimento;
use App\Models\User;
use App\Services\ContaHospitalarService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;

class ExameController extends Controller
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

        if (!$user->can('listar todos') && !$user->can('listar exame') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        //
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $exames = Exame::when($request->data_exame, function ($query, $value) {
            $query->whereDate('data_exame', $value);
        })
            ->when($request->prioridade_id, function ($query, $value) {
                $query->where('prioridade_id', $value);
            })
            ->when($request->paciente_id, function ($query, $value) {
                $query->where('paciente_id', $value);
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->with(['items', 'prioridade', 'paciente', 'profissional_saude.funcionario', 'entidade', 'user'])
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $empresa = Entidade::with(['variacoes', 'clientes', 'marcas', 'categorias'])->findOrFail($entidade->empresa->id);

        $pacientes = Cliente::where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $prioridades = Prioridade::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Exames",
            "descricao" => env('APP_NAME'),
            "exames" => $exames,
            "empresa" => $empresa,
            "pacientes" => $pacientes,
            "prioridades" => $prioridades,
            "requests" => $request->all("data_exame", "prioridade_id", "paciente_id", "status"),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.exames.index', $head);
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

        $exames = Exame::when($request->data_exame, function ($query, $value) {
            $query->whereDate('data_exame', $value);
        })
            ->when($request->prioridade_id, function ($query, $value) {
                $query->where('prioridade_id', $value);
            })
            ->when($request->paciente_id, function ($query, $value) {
                $query->where('paciente_id', $value);
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->with(['items.produto', 'prioridade', 'paciente', 'profissional_saude.funcionario', 'entidade', 'user'])
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $paciente = Cliente::find($request->paciente_id);

        $prioridade = Prioridade::find($request->prioridade_id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "RELATÓRIO DE EXAMES AGENDAS",
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "descricao" => env('APP_NAME'),
            "exames" => $exames,
            "paciente" => $paciente,
            "prioridade" => $prioridade,
            "requests" => $request->all("status", "data_exame"),
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.exames.imprimir-todas', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }


    public function create(Request $request)
    {
        $user = auth()->user();
        if (!$user->can('criar todos') && !$user->can('criar exame') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $categoria = Categoria::whereIn('categoria', ['Exames', 'Exame', 'exames', 'exame'])->where('entidade_id', $entidade->empresa->id)->pluck("id");

        $produtos = Produto::whereIn('categoria_id', $categoria)->where('entidade_id', $entidade->empresa->id)
            ->get();

        $prioridades = Prioridade::where('entidade_id', $entidade->empresa->id)
            ->get();

        $origem = Atendimento::with(['contaHospitalar'])->find($request->atendimento_id);

        $medicos = Medico::with(["funcionario"])
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $pacientes = Cliente::when($origem ? $origem->cliente_id : "", function ($query, $value) {
            $query->where('id', $value);
        })
            ->when($request->paciente_id, function ($query, $value) {
                $query->where('id', $value);
            })
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => "Marcar Exames",
            "descricao" => env('APP_NAME'),
            "entidade" => $entidade,
            "produtos" => $produtos,
            "medicos" => $medicos,
            "origem" => $origem,
            "request_ordem" => $request->origem,
            "prioridades" => $prioridades,
            "pacientes" => $pacientes,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.exames.create', $head);
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

        if (!$user->can('criar todos') && !$user->can('criar exame') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            "data_exame" => "required|date",
            "hora_exame" => "required",
            "paciente_id" => "required",
            "prioridade_id" => "required",
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

            $items = ExameItem::with(["produto.categoria", "produto.paramentros.subparamentros"])->whereNull("exame_id")
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

            $solicitante_id = $atendimento->cliente_id ?? null;
            $solicitante_type = "PACIENTE";

            // Data passada
            $dataHoraExame = Carbon::parse($request->data_exame . ' ' . $request->hora_exame);

            if ($dataHoraExame->lt(now())) {
                return response()->json(["message" => "Não é permitido agendar consultas em datas passadas."], 404);
            }

            // Médico já possui consulta neste horário
            if ($request->profissional_saude_id != null) {
                $exameMedico = Exame::where('profissional_saude_id', $request->profissional_saude_id)
                    ->whereDate('data_exame', $request->data_exame)
                    ->whereTime('hora_exame', $request->hora_exame)
                    ->whereNotIn('status', ['CANCELADA', 'CONCLUIDO'])
                    ->exists();

                if ($exameMedico) {
                    return response()->json(["message" => "O médico já possui uma consulta neste horário."], 404);
                }
            }

            if ($request->paciente_id != null) {

                // Paciente já possui consulta neste horário
                $examePaciente = Exame::where('paciente_id', $request->paciente_id)
                    ->whereDate('data_exame', $request->data_exame)
                    ->whereTime('hora_exame', $request->hora_exame)
                    ->whereNotIn('status', ['CANCELADA', 'CONCLUIDO'])
                    ->exists();

                if ($examePaciente) {
                    return response()->json(["message" => "O paciente já possui uma consulta neste horário."], 404);
                }
            }

            $exame = Exame::create([
                "data_exame" => $request->data_exame,
                "hora_exame" => $request->hora_exame,
                "paciente_id" => $request->paciente_id,
                "prioridade_id" => $request->prioridade_id,
                "atendimento_id" => $atendimento ? $atendimento->id : NULL,
                "profissional_saude_id" => $request->profissional_saude_id ?? NULL,
                "solicitante_id" => $solicitante_id ?? $request->prioridade_id,
                "solicitante_type" => $solicitante_type ?? "PACIENTE",
                "status" => "AGENDADA",
                "user_id" => Auth::user()->id,
                "entidade_id" =>  $entidade->empresa->id,
                "observacao" => $request->observacao,
            ]);

            $resultado = ResultadoExame::create([
                "exame_id" => $exame->id,
                "status" => "processo",
                "referencia" => "RESULT-" . time(),
                "observacoes_resultado" => NULL,
                "data_realizacao" => NULL,
                "hora_realizacao" => NULL,
                "user_id" => Auth::user()->id,
                "entidade_id" =>  $entidade->empresa->id,
            ]);

            foreach ($items as $item) {
                if ($item->produto->paramentros) {
                    foreach ($item->produto->paramentros as $paramentro) {

                        $resultado_parametro = ResultadoExameParamentro::create([
                            'resultado_id' => $resultado->id,
                            'exame_id' => $paramentro->exame_id,
                            'nome' => $paramentro->nome,
                            'ordem' => $paramentro->ordem,
                            "user_id" => Auth::user()->id,
                            "entidade_id" =>  $entidade->empresa->id,
                        ]);

                        foreach ($paramentro->subparamentros as $subparamentro) {
                            if ($subparamentro->tipo == "imagem") {
                                $i = ResultadoExameSubParamentroImagem::create([
                                    "resultado_id" => $resultado->id,
                                    "parametro_id" => $resultado_parametro->id,
                                    "subparametro_exame_id" => $subparamentro->id,
                                    'ficheiro' => NULL,
                                    'descricao' => NULL,
                                    'ordem' => NULL,
                                    "item_exame_id" => $item->id,
                                    "user_id" => Auth::user()->id,
                                    "entidade_id" =>  $entidade->empresa->id,
                                ]);
                            }
                            $p = ResultadoExameSubParamentro::create([
                                "resultado_id" => $resultado->id,
                                "parametro_id" => $resultado_parametro->id,
                                "subparametro_exame_id" => $subparamentro->id,
                                "valor" => NULL,
                                "item_exame_id" => $item->id,
                                "user_id" => Auth::user()->id,
                                "entidade_id" =>  $entidade->empresa->id,
                            ]);
                        }
                    }
                }

                $item_ = ExameItem::findOrFail($item->id);
                $item_->exame_id = $exame->id;
                $item_->status = "concluido";
                $item_->update();
            }

            if ($atendimento && $atendimento != null) {
                $atendimento = Atendimento::findOrFail($atendimento->id);
                $atendimento->status = "aguardando";
                $atendimento->update();
            } else {

                $code = uniqid(time());

                $inicioDoDia = Carbon::parse($request->data_exame)->startOfDay();
                $fimDoDia = Carbon::parse($request->data_exame)->endOfDay();

                $tipo_atendimento = TipoAtendimento::where("sigla", "Exames")->where('entidade_id', $entidade->empresa->id)->first();

                $total_atendimentos = Atendimento::whereBetween('created_at', [$inicioDoDia, $fimDoDia])->where("tipo_atendimento_id", $tipo_atendimento->id ?? "")->where('entidade_id', $entidade->empresa->id)->count();
                $total_atendimentos = $total_atendimentos  + 1;

                $sigla = $tipo_atendimento ? $tipo_atendimento->sigla : NULL;

                $atendimento = Atendimento::create([
                    'status' => "aguardando",
                    'numero' => "{$sigla} - {$total_atendimentos}",
                    'cliente_id' => $request->paciente_id,
                    'prioridade_id' => $request->prioridade_id,
                    'data_at' => $request->data_exame,
                    'code' => $code,
                    'tipo_atendimento_id' => $tipo_atendimento ? $tipo_atendimento->id : NULL,
                    'profissional_id' => $request->profissional_saude_id ?? NULL,
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);

                $exame->atendimento_id = $atendimento->id;
                $exame->update();
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

        return response()->json(["message" => "Exame registrada com sucesso!", "exame" => $exame]);
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

        if (!$user->can('criar todos') && !$user->can('criar exame') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $produto = Produto::findOrFail($request->exame_id);

            $verificar_items = ExameItem::where('produto_id', $produto->id)
                ->where('exame_id', NULL)
                ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            if (!$verificar_items) {
                ExameItem::create([
                    'produto_id' => $produto->id,
                    'exame_id' => NULL,
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

        $query = ExameItem::with(["produto.categoria"])->whereNull('exame_id')
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

        if (!$user->can('criar todos') && !$user->can('criar exame') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $exame = ExameItem::findOrFail($id);
        $exame->delete();

        $query = ExameItem::with(["produto.categoria"])->whereNull('exame_id')
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
    public function actualizarItems(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar exame') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $produto = Produto::findOrFail($request->id);
        $exame = Exame::findOrFail($request->exame_id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $verificar_items = ExameItem::where('produto_id', $produto->id)
                ->where('exame_id', $exame->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            if (!$verificar_items) {
                ExameItem::create([
                    'produto_id' => $produto->id,
                    'exame_id' => $exame->id,
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

        $query = ExameItem::with(["produto.categoria"])
            ->where('exame_id', $exame->id)
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
    public function deleteActualizarItems($id, $exame_id)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar exame') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $exame = ExameItem::findOrFail($id);
        $exame->delete();

        $query = ExameItem::with(["produto.categoria"])
            ->where('exame_id', $exame_id)
            ->where('entidade_id', $entidade->empresa->id);

        $items = $query->get();
        $total = $query->sum("valor");

        return response()->json(["items" => $items, "total" => $total], 200);
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

        if (!$user->can('editar todos') && !$user->can('editar exame') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            "data_exame" => "required",
            "hora_exame" => "required",
            "paciente_id" => "required",
        ]);

        $exame = Exame::findOrFail($id);
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $items = ExameItem::with(["produto.categoria"])->where("exame_id", $exame->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->get();

            $total = 0;

            foreach ($items as $item) {
                $total = $total + $item->valor;
            }

            $exame->data_exame = $request->data_exame;
            $exame->hora_exame = $request->hora_exame;
            $exame->paciente_id = $request->paciente_id;
            $exame->profissional_saude_id = $request->profissional_saude_id;
            $exame->prioridade_id = $request->prioridade_id;
            $exame->status = $exame->status;
            $exame->pago = $exame->pago;
            $exame->total = $total;
            $exame->observacao = $request->observacao;

            foreach ($items as $item) {
                $item_ = ExameItem::findOrFail($item->id);
                $item_->exame_id = $exame->id;
                $item_->status = "concluido";
                $item_->update();
            }

            $exame->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(["message" => "Exame registrada com sucesso!", "exame" => $exame]);
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

        if (!$user->can('listar todos') && !$user->can('listar exame') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $exame = Exame::with(
            [
                'solicitante_medico',
                'profissional',
                'resultado.resultados_paramentros.resultadosubparamentros.subparametroexame',
                'resultado.resultados_paramentros.resultadosubparamentrosImagem.subparametroexame',
                'consulta',
                'solicitante_paciente',
                'items',
                'items.resultado_parametro_exame.resultadosubparamentros.subparametroexame',
                'items.resultado_parametro_exame.resultadosubparamentrosImagem.subparametroexame',
                'prioridade',
                'atendimento',
                'paciente',
                'profissional_saude.funcionario',
                'entidade',
                'user'
            ]
        )->findOrFail($id);

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "exame" => $exame,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.exames.show', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function imprimir_individual(string $id)
    {
        $exame = Exame::with([
            'solicitante_medico',
            'profissional',
            'consulta',
            'solicitante_paciente',
            'items',

            'items.resultado_parametro_exame.resultadosubparamentros.subparametroexame',
            'items.resultado_parametro_exame.resultadosubparamentrosImagem.subparametroexame',

            'prioridade',
            'atendimento',
            'paciente',
            'profissional_saude.funcionario',
            'entidade',
            'user'
        ])->findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "FICHA DE EXAMES INDIVIDUAL",
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "descricao" => env('APP_NAME'),
            "exame" => $exame,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.exames.ficha-exames-individual', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function imprimir(string $id)
    {
        $exame = Exame::with([
            'solicitante_medico',
            'profissional',
            'consulta',
            'solicitante_paciente',
            'items',

            'items.resultado_parametro_exame.resultadosubparamentros.subparametroexame',
            'items.resultado_parametro_exame.resultadosubparamentrosImagem.subparametroexame',

            'prioridade',
            'atendimento',
            'paciente',
            'profissional_saude.funcionario',
            'entidade',
            'user'
        ])->findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "RELATÓRIO DE EXAMES FICHA COMPLETA",
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "descricao" => env('APP_NAME'),
            "exame" => $exame,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.exames.ficha-exames-completa', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function imprimir_exames_atendimentos(string $id)
    {

        $atendimento = Atendimento::with([
            'exames.solicitante_medico',
            'exames.profissional',
            'exames.consulta',
            'exames.solicitante_paciente',
            'exames.items',
            'exames.items.paramentos_exames.paramentro',
            'exames.items.paramentos_exames_imagem.paramentro',
            'exames.prioridade',
            'exames.atendimento',
            'exames.paciente',
            'exames.profissional_saude.funcionario',
            'exames.entidade',
            'exames.user'
        ])->findOrFail($id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Ficha de exames do atendimento",
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "descricao" => env('APP_NAME'),
            "atendimento" => $atendimento,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.exames.ficha-exames-atendimento', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
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

        if (!$user->can('editar todos') && !$user->can('editar exame') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $exame = Exame::with(
            ['solicitante_medico', 'profissional', 'consulta', 'solicitante_paciente', 'items', 'prioridade', 'paciente', 'profissional_saude.funcionario', 'entidade', 'user']
        )->findOrFail($id);

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
            "exame" => $exame,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.exames.edit', $head);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancelar_exame($id)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar exame') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $exame = Exame::findOrFail($id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            if ($exame->status == "AGENDADA") {
                $estado = "CANCELADA";
            }

            if ($exame->status == "CANCELADA") {
                $estado = "AGENDADA";
            }

            $exame->status = $estado;
            $exame->update();

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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth()->user();

        if (!$user->can('eliminar todos') && !$user->can('eliminar exame')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $exame = Exame::findOrFail($id);
            $exame->delete();

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
