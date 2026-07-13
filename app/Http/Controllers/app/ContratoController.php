<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\CategoriaCargo;
use App\Models\Contrato;
use App\Models\Departamento;
use App\Models\Desconto;
use App\Models\DescontoContrato;
use App\Models\Funcionario;
use App\Models\Subsidio;
use App\Models\SubsidioContrato;
use App\Models\TipoContrato;
use App\Models\TipoPagamento;
use App\Models\TipoProcessamento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ContratoController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar contrato')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        // Data atual
        $dataAtual = Carbon::now()->format('Y-m-d');

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // vamos desactivar automaticamente dos os contratos expirados
        $contratos_expirados = Contrato::with(['categoria', 'subsidios_contrato', 'descontos_contrato', 'funcionario', 'cargo', 'tipo_contrato', 'user', 'forma_pagamento'])
            ->where('entidade_id', $entidade->empresa->id)
            ->whereDate('data_final', '<=', $dataAtual)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($contratos_expirados as $expirado) {
            if ($expirado->tipo_contrato->nome == "Determinado") {
                $update = Contrato::findOrFail($expirado->id);
                $update->status = 'desactivo';
                $update->update();
            }
        }

        $contratos = Contrato::with(['categoria', 'subsidios_contrato', 'descontos_contrato', 'funcionario', 'cargo', 'tipo_contrato', 'user', 'forma_pagamento'])->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "contratos" => $contratos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contratos.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar contrato')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $tipos_contratos = TipoContrato::where('entidade_id', $entidade->empresa->id)->get();
        $cargos = Cargo::where('entidade_id', $entidade->empresa->id)->get();
        $funcionarios = Funcionario::where('entidade_id', $entidade->empresa->id)->get();
        $categorias = CategoriaCargo::where('entidade_id', $entidade->empresa->id)->get();
        $forma_pagamentos = TipoPagamento::get();

        $subsidios = Subsidio::where('entidade_id', $entidade->empresa->id)->get();
        $descontos = Desconto::where('entidade_id', $entidade->empresa->id)->get();
        $processamentos = TipoProcessamento::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "tipos_contratos" => $tipos_contratos,
            "cargos" => $cargos,
            "funcionarios" => $funcionarios,
            "categorias" => $categorias,
            "forma_pagamentos" => $forma_pagamentos,
            "subsidios" => $subsidios,
            "descontos" => $descontos,
            "processamentos" => $processamentos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contratos.create', $head);
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

        if (!$user->can('criar todos') && !$user->can('criar contrato')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'funcionario_id'  => 'required|string',
            'cargo_id'  => 'required|string',
            'categoria_id'  => 'required|string',
            'tipo_contrato_id'  => 'required|string',
            'data_inicio'  => 'required|string',
            'data_final'  => 'required|string',
            'hora_entrada'  => 'required|string',
            'hora_saida'  => 'required|string',
            'status'  => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $total_contratos = Contrato::where('entidade_id', $entidade->empresa->id)->count();

            $cargo = Cargo::findOrFail($request->cargo_id);

            $verificar_contrato = Contrato::where('entidade_id', $entidade->empresa->id)->where('funcionario_id', $request->funcionario_id)->first();

            if (!$verificar_contrato) {

                $dataInicio = Carbon::parse($request->input('data_inicio'));
                $dataFinal = Carbon::parse($request->input('data_final'));

                // Calcular a diferença em meses
                $diferencaMeses = $dataInicio->diffInMonths($dataFinal);

                $create = Contrato::create([
                    'status' => $request->status,
                    'renovacoes_efectuadas' => 0,

                    'antiguidade' => $diferencaMeses,
                    'duracao_renovacao' => 0,

                    'user_id' => Auth::user()->id,
                    'funcionario_id' => $request->funcionario_id,
                    'categoria_id' => $request->categoria_id,
                    'cargo_id' => $cargo->id,
                    'departamento_id' => $cargo->departamento_id,
                    'tipo_contrato_id' => $request->tipo_contrato_id,

                    'forma_pagamento_id' => $request->forma_pagamento_id,
                    'hora_entrada' => $request->hora_entrada,
                    'hora_saida' => $request->hora_saida,
                    'data_inicio' => $request->data_inicio,
                    'data_final' => $request->data_final,
                    'data_envio_previo' => $request->data_envio_previo,
                    'data_demissao' => $request->data_demissao,
                    'data_admissao' => $request->data_admissao,
                    'entidade_id' => $entidade->empresa->id,

                    'salario_base' => $request->salario_base,

                    'dias_processamento' => $request->dias_processamento,
                    'subsidio_natal' => $request->subsidio_natal,
                    'forma_pagamento_natal' => $request->forma_pagamento_natal,
                    'mes_pagamento_natal' => $request->mes_pagamento_natal,
                    'subsidio_ferias' => $request->subsidio_ferias,
                    'forma_pagamento_ferias' => $request->forma_pagamento_ferias,
                    'mes_pagamento_ferias' => $request->mes_pagamento_ferias,

                ]);

                $create->numero = "CONTR Nº 00" . $total_contratos + 1;
                $create->save();

                if ($request->subsidio_id[0] != null) {
                    foreach ($request->subsidio_id as $index => $subsidioId) {
                        SubsidioContrato::create([
                            'subsidio_id' => $subsidioId,
                            'contrato_id' => $create->id,
                            'salario' => $request->salario_subsidio[$index],
                            'processamento_id' => $request->processamento_id[$index],
                            'user_id' => Auth::user()->id,
                            'entidade_id' => $entidade->empresa->id,
                        ]);
                    }
                }

                if ($request->desconto_id[0] != null) {
                    foreach ($request->desconto_id as $index => $descontoId) {
                        DescontoContrato::create([
                            'desconto_id' => $descontoId,
                            'contrato_id' => $create->id,
                            'salario' => $request->salario_desconto[$index],
                            'processamento_id' => $request->processamento_desconto_id[$index],
                            'user_id' => Auth::user()->id,
                            'entidade_id' => $entidade->empresa->id,
                        ]);
                    }
                }
            } else {
                return response()->json(['success' => true, 'message' => "Este Funcionário Já tem um contrato assinado!"], 404);
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



        return response()->json(['success' => true, 'message' => "Dados salvos com sucesso!"], 200);
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

        if (!$user->can('listar todos') && !$user->can('listar contrato')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $contrato = Contrato::with(['categoria', 'subsidios_contrato.processamento', 'subsidios_contrato.subsidio', 'descontos_contrato.processamento', 'descontos_contrato.desconto', 'funcionario', 'cargo', 'tipo_contrato', 'user', 'forma_pagamento'])->findOrFail($id);

        $subsidios = Subsidio::where('entidade_id', $entidade->empresa->id)->get();
        $descontos = Desconto::where('entidade_id', $entidade->empresa->id)->get();
        $processamentos = TipoProcessamento::where('entidade_id', $entidade->empresa->id)->get();


        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "contrato" => $contrato,
            "subsidios" => $subsidios,
            "descontos" => $descontos,
            "processamentos" => $processamentos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contratos.show', $head);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mudar_estado($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar contrato')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $contrato = Contrato::findOrFail($id);

            if ($contrato->status == "activo") {
                $status = "desactivo";
            }

            if ($contrato->status == "desactivo") {
                $status = "activo";
            }

            $contrato->status = $status;
            $contrato->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados salvos com sucesso!"], 200);
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

        if (!$user->can('editar todos') && !$user->can('editar contrato')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $contrato = Contrato::with(['categoria', 'subsidios_contrato.subsidio', 'descontos_contrato.desconto', 'funcionario', 'cargo', 'tipo_contrato', 'user'])->findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $departamentos = Departamento::where('entidade_id', $entidade->empresa->id)->get();
        $tipos_contratos = TipoContrato::where('entidade_id', $entidade->empresa->id)->get();
        $cargos = Cargo::where('entidade_id', $entidade->empresa->id)->get();
        $funcionarios = Funcionario::where('entidade_id', $entidade->empresa->id)->get();
        $categorias = CategoriaCargo::where('entidade_id', $entidade->empresa->id)->get();
        $subsidios = Subsidio::where('entidade_id', $entidade->empresa->id)->get();
        $descontos = Desconto::where('entidade_id', $entidade->empresa->id)->get();
        $processamentos = TipoProcessamento::where('entidade_id', $entidade->empresa->id)->get();

        $forma_pagamentos = TipoPagamento::get();

        $head = [
            "titulo" => __('messages.editar'),
            "departamentos" => $departamentos,
            "tipos_contratos" => $tipos_contratos,
            "forma_pagamentos" => $forma_pagamentos,
            "cargos" => $cargos,
            "categorias" => $categorias,
            "funcionarios" => $funcionarios,
            "subsidios" => $subsidios,
            "descontos" => $descontos,
            "processamentos" => $processamentos,
            "descricao" => env('APP_NAME'),
            "contrato" => $contrato,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contratos.edit', $head);
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

        if (!$user->can('editar todos') && !$user->can('editar contrato')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'funcionario_id'  => 'required|string',
            'cargo_id'  => 'required|string',
            'categoria_id'  => 'required|string',
            'tipo_contrato_id'  => 'required|string',
            'data_inicio'  => 'required|string',
            'data_final'  => 'required|string',
            'hora_entrada'  => 'required|string',
            'hora_saida'  => 'required|string',
            'status'  => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            // $verificar_pacote = PacoteSalarial::where('entidade_id', $entidade->empresa->id)->where('cargo_id', $request->cargo_id)->where('categoria_id', $request->categoria_id)->first();

            // if(!$verificar_pacote){
            //     return redirect()->back()->with("danger", "Por Favor, primeiramente deves cadastrar uma pacote salarial que seja compactível com o cargo e a categoria deste funcionário!");
            // }

            $contrato = Contrato::findOrFail($id);

            $cargo = Cargo::findOrFail($request->cargo_id);

            $contrato->cargo_id = $cargo->id;
            $contrato->departamento_id = $cargo->departamento_id;
            $contrato->status = $request->status;

            $contrato->funcionario_id = $request->funcionario_id;
            $contrato->categoria_id = $request->categoria_id;
            $contrato->cargo_id = $cargo->id;
            $contrato->tipo_contrato_id = $request->tipo_contrato_id;
            $contrato->forma_pagamento_id = $request->forma_pagamento_id;
            $contrato->hora_entrada = $request->hora_entrada;
            $contrato->hora_saida = $request->hora_saida;
            $contrato->data_inicio = $request->data_inicio;
            $contrato->data_final = $request->data_final;
            $contrato->departamento_id = $cargo->departamento_id;

            $contrato->data_envio_previo = $request->data_envio_previo;
            $contrato->data_demissao = $request->data_demissao;
            $contrato->data_admissao = $request->data_admissao;

            //$contrato->pacote_salarial_id = $verificar_pacote->id;
            $contrato->salario_base = $request->salario_base;

            $contrato->dias_processamento = $request->dias_processamento;
            $contrato->subsidio_natal = $request->subsidio_natal;
            $contrato->forma_pagamento_natal = $request->forma_pagamento_natal;
            $contrato->mes_pagamento_natal = $request->mes_pagamento_natal;
            $contrato->subsidio_ferias = $request->subsidio_ferias;
            $contrato->forma_pagamento_ferias = $request->forma_pagamento_ferias;
            $contrato->mes_pagamento_ferias = $request->mes_pagamento_ferias;

            // Deletar os registros atuais do funcionário para recriar os novos
            SubsidioContrato::where('contrato_id', $contrato->id)->delete();
            DescontoContrato::where('contrato_id', $contrato->id)->delete();

            if ($request->subsidio_id[0] != null) {
                foreach ($request->subsidio_id as $index => $subsidioId) {
                    SubsidioContrato::create([
                        'subsidio_id' => $subsidioId,
                        'contrato_id' => $contrato->id,
                        'salario' => $request->salario_subsidio[$index],
                        'processamento_id' => $request->processamento_id[$index],
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }
            }

            if ($request->desconto_id[0] != null) {
                foreach ($request->desconto_id as $index => $descontoId) {
                    DescontoContrato::create([
                        'desconto_id' => $descontoId,
                        'contrato_id' => $contrato->id,
                        'salario' => $request->salario_desconto[$index],
                        'processamento_id' => $request->processamento_desconto_id[$index],
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }
            }

            $contrato->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        $contrato->update();

        return response()->json(['success' => true, 'message' => "Dados salvos com sucesso!"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get_subsidio_contrato($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar contrato')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        // Realizar operações de banco de dados aqui     
        $contrato = SubsidioContrato::findOrFail($id);


        return response()->json(['success' => true, 'data' => $contrato], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_subsidio_contrato(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar contrato')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'subsidio_id'  => 'required',
            'processamento_id_subsidio'  => 'required',
            'salario_subsidio'  => 'required',
            'contrato_id_subsidio'  => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $contrato = Contrato::findOrFail($request->contrato_id_subsidio);

            SubsidioContrato::create([
                'subsidio_id' => $request->subsidio_id,
                'contrato_id' => $contrato->id,
                'salario' => $request->salario_subsidio,
                'processamento_id' => $request->processamento_id_subsidio,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
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



        return response()->json(['success' => true, 'message' => "Dados salvos com sucesso!"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_subsidio_contrato(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar contrato')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $request->validate([
            'subsidio_id'  => 'required',
            'processamento_id_subsidio'  => 'required',
            'salario_subsidio'  => 'required',
            'contrato_id_subsidio'  => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui     

            // Realizar operações de banco de dados aqui     
            $subsidio = SubsidioContrato::findOrFail($id);

            $subsidio->subsidio_id = $request->subsidio_id;
            $subsidio->salario = $request->salario_subsidio;
            $subsidio->processamento_id = $request->processamento_id_subsidio;
            $subsidio->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados salvos com sucesso!"], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_subsidio_contrato($id)
    {
        $user = auth()->user();

        if (!$user->can('eliminar todos') && !$user->can('eliminar contrato')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui     
            $contrato = SubsidioContrato::findOrFail($id);
            $contrato->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }


        return response()->json(['success' => true, 'message' => "Dados Excluído com sucesso!"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get_desconto_contrato($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar contrato')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        // Realizar operações de banco de dados aqui     
        $contrato = DescontoContrato::findOrFail($id);

        return response()->json(['success' => true, 'data' => $contrato], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_desconto_contrato(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar contrato')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'desconto_id'  => 'required',
            'salario_desconto'  => 'required',
            'processamento_desconto_id'  => 'required',
            'contrato_id_desconto'  => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $contrato = Contrato::findOrFail($request->contrato_id_desconto);

            DescontoContrato::create([
                'desconto_id' => $request->desconto_id,
                'contrato_id' => $contrato->id,
                'salario' => $request->salario_desconto,
                'processamento_id' => $request->processamento_desconto_id,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
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

        return response()->json(['success' => true, 'message' => "Dados salvos com sucesso!"], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_desconto_contrato(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar contrato')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $request->validate([
            'desconto_id'  => 'required',
            'salario_desconto'  => 'required',
            'processamento_desconto_id'  => 'required',
            'contrato_id_desconto'  => 'required',
        ]);


        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui     

            // Realizar operações de banco de dados aqui     
            $desconto = DescontoContrato::findOrFail($id);

            $desconto->desconto_id = $request->desconto_id;
            $desconto->salario = $request->salario_desconto;
            $desconto->processamento_id = $request->processamento_desconto_id;
            $desconto->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }


        return response()->json(['success' => true, 'message' => "Dados salvos com sucesso!"], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_desconto_contrato($id)
    {
        $user = auth()->user();

        if (!$user->can('eliminar todos') && !$user->can('eliminar contrato')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui     
            $contrato = DescontoContrato::findOrFail($id);
            $contrato->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }


        return response()->json(['success' => true, 'message' => "Dados Excluído com sucesso!"], 200);
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

        if (!$user->can('eliminar todos') && !$user->can('eliminar contrato')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui     
            $contrato = Contrato::findOrFail($id);
            $contrato->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }


        return response()->json(['success' => true, 'message' => "Dados Excluído com sucesso!"], 200);
    }
}
