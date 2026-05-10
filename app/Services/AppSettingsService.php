<?php

namespace App\Services;

use App\Models\AppSettingModel;

class AppSettingsService
{
    private const DEFAULTS = [
        'gold_price' => 49.99,
    ];

    /**
     * @return array{gold_price: float}
     */
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
            if (!is_array($row)) {
                continue;
            }
            $key = (string) ($row['libelle'] ?? '');
            if ($key === '') {
                continue;
            }
            $settings[$key] = $row['value'] ?? null;
        }

        return array_merge(self::DEFAULTS, $settings);
    }

    public static function getGoldPrice(): float
    {
        $settings = self::getAll();
        $value = $settings['gold_price'] ?? self::DEFAULTS['gold_price'];
        return (float) $value;
    }

    public static function setGoldPrice(float $price): bool
    {
        if (!is_finite($price) || $price <= 0) {
            return false;
        }

        $model = new AppSettingModel();

        try {
            $existing = $model->where('libelle', 'gold_price')->first();
            if ($existing) {
                $id = (int) ($existing['id'] ?? 0);
                if ($id > 0) {
                    return (bool) $model->update($id, ['value' => (string) $price]);
                }
            }

            return (bool) $model->insert(['libelle' => 'gold_price', 'value' => (string) $price]);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
