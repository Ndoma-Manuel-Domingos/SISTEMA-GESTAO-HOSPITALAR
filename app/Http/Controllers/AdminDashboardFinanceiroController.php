<?php

namespace App\Http\Controllers;

use App\Models\Mensalidade;
use App\Models\MensalidadeCota;
use App\Models\Pagamento;
use App\Models\PagamentoCota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class AdminDashboardFinanceiroController extends Controller
{
    public function index()
    {
        // TOTAL RECEBIDO
        $recebido = Pagamento::sum('valor_pago');

        // INADIMPLENTES (mensalidades vencidas e não pagas)
        $inadimplentes = Mensalidade::where('status', 'vencido')
            ->count();

        // LISTA PRINCIPAL
        $mensalidades = Mensalidade::with('entidade')
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => "Controle de Mensalidade",
            "descricao" => env('APP_NAME'),
            "mensalidades" => $mensalidades,
            "recebido" => $recebido,
            "inadimplentes" => $inadimplentes,
        ];

        return view('admin.operacoes.index', $head);
    }

    public function indexCota(Request $request)
    {
        // TOTAL RECEBIDO
        $recebido = PagamentoCota::sum('valor_pago');

        // INADIMPLENTES (mensalidades vencidas e não pagas)
        $inadimplentes = MensalidadeCota::where('status', 'vencido')->count();

        $valor_acumulado_em_dividas = MensalidadeCota::where('status', 'vencido')->sum('saldo_devedor');
        $valor_acumulado_em_pendente = MensalidadeCota::where('status', 'pendente')->sum('saldo_devedor');

        $query = MensalidadeCota::query();

        $query->when($request->status, function ($q, $value) {
            $q->where('status', $value);
        });

        $query->when($request->mes, function ($q, $value) {
            $q->where('mes', $value);
        });

        $query->when($request->ano, function ($q, $value) {
            $q->where('ano', $value);
        });

        $query->when($request->membro, function ($q, $value) {
            $q->whereHas('membro', function ($q) use ($value) {
                $q->where('nome', 'like', '%' . $value . '%');
            });
        });

        $mensalidades = $query->with('membro')
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => "Controle de cotas",
            "descricao" => env('APP_NAME'),
            "mensalidades" => $mensalidades,
            "recebido" => $recebido,
            "inadimplentes" => $inadimplentes,
            "valor_acumulado_em_dividas" => $valor_acumulado_em_dividas,
            "valor_acumulado_em_pendente" => $valor_acumulado_em_pendente,

            "requests" => $request->all('status', 'mes', 'ano', 'membro')
        ];

        return view('admin.operacoes.index-cota', $head);
    }

    public function indexCotaImprimir(Request $request)
    {
        $query = MensalidadeCota::query();

        $query->when($request->status, function ($q, $value) {
            $q->where('status', $value);
        });

        $query->when($request->mes, function ($q, $value) {
            $q->where('mes', $value);
        });

        $query->when($request->ano, function ($q, $value) {
            $q->where('ano', $value);
        });

        $query->when($request->membro, function ($q, $value) {
            $q->whereHas('membro', function ($q) use ($value) {
                $q->where('nome', 'like', '%' . $value . '%');
            });
        });

        $mensalidades = $query->with('membro')
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => "Controle de cotas",
            "descricao" => env('APP_NAME'),
            "mensalidades" => $mensalidades,
            "requests" => $request->all('status', 'mes', 'ano', 'membro')
        ];

        $pdf = PDF::loadView('dashboard.relatorios.relatorio-cota', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function dados(Request $request)
    {
        $inicio = $request->inicio;
        $fim = $request->fim;
        $tipo = $request->tipo;

        $query = DB::table('operacoes_financeiras')
            ->join('entidades', 'entidades.id', '=', 'operacoes_financeiras.entidade_id')
            ->where('movimento', 'E');

        if ($inicio && $fim) {
            $query->whereBetween('date_at', [$inicio, $fim]);
        }

        // AGRUPAMENTO
        switch ($tipo) {

            case 'mensal':
                $query->select(
                    DB::raw("DATE_FORMAT(date_at, '%Y-%m') as periodo"),
                    'entidades.nome',
                    DB::raw('SUM(motante) as total')
                )
                    ->groupBy('periodo', 'entidades.nome');
                break;

            case 'trimestral':
                $query->select(
                    DB::raw("CONCAT(YEAR(date_at), '-T', QUARTER(date_at)) as periodo"),
                    'entidades.nome',
                    DB::raw('SUM(motante) as total')
                )
                    ->groupBy('periodo', 'entidades.nome');
                break;

            default:
                $query->select(
                    DB::raw("YEAR(date_at) as periodo"),
                    'entidades.nome',
                    DB::raw('SUM(motante) as total')
                )
                    ->groupBy('periodo', 'entidades.nome');
                break;
        }

        $dados = $query->orderBy('periodo')
            ->get();

        return response()->json($dados);
    }

    public function pdf(Request $request)
    {
        $inicio = $request->inicio;
        $fim = $request->fim;
        $tipo = $request->tipo;

        $query = DB::table('operacoes_financeiras')
            ->join('entidades', 'entidades.id', '=', 'operacoes_financeiras.entidade_id')
            ->where('movimento', 'E');

        if ($inicio && $fim) {
            $query->whereBetween('date_at', [$inicio, $fim]);
        }

        switch ($tipo) {

            case 'mensal':

                $query->select(
                    DB::raw("DATE_FORMAT(date_at, '%Y-%m') as periodo"),
                    'entidades.nome',
                    DB::raw('SUM(motante) as total')
                )
                    ->groupBy('periodo', 'entidades.nome');

                break;

            case 'trimestral':

                $query->select(
                    DB::raw("CONCAT(YEAR(date_at), '-T', QUARTER(date_at)) as periodo"),
                    'entidades.nome',
                    DB::raw('SUM(motante) as total')
                )
                    ->groupBy('periodo', 'entidades.nome');

                break;

            default:

                $query->select(
                    DB::raw("YEAR(date_at) as periodo"),
                    'entidades.nome',
                    DB::raw('SUM(motante) as total')
                )
                    ->groupBy('periodo', 'entidades.nome');

                break;
        }

        $dados = $query->orderBy('periodo')->get();

        $pdf = Pdf::loadView(
            'admin.operacoes.pdf-financeiro',
            compact('dados', 'inicio', 'fim', 'tipo')
        );

        return $pdf->stream('relatorio-financeiro.pdf');
    }


    public function storePagamento(Request $request)
    {
        $request->validate([
            'mensalidade_id' => 'required',
            'valor_pago' => 'required|numeric|min:0.01',
            'metodo_pagamento' => 'required'
        ]);

        $mensalidade = Mensalidade::findOrFail($request->mensalidade_id);

        // REGISTAR PAGAMENTO
        Pagamento::create([
            'entidade_id' => $mensalidade->entidade_id,
            'mensalidade_id' => $mensalidade->id,
            'valor_pago' => $request->valor_pago,
            'metodo_pagamento' => $request->metodo_pagamento,
            'referencia' => $request->referencia,
            'data_pagamento' => now()
        ]);

        // ATUALIZAR VALORES
        $mensalidade->valor_pago += $request->valor_pago;

        $mensalidade->saldo_devedor = $mensalidade->valor_total - $mensalidade->valor_pago;

        if ($mensalidade->saldo_devedor <= 0) {
            $mensalidade->status = 'pago';
            $mensalidade->data_pagamento = now();
            $mensalidade->saldo_devedor = 0;
        } else {
            $mensalidade->status = 'parcial';
        }

        $mensalidade->save();

        return redirect()->back()->with('success', 'Pagamento registado com sucesso!');
    }

    public function createPagamento(string $id)
    {
        $mensalidade = Mensalidade::with('entidade')->findOrFail($id);

        return view('admin.operacoes.create', compact('mensalidade'));
    }

    public function storePagamentoCota(Request $request)
    {
        $request->validate([
            'valor_pago' => 'required|numeric|min:1',
            'data_pagamento' => 'required|date',
            'metodo_pagamento' => 'required',
            'comprovativo' => 'nullable|mimes:jpg,jpeg,png,pdf|max:5000',
            'mensalidade_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $mensalidade = MensalidadeCota::findOrFail($request->mensalidade_id);

            $verificarReferencia = PagamentoCota::where('referencia', $request->referencia)->get();

            if ($verificarReferencia && count($verificarReferencia) != 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Referencia do pagamento já foi usada.'
                ], 404);
            }

            if ($mensalidade->status == 'pago') {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta mensalidade já foi paga.'
                ], 404);
            }

            if ($request->valor_pago <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Valor inválido.'
                ], 404);
            }

            if ($request->valor_pago > $mensalidade->saldo_devedor) {
                return response()->json([
                    'success' => false,
                    'message' => 'O valor informado é maior que o saldo devedor.'
                ], 404);
            }

            $comprovativo = null;

            if ($request->hasFile('comprovativo')) {
                $comprovativo = $request->file('comprovativo')->store('comprovativos', 'public');
            }

            // REGISTAR PAGAMENTO
            PagamentoCota::create([
                'numero' => "COMP-" . time(),
                'membro_id' => $mensalidade->membro_id,
                'mensalidade_id' => $mensalidade->id,
                'user_id' => Auth::user()->id,
                'valor_pago' => $request->valor_pago,
                'metodo_pagamento' => $request->metodo_pagamento,
                'referencia' => $request->referencia,
                'comprovativo' => $comprovativo,
                'banco_origem' => $request->banco_origem,
                'observacoes' => $request->observacoes,
                'data_pagamento' => now()
            ]);

            // ATUALIZAR VALORES
            $mensalidade->valor_pago += $request->valor_pago;

            $mensalidade->saldo_devedor = $mensalidade->valor_total - $mensalidade->valor_pago;

            if ($mensalidade->saldo_devedor <= 0) {
                $mensalidade->status = 'pago';
                $mensalidade->data_pagamento = now();
                $mensalidade->saldo_devedor = 0;
            } else {
                $mensalidade->status = 'parcial';
            }

            $mensalidade->save();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!", 'mensalidade' => $mensalidade], 200);
    }

    public function gerarComprovativo(string $id)
    {
        $mensalidade = MensalidadeCota::with('membro', 'pagamentos')->findOrFail($id);

        $pdf = Pdf::loadView('admin.operacoes.pdf-mensalidade', [
            'mensalidade' => $mensalidade
        ]);

        $pdf->setPaper('A4');

        return $pdf->stream('comprovativo-mensalidade-' . $mensalidade->id . '.pdf');
    }

    public function createPagamentoCota(string $id)
    {
        $mensalidade = MensalidadeCota::with('membro')->findOrFail($id);

        return view('admin.operacoes.create-cota', compact('mensalidade'));
    }


    public function gerarMensalidades()
    {
        Artisan::call('mensalidades:gerar');
        Artisan::call('mensalidadescota:gerar');

        return back()->with('success', 'Mensalidades geradas com sucesso!');
    }

    public function calcularJuros()
    {
        Artisan::call('mensalidades:juros');
        Artisan::call('mensalidadescota:juros');

        return back()->with('success', 'Juros e multas atualizados com sucesso!');
    }
}
