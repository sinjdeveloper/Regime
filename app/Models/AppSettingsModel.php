<?php

namespace App\Models;

use CodeIgniter\Model;

class AppSettingModel extends Model
{
    protected $table = 'app_settings';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = ['libelle', 'value'];

    public function get(string $key, float $default = 0): float
    {
        $row = $this->where('libelle', $key)->first();
        return $row ? (float)$row['value'] : $default;
    }

    public function setSetting(string $key, float $value): bool
    {
        $existing = $this->where('libelle', $key)->first();

        if ($existing) {
            return $this->update($existing['id'], [
                'value' => $value
            ]);
        }

        return $this->insert([
            'libelle' => $key,
            'value' => $value
        ]) !== false;
    }
}
