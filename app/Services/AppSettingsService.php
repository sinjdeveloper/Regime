<?php

namespace App\Services;

use App\Models\AppSettingModel;

class AppSettingsService
{
    private const DEFAULTS = [
        'gold_price' => 49.99,
        'gold_discount' => 15,
    ];

    public static function getAll(): array
    {
        $model = new AppSettingModel();

        try {
            $rows = $model->findAll();
        } catch (\Throwable $e) {
            return self::DEFAULTS;
        }

        $settings = [];

        foreach ($rows as $row) {
            $key = $row['libelle'] ?? null;
            if (!$key) continue;

            $settings[$key] = (float) $row['value'];
        }

        return array_merge(self::DEFAULTS, $settings);
    }

    public static function getGoldPrice(): float
    {
        return (float)(self::getAll()['gold_price']);
    }

    public static function setGoldPrice(float $price): bool
    {
        if ($price <= 0) return false;

        $model = new AppSettingModel();
        return $model->setSetting('gold_price', $price);
    }

    public static function getGoldDiscount(): int
    {
        return (int)(self::getAll()['gold_discount']);
    }

    public static function setGoldDiscount(int $discount): bool
    {
        if ($discount < 0 || $discount > 100) return false;

        $model = new AppSettingModel();
        return $model->setSetting('gold_discount', (float)$discount);
    }
}
