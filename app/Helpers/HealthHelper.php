<?php

namespace App\Helpers;

/**
 * HealthHelper - Utilitaires pour calculs de santé (IMC, etc.)
 */
class HealthHelper
{
    /**
     * Calcul de l'Indice de Masse Corporelle (IMC)
     * Formule: poids (kg) / (taille (m))²
     *
     * @param float $poids   Poids en kg
     * @param float $taille  Taille en cm
     * @return float IMC arrondi à 1 décimale
     */
    public static function calculateIMC(float $poids, float $taille): float
    {
        // Convertir taille de cm à m
        $talleMeters = $taille / 100;
        $imc = $poids / ($talleMeters ** 2);
        return round($imc, 1);
    }

    /**
     * Interprétation de l'IMC selon les normes WHO
     * - IMC < 18.5: Insuffisance pondérale
     * - IMC 18.5-24.9: Normal
     * - IMC >= 25: Surpoids
     * - IMC >= 30: Obésité (non demandé mais utile)
     *
     * @param float $imc Indice de Masse Corporelle
     * @return string Interprétation en français
     */
    public static function interpretIMC(float $imc): string
    {
        if ($imc < 18.5) {
            return 'insuffisance_pondérale';
        } elseif ($imc < 25) {
            return 'normal';
        } elseif ($imc < 30) {
            return 'surpoids';
        } else {
            return 'obésité';
        }
    }

    /**
     * Calcul de la différence entre poids objectif et poids actuel
     *
     * @param float $poidsActuel     Poids actuel en kg
     * @param float $poidsObjectif   Poids objectif en kg
     * @return float Différence (positive = perte, négative = gain)
     */
    public static function calculatePoidsDifference(float $poidsActuel, float $poidsObjectif): float
    {
        return round($poidsActuel - $poidsObjectif, 2);
    }

    /**
     * Vérification si variation poids est acceptable
     * Accepte une variation ±10% du poids actuel
     *
     * @param float $poidsActuel       Poids actuel en kg
     * @param float $poidsObjectif     Poids objectif en kg
     * @param float $tolerancePercent  Tolérance en pourcentage (défaut: 10%)
     * @return bool True si variation acceptable
     */
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
