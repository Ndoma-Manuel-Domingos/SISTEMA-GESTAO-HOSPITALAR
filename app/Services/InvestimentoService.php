<?php

namespace App\Services;

use App\Models\Investimento;

class InvestimentoService
{
    public function calculateROI(Investimento $investimento)
    {
        if ($investimento->valor_investido <= 0) {
            return 0;
        }

        return (($investimento->valor_atual - $investimento->valor_investido) / $investimento->valor_investido) * 100;
    }

    public function calculateProfit(Investimento $investimento)
    {
        return $investimento->valor_atual - $investimento->valor_investido;
    }

    public function calculatePayback(Investimento $investimento)
    {

        $retorno_mensal = $investimento->returnos()->avg('motante');

        if ($retorno_mensal <= 0) {
            return null;
        }

        return $investimento->valor_investido / $retorno_mensal;
    }
}
