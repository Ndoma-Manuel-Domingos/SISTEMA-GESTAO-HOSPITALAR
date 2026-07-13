<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\Caixa;
use App\Models\Conta;
use App\Models\ContaBancaria;
use App\Models\Contrato;
use App\Models\Dispesa;
use App\Models\Exercicio;
use App\Models\Funcionario;
use App\Models\MarcacaoFalta;
use App\Models\MarcacaoFeria;
use App\Models\Movimento;
use App\Models\OperacaoFinanceiro;
use App\Models\Periodo;
use App\Models\Processamento;
use App\Models\Subconta;
use App\Models\TaxaIRT;
use App\Models\TipoPagamento;
use App\Models\TipoProcessamento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use PDF;

class ProcessamentoController extends Controller
{

    use TraitHelpers;
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

        if (!$user->can('listar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $faltas = MarcacaoFalta::where("entidade_id", $entidade->empresa->id)->orderBy('created_at', 'desc')->get();

        $processamentos = Processamento::with(['exercicio', 'periodo', 'funcionario', 'processamento', 'user'])
            ->when($request->processamento_id, function ($query, $value) {
                $query->where('processamento_id', $value);
            })
            ->when($request->exercicio_id, function ($query, $value) {
                $query->where('exercicio_id', $value);
            })
            ->when($request->periodo_id, function ($query, $value) {
                $query->where('periodo_id', $value);
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            // ->when($request->data_inicio, function($query, $value){
            //     $query->whereDate('data_registro', '=>', $value);
            // })
            // ->when($request->data_final, function($query, $value){
            //     $query->whereDate('data_registro', '<=', $value);
            // })
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();


        $tipo_processamentos = TipoProcessamento::where('entidade_id', $entidade->empresa->id)
            ->get();

        $exercicios = Exercicio::where('entidade_id', $entidade->empresa->id)
            ->get();

        $periodos = Periodo::where('entidade_id', $entidade->empresa->id)
            ->where('exercicio_id', $this->exercicio())
            ->get();

        $head = [
            "titulo" => "Processamentos",
            "descricao" => env('APP_NAME'),
            "faltas" => $faltas,
            "processamentos" => $processamentos,
            "tipo_processamentos" => $tipo_processamentos,
            "periodos" => $periodos,
            "exercicios" => $exercicios,
            "requests" => $request->all('data_inicio', 'data_final', 'funcionario_id', 'processamento_id', 'exercicio_id', 'periodo_id', 'status'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.processamentos.index', $head);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function emissao_recibo(Request $request)
    {
        //
        $user = auth()->user();

        // if(!$user->can('listar todos') && !$user->can('listar subsidio')){
        //     
        //     return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $faltas = MarcacaoFalta::where("entidade_id", $entidade->empresa->id)->orderBy('created_at', 'desc')->get();

        $processamentos = Processamento::with(['exercicio', 'periodo', 'funcionario', 'processamento', 'user'])
            ->when($request->processamento_id, function ($query, $value) {
                $query->where('processamento_id', $value);
            })
            ->when($request->exercicio_id, function ($query, $value) {
                $query->where('exercicio_id', $value);
            })
            ->when($request->periodo_id, function ($query, $value) {
                $query->where('periodo_id', $value);
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->whereIn('status', ['pago'])
            // ->when($request->data_inicio, function($query, $value){
            //     $query->whereDate('data_registro', '=>', $value);
            // })
            // ->when($request->data_final, function($query, $value){
            //     $query->whereDate('data_registro', '<=', $value);
            // })
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $tipo_processamentos = TipoProcessamento::where('entidade_id', $entidade->empresa->id)
            // ->orderBy('created_at', 'desc')
            ->get();

        $exercicios = Exercicio::where('entidade_id', $entidade->empresa->id)
            // ->orderBy('created_at', 'desc')
            ->get();

        $periodos = Periodo::where('entidade_id', $entidade->empresa->id)
            ->where('exercicio_id', $this->exercicio())
            ->get();

        $head = [
            "titulo" => "Emissão de Recibos",
            "descricao" => env('APP_NAME'),
            "faltas" => $faltas,
            "processamentos" => $processamentos,
            "tipo_processamentos" => $tipo_processamentos,
            "periodos" => $periodos,
            "exercicios" => $exercicios,
            "requests" => $request->all('data_inicio', 'data_final', 'funcionario_id', 'processamento_id', 'exercicio_id', 'periodo_id', 'status'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.processamentos.emissao-recibos', $head);
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

        if (!$user->can('criar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $funcionarios = Funcionario::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'faltas'])->where("entidade_id", $entidade->empresa->id)->orderBy('created_at', 'asc')->get();

        $tipo_processamentos = TipoProcessamento::where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $exercicios = Exercicio::where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $periodos = Periodo::where('entidade_id', $entidade->empresa->id)
            ->where('exercicio_id', $this->exercicio())
            ->get();

        $processamentos = Processamento::with(['exercicio', 'periodo', 'funcionario', 'processamento', 'user'])->where('status', 'pendente')
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => "Processamentos",
            "descricao" => env('APP_NAME'),
            "funcionarios" => $funcionarios,

            "tipo_processamentos" => $tipo_processamentos,
            "periodos" => $periodos,
            "exercicios" => $exercicios,
            "processamentos" => $processamentos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.processamentos.create', $head);
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

        if (!$user->can('criar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'processamento_id' => 'required|string',
            'exercicio_id' => 'required|string',
            'periodo_id' => 'required|string',
            'dias_processados' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $tipo_processamento = TipoProcessamento::findOrFail($request->processamento_id);
            $periodo = Periodo::findOrFail($request->periodo_id);
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            // Data atual
            $dataAtual = Carbon::now()->format('Y-m-d');

            $code = uniqid(time());

            if ($tipo_processamento->sigla == "V" || $tipo_processamento->nome == "Vencimento") {
                // Consultar contratos que estão ativos e cujo data_final é menor ou igual à data atual
                $contratos = Contrato::where('entidade_id', $entidade->empresa->id)
                    ->where('status', 'activo')
                    ->whereDate('data_final', '>=', $periodo->final) // antes era da data actual, mais vo colocar da final do mesmo que esta sendo processado, porque pode se dar o caso que o funcionario ate no mesmo a ser pago ainda na tinha completado 22 dias
                    ->whereRaw('DATE_ADD(data_inicio, INTERVAL 22 DAY) <= ?', [$periodo->final])
                    // ->whereDate('data_final', '>=', $dataAtual)
                    // ->whereRaw('DATE_ADD(data_inicio, INTERVAL 22 DAY) <= ?', [$dataAtual])
                    ->pluck('funcionario_id');

                if ($contratos->isEmpty()) {
                    return response()->json(['success' => false, 'message' => "Nenhum contrato valido encontrado, verifica a data do fim de contrato dos funcionários!"], 404);
                }

                $funcionarios = Funcionario::with(['contrato.subsidios_contrato.subsidio'])
                    ->with(['contrato.subsidios_contrato' => function ($query) use ($request) {
                        $query->where('processamento_id', $request->processamento_id);
                    }])
                    ->with(['contrato.descontos_contrato' => function ($query) use ($request) {
                        $query->where('processamento_id', $request->processamento_id);
                    }])
                    ->with(['faltas' => function ($query) use ($periodo) {
                        if ($periodo->inicio && $periodo->final) {
                            $query->whereBetween('data_registro', [$periodo->inicio, $periodo->final]);
                        }
                    }])
                    ->where('entidade_id', $entidade->empresa->id)
                    ->whereIn('id', $contratos)
                    ->orderBy('created_at', 'desc')
                    ->get();

                foreach ($funcionarios as $funcionario) {

                    $salario_iliquido = 0;
                    $salario_base = 0;
                    $total_subsidios = 0;
                    $total_outros_descontos = 0;
                    $total_faltas = 0;

                    $total_valor_desconto_faltas = 0;

                    // Subsídios sujeitos a IRT e Não Sujeitos
                    $subsidio_soma_s = 0;
                    $subsidio_soma_n = 0;


                    $soma_subsidios_irt = 0;

                    // Subsídios sujeitos a INSS e Não Sujeitos
                    $subsidio_soma_inss = 0;

                    $numero_faltas = count($funcionario->faltas);

                    $ramanescente = 0;

                    // Salario base do funcionario
                    $salario_base = $funcionario->contrato->salario_base ?? 0;

                    if ($funcionario->contrato->subsidios_contrato) {
                        foreach ($funcionario->contrato->subsidios_contrato as $subsidio) {

                            if ($subsidio->subsidio->irt == 'Y') {
                                if ($subsidio->salario > $subsidio->subsidio->limite_isencao) {
                                    $result_ = $subsidio->salario - $subsidio->subsidio->limite_isencao;

                                    if ($result_ <= -1) {
                                        $result_ = $result_ * (-1);
                                    }

                                    $soma_subsidios_irt += $result_;
                                }
                            }

                            $total_subsidios += $subsidio->salario;
                        }
                    }

                    if ($funcionario->contrato->descontos_contrato) {
                        foreach ($funcionario->contrato->descontos_contrato as $desconto) {

                            if ($desconto->desconto->tipo == "O") {

                                if ($desconto->desconto->tipo_valor == 'P') {
                                    $total_outros_descontos += $salario_base * ($desconto->salario / 100);
                                }

                                if ($desconto->desconto->tipo_valor == 'E') {
                                    $total_outros_descontos += $desconto->salario;
                                }
                            }
                        }
                    }

                    $salario_iliquido = $salario_base + $total_subsidios;


                    $inss = $salario_iliquido * (3 / 100);
                    $inss_empresa = $salario_iliquido * (8 / 100);

                    $materia_coletavel = ($salario_base + $soma_subsidios_irt) - $inss;


                    $tabela = TaxaIRT::where('remuneracao', '>=', $materia_coletavel)
                        ->where('abatimento', '<=', $materia_coletavel)
                        ->where('exercicio_id', $this->exercicio())
                        ->first();

                    if ($tabela) {
                        $ramanescente =  $materia_coletavel - $tabela->excesso;

                        $irt = $tabela->valor_fixo + ($ramanescente * ($tabela->taxa / 100));
                    } else {
                        $ramanescente  = 0;

                        $irt  = 0;
                    }

                    $total_faltas = ($salario_base * $numero_faltas) / $request->dias_processados;

                    $desconto = $irt + $inss + $total_faltas + $total_outros_descontos;

                    $salario_liquido = $salario_iliquido - $desconto;

                    $verificar_processamento = Processamento::where('funcionario_id', $funcionario->id)
                        ->where('exercicio_id', $request->exercicio_id)
                        ->where('periodo_id', $request->periodo_id)
                        ->where('processamento_id', $request->processamento_id)
                        ->where('entidade_id', $entidade->empresa->id)
                        ->whereIn('status', ['Pendente'])
                        ->first();

                    // 210 600
                    if (!$verificar_processamento) {
                        Processamento::create([
                            'data_registro' => date("Y-m-d"),
                            'funcionario_id' => $funcionario->id,
                            'exercicio_id' => $request->exercicio_id,
                            'periodo_id' => $request->periodo_id,
                            'outros_descontos' => $total_outros_descontos,
                            'material_colectavel' => $materia_coletavel,
                            'irt' => $irt,
                            'inss' => $inss,
                            'inss_empresa' => $inss_empresa,

                            'taxa_irt' => $tabela->taxa ?? 0,
                            'escalao' => $tabela->escalao ?? "",

                            'forma_pagamento' => $funcionario->contrato->forma_pagamento_id,
                            'categoria' => $funcionario->categoria,
                            'processamento_id' => $request->processamento_id,
                            'dias_processados' => $request->dias_processados,
                            'valor_base' => $salario_base,
                            'valor_iliquido' => $salario_iliquido,
                            'valor_liquido' => $salario_liquido,
                            'faltas' => $total_faltas,
                            'total_desconto' => $desconto,
                            'total_subsidios' => $total_subsidios,
                            'data_inicio' => $periodo->inicio,
                            'data_final' => $periodo->final,
                            'status' => 'Pendente',
                            'user_id' => Auth::user()->id,
                            'entidade_id' => $entidade->empresa->id,
                        ]);


                        if ($funcionario->categoria == "Empregados") {
                            $subconta_remuneracao = Subconta::whereIn('numero', ['72.2'])->where('entidade_id', $entidade->empresa->id)->first();
                            $subconta_encargos_sobre_remuneracao_inns_8 = Subconta::whereIn('numero', ['72.5.2'])->where('entidade_id', $entidade->empresa->id)->first();
                        }

                        if ($funcionario->categoria == "Orgão Sociais") {
                            $subconta_remuneracao = Subconta::whereIn('numero', ['72.1'])->where('entidade_id', $entidade->empresa->id)->first();
                            $subconta_encargos_sobre_remuneracao_inns_8 = Subconta::whereIn('numero', ['72.5.1'])->where('entidade_id', $entidade->empresa->id)->first();
                        }

                        $subconta_funcionario = Subconta::where('id', $funcionario->subconta_id)->where('entidade_id', $entidade->empresa->id)->first();
                        $subconta_imposto_rendimento_trabalho = Subconta::whereIn('numero', ['34.3'])->where('entidade_id', $entidade->empresa->id)->first();
                        $subconta_outros_imposto = Subconta::whereIn('numero', ['34.9'])->where('entidade_id', $entidade->empresa->id)->first();

                        // debitar o custo com pessoal [72.2, 72.1]
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta_remuneracao->id,
                            'status' => true,
                            'movimento' => 'E',
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'credito' => 0,
                            'debito' => $salario_iliquido ?? 0,
                            'observacao' => $subconta_remuneracao->nome,
                            'code' => $code,
                            'data_at' => date("Y-m-d"),
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $this->exercicio(),
                            'periodo_id' => $this->periodo(),
                        ]);

                        // debitar encarrago sociais [72.5.2, 72.5.1]
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta_encargos_sobre_remuneracao_inns_8->id,
                            'status' => true,
                            'movimento' => 'E',
                            'credito' => 0,
                            'debito' => $inss_empresa ?? 0,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'observacao' => $subconta_encargos_sobre_remuneracao_inns_8->nome,
                            'code' => $code,
                            'data_at' => date("Y-m-d"),
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $this->exercicio(),
                            'periodo_id' => $this->periodo(),
                        ]);

                        // creditar remunerações com pessoal [36.1, 36.2]
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta_funcionario->id,
                            'status' => true,
                            'movimento' => 'S',
                            'credito' => $salario_liquido ?? 0,
                            'debito' => 0,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'observacao' => $subconta_funcionario->nome,
                            'code' => $code,
                            'data_at' => date("Y-m-d"),
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $this->exercicio(),
                            'periodo_id' => $this->periodo(),
                        ]);

                        // creditar imposto de rendimento de trabalho IRT [34.3]
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta_imposto_rendimento_trabalho->id,
                            'status' => true,
                            'movimento' => 'S',
                            'credito' => $irt ?? 0,
                            'debito' => 0,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'observacao' => $subconta_imposto_rendimento_trabalho->nome,
                            'code' => $code,
                            'data_at' => date("Y-m-d"),
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $this->exercicio(),
                            'periodo_id' => $this->periodo(),
                        ]);

                        // imposto inns 3% & 8% [34.9]
                        $movimeto = Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta_outros_imposto->id,
                            'status' => true,
                            'movimento' => 'S',
                            'credito' => ($inss ?? 0) + ($inss_empresa ?? 0),
                            'debito' => 0,
                            'observacao' => $subconta_outros_imposto->nome,
                            'code' => $code,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'data_at' => date("Y-m-d"),
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $this->exercicio(),
                            'periodo_id' => $this->periodo(),
                        ]);
                    }
                }
            }

            if ($tipo_processamento->sigla == "F" || $tipo_processamento->nome == "Subsídio de Férias") {

                $total_outros_descontos = 0;

                $marcacao_ferias = MarcacaoFeria::where('exercicio_id', $request->exercicio_id)
                    ->where('periodo_id', $request->periodo_id)
                    // ->where('funcionario_id', $request->funcionario_id)
                    // ->whereDate('data_inicio', '>=', $dataAtual)
                    // ->whereDate('data_final', '<=', $dataAtual)
                    ->where('status', 'Nao Processados')
                    ->pluck('funcionario_id');

                if ($marcacao_ferias) {

                    $funcionarios = Funcionario::with(['contrato.subsidios_contrato.subsidio'])
                        ->where('entidade_id', $entidade->empresa->id)
                        ->whereIn('id', $marcacao_ferias)
                        ->orderBy('created_at', 'desc')
                        ->get();

                    foreach ($funcionarios as $funcionario) {

                        if ($funcionario->contrato->mes_pagamento_ferias == $periodo->mes_processamento) {
                            if ($funcionario->contrato->forma_pagamento_ferias == "completa") {

                                $salario_base = $funcionario->contrato->salario_base ?? 0;
                                $subsidio_ferias = ($funcionario->contrato->salario_base ?? 0) * ($funcionario->contrato->subsidio_ferias / 100);

                                $inss = $subsidio_ferias * (3 / 100);
                                $inss_empresa = $subsidio_ferias * (8 / 100);

                                // subsidio de ferias não e aplicado o inss
                                $inss = 0;
                                $inss_empresa = 0;

                                $materia_coletavel = $subsidio_ferias - $inss;

                                $tabela = TaxaIRT::where('remuneracao', '>=', $materia_coletavel)->where('abatimento', '<=', $materia_coletavel)->where('exercicio_id', $this->exercicio())->first();

                                if ($tabela) {
                                    $ramanescente =  $materia_coletavel - $tabela->excesso;

                                    $irt = $tabela->valor_fixo + ($ramanescente * ($tabela->taxa / 100));
                                } else {
                                    $ramanescente  = 0;

                                    $irt  = 0;
                                }

                                $total_faltas = 0;

                                $desconto = $irt + $inss + $total_faltas + $total_outros_descontos;

                                $salario_liquido = $subsidio_ferias - $desconto;

                                $verificar_processamento = Processamento::where('funcionario_id', $funcionario->id)
                                    ->where('exercicio_id', $request->exercicio_id)
                                    ->where('periodo_id', $request->periodo_id)
                                    ->where('processamento_id', $request->processamento_id)
                                    ->where('entidade_id', $entidade->empresa->id)
                                    ->whereIn('status', ['Pendente'])
                                    ->first();

                                if (!$verificar_processamento) {
                                    Processamento::create([
                                        'data_registro' => date("Y-m-d"),
                                        'funcionario_id' => $funcionario->id,
                                        'exercicio_id' => $request->exercicio_id,
                                        'periodo_id' => $request->periodo_id,
                                        'outros_descontos' => $total_outros_descontos,
                                        'material_colectavel' => $materia_coletavel,
                                        'irt' => $irt,
                                        'inss' => $inss,
                                        'inss_empresa' => $inss_empresa,
                                        'categoria' => $funcionario->categoria,
                                        'forma_pagamento' => $funcionario->contrato->forma_pagamento_id,
                                        'dias_processados' => $request->dias_processados,
                                        'processamento_id' => $request->processamento_id,
                                        'valor_base' => $salario_base,
                                        'valor_iliquido' => $subsidio_ferias,
                                        'valor_liquido' => $salario_liquido,
                                        'faltas' => $total_faltas,
                                        'total_desconto' => $desconto,
                                        'total_subsidios' => 0,
                                        'data_inicio' => $periodo->inicio,
                                        'data_final' => $periodo->final,
                                        'status' => 'Pendente',
                                        'user_id' => Auth::user()->id,
                                        'entidade_id' => $entidade->empresa->id,
                                    ]);
                                }
                            }
                        }
                    }

                    $ferias_processadas = MarcacaoFeria::where('exercicio_id', $request->exercicio_id)
                        ->where('periodo_id', $request->periodo_id)
                        ->where('status', 'Nao Processados')
                        ->get();

                    foreach ($ferias_processadas as $item) {
                        $update = MarcacaoFeria::findOrFail($item->id);
                        $update->status = "Processados";
                        $update->update();
                    }
                } else {
                    return response()->json(['success' => false, 'message' => "Nenhum funcionário encontrado com escala de ferias neste Período e Exercício, Exito aqueles que já tem processamentos!"], 404);
                }
            }

            if ($tipo_processamento->sigla == "N" || $tipo_processamento->nome == "Subsídio Natal") {

                $total_outros_descontos = 0;

                $contratos = Contrato::where('entidade_id', $entidade->empresa->id)
                    ->where('status', 'activo')
                    ->whereDate('data_final', '>=', $dataAtual)
                    ->pluck('funcionario_id');

                if ($contratos->isEmpty()) {
                    return response()->json(['success' => false, 'message' => "Nenhum contrato valido encontrado, verifica a data do fim de contrato dos funcionários"], 404);
                }

                foreach ($funcionarios as $funcionario) {
                    if ($funcionario->contrato->mes_pagamento_natal == $periodo->mes_processamento) {
                        if ($funcionario->contrato->forma_pagamento_natal == "completa") {

                            $salario_base = $funcionario->contrato->salario_base ?? 0;
                            $subsidio_natal = ($funcionario->contrato->salario_base ?? 0) * ($funcionario->contrato->subsidio_natal / 100);

                            $inss = $subsidio_natal * (3 / 100);
                            $inss_empresa = $subsidio_natal * (8 / 100);


                            $materia_coletavel = $subsidio_natal - $inss;

                            $tabela = TaxaIRT::where('remuneracao', '>=', $materia_coletavel)->where('abatimento', '<=', $materia_coletavel)->where('exercicio_id', $this->exercicio())->first();

                            if ($tabela) {
                                $ramanescente =  $materia_coletavel - $tabela->excesso;

                                $irt = $tabela->valor_fixo + ($ramanescente * ($tabela->taxa / 100));
                            } else {
                                $ramanescente  = 0;

                                $irt  = 0;
                            }

                            $total_faltas = 0;

                            $desconto = $irt + $inss + $total_faltas + $total_outros_descontos;

                            $salario_liquido = $subsidio_natal - $desconto;

                            $verificar_processamento = Processamento::where('funcionario_id', $funcionario->id)
                                ->where('exercicio_id', $request->exercicio_id)
                                ->where('periodo_id', $request->periodo_id)
                                ->where('processamento_id', $request->processamento_id)
                                ->where('entidade_id', $entidade->empresa->id)
                                ->whereIn('status', ['Pendente'])
                                ->first();

                            if (!$verificar_processamento) {
                                Processamento::create([
                                    'data_registro' => date("Y-m-d"),
                                    'funcionario_id' => $funcionario->id,
                                    'exercicio_id' => $request->exercicio_id,
                                    'periodo_id' => $request->periodo_id,
                                    'total_subsidios' => $total_outros_descontos,
                                    'material_colectavel' => $materia_coletavel,
                                    'irt' => $irt,
                                    'inss' => $inss,
                                    'inss_empresa' => $inss_empresa,
                                    'forma_pagamento' => $funcionario->contrato->forma_pagamento_id,
                                    'categoria' => $funcionario->categoria,
                                    'dias_processados' => $request->dias_processados,
                                    'valor_base' => $salario_base,
                                    'valor_iliquido' => $subsidio_natal,
                                    'processamento_id' => $request->processamento_id,
                                    'valor_liquido' => $salario_liquido,
                                    'faltas' => $total_faltas,
                                    'total_desconto' => $desconto,
                                    'total_subsidios' => 0,
                                    'data_inicio' => $periodo->inicio,
                                    'data_final' => $periodo->final,
                                    'status' => 'Pendente',
                                    'user_id' => Auth::user()->id,
                                    'entidade_id' => $entidade->empresa->id,
                                ]);
                            }
                        }
                    } else {
                        return response()->json(['success' => false, 'message' => "Ocorreu um erro ao processar o subsídio de natal, mês invalido!"], 404);
                    }
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

        return response()->json(['success' => true, 'message' => "Processamento concluido com sucesso!"], 200);
    }

    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mapas_irt_mapa_inss(Request $request)
    {
        //        
        $user = auth()->user();

        if (!$user->can('listar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $tipo_processamentos = TipoProcessamento::where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $exercicios = Exercicio::where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $periodos = Periodo::where('entidade_id', $entidade->empresa->id)
            ->where('exercicio_id', $this->exercicio())
            ->get();

        $processamentos = [];

        $processamentos = Processamento::when($request->exercicio_id, function ($query, $value) {
            $query->where('exercicio_id', $value);
        })
            ->when($request->periodo_id, function ($query, $value) {
                $query->where('periodo_id', $value);
            })
            ->with(['exercicio', 'periodo', 'funcionario', 'processamento', 'user'])
            ->where('entidade_id', $entidade->empresa->id)
            ->whereIn('status', ['pendente'])
            ->orderBy('created_at', 'desc')
            ->get();

        // when($request->tipo_documento, function ($query, $value) {
        //     $query->where('processamento_id', $value);
        // })
        // ->

        $head = [
            "titulo" => "MAPA DE IRT & MAPA INSS",
            "descricao" => env('APP_NAME'),
            "tipo_processamentos" => $tipo_processamentos,
            "periodos" => $periodos,
            "exercicios" => $exercicios,
            "processamentos" => $processamentos,
            "forma_pagmento" => TipoPagamento::get(),
            'requests' => $request->all('tipo_documento', 'exercicio_id', 'periodo_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];


        return view('dashboard.processamentos.mapa-irt-inss', $head);
    }
    
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pagamentos(Request $request)
    {
        //        
        $user = auth()->user();

        if (!$user->can('listar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $processamentos = [];

        // if ($request->proc_id && $request->exer_id && $request->per_id) {

        $processamentos = Processamento::when($request->proc_id, function ($query, $value) {
            $query->where('processamento_id', $value);
        })
            ->when($request->exer_id, function ($query, $value) {
                $query->where('exercicio_id', $value);
            })
            ->when($request->per_id, function ($query, $value) {
                $query->where('periodo_id', $value);
            })
            ->with(['exercicio', 'periodo', 'funcionario', 'processamento', 'user'])
            ->where('entidade_id', $entidade->empresa->id)
            ->whereIn('status', ['pendente'])
            ->orderBy('created_at', 'desc')
            ->get();
        // }


        $tipo_processamentos = TipoProcessamento::where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $exercicios = Exercicio::where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $periodos = Periodo::where('entidade_id', $entidade->empresa->id)
            ->where('exercicio_id', $this->exercicio())
            ->get();

        $caixas = Caixa::where('entidade_id', $entidade->empresa->id)
            ->where('status_admin', 'liberado')->get();
        $bancos = ContaBancaria::where('entidade_id', $entidade->empresa->id)->get();

        $dispesas = Dispesa::where('entidade_id', $entidade->empresa->id)->where('type', 'D')->get();

        $head = [
            "titulo" => "Pagamento de Processamentos",
            "descricao" => env('APP_NAME'),
            "tipo_processamentos" => $tipo_processamentos,
            "processamentos" => $processamentos,
            "periodos" => $periodos,
            "exercicios" => $exercicios,
            "caixas" => $caixas,
            "bancos" => $bancos,
            "dispesas" => $dispesas,
            "forma_pagmento" => TipoPagamento::get(),
            'requests' => $request->all('proc_id', 'exer_id', 'per_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];


        return view('dashboard.processamentos.pagamentos', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pagamentos_store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'processamento_id' => 'required|string',
            'exercicio_id' => 'required|string',
            'periodo_id' => 'required|string',
            'dias_processados' => 'required|string',
        ]);

        $forma = TipoPagamento::where('tipo', $request->forma_de_pagamento)->first();

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);


        $dispesa = Dispesa::findOrFail($request->dispesa_id);

        if ($forma->tipo === "NU") {
            $request->validate([
                'caixa_id' => 'required',
            ]);

            $subconta = Caixa::findOrFail($request->caixa_id);
            $formas = "C";
        }

        if ($forma->tipo === "MB" || $forma->tipo === "DE" || $forma->tipo === "TE") {
            $request->validate([
                'banco_id' => 'required',
            ]);

            $subconta = ContaBancaria::findOrFail($request->banco_id);

            $formas = "B";
        }

        if ($forma->tipo === "OU") {
            $request->validate([
                'caixa_id' => 'required',
                'banco_id' => 'required',
            ]);
            $formas = "O";
            $caixa = Caixa::findOrFail($request->caixa_id);
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $code = uniqid(time());

            $processamentos = Processamento::where('exercicio_id', $request->exercicio_id)
                ->where('periodo_id', $request->periodo_id)
                ->where('processamento_id', $request->processamento_id)
                ->where('entidade_id', $entidade->empresa->id)
                ->whereIn('status', ['Pendente'])
                ->get();

            $caixaActivo = Caixa::where('active', true)
                ->where('status', 'aberto')
                ->where('status_admin', 'liberado')
                ->where('user_open_id', '=', Auth::user()->id)
                ->where('entidade_id', '=', $entidade->empresa->id)
                ->first();

            if ($processamentos) {

                $valor_total = 0;
                $irt_total = 0;
                $inns_total = 0;
                $liquido_total = 0;

                foreach ($processamentos as $processamento) {
                    $update = Processamento::findOrFail($processamento->id);

                    if ($processamento->categoria == "Empregados") {
                        $subconta_remuneracao = Subconta::whereIn('numero', ['72.2'])->where('entidade_id', $entidade->empresa->id)->first();
                    }

                    if ($processamento->categoria == "Orgão Sociais") {
                        $subconta_remuneracao = Subconta::whereIn('numero', ['72.1'])->where('entidade_id', $entidade->empresa->id)->first();
                    }

                    $valor_total += ($processamento->irt + $processamento->inss + $processamento->inss_empresa + $processamento->valor_liquido);
                    $liquido_total += $processamento->valor_liquido;
                    $irt_total += $processamento->irt;
                    $inns_total += $processamento->inss + $processamento->inss_empresa;
                    $update->status = 'Pago';
                    $update->update();
                }

                if ($forma->tipo === "NU") {
                    $caixa = Caixa::findOrFail($request->caixa_id);
                    $verificar_saldo = $this->saldo_conta($caixa->subconta_id);
                    if ($request->valor_a_pagar > $verificar_saldo) {
                        return response()->json(['message' => "Pretende realizar o pagamento dos salários utilizando os fundos do caixa: {$caixa->conta} - {$caixa->nome}. No entanto, o saldo atual não é suficiente para cobrir essa despesa. Sugerimos adicionar fundos a este caixa para prosseguir com a transação."], 404);
                    }
                }

                if ($forma->tipo === "MB" || $forma->tipo === "DE" || $forma->tipo === "TE") {
                    $banco = ContaBancaria::findOrFail($request->banco_id);

                    $verificar_saldo = $this->saldo_conta($banco->subconta_id);

                    if ($request->valor_a_pagar > $verificar_saldo) {
                        return response()->json(['message' => "Pretende realizar o pagamento dos salários utilizando os fundos da conta bancária: {$banco->conta} - {$banco->nome}. No entanto, o saldo atual não é suficiente para cobrir essa despesa. Sugerimos adicionar fundos a esta conta bancária para prosseguir com a transação."], 404);
                    }
                }

                OperacaoFinanceiro::create([
                    'nome' => $dispesa->nome,
                    'status' => "pago",
                    'formas' => $formas,
                    'motante' => $valor_total,
                    'subconta_id' => $subconta->subconta_id,
                    // 'cliente_id' => $fornecedor->id,
                    // 'fornecedor_id' => $fornecedor->id,
                    'model_id' => $dispesa->id,
                    'type' => "D",
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'parcelado' => "N",
                    'status_pagamento' => "pago",
                    'code' => $code,
                    'descricao' => $dispesa->nome,
                    'movimento' => "S",
                    'user_open_id' => Auth::user()->id,
                    'date_at' => date("Y-m-d"),
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                    'code_caixa' => $caixaActivo->code_caixa ?? NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                ]);


                $subconta_imposto_rendimento_trabalho = Subconta::whereIn('numero', ['34.3'])->where('entidade_id', $entidade->empresa->id)->first();
                $subconta_outros_imposto = Subconta::whereIn('numero', ['34.9'])->where('entidade_id', $entidade->empresa->id)->first();

                // creditar imposto de rendimento de trabalho IRT
                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $subconta_imposto_rendimento_trabalho->id,
                    'status' => true,
                    'movimento' => 'S',
                    'credito' => $inns_total ?? 0,
                    'debito' => 0,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'observacao' => $subconta_imposto_rendimento_trabalho->nome,
                    'code' => $code,
                    'data_at' => date("Y-m-d"),
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                // imposto inns 3% & 8%
                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $subconta_outros_imposto->id,
                    'status' => true,
                    'movimento' => 'S',
                    'credito' => ($inss ?? 0) + ($inss_empresa ?? 0),
                    'debito' => 0,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'observacao' => $subconta_outros_imposto->nome,
                    'code' => $code,
                    'data_at' => date("Y-m-d"),
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);
            }

            if (count($processamentos) == 0) {
                return response()->json(['success' => false, 'message' => "Sem processamento encontrato!"], 404);
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

        return response()->json(['success' => true, 'message' => "Sem processamento encontrato!"], 200);
    }

    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function anulacao(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('listar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $processamentos = Processamento::with(['exercicio', 'periodo', 'funcionario', 'processamento', 'user'])
            ->where('entidade_id', $entidade->empresa->id)
            ->whereIn('status', ['Anulado'])
            ->orderBy('created_at', 'desc')
            ->get();

        $tipo_processamentos = TipoProcessamento::where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $exercicios = Exercicio::where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $periodos = Periodo::where('entidade_id', $entidade->empresa->id)
            ->where('exercicio_id', $this->exercicio())
            ->get();

        $head = [
            "titulo" => "Anulação de Processamentos",
            "descricao" => env('APP_NAME'),
            "tipo_processamentos" => $tipo_processamentos,
            "processamentos" => $processamentos,
            "periodos" => $periodos,
            "exercicios" => $exercicios,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.processamentos.anulacao', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function anulacao_store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar subsidio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            if ($request->has('ids') || is_array($request->ids) || !empty($request->ids)) {

                $request->validate([
                    'ids' => 'required|array',
                ]);

                $processamentos = Processamento::where('entidade_id', $entidade->empresa->id)
                    ->whereIn('status', ['Pendente'])
                    ->whereIn('id', $request->ids)
                    ->get();

                if (count($processamentos) == 0) {
                    return response()->json(['success' => false, 'message' => "Sem processamento encontrato, não é possível anular pagamentos da efectuados!"], 404);
                }

                if ($processamentos) {
                    foreach ($processamentos as $processamento) {
                        $update = Processamento::findOrFail($processamento->id);
                        $update->status = 'Anulado';
                        $update->update();
                    }
                }
            } else {
                $request->validate([
                    'processamento_id' => 'required|string',
                    'exercicio_id' => 'required|string',
                    'periodo_id' => 'required|string',
                ]);

                $processamentos = Processamento::where('exercicio_id', $request->exercicio_id)
                    ->where('periodo_id', $request->periodo_id)
                    ->where('processamento_id', $request->processamento_id)
                    ->where('entidade_id', $entidade->empresa->id)
                    ->whereIn('status', ['Pendente'])
                    ->get();

                if ($processamentos) {
                    foreach ($processamentos as $processamento) {
                        $update = Processamento::findOrFail($processamento->id);
                        $update->status = 'Anulado';
                        $update->update();
                    }
                }

                if (count($processamentos) == 0) {
                    return response()->json(['success' => false, 'message' => "Sem processamento encontrato!"], 404);
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


        return response()->json(['success' => true, 'message' => "Processamento Anulado com sucesso!"], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function recibo(Request $request, $id)
    {
        $user = auth()->user();

        $processamento = Processamento::with([
            'exercicio',
            'periodo',
            'funcionario.contrato.forma_pagamento',
            'funcionario.contrato.categoria',
            'funcionario.contrato.subsidios_contrato.subsidio',
            'funcionario.contrato.descontos_contrato.desconto',
            'funcionario.contrato.cargo.departamento',
            'funcionario.contrato.tipo_contrato',
            'processamento',
            'user'
        ])
            ->orderBy('created_at', 'desc')
            ->findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);


        $head = [
            "titulo" => "Recibo",
            "descricao" => env('APP_NAME'),
            "processamento" => $processamento,

            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.processamentos.recibo', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
}
