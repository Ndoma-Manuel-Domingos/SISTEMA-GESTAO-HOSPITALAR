<?php

namespace App\Services;

use App\Models\FichaTriagem;
use Carbon\Carbon;

class RelatorioTriagemService
{
    public function cards()
    {
        return [
            'total_triagens' => FichaTriagem::count(),
            'hoje' => FichaTriagem::whereDate('created_at', today())->count(),
            'concluidos' => FichaTriagem::where('status', 'CONCLUIDO')->count(),
            'em_atendimento' => FichaTriagem::where('status', 'EM ATENDIMENTO')->count(),
        ];
    }

    public function triagensPeriodo()
    {
        return FichaTriagem::selectRaw("
            DATE(created_at) data,
            COUNT(*) total
        ")
            ->groupBy('data')
            ->orderBy('data')
            ->get();
    }

    public function prioridades()
    {
        return FichaTriagem::selectRaw("
            prioridade_id,
            COUNT(*) total
        ")
            ->groupBy('prioridade_id')
            ->get();
    }

    public function profissionais()
    {
        return FichaTriagem::selectRaw("
            profissional_id,
            COUNT(*) total
        ")
            ->groupBy('profissional_id')
            ->orderByDesc('total')
            ->get();
    }

    public function queixas()
    {
        return FichaTriagem::selectRaw("
            queixa_principal,
            COUNT(*) total
        ")
            ->groupBy('queixa_principal')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
    }

    public function imc()
    {
        return FichaTriagem::selectRaw("
            imc_classificacao,
            COUNT(*) total
        ")
            ->groupBy('imc_classificacao')
            ->get();
    }

    public function sinaisVitais()
    {
        return [
            'febre' => FichaTriagem::where('temperatura', '>', 37.8)->count(),

            'taquicardia' => FichaTriagem::where('freq_cardiaca', '>', 100)->count(),

            'hipertensos' => FichaTriagem::where('pressao', 'LIKE', '%14/%')->count(),
        ];
    }

    public function status()
    {
        return FichaTriagem::selectRaw("
            status,
            COUNT(*) total
        ")
            ->groupBy('status')
            ->get();
    }

    public function tempoAtendimento()
    {
        return FichaTriagem::selectRaw("
            AVG(TIMESTAMPDIFF(MINUTE,created_at,updated_at))
            as media
        ")
            ->first();
    }

    public function entidades()
    {
        return FichaTriagem::selectRaw("
            entidade_id,
            COUNT(*) total
        ")
            ->groupBy('entidade_id')
            ->get();
    }
}
