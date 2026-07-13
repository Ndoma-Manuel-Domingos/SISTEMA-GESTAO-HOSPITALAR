<?php

namespace App\Http\Controllers;

use App\Models\ContaHospitalar;
use App\Models\FacturaSeguradora;
use App\Models\FacturaSeguradoraConta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

use Barryvdh\DomPDF\Facade\Pdf;

class FechoContaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Listagem das contas
     */
    public function index(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $mes = $request->mes ?? date('m');
        $ano = $request->ano ?? date('Y');

        $contas = ContaHospitalar::with([
            'plano.seguradora'
        ])
            ->whereNotNull('plano_id')
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $ano)
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $dados = [];
        foreach (
            $contas->groupBy(function ($conta) {
                return $conta->plano->seguradora->id;
            }) as $seguradoraId => $items
        ) {
            $seguradora = $items->first()->plano->seguradora;
            $dados[] = [
                'seguradora' => $seguradora,
                'quantidade' => $items->count(),
                'total' => $items->sum('valor_seguradora'),
                'pago' => $items->sum('valor_pago_seguradora'),
                'divida' => $items->sum('saldo_seguradora'),
                'mes' => $mes,
                'ano' => $ano
            ];
        }

        return view("dashboard.atendimentos.contas.fecho", [
            "titulo" => "Cobraça da Seguradora",
            "descricao" => env("APP_NAME"),
            "dados" => $dados,
            "mes" => $mes,
            "ano" => $ano,
            "empresa_logada" => $entidade,
        ]);
    }


    public function mais_detalhe(string $seguradora, string $mes, string $ano)
    {
        $contas = ContaHospitalar::with(['paciente', 'plano', 'itens'])
            ->whereHas('plano', function ($q) use ($seguradora) {
                $q->where('seguradora_id', $seguradora);
            })
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $ano)
            ->get();

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        return view("dashboard.atendimentos.contas.fecho-show", [
            "titulo" => "Mais detalhe - Cobraça da Seguradora",
            "descricao" => env("APP_NAME"),
            'contas' => $contas,
            'seguradora' => $contas->first() ? $contas->first()->plano->seguradora : null,
            'mes' => $mes,
            'ano' => $ano,
            "empresa_logada" => $entidade,
        ]);
    }

    public function export(string $id)
    {
        $factura = FacturaSeguradora::with([
            'seguradora',
            'contas.itens',
            'contas.paciente'
        ])->where('seguradora_id', $id)->first();

        $pdf = Pdf::loadView('dashboard.atendimentos.contas.pdf', compact('factura'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('FS_' . $factura->numero . '.pdf');
    }

    public function store(Request $request)
    {

        $request->validate([
            'seguradora_id' => 'required|exists:seguradoras,id',
            'mes' => 'required|min:1|max:12',
            'ano' => 'required'
        ]);

        DB::beginTransaction();

        try {

            $entidade = User::with('empresa')->findOrFail(Auth::id());
            /* Procura todas as contas da seguradora ainda não faturadas */
            $contas = ContaHospitalar::with(['plano'])
                ->whereHas('plano', function ($q) use ($request) {
                    $q->where('seguradora_id', $request->seguradora_id);
                })
                ->whereMonth('created_at', $request->mes)
                ->whereYear('created_at', $request->ano)
                ->where('saldo_seguradora', '>', 0)
                ->where('entidade_id', $entidade->empresa->id)
                ->where('status', 'ABERTA')
                ->get();

            if ($contas->isEmpty()) {
                throw new \Exception('Não existem contas para faturar.');
            }

            /* Totais  */
            $subtotal = $contas->sum('saldo_seguradora');
            $desconto = 0;
            $acrescimo = 0;
            $total = $subtotal - $desconto + $acrescimo;

            /* Cria a Factura */
            $factura = FacturaSeguradora::create([
                'numero' => 'FS' . now()->format('YmdHis'),
                'seguradora_id'    => $request->seguradora_id,
                'mes'   => $request->mes,
                'ano'   => $request->ano,
                'subtotal'  => $subtotal,
                'desconto'  => $desconto,
                'acrescimo' => $acrescimo,
                'total' => $total,
                'valor_pago' => 0,
                'saldo' => $total,
                'status' => 'PENDENTE',
                'observacao' => $request->observacao,
                'data_emissao' => now(),
                'data_vencimento' => now()->addDays(30),
                'user_id' => Auth::id(),
                'entidade_id' => $entidade->empresa->id
            ]);

            /* Adiciona as Contas  */
            foreach ($contas as $conta) {
                FacturaSeguradoraConta::create([
                    'factura_seguradora_id' => $factura->id,
                    'conta_hospitalar_id'   => $conta->id,
                    'subtotal' => $conta->valor_seguradora,
                    'desconto' => 0,
                    'acrescimo' => 0,
                    'total'  => $conta->valor_seguradora,
                    'valor' => $conta->saldo_seguradora,
                ]);
                /*  Marca a conta como faturada */
                $conta->update([
                    'status' => 'EM_ANDAMENTO'
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Factura da seguradora gerada com sucesso.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
