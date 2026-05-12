<?php

namespace App\Helpers;


class HealthHelper
{

    public static function calculateIMC(float $poids, float $taille): float
    {
        // Convertir taille de cm à m
        $talleMeters = $taille / 100;
        $imc = $poids / ($talleMeters ** 2);
        return round($imc, 1);
    }


    public static function interpretIMC(float $imc): string
    {
        if ($imc < 18.5) {
            return 'Underweight';
        } elseif ($imc < 25) {
            return 'Ideal Weight';
        } elseif ($imc < 30) {
            return 'Overweight';
        } else {
            return 'Obésité';
        }
    }


    public static function calculatePoidsDifference(float $poidsActuel, float $poidsObjectif): float
    {
        return round($poidsActuel - $poidsObjectif, 2);
    }

    public static function isWeightChangeAcceptable(
        float $poidsActuel,
        float $poidsObjectif,
        float $tolerancePercent = 10
    ): bool {
        $tolerance = ($poidsActuel * $tolerancePercent) / 100;
        $difference = abs($poidsActuel - $poidsObjectif);
        return $difference <= $tolerance;
    }
}
