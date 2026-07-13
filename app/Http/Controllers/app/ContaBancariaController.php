<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Banco;
use App\Models\Caixa;
use App\Models\ContaBancaria;
use App\Models\Conta;
use App\Models\Entidade;
use App\Models\MovimentoBanco;
use App\Models\Subconta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\TraitHelpers;
use App\Models\Movimento;
use App\Models\OperacaoFinanceiro;
use Illuminate\Support\Facades\File;
use PDF;

class ContaBancariaController extends Controller
{

    use TraitHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar banco')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::findOrFail($entidade->empresa->id);

        $bancos = ContaBancaria::with(['banco'])->where("entidade_id", $entidade->empresa->id)->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "bancos" => $bancos,
            "entidade" => $entidade,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contas-bancarias.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar banco')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        if (!isset($request->createLoja)) {
            return redirect()->route('lojas.index');
        }

        $bancos = Banco::get();

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "loja_id" => $request->createLoja,
            "bancos" => $bancos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contas-bancarias.create', $head);
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

        if (!$user->can('criar todos') && !$user->can('criar banco')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'banco_id' => 'required|string',
            'moeda' => 'required|string',
            'tipo_banco_id' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $code = uniqid(time());
            $nova_conta = "";

            $banco = Banco::findOrFail($request->banco_id);

            if ($request->tipo_banco_id == "DO") {
                $conta = Conta::where('conta', '43')->where('entidade_id', $entidade->empresa->id)->first();
                if ($request->moeda == "KZ") {
                    $serie = "43.1.";
                } else {
                    $serie = "43.2.";
                }
            }
            if ($request->tipo_banco_id == "DP") {
                $conta = Conta::where('conta', '42')->where('entidade_id', $entidade->empresa->id)->first();
                if ($request->moeda == "KZ") {
                    $serie = "42.1.";
                } else {
                    $serie = "42.2.";
                }
            }
            if ($request->tipo_banco_id == "OD") {
                $conta = Conta::where('conta', '44')->where('entidade_id', $entidade->empresa->id)->first();
                if ($request->moeda == "KZ") {
                    $serie = "44.1.";
                } else {
                    $serie = "44.2.";
                }
            }

            $subc_ = Subconta::where('numero', 'like', "{$serie}%")->where('entidade_id', $entidade->empresa->id)->count() + 1;
            $nova_conta =  $serie . "{$subc_}";

            $subconta = Subconta::create([
                'entidade_id' => $entidade->empresa->id,
                'numero' => $nova_conta,
                'nome' => $banco->nome,
                'tipo_conta' => 'M',
                'code' => $code,
                'status' => $conta->status,
                'conta_id' => $conta->id,
                'user_id' => Auth::user()->id,
            ]);

            $banco = ContaBancaria::create([
                'banco_id' => $request->banco_id,
                'nome' => $banco->nome,
                'status' => $request->status,
                'user_id' => Auth::user()->id,
                'numero_conta' => $request->numero_conta,
                'tipo_banco_id' => $request->tipo_banco_id,
                'iban' => $request->iban,
                'code' => $code,
                'conta' => $nova_conta,

                "moeda" => $request->moeda,

                'nib' => $request->nib,
                'switf' => $request->switf,
                'nome_agencia' => $request->nome_agencia,
                'numero_gestor' => $request->numero_gestor,
                'nome_titular' => $request->nome_titular,
                'morada_titular' => $request->morada_titular,
                'local_titular' => $request->local_titular,
                'codigo_postal_titular' => $request->codigo_postal_titular,


                "loja_id" => $request->loja_id,
                'entidade_id' => $entidade->empresa->id,
                'subconta_id' => $subconta->id,
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
    public function show($id, Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar banco')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $banco = ContaBancaria::findOrFail($id);

        $utilizadores = User::where("entidade_id", $entidade->empresa->id)->get();

        $movimentos = OperacaoFinanceiro::where('subconta_id', $banco->subconta_id)
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('date_at', '>=', Carbon::createFromDate($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('date_at', '<=', Carbon::createFromDate($value));
            })
            ->where('entidade_id', $entidade->empresa->id)
            ->with(['user', 'subconta', 'centro_custo'])
            ->orderBy('id', 'desc')
            ->get();

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "banco" => $banco,
            "movimentos" => $movimentos,
            "dados" => $entidade,
            "utilizadores" => $utilizadores,
            "requests" => $request->all('data_inicio', 'data_final', 'operador_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contas-bancarias.show', $head);
    }


    public function movimentos_banco(Request $request)
    {
        $user = auth()->user();

        // if(!$user->can('movimento no banco')){
        //     return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $users = User::where('entidade_id', '=', $entidade->empresa->id)->get();
        $bancos = ContaBancaria::with(['banco'])->where('entidade_id', $entidade->empresa->id)->get();

        $movimentos = OperacaoFinanceiro::where('entidade_id', $entidade->empresa->id)
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('date_at', '>=', Carbon::createFromDate($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('date_at', '<=', Carbon::createFromDate($value));
            })
            ->when($request->banco_id, function ($query, $value) {
                $query->where('subconta_id', $value);
            })
            ->when($request->operador_id, function ($query, $value) {
                $query->where('user_id', $value);
            })
            ->with(['user', 'subconta', 'centro_custo'])
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Movimentos dos bancos",
            "descricao" => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "loja" => Entidade::findOrFail($entidade->empresa->id),
            "movimentos" => $movimentos,
            "users" => $users,
            "bancos" => $bancos,
            "empresa" => $empresa,
            "requests" => $request->all('data_inicio', 'data_final', 'operador_id', 'banco_id'),
            "user" => User::find($request->operador_id),
            "banco" => ContaBancaria::where('subconta_id', $request->banco_id)->first(),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        if ($request->documento_pdf === "exportar_pdf") {

            $pdf = PDF::loadView('dashboard.contas-bancarias.movimentos-pdf', $head);
            $pdf->setPaper('A4', 'portrait');

            return $pdf->stream();
        } else {
            return view('dashboard.vendas.caixas.movimentos', $head);
        }
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

        if (!$user->can('editar todos') && !$user->can('editar banco')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $banco = ContaBancaria::with(['banco'])->findOrFail($id);

        $bancos = Banco::get();

        $head = [
            "titulo" => __('messages.editar'),
            "descricao" => env('APP_NAME'),
            "banco" => $banco,
            "bancos" => $bancos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contas-bancarias.edit', $head);
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

        if (!$user->can('editar todos') && !$user->can('editar banco')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'banco_id' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $code = uniqid(time());
            $nova_conta = "";

            $conta_bancaria = ContaBancaria::with(['banco'])->findOrFail($id);
            $banco = Banco::findOrFail($request->banco_id);

            if ($request->tipo_banco_id == "DO") {
                $conta = Conta::where('conta', '43')->where('entidade_id', $entidade->empresa->id)->first();
                if ($request->moeda == "KZ") {
                    $serie = "43.1.";
                } else {
                    $serie = "43.2.";
                }
            }
            if ($request->tipo_banco_id == "DP") {
                $conta = Conta::where('conta', '42')->where('entidade_id', $entidade->empresa->id)->first();
                if ($request->moeda == "KZ") {
                    $serie = "42.1.";
                } else {
                    $serie = "42.2.";
                }
            }
            if ($request->tipo_banco_id == "OD") {
                $conta = Conta::where('conta', '44')->where('entidade_id', $entidade->empresa->id)->first();
                if ($request->moeda == "KZ") {
                    $serie = "44.1.";
                } else {
                    $serie = "44.2.";
                }
            }


            if ($conta_bancaria->code == NULL) {
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

                $conta_bancaria->conta = $nova_conta;
                $conta_bancaria->code = $code;
                $conta_bancaria->subconta_id = $subconta->id;
            }

            if ($request->tipo_banco_id != $conta_bancaria->tipo_banco_id) {
                $subconta = Subconta::where('numero', 'like', "{$serie}%")->where('entidade_id', $entidade->empresa->id)->count() + 1;
                $nova_conta =  $serie . "{$subconta}";

                if ($subconta) {
                    $subc_up = Subconta::findOrFail($conta_bancaria->subconta_id);
                    $subc_up->numero = $nova_conta;
                    $subc_up->nome = $request->nome;
                    $subc_up->code = $code;
                    $subc_up->update();
                }

                $conta_bancaria->conta = $nova_conta;
                $conta_bancaria->code = $code;
            }

            $conta_bancaria->banco_id = $request->banco_id;
            $conta_bancaria->nome = $banco->nome;
            $conta_bancaria->status = $request->status;
            $conta_bancaria->numero_conta = $request->numero_conta;
            $conta_bancaria->tipo_banco_id = $request->tipo_banco_id;
            $conta_bancaria->iban = $request->iban;
            $conta_bancaria->moeda = $request->moeda;

            $conta_bancaria->nib = $request->nib;
            $conta_bancaria->switf = $request->switf;
            $conta_bancaria->nome_agencia = $request->nome_agencia;
            $conta_bancaria->numero_gestor = $request->numero_gestor;
            $conta_bancaria->nome_titular = $request->nome_titular;
            $conta_bancaria->morada_titular = $request->morada_titular;
            $conta_bancaria->local_titular = $request->local_titular;
            $conta_bancaria->codigo_postal_titular = $request->codigo_postal_titular;

            $conta_bancaria->update();

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

        if (!$user->can('eliminar todos') && !$user->can('eliminar banco')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $banco = ContaBancaria::with(['banco'])->findOrFail($id);
            $banco->delete();
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

    // abertura do TPA 
    public function abertura_bancos()
    {
        $user = auth()->user();

        // if(!$user->can('abertura do caixa')){
        //     return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $bancos = ContaBancaria::with(['banco'])
            ->where('active', true)
            ->where('user_open_id', Auth::user()->id)
            ->where('status', 'fechado')
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        if (count($bancos) !== 0) {
            Alert::warning('Alerta!', 'Já Existe Caixa Aberto no Momento!');
            return redirect()->route('pronto-venda');
        }

        $bancos_ = ContaBancaria::with(['banco'])
            ->where('active', false)
            ->where('status', 'fechado')
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $head = [
            "titulo" => "Abertura do TPA",
            "descricao" => env('APP_NAME'),
            "bancos" => $bancos_,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contas-bancarias.abertura', $head);
    }

    public function abertura_bancos_create(Request $request)
    {
        $user = auth()->user();

        // if(!$user->can('abertura do caixa')){
        //     
        //     return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $request->validate([
            'valor' => 'required|string',
            'banco_id' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $caixaActivo = Caixa::where('active', true)
                ->where('status', 'aberto')
                ->where('status_admin', 'liberado')
                ->where('user_open_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            if (!$caixaActivo) {
                return response()->json(['message' => 'Por favor, não podes realizar nenhuma activação de TPA sem antes abrir o caixa!'], 404);
            }

            $bancoActivo = ContaBancaria::with(['banco'])->findOrFail($request->banco_id);
            $code = uniqid(time());
            // contabilidade            
            Movimento::create([
                'user_id' => Auth::user()->id,
                'subconta_id' => $bancoActivo->subconta_id,
                'exercicio_id' => $this->exercicio(),
                'periodo_id' => $this->periodo(),
                'status' => true,
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                'movimento' => 'E',
                'observacao' => 'Abertura do TPA',
                'credito' => 0,
                'debito' => $request->valor,
                'code' => $code,
                'data_at' => date("Y-m-d"),
                'entidade_id' => $entidade->empresa->id,
            ]);

            // finanças
            OperacaoFinanceiro::create([
                'nome' => $bancoActivo->nome,
                'status' => "pago",
                'motante' => $request->valor,
                'formas' => 'B',
                'code_caixa' => $caixaActivo->code_caixa ?? NULL,
                'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                'subconta_id' => $bancoActivo->subconta_id,
                'model_id' => $this->receita_padrao(),
                'type' => 'R',
                'status_pagamento' => "pago",
                'code' => $code,
                'descricao' => $bancoActivo->nome,
                'movimento' => 'E',
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                'date_at' => date("Y-m-d"),
                'user_id' => Auth::user()->id,
                'user_open_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
                'exercicio_id' => $this->exercicio(),
                'periodo_id' => $this->periodo(),
            ]);

            $bancoActivo->status = "aberto";
            $bancoActivo->active = true;
            $bancoActivo->user_open_id = Auth::user()->id;
            $bancoActivo->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => "Operação realizado com sucesso.!", 'success' => true, 'redirect' => route('pronto-venda')]);
    }

    // feachamento do TPA
    public function fechamento_bancos($id = null)
    {
        $user = auth()->user();



        try {
            // Inicia a transação
            DB::beginTransaction();

            $bancoActivo = ContaBancaria::find($id);
            if (!$bancoActivo) {
                return response()->json(['message' => 'Verificar a conta bancária (TPA) que pretendes fechar, por favor'], 404);
            }

            if ($bancoActivo->status == "fechado" && $bancoActivo->active == false) {
                return redirect()->route('dashboard');
            }

            $bancoActivo->status = "fechado";
            $bancoActivo->active = false;
            $bancoActivo->user_open_id = NULL;
            $bancoActivo->user_close_id = Auth::user()->id;
            $bancoActivo->update();

            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger("Error", $e->getMessage());
            return redirect()->back()->with("danger", $e->getMessage());
        }


        return response()->json(['message' => "Conta bancária(TPA) desactivo com sucesso.!"], 200);
    }

    public function fechamento_bancos_create(Request $request)
    {
        $user = auth()->user();
        // if(!$user->can('fecho do caixa')){
        //     return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }

        $request->validate([
            'valor' => 'required|numeric',
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $bancoActivo = ContaBancaria::where('active', true)
            ->where('status', '=', 'aberto')
            ->where('user_open_id', '=', Auth::user()->id)
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->first();

        if ($bancoActivo) {
            $statusBanco = ContaBancaria::with(['banco'])->findOrFail($bancoActivo->id);
            $statusBanco->status = "fechado";
            $statusBanco->active = false;
            $statusBanco->user_open_id = NULL;
            $statusBanco->user_close_id = Auth::user()->id;
            $statusBanco->update();
        } else {
            Alert::error('Erro', 'Aconteceu um erro ao fechar o banco');
            return redirect()->back();
        }
    }

    // relatorio fechamento caixa
    public function relatorio_fechamento_bancos($id)
    {
        $user = auth()->user();

        if (!$user->can('fecho do caixa')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $movimento = MovimentoBanco::with(["user", "banco"])->findOrFail($id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Fecho do TPA",
            "descricao" => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "empresa" => Entidade::findOrFail($entidade->empresa->id),
            "movimento" => $movimento,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.contas-bancarias.relatorio-fecho', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bancoDesactivar($id)
    {
        //

        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar banco')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $banco = ContaBancaria::with(['banco'])->findOrFail($id);

        if ($banco->active == false) {
            $banco->status = true;
        } else {
            $banco->status = false;
        }

        if ($banco->update()) {
            Alert::success("Sucesso!", "Banco Suspendida do successo");
            return redirect()->route('lojas.index');
        } else {
            Alert::error("Erro!", "Não foi possível Suspender a Banco");
            return redirect()->route('lojas.index');
        }
    }

    // movimentos detalhado do banco
    public function bancoDetalhe($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar banco')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $movimentos = MovimentoBanco::with('user')->where("entidade_id", $entidade->empresa->id)
            ->with('banco')->findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "movimento" => $movimentos,
            "empresa" => $empresa,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];


        return view('dashboard.contas-bancarias.detalhe', $head);
    }

    public function movimentos_imprimir(Request $request)
    {
        $movimento = MovimentoBanco::with(['user', 'banco'])->findOrFail($request->id_imprimir);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Movimento do banco detalhado",
            "descricao" => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "loja" => Entidade::findOrFail($entidade->empresa->id),
            "movimento" => $movimento,
            "empresa" => $empresa,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.contas-bancarias.movimentos-detalhe-pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
}
