<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitChavesSaft;
use App\Http\Controllers\TraitHelpers;
use App\Models\Atendimento;
use App\Models\CatalogoExame\DuracaoPreInscricaoMedica;
use App\Models\CatalogoExame\FrequenciaPreInscricaoMedica;
use App\Models\CatalogoExame\ViaPreInscricaoMedica;
use App\Models\SolicitarInternamento;
use App\Models\Cliente;
use App\Models\Consulta;
use App\Models\Equipa;
use App\Models\EvolucaoMedica;
use App\Models\Internamento;
use App\Models\Leito;
use App\Models\LojaProduto;
use App\Models\Obito;
use App\Models\PlanoInternamento;
use App\Models\Produto;
use App\Models\TipoAtendimento;
use App\Models\User;
use App\Models\UserLoja;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

use Ramsey\Uuid\Uuid;

use PDF;
use phpseclib\Crypt\RSA;


class InternamentoController extends Controller
{
    use TraitChavesSaft;
    use TraitHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar internamento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $internamentos = Internamento::with(["tipo_atendimento", "leito", "paciente", "equipa", "user", "entidade"])
            ->where("entidade_id", $entidade->entidade_id)
            ->orderBy("id", "desc")
            ->get();

        $head = [
            "titulo" => "Internamentos",
            "descricao" => env("APP_NAME"),
            "internamentos" => $internamentos,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.internamentos.index", $head);
    }

