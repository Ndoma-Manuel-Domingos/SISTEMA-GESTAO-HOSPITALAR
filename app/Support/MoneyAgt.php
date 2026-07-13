<?php

namespace App\Support;

class MoneyAgt
{

    public static function ceil(string $value, int $precision = 2): string
    {
        // normaliza separador decimal
        $value = str_replace(',', '.', $value);

        [$int, $decimals] = array_pad(explode('.', $value), 2, '');

        // completa decimais se forem poucas
        if (strlen($decimals) <= $precision) {
            return bcadd($value, '0', $precision);
        }

        // parte que será mantida (até a precisão)
        $keptDecimals = substr($decimals, 0, $precision);

        // parte descartada (depois da precisão)
        $remaining = substr($decimals, $precision);

        // 👉 se tudo que sobra for zero → NÃO arredonda
        if ((int) $remaining === 0) {
            return bcadd("{$int}.{$keptDecimals}", '0', $precision);
        }

        // 👉 se sobrou algo ≠ 0 → faz ceil
        $factor = bcpow('10', (string) $precision);

        return bcdiv(
            bcadd(
                bcmul($value, $factor, 0),
                '1',
                0
            ),
            $factor,
            $precision
        );
    }
}
