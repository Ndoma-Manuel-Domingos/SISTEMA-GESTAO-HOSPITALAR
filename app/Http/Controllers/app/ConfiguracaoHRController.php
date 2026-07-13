<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Banco;
use App\Models\Caixa;
use App\Models\Cargo;
use App\Models\ConfiguracaoRecursoHumano;
use App\Models\ContaBancaria;
use App\Models\Contrato;
use App\Models\Departamento;
use App\Models\Dispesa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ConfiguracaoHRController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();

        $entidade = User::with(['empresa'])->findOrFail($user->id);

        $configuracao = ConfiguracaoRecursoHumano::where('entidade_id', $entidade->empresa->id)->first();

        $caixas = Caixa::where('entidade_id', $entidade->empresa->id)
            ->where('status_admin', 'liberado')->get();
        $bancos = ContaBancaria::where('entidade_id', $entidade->empresa->id)->get();
        $dispesas = Dispesa::where('type', 'D')->where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Configurações recursos humanos",
            "descricao" => env('APP_NAME'),
            "caixas" => $caixas,
            "bancos" => $bancos,
            "dispesas" => $dispesas,
            "configuracao" => $configuracao,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.configuracao-rh.create', $head);
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
        $entidade = User::with(['empresa'])->findOrFail($user->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            if ($request->configuracao_id != null) {
                $configuracao = ConfiguracaoRecursoHumano::findOrFail($request->configuracao_id);
                $configuracao->horas_diarias = $request->horas_diarias;
                $configuracao->horas_semanais = $request->horas_semanais;
                $configuracao->caixa_pagamento_id = $request->caixa_pagamento_id;
                $configuracao->banco_pagamento_id = $request->banco_pagamento_id;
                $configuracao->dispesa_pagamento_id = $request->dispesa_pagamento_id;
                $configuracao->update();
            } else {

                ConfiguracaoRecursoHumano::create([
                    "horas_diarias" => $request->horas_diarias,
                    "horas_semanais" => $request->horas_semanais,
                    "caixa_pagamento_id" => $request->caixa_pagamento_id,
                    "banco_pagamento_id" => $request->banco_pagamento_id,
                    "dispesa_pagamento_id" => $request->dispesa_pagamento_id,
                    "entidade_id" => $entidade->empresa->id,
                    "user_id" => Auth::user()->id,
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

        return response()->json(['success' => true, 'message' => "Dados salvos com sucesso!"], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {}

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

        // if(!$user->can('editar todos') && !$user->can('editar departamento')){
        //     
        //     return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }

        $request->validate([
            'nome' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $departamento = Departamento::findOrFail($id);
            $departamento->update($request->all());

            $departamento->update();

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
    public function destroy($id) {}



    public function graficoFuncionarioDepartamentos(Request $requst)
    {

        $entidade = User::with('empresa.tipo_entidade.modulos')->findOrFail(Auth::user()->id);

        // Consulta as receitas e soma os valores de cada categoria
        $dados = Departamento::where('entidade_id', $entidade->empresa->id)->with(['contratos' => function ($query) {
            $query->where('status', 'activo');
        }])->get()
            ->map(function ($departamento) {
                return [
                    'nome' => $departamento->nome,
                    'total' => $departamento->contratos->count('id'),
                ];
            });

        return response()->json($dados);
    }


    public function graficoFuncionarioCargos(Request $requst)
    {
        $entidade = User::with('empresa.tipo_entidade.modulos')->findOrFail(Auth::user()->id);

        // Consulta as receitas e soma os valores de cada categoria
        $dados = Cargo::where('entidade_id', $entidade->empresa->id)->with(['departamento', 'contratos' => function ($query) {
            $query->where('status', 'activo');
        }])->get()
            ->map(function ($cargo) {
                return [
                    'nome' => $cargo->nome,
                    'total' => $cargo->contratos->count('id'),
                ];
            });

        return response()->json($dados);
    }



    public function graficoTaxaRotatividadeAnual(Request $request)
    {
        $entidade = User::with('empresa.tipo_entidade.modulos')->findOrFail(Auth::user()->id);
        //  Ano inicio das actividades
        $anoInicioActividade = (int) $entidade->empresa->ano_inicio_actividade;
        // Ano anterior 
        $anoAnterior = now()->year - 1;
        // ano actual
        $anoAtual = now()->year;

        // Inicializa os dados para cada mês
        $dadosMensais = array_fill(1, 12, [
            'admitidos' => 0,
            'demitidos' => 0,
            'total_funcionarios' => 0,
            'taxa' => 0,
        ]);

        // Obtém o total de funcionários ativos no início do ano ou no inicio da actividade até o ano anterior para sabemos quantos funcionario começamos esse ano
        $totalFuncionarios = Contrato::where('entidade_id', $entidade->empresa->id)
            ->where('status', 'activo')
            ->whereNull('data_demissao')
            ->whereDate('data_admissao', '>=', "$anoInicioActividade-01-01")
            ->whereDate('data_admissao', '<=', "$anoAnterior-12-31")
            ->count();

        // Obtém todos os contratos do ano atual (admitidos ou demitidos)
        $contratos = Contrato::where('entidade_id', $entidade->empresa->id)
            ->whereIn('status', ['activo'])
            ->whereYear('data_admissao', $anoAtual)
            ->orWhereYear('data_demissao', $anoAtual)
            ->get();

        // Inicializa contadores anuais
        $totalAdmitidosAnual = 0;
        $totalDemitidosAnual = 0;

        foreach ($contratos as $contrato) {
            if ($contrato->data_admissao && Carbon::parse($contrato->data_admissao)->year == $anoAtual && $contrato->entidade_id == $entidade->empresa->id) {
                $mesAdmissao = Carbon::parse($contrato->data_admissao)->month;
                $dadosMensais[$mesAdmissao]['admitidos']++;
                $totalAdmitidosAnual++;
            }

            if ($contrato->data_demissao && Carbon::parse($contrato->data_demissao)->year == $anoAtual && $contrato->entidade_id == $entidade->empresa->id) {
                $mesDemissao = Carbon::parse($contrato->data_demissao)->month;
                $dadosMensais[$mesDemissao]['demitidos']++;
                $totalDemitidosAnual++;
            }
        }
        $totalFuncionarios_a = $totalFuncionarios_d = 0;
        // Atualiza o total de funcionários mês a mês
        for ($mes = 1; $mes <= 12; $mes++) {
            // Atualiza o número total de funcionários no mês
            $totalFuncionarios_a += $dadosMensais[$mes]['admitidos'];
            $totalFuncionarios_d += $dadosMensais[$mes]['demitidos'];

            // Garante que o total não fique negativo
            $dadosMensais[$mes]['total_funcionarios'] = ($totalFuncionarios_a + $totalFuncionarios) - $totalFuncionarios_d;

            // if($totalFuncionarios_d != 0) {
            //     $dadosMensais[$mes]['taxa'] = ($totalFuncionarios_d / ($totalFuncionarios_a + $totalFuncionarios)) * 100;
            // }else {
            //     $dadosMensais[$mes]['taxa'] = 0;
            // }

        }


        $total = $totalFuncionarios_a - $totalFuncionarios_d;

        // Retorna os dados formatados para o frontend
        return response()->json([
            'mensal' => $dadosMensais,
            'totais' => [
                'admitido' => $totalFuncionarios_a,
                'demitido' => $totalFuncionarios_d,
                'taxa' => $total,
                'total' => $total,
            ],
        ]);
    }
}
