<?php

namespace App\Support;

use NumberFormatter;

class MontoEnLetras
{
    public static function convertir(float $monto): string
    {
        $totalCentavos = (int) round($monto * 100);
        $lempiras = intdiv($totalCentavos, 100);
        $centavos = $totalCentavos % 100;

        $letras = (new NumberFormatter('es', NumberFormatter::SPELLOUT))->format($lempiras);

        $letras = preg_replace('/veintiuno$/', 'veintiún', $letras);
        $letras = preg_replace('/uno$/', 'un', $letras);

        return mb_strtoupper($letras) . ' LEMPIRAS CON ' . str_pad($centavos, 2, '0', STR_PAD_LEFT) . '/100';
    }
}