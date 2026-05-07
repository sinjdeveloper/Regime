<?php

namespace App\Helpers;

/**
 * PricingHelper - Utilitaires pour calculs de prix et réductions
 */
class PricingHelper
{
    // Constante pour réduction Gold
    private const GOLD_DISCOUNT_PERCENTAGE = 15;

    /**
     * Applique la réduction Gold (15%) sur un prix si applicable
     *
     * @param float $prix    Prix original
     * @param bool  $estGold Si le client a l'abonnement Gold
     * @return float Prix avec réduction appliquée (arrondi à 2 décimales)
     */
    public static function applyGoldReduction(float $prix, bool $estGold = false): float
    {
        if (!$estGold) {
            return round($prix, 2);
        }

        $reduction = ($prix * self::GOLD_DISCOUNT_PERCENTAGE) / 100;
        $prixFinal = $prix - $reduction;
        return round($prixFinal, 2);
    }

    /**
     * Calcul du montant de réduction Gold
     *
     * @param float $prix Prix original
     * @return float Montant de la réduction
     */
    public static function calculateGoldDiscount(float $prix): float
    {
        $discount = ($prix * self::GOLD_DISCOUNT_PERCENTAGE) / 100;
        return round($discount, 2);
    }

    /**
     * Retourne le pourcentage de réduction Gold
     *
     * @return int Pourcentage de réduction
     */
    public static function getGoldDiscountPercentage(): int
    {
        return self::GOLD_DISCOUNT_PERCENTAGE;
    }

    /**
     * Calcul du prix d'une suggestion avec réductions appliquées
     * Prix total = prix régime (avec Gold si applicable)
     * Le sport n'ajoute pas de coût mais applique une réduction % en performance
     *
     * @param float $prixRegime    Prix du régime
     * @param bool  $estGold       Si le client a l'abonnement Gold
     * @return float Prix final du régime après réductions
     */
    public static function calculateSuggestionPrice(float $prixRegime, bool $estGold = false): float
    {
        return self::applyGoldReduction($prixRegime, $estGold);
    }

    /**
     * Format prix pour affichage
     *
     * @param float $prix Prix à formatter
     * @return string Prix formaté en euros (ex: "29,99 €")
     */
    public static function formatPrice(float $prix): string
    {
        return number_format($prix, 2, ',', ' ') . ' €';
    }
}
