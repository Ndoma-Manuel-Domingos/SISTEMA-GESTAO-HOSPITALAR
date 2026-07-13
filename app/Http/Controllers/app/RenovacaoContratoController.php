<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\CategoriaCargo;
use App\Models\Contrato;
use App\Models\Funcionario;
use App\Models\TipoContrato;
use App\Models\TipoFuncionario;
use App\Models\TipoPagamento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class RenovacaoContratoController extends Controller
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

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Data atual
        $dataAtual = Carbon::now()->format('Y-m-d');

        $contratos = Contrato::with(['categoria', 'pacote_salarial', 'funcionario', 'cargo', 'tipo_contrato', 'user', 'forma_pagamento'])->where("entidade_id", $entidade->empresa->id)
            // ->whereDate('data_final', '<=', $dataAtual)
            // ->where('status', 'desactivo')
            ->where('renovacoes_efectuadas', '!=', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => "Renovações de Contratos",
            "descricao" => env('APP_NAME'),
            "contratos" => $contratos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.renovacoes-contratos.index', $head);
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
        $tipos_funcionarios = TipoFuncionario::where('entidade_id', $entidade->empresa->id)->get();

        // Data atual
        $dataAtual = Carbon::now()->format('Y-m-d');

        $contratos = Contrato::with(['categoria', 'pacote_salarial', 'funcionario', 'cargo', 'tipo_contrato', 'user', 'forma_pagamento'])->where("entidade_id", $entidade->empresa->id)
            ->whereDate('data_final', '<=', $dataAtual)
            ->where('status', 'desactivo')
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "tipos_contratos" => $tipos_contratos,
            "tipos_funcionarios" => $tipos_funcionarios,
            "cargos" => $cargos,
            "funcionarios" => $funcionarios,
            "categorias" => $categorias,
            "contratos" => $contratos,
            "forma_pagamentos" => $forma_pagamentos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.renovacoes-contratos.create', $head);
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
            'contrato_id'  => 'required|string',
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

            $update = Contrato::findOrFail($request->contrato_id);

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            // Suponha que as datas são recebidas de um formulário ou outra entrada de usuário
            $dataInicio = Carbon::parse($update->data_final);
            $dataFinal = Carbon::parse($request->input('data_final'));

            // Calcular a diferença em meses
            $diferencaMeses = $dataInicio->diffInMonths($dataFinal);

            if ($update->data_final == $request->data_final) {
                return redirect()->back()->with("warning", "Data do Final de contrato continua a mesma!");
            }
            $update->renovacoes_efectuadas = $update->renovacoes_efectuadas + 1;

            $update->status = $request->status;
            $update->antiguidade = $update->antiguidade  + $diferencaMeses;
            $update->duracao_renovacao = $diferencaMeses;
            $update->situacao_apos_renovacao = $request->situacao_apos_renovacao;
            // $update->funcionario_id = $request->funcionario_id;
            $update->tipo_contrato_id = $request->tipo_contrato_id;

            $update->hora_entrada = $request->hora_entrada;
            $update->hora_saida = $request->hora_saida;
            $update->data_inicio = $request->data_inicio;
            $update->data_final = $request->data_final;
            $update->nova_data_final = $request->data_final;
            $update->data_envio_previo = $request->data_envio_previo;
            $update->data_demissao = $request->data_demissao;

            $update->update();

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

        $contrato = Contrato::with(['categoria', 'pacote_salarial.subsidios_pacotes.subsidio', 'funcionario', 'cargo', 'tipo_contrato', 'user'])->findOrFail($id);

        return response()->json([
            "contrato" => $contrato,
        ], 200);
    }
}
