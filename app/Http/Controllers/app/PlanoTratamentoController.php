<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitChavesSaft;
use App\Http\Controllers\TraitHelpers;
use App\Models\Atendimento;
use App\Models\Caixa;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\ContaBancaria;
use App\Models\ContaCliente;
use App\Models\Equipa;
use App\Models\OperacaoFinanceiro;
use App\Models\Exercicio;
use App\Models\ItemVenda;
use App\Models\Leito;
use App\Models\Movimento;
use App\Models\MovimentoContaCliente;
use App\Models\PlanoTratamento;
use App\Models\Produto;
use App\Models\Receita;
use App\Models\Seguradora;
use App\Models\SessaoTratamento;
use App\Models\Subconta;
use App\Models\TipoAtendimento;
use App\Models\TipoPagamento;
use App\Models\User;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

use Ramsey\Uuid\Uuid;

use PDF;
use phpseclib\Crypt\RSA;


class PlanoTratamentoController extends Controller
{
    use TraitChavesSaft;
    use TraitHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar tratamento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $tratamento = PlanoTratamento::when($request->paciente_id, function ($query, $value) {
            $query->where("paciente_id", $value);
        })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate("data_inicio", ">=", $value);
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate("data_final", "<=", $value);
            })
            ->with(["tipo_atendimento", "atendimento", "produto", "sessoes_tratamento", "paciente", "equipa", "user", "entidade", "factura"])
            ->where("entidade_id", $entidade->entidade_id)
            ->orderBy("id", "desc")
            ->get();

        $pacientes = Cliente::where("entidade_id", $entidade->entidade_id)->get();

        $head = [
            "titulo" => "Plano Tratamento",
            "descricao" => env("APP_NAME"),
            "tratamentos" => $tratamento,
            "pacientes" => $pacientes,
            "requests" => $request->all("paciente_id", "status", "data_inicio", "data_final"),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.planos-tratamentos.index", $head);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function imprimir_all(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar tratamento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        //
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $tratamento = PlanoTratamento::when($request->paciente_id, function ($query, $value) {
            $query->where("paciente_id", $value);
        })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->with(["tipo_atendimento", "atendimento", "produto", "sessoes_tratamento", "paciente", "equipa", "user", "entidade"])
            ->where("entidade_id", $entidade->entidade_id)
            ->orderBy("id", "desc")
            ->get();

        $paciente = Cliente::find($request->paciente_id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Imprimir Lista de tratamento",
            "descricao" => env("APP_NAME"),
            "tratamentos" => $tratamento,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "paciente" => $paciente,
            "requests" => $request->all("status", "data_inicio", "data_final"),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];


        $pdf = PDF::loadView('dashboard.planos-tratamentos.imprimir', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function create(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar tratamento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $leitos = Leito::where("status", "livre")
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $equipas = Equipa::whereIn("status", ["desactiva"])
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $tipos_atendimentos = TipoAtendimento::where('entidade_id', $entidade->empresa->id)
            ->get();

        $query = Cliente::where('entidade_id', $entidade->empresa->id);

        $atendimento = Atendimento::findOrFail($request->atendimento_id);

        $query->when($atendimento->cliente_id, function ($query, $value) {
            $query->where("id", $value);
        });

        $pacientes = $query->get();

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env("APP_NAME"),
            "pacientes" => $pacientes,
            "equipas" => $equipas,
            "leitos" => $leitos,
            "atendimento" => $atendimento,
            "tipos_atendimentos" => $tipos_atendimentos,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.planos-tratamentos.create", $head);
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

        if (!$user->can('criar todos') && !$user->can('criar tratamento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $request->validate([
            'paciente_id' => 'required|string',
            'data_inicio' => 'required|date',
            'equipa_id' => 'required',
            'atendimento_id' => 'required',
            'tipo' => 'required',
            'duracao_semanas' => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $verificar = PlanoTratamento::where('status', "activo")
                ->where('paciente_id', $request->paciente_id)
                ->first();

            if (!$verificar) {
                $plano = PlanoTratamento::create([
                    'status' => "activo", // 'Ativo','Suspenso','finalizado', 'cancelado
                    'paciente_id' => $request->paciente_id,
                    'equipa_id' => $request->equipa_id,
                    'atendimento_id' => $request->atendimento_id, //origem do internamento
                    'titulo' => $request->titulo,
                    'descricao' => $request->descricao,
                    'tipo' => $request->tipo,
                    'objectivo' => $request->objectivo,
                    'orientacoes_gerais' => $request->orientacoes_gerais,
                    'data_inicio' => $request->data_inicio,
                    'data_final' => now()->addWeeks($request->duracao_semanas),
                    'duracao_semanas' => $request->duracao_semanas,
                    'frequencia' => $request->frequencia,
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);

                for ($i = 1; $i <= ($request->duracao_semanas * 7); $i++) {
                    SessaoTratamento::create([
                        'plano_atendimento_id' => $plano->id, // 'Ativo','Suspenso','finalizado', 'cancelado
                        'observacoes' => NULL,
                        'status' => NULL,
                        'data_at' => date("Y-m-d", strtotime($request->data_inicio . "+{$i}days")),
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }
            }

            // Defina o intervalo de hoje
            $inicioDoDia = Carbon::today();
            $fimDoDia = Carbon::today()->endOfDay();

            // Destino
            $tipo_atendimento = TipoAtendimento::where("sigla", "Tratamento")->where("entidade_id", $entidade->empresa->id)->first();

            $total_atendimentos = Atendimento::whereBetween('created_at', [$inicioDoDia, $fimDoDia])->where("tipo_atendimento_id", $tipo_atendimento->id ?? "")->where('entidade_id', $entidade->empresa->id)->count();
            $total_atendimentos = $total_atendimentos  + 1;

            $atendimento = Atendimento::findOrFail($request->atendimento_id);
            $atendimento->status = "tratamento";
            $atendimento->numero = "{$tipo_atendimento->sigla} - {$total_atendimentos}";
            $atendimento->tipo_atendimento_id = $tipo_atendimento ? $tipo_atendimento->id : NULL;
            $atendimento->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!", "plano" => $plano], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cancelar(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar tratamento')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $user = auth()->user();

        if (!$user->can('criar todos')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'motivo_cancelamento' => 'required',
            'tratamento_id' => 'required',
            'data_cancelamento' => 'required',
        ]);

        try {

            $tratamento = PlanoTratamento::findOrFail($request->tratamento_id);

            $tratamento->motivo_cancelamento = $request->motivo_cancelamento;
            $tratamento->status = "cancelado";
            $tratamento->data_cancelamento = $request->data_cancelamento;

            $atendimento = Atendimento::findOrFail($tratamento->atendimento_id);
            $atendimento->status = "atendido";

            $atendimento->update();
            $tratamento->update();

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function suspender(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar tratamento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'motivo_suspesao' => 'required',
            'tratamento_id' => 'required',
            'data_suspesao' => 'required',
        ]);

        try {

            $tratamento = PlanoTratamento::findOrFail($request->tratamento_id);

            $tratamento->motivo_suspesao = $request->motivo_suspesao;
            $tratamento->status = "suspenso";
            $tratamento->data_suspesao = $request->data_suspesao;

            $atendimento = Atendimento::findOrFail($tratamento->atendimento_id);
            $atendimento->status = "atendido";

            $atendimento->update();
            $tratamento->update();

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
    public function imprimir($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar tratamento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $tratamento = PlanoTratamento::with(["tipo_atendimento", "atendimento", "produto", "sessoes_tratamento", "paciente", "equipa", "user", "entidade"])->findOrFail($id);

        $head = [
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "titulo" => "Ficha Tratamento",
            "descricao" => env('APP_NAME'),
            "tratamento" => $tratamento,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.planos-tratamentos.ficha-tecnica', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function finalizar(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar tratamento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'observacoes_finais' => 'required',
            'tratamento_id' => 'required',
            'data_finalizacao' => 'required',
        ]);

        try {

            $tratamento = PlanoTratamento::findOrFail($request->tratamento_id);

            $tratamento->observacoes_finais = $request->observacoes_finais;
            $tratamento->status = "finalizado";
            $tratamento->data_finalizacao = $request->data_finalizacao;

            $atendimento = Atendimento::findOrFail($tratamento->atendimento_id);
            $atendimento->status = "atendido";

            $atendimento->update();
            $tratamento->update();

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
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar tratamento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $tratamento = PlanoTratamento::with(["tipo_atendimento", "atendimento", "produto", "sessoes_tratamento", "paciente", "equipa", "user", "entidade", "factura"])->findOrFail($id);

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env("APP_NAME"),
            "tratamento" => $tratamento,
            "loja" => User::with(["empresa"])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.planos-tratamentos.show', $head);
    }

    //
    public function lancarResultado(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar tratamento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $item = SessaoTratamento::findOrFail($request->sessaoSelecionada);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $item->observacoes = $request->observacoes;
            $item->status = $request->status;
            $item->update();


            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => 'Dados Excluido com sucesso!'], 200);
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

        if (!$user->can('editar todos') && !$user->can('editar tratamento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $tratamento = PlanoTratamento::with(["tipo_atendimento", "atendimento", "produto", "sessoes_tratamento", "paciente", "equipa", "user", "entidade"])->findOrFail($id);

        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $leitos = Leito::where("status", "livre")
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $equipas = Equipa::whereIn("status", ["desactiva"])
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $tipos_atendimentos = TipoAtendimento::where('entidade_id', $entidade->empresa->id)
            ->get();

        $query = Cliente::where('entidade_id', $entidade->empresa->id);

        $query->when($tratamento->paciente_id, function ($query, $value) {
            $query->where("id", $value);
        });

        $pacientes = $query->get();

        $head = [
            "titulo" => __('messages.editar'),
            "descricao" => env("APP_NAME"),
            "equipas" => $equipas,
            "leitos" => $leitos,
            "tratamento" => $tratamento,
            "pacientes" => $pacientes,
            "tipos_atendimentos" => $tipos_atendimentos,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.planos-tratamentos.edit", $head);
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

        if (!$user->can('editar todos') && !$user->can('editar tratamento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $request->validate([
            'paciente_id' => 'required|string',
            'data_inicio' => 'required|date',
            'equipa_id' => 'required',
            'tipo' => 'required',
            'duracao_semanas' => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $tratamento = PlanoTratamento::with(["sessoes_tratamento"])->findOrFail($id);

            if ($tratamento->sessoes_tratamento) {
                foreach ($tratamento->sessoes_tratamento as $item) {
                    SessaoTratamento::findOrFail($item->id)->delete();
                }
            }

            $tratamento->paciente_id = $request->paciente_id;
            $tratamento->equipa_id = $request->equipa_id;
            $tratamento->titulo = $request->titulo;
            $tratamento->descricao = $request->descricao;
            $tratamento->tipo = $request->tipo;
            $tratamento->objectivo = $request->objectivo;
            $tratamento->observacoes_finais = $request->orientacoes_gerais;
            $tratamento->data_inicio = $request->data_inicio;
            $tratamento->data_final = now()->addWeeks($request->duracao_semanas);
            $tratamento->duracao_semanas = $request->duracao_semanas;
            $tratamento->frequencia = $request->frequencia;

            for ($i = 1; $i <= ($request->duracao_semanas * 7); $i++) {
                SessaoTratamento::create([
                    'plano_atendimento_id' => $tratamento->id, // 'Ativo','Suspenso','finalizado', 'cancelado
                    'observacoes' => NULL,
                    'status' => NULL,
                    'data_at' => date("Y-m-d", strtotime($request->data_inicio . "+{$i}days")),
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
            }

            $tratamento->update();

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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth()->user();

        if (!$user->can('eliminar todos') && !$user->can('eliminar tratamento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $tratamento = PlanoTratamento::findOrFail($id);
            $tratamento->delete();

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
