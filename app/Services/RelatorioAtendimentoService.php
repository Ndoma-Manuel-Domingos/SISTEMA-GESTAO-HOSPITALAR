<?php

namespace App\Services;

use App\Models\Atendimento;
use Carbon\Carbon;

class RelatorioAtendimentoService
{

    public function totalAtendimentosHoje()
    {
        return Atendimento::whereDate('data_at', Carbon::today())->count();
    }

    public function totalAtendimentosMes()
    {
        return Atendimento::whereMonth('data_at', Carbon::now()->month)
            ->whereYear('data_at', Carbon::now()->year)
            ->count();
    }

    public function atendimentosPorStatus()
    {
        return Atendimento::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get();
    }

    public function atendimentosPorMedico()
    {
        return Atendimento::selectRaw('profissional_id, COUNT(*) as total')
            ->groupBy('profissional_id')
            ->orderByDesc('total')
            ->get();
    }

    public function atendimentosPorTipo()
    {
        return Atendimento::selectRaw('tipo_atendimento_id, COUNT(*) as total')
            ->groupBy('tipo_atendimento_id')
            ->get();
    }

    public function taxaFaltas()
    {
        $total = Atendimento::count();

        $faltas = Atendimento::where('status', 'ausente')->count();

        return $total > 0 ? round(($faltas / $total) * 100, 2) : 0;
    }

    public function internamentosMes()
    {
        return Atendimento::where('status', 'internamento')
            ->whereMonth('data_at', Carbon::now()->month)
            ->count();
    }

    public function tratamentosAtivos()
    {
        return Atendimento::where('status', 'tratamento')->count();
    }

    public function evolucaoDiaria()
    {
        return Atendimento::selectRaw('data_at, COUNT(*) as total')
            ->groupBy('data_at')
            ->orderBy('data_at')
            ->get();
    }

    public function evolucaoMensal()
    {
        return Atendimento::selectRaw('YEAR(data_at) as ano, MONTH(data_at) as mes, COUNT(*) as total')
            ->groupBy('ano', 'mes')
            ->orderBy('ano')
            ->orderBy('mes')
            ->get();
    }

    public function tempoMedioAtendimento()
    {
        return Atendimento::selectRaw("
        AVG(TIMESTAMPDIFF(MINUTE, created_at, updated_at)) as media_minutos
    ")
            ->whereNotNull('created_at')
            ->whereNotNull('updated_at')
            ->first()
            ->media_minutos;
    }


    public function tempoMedioPorDia()
    {
        return Atendimento::selectRaw("
        data_at,
        AVG(TIMESTAMPDIFF(MINUTE, created_at, updated_at)) as media
    ")
            ->whereNotNull('created_at')
            ->whereNotNull('updated_at')
            ->groupBy('data_at')
            ->orderBy('data_at')
            ->get();
    }
}
