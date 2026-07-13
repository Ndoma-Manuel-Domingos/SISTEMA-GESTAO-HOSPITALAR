<?php

namespace App\Services;

use App\Models\OperacaoFinanceiro;

class DREService
{
    public function generate(string $empresa_id, string $loja_id = "")
    {
        $revenue = OperacaoFinanceiro::where('type', 'R')
            ->where('entidade_id', $empresa_id)
            ->where('loja_id', $loja_id)
        ->sum('motante');

        $expenses = OperacaoFinanceiro::where('type', 'D')
            ->where('entidade_id', $empresa_id)
            ->where('loja_id', $loja_id)
        ->sum('motante');

        $grossProfit = $revenue - $expenses;
        
        $margin = 0;

        if ($revenue != 0) {
            $margin = ($grossProfit / $revenue) * 100;
        }

        return [
            'revenue' => $revenue,
            'expenses' => $expenses,
            'profit' => $grossProfit,
            'margin' => $margin,
        ];
    }
}
