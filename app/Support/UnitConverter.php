<?php

namespace App\Support;

use App\Models\Unidade;
use InvalidArgumentException;

class UnitConverter
{
    private static array $densities = [
        // líquidos base
        'water' => 1.0,        // água pura (aprox. 4°C)
    
        // óleos
        'oil' => 0.92,         // óleo vegetal comum
        'olive_oil' => 0.91,   // azeite de oliva
        'diesel' => 0.83,      // gasóleo/diesel
        'engine_oil' => 0.88,  // óleo de motor
    
        // combustíveis
        'gasoline' => 0.74,    // gasolina comum
        'kerosene' => 0.80,    // querosene
    
        // outros líquidos comuns
        'milk' => 1.03,        // leite
        'honey' => 1.42,       // mel
        'alcohol' => 0.79,     // etanol (~96%)
    
        // químicos gerais (aproximações úteis)
        'glycerin' => 1.26,    // glicerina
        'seawater' => 1.025,   // água do mar
    ];
    
    public static function converterParaBase(float $quantidade, Unidade $unidade)
    {
        return $quantidade * $unidade->fator_conversao;
    }

    public static function converterDaBase( float $quantidade, Unidade $unidade)
    {
        return $quantidade / $unidade->fator_conversao;
    }
    
    
    // // 📦 ENTRADA COMPRA
    // public static function entradaProduto($materia, $qtd, $unidade)
    // {
    //     $base = self::converterParaBase($qtd, $unidade);

    //     $saldoAnterior = $materia->stock_atual;
    //     $saldoAtual = $saldoAnterior + $base;

    //     $materia->update([
    //         'stock_atual' => $saldoAtual
    //     ]);

    //     MovimentoEstoque::create([
    //         'tipo' => 'ENTRADA_COMPRA',
    //         'materia_prima_id' => $materia->id,
    //         'quantidade_original' => $qtd,
    //         'quantidade_base' => $base,
    //         'unidade_id' => $unidade->id,
    //         'saldo_anterior' => $saldoAnterior,
    //         'saldo_atual' => $saldoAtual
    //     ]);
    // }

    // // 🏭 SAÍDA PRODUÇÃO
    // public static function saidaProducao($materia, $qtd, $unidade)
    // {
    //     $base = self::converterParaBase($qtd, $unidade);

    //     if ($materia->stock_atual < $base) {
    //         throw new \Exception("Stock insuficiente");
    //     }

    //     $saldoAnterior = $materia->stock_atual;
    //     $saldoAtual = $saldoAnterior - $base;

    //     $materia->update([
    //         'stock_atual' => $saldoAtual
    //     ]);

    //     MovimentoEstoque::create([
    //         'tipo' => 'SAIDA_PRODUCAO',
    //         'materia_prima_id' => $materia->id,
    //         'quantidade_original' => $qtd,
    //         'quantidade_base' => $base,
    //         'unidade_id' => $unidade->id,
    //         'saldo_anterior' => $saldoAnterior,
    //         'saldo_atual' => $saldoAtual
    //     ]);
    // }

    // // 🍞 ENTRADA PRODUTO FINAL
    // public static function entradaCompra($produto, $qtd)
    // {
    //     $saldoAnterior = $produto->stock_atual;
    //     $saldoAtual = $saldoAnterior + $qtd;

    //     $produto->update([
    //         'stock_atual' => $saldoAtual
    //     ]);

    //     MovimentoEstoque::create([
    //         'tipo' => 'ENTRADA_PRODUTO',
    //         'produto_id' => $produto->id,
    //         'quantidade_original' => $qtd,
    //         'quantidade_base' => $qtd,
    //         'saldo_anterior' => $saldoAnterior,
    //         'saldo_atual' => $saldoAtual
    //     ]);
    // }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
     * Converte qualquer unidade para gramas (base interna)
    */
    public static function toGrams(float $quantity, string $unit, string $material = 'water'): float
    {
        $density = self::$densities[$material] ?? null;
    
        switch ($unit) {
            case 'kg':
                return $quantity * 1000;
                
            case 'g':
                return $quantity;
                
            // volume (precisa de densidade)
            case 'l':
                if ($density === null) {
                    throw new InvalidArgumentException("Material sem densidade definida.");
                }
                return $quantity * 1000 * $density;
                
            case 'ml':
                if ($density === null) {
                    throw new InvalidArgumentException("Material sem densidade definida.");
                }
                return $quantity * $density;
                
            // unidade simples (sem conversão física)
            case 'un':
                return $quantity;

            default:
                throw new InvalidArgumentException("Unidade inválida: $unit");

        }
    }
    
    
    /**
     * Converte gramas de volta para a unidade original
     */
    public static function fromGrams(float $grams, string $unit, string $material = 'water'): float
    {
        $density = self::$densities[$material] ?? null;

        switch ($unit) {

            // massa
            case 'kg':
                return $grams / 1000;

            case 'g':
                return $grams;

            // volume (precisa de densidade)
            case 'l':
                if ($density === null) {
                    throw new InvalidArgumentException("Material sem densidade definida.");
                }
                return $grams / (1000 * $density);

            case 'ml':
                if ($density === null) {
                    throw new InvalidArgumentException("Material sem densidade definida.");
                }
                return $grams / $density;

            // unidade simples
            case 'un':
                return $grams;

            default:
                throw new InvalidArgumentException("Unidade inválida: $unit");
        }
    }
    
}