    public function create(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar internamento') && !$user->can('consultorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        //
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $leitos = Leito::with(['quarto.tipo', 'quarto.andar'])->where("status", "livre")
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $equipas = Equipa::whereIn("status", ["desactiva"])
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $tipos_atendimentos = TipoAtendimento::where('entidade_id', $entidade->empresa->id)
            ->get();

        $query = Cliente::where('entidade_id', $entidade->empresa->id);

        $atendimento = Atendimento::find($request->atendimento_id);

        if (!$atendimento) {
            return redirect()->back();
        }

        $query->when($atendimento->cliente_id, function ($query, $value) {
            $query->where("id", $value);
        });

        $pacientes = $query->get();

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
            "titulo" => __('messages.novo'),
            "descricao" => env("APP_NAME"),
            "pacientes" => $pacientes,
            "produtos" => $produtos,
            "equipas" => $equipas,
            "leitos" => $leitos,
            "vias" => $vias,
            "duracoes" => $duracoes,
            "frequencias" => $frequencias,
            "atendimento" => $atendimento,
            "tipos_atendimentos" => $tipos_atendimentos,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.internamentos.create", $head);
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

        if (!$user->can('criar todos') && !$user->can('criar internamento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $request->validate([
            'paciente_id' => 'required|string',
            'data_internacao' => 'required|date',
            'equipa_id' => 'required',
            'atendimento_id' => 'required|exists:atendimentos,id',
            'diagnostico_inicial' => 'required',
            'leito_id' => 'required|exists:leitos,id',

            'medicamentos' => 'nullable|array',

            'medicamentos.*.medicamento_id' => 'required',
            'medicamentos.*.dose' => 'nullable|string|max:100',
            'medicamentos.*.via' => 'required|string|max:100',
            'medicamentos.*.frequencia' => 'required|string|max:100',
            'medicamentos.*.duracao' => 'required|string|max:100',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $solicitacao = Internamento::where('entidade_id', $entidade->empresa->id)->count();
            $solicitacao = $solicitacao  + 1;

            $leito = Leito::findOrFail($request->leito_id);

            $verificar = Internamento::where("status", "activo")
                ->where("paciente_id", $request->paciente_id)
                ->where("equipa_id", $request->equipa_id)
                ->where("leito_id", $request->leito_id)
                ->first();

            if (!$verificar) {

                $internamento = Internamento::create([
                    "numero" => "INTER - {$solicitacao}",
                    "status" => "activo", // "Activo","Alta","Obito"
                    "paciente_id" => $request->paciente_id,
                    "leito_id" => $request->leito_id,
                    "consulta_id" => $request->consulta_id ?? NULL,
                    "equipa_id" => $request->equipa_id,
                    "atendimento_id" => $request->atendimento_id, //origem do internamento
                    "motivo" => $request->motivo,
                    "diagnostico_inicial" => $request->diagnostico_inicial,
                    "data_internacao" => $request->data_internacao,
                    "data_alta" => $request->data_alta,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);


                foreach ($request->medicamentos as $item) {
                    PlanoInternamento::create([
                        'internamento_id' => $internamento->id,
                        'medicamento' => $item['medicamento_id'],
                        'dose' => $item['dose'],
                        'via' => $item['via'],
                        'frequencia' => $item['frequencia'],
                        'duracao' => $item['duracao'],
                        "entidade_id" => $entidade->empresa->id,
                    ]);
                }

                EvolucaoMedica::create([
                    "internamento_id" => $internamento->id,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);

                $leito->status = "ocupada";
                $leito->update();

                $tipo_atendimento = TipoAtendimento::where("sigla", "Internamento")->where("entidade_id", $entidade->empresa->id)->first();

                $inicioDoDia = Carbon::parse($request->data_consulta)->startOfDay();
                $fimDoDia = Carbon::parse($request->data_consulta)->endOfDay();

                $total_atendimentos = Atendimento::whereBetween("created_at", [$inicioDoDia, $fimDoDia])->where("tipo_atendimento_id", $tipo_atendimento->id ?? "")->where("entidade_id", $entidade->empresa->id)->count();
                $total_atendimentos = $total_atendimentos  + 1;

                $sigla = $tipo_atendimento ? $tipo_atendimento->sigla : NULL;

                $atendimento = Atendimento::findOrFail($request->atendimento_id);
                $atendimento->status = "internamento";
                $atendimento->numero = "{$sigla} - {$total_atendimentos}";
                $atendimento->update();

                $consulta = Consulta::find($request->consulta_id);
                if ($consulta) {
                    $consulta->internamento_id = $internamento->id;
                    $consulta->update();
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

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!", "internamento" => $internamento], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function actualizar_evolucao_media(Request $request)
    {

        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar evolucao medica')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $request->validate([
            'data_evolucao' => 'required|date',
            'observacao' => 'required',
            'internamento_id' => 'required',
        ]);

        try {
            // Realizar operações de banco de dados aqui
            EvolucaoMedica::create([
                "data_evolucao" => $request->data_evolucao,
                "observacoes" => $request->observacao,
                "tipo" => $request->tipo_evolucao_medica,
                "internamento_id" => $request->internamento_id,
                "user_id" => Auth::user()->id,
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dar_alta(Request $request)
    {

        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar internamento') && !$user->can('consultorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'data_alta' => 'required|date',
            'resumo' => 'required',
            'internamento_id' => 'required',
        ]);

        try {

            $internamento = Internamento::findOrFail($request->internamento_id);

            if ($internamento->pago == "NAO PAGO") {
                return response()->json(['success' => false, 'message' => "O Paciente não esta com a situação financeira regularizada!"], 404);
            }

            $internamento->resumo_alta = $request->resumo;
            $internamento->status = "alta";
            $internamento->data_alta = $request->data_alta;

            $atendimento = Atendimento::findOrFail($internamento->atendimento_id);
            $atendimento->status = "atendido";

            $atendimento->update();
            $internamento->update();

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function transferencia_paciente(Request $request)
    {

        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar internamento') && !$user->can('consultorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'data_transferencia' => 'required|date',
            'resumo' => 'required',
            'internamento_id' => 'required',
        ]);

        try {

            $internamento = Internamento::findOrFail($request->internamento_id);
            $internamento->resumo_transferencia = $request->resumo;
            $internamento->status = "transferido";
            $internamento->data_alta = $request->data_transferencia;
            $internamento->update();

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function obito_paciente(Request $request)
    {

        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar internamento') && !$user->can('consultorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'data_obito' => 'required|date',
            'resumo' => 'required',
            'internamento_id' => 'required',
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        try {

            $internamento = Internamento::findOrFail($request->internamento_id);
            $internamento->resumo_obito = $request->resumo;
            $internamento->status = "obito";
            $internamento->data_alta = $request->data_obito;
            $internamento->update();

            $total_obito = Obito::where("entidade_id", $entidade->empresa->id)->count();
            $total_obito = $total_obito  + 1;

            Obito::create([
                "paciente_id" => $internamento->paciente_id,
                "medico_id" => NULL,
                "atendimento_id" => $internamento->atendimento_id, // encaminhamento
                "data_obito" => $request->data_obito,
                "hora_obito" => $request->hora_obito,
                "local_obito" => $request->local_obito,
                "tipo_obito" => $request->tipo_obito,
                "documento_declaracao" => "Nº {$total_obito}",
                "status" => "aguardando_morgue",
                "comunicacao_obito" => $request->comunicacao_obito,
                "causa_obito" => $request->resumo,
                "pago" => "NAO PAGO",
                "total" => 0,
                "user_id" => Auth::user()->id,
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atender_paciente($id, $status)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar internamento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $solicitacao = SolicitarInternamento::findOrFail($id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            if ($status == "aceitar") {
                $estado = "atendido";
            }
            if ($status == "cancelar") {
                $estado = "cancelada";
            }

            $solicitacao->status = $estado;
            $solicitacao->update();
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            dd($e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => 'Dados Actualizado com sucesso!'], 200);
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

        if (!$user->can('listar todos') && !$user->can('listar internamento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $internamento = Internamento::with([
            "atendimento.consultas",
            "atendimento.exames",
            "plano_internamento",
            "atendimento.triagem",
            "atendimento.receitas.items",
            "evolucao_medica",
            "tipo_atendimento",
            "leito",
            "paciente",
            "equipa",
            "user",
            "entidade"
        ])->findOrFail($id);

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env("APP_NAME"),
            "internamento" => $internamento,
            "loja" => User::with(["empresa"])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.internamentos.show', $head);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function lista_exames($id)
    {

        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar internamento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $internamento = Internamento::with(
            [
                "atendimento.receita",
                "atendimento.consultas",
                "atendimento.exames",

                'atendimento.exames.items.resultado_parametro_exame.resultadosubparamentros.subparametroexame',
                'atendimento.exames.items.resultado_parametro_exame.resultadosubparamentrosImagem.subparametroexame',

                "atendimento.triagem",
                "evolucao_medica",
                "tipo_atendimento",
                "leito",
                "paciente",
                "equipa",
                "user",
                "entidade"
            ]
        )->findOrFail($id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Ficha dos Exames",
            "descricao" => env('APP_NAME'),
            "internamento" => $internamento,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),

            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.internamentos.ficha-exames', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function imprimir($id)
    {

        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar internamento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $internamento = Internamento::with(["atendimento.consultas", "plano_internamento", "atendimento.exames", "atendimento.triagem", "evolucao_medica", "tipo_atendimento", "leito", "paciente", "equipa", "user", "entidade"])->findOrFail($id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        $head = [
            "titulo" => "Ficha Técnica",
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "descricao" => env('APP_NAME'),
            "internamento" => $internamento,

            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.internamentos.ficha-tecnica', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function lista_receitas($id)
    {

        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar internamento')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $internamento = Internamento::with(["atendimento.receitas", "atendimento.receita", "atendimento.consultas", "atendimento.exames", "atendimento.triagem", "evolucao_medica", "tipo_atendimento", "leito", "paciente", "equipa", "user", "entidade"])->findOrFail($id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Ficha da Receitas",
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "descricao" => env('APP_NAME'),
            "internamento" => $internamento,

            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.internamentos.ficha-receitas', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function plano_medico_internamento_export($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar internamento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $internamento = Internamento::with(["plano_internamento", "paciente", "equipa", "user", "entidade"])->findOrFail($id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Ficha do Plano Médico de Internamento",
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "descricao" => env('APP_NAME'),
            "internamento" => $internamento,

            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.internamentos.ficha-plano-medico-internamento', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function lista_consultas($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar internamento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $internamento = Internamento::with(["atendimento.receita", "atendimento.consultas", "atendimento.exames", "atendimento.triagem", "evolucao_medica", "tipo_atendimento", "leito", "paciente", "equipa", "user", "entidade"])->findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Ficha das consultas",
            "descricao" => env('APP_NAME'),
            "internamento" => $internamento,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),

            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.internamentos.ficha-consultas', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function lista_evolucao_medica($id)
    {

        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar internamento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $internamento = Internamento::with(["atendimento.consultas", "atendimento.exames", "atendimento.triagem", "evolucao_medica", "tipo_atendimento", "leito", "paciente", "equipa", "user", "entidade"])->findOrFail($id);
        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Ficha da Evolução Médica",
            "descricao" => env('APP_NAME'),
            "internamento" => $internamento,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.internamentos.evolucao-medica-ficha', $head);
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

        if (!$user->can('editar todos') && !$user->can('editar internamento')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        //
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $internamento = Internamento::with(["atendimento.consultas", "plano_internamento", "atendimento.exames", "atendimento.triagem", "evolucao_medica", "tipo_atendimento", "leito", "paciente", "equipa", "user", "entidade"])->findOrFail($id);

        $leitos = Leito::where('entidade_id', $entidade->empresa->id)
            ->get();


        $equipas = Equipa::whereIn("status", ["desactiva"])
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $tipos_atendimentos = TipoAtendimento::where('entidade_id', $entidade->empresa->id)
            ->get();


        $query = Cliente::where('entidade_id', $entidade->empresa->id);

        $pacientes = $query->get();


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
            "titulo" => __('messages.editar'),
            "descricao" => env("APP_NAME"),
            "equipas" => $equipas,
            "produtos" => $produtos,
            "vias" => $vias,
            "duracoes" => $duracoes,
            "frequencias" => $frequencias,
            "leitos" => $leitos,
            "internamento" => $internamento,
            "pacientes" => $pacientes,
            "tipos_atendimentos" => $tipos_atendimentos,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.internamentos.edit", $head);
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

        if (!$user->can('editar todos') && !$user->can('editar internamento')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'paciente_id' => 'required|string',
            'data_internacao' => 'required|date',
            'equipa_id' => 'required',
            'diagnostico_inicial' => 'required',
            'leito_id' => 'required|exists:leitos,id',

            'medicamentos' => 'nullable|array',

            'medicamentos.*.medicamento_id' => 'required',
            'medicamentos.*.dose' => 'nullable|string|max:100',
            'medicamentos.*.via' => 'required|string|max:100',
            'medicamentos.*.frequencia' => 'required|string|max:100',
            'medicamentos.*.duracao' => 'required|string|max:100',
        ]);


        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $internamento = Internamento::findOrFail($id);

            if ($request->leito_id != $internamento->leito_id) {
                $leito_actual = Leito::findOrFail($request->leito_id);


                $leito_antigo = Leito::findOrFail($internamento->leito_id);
                $leito_antigo->status = "livre";


                if ($leito_actual->status == "ocupada") {
                    return response()->json(['success' => false, 'message' => "Este leito já esta ocupado, escolha outro leito!"], 404);
                }

                $leito_actual->status = "ocupada";
                $leito_actual->update();
                $leito_antigo->update();
            }

            $internamento->paciente_id = $request->paciente_id;
            $internamento->data_internacao = $request->data_internacao;
            $internamento->data_alta = $request->data_alta;
            $internamento->equipa_id = $request->equipa_id;
            $internamento->leito_id = $request->leito_id;
            $internamento->motivo = $request->motivo;
            $internamento->diagnostico_inicial = $request->diagnostico_inicial;

            $internamento->update();

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            foreach ($request->medicamentos as $med) {
                if (isset($med['id'])) {
                    PlanoInternamento::where('id', $med['id'])
                        ->update([
                            'medicamento' => $med['medicamento_id'],
                            'dose' => $med['dose'],
                            'via' => $med['via'],
                            'frequencia' => $med['frequencia'],
                            'duracao' => $med['duracao']
                        ]);
                } else {
                    PlanoInternamento::create([
                        'internamento_id' => $internamento->id,
                        'medicamento' => $med['medicamento_id'],
                        'dose' => $med['dose'],
                        'via' => $med['via'],
                        'frequencia' => $med['frequencia'],
                        'duracao' => $med['duracao'],
                        "entidade_id" => $entidade->empresa->id,
                    ]);
                }
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

        if (!$user->can('eliminar todos') || !$user->can('eliminar internamento')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $internamento = Internamento::findOrFail($id);

            $leito = Leito::findOrFail($internamento->leito_id);
            $leito->status = "livre";
            $leito->update();

            $internamento->delete();

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
