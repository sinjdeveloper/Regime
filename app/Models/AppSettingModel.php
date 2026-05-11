<?php

namespace App\Models;

use CodeIgniter\Model;

class AppSettingModel extends Model
{
    protected $table = 'app_settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $protectFields = true;
    protected $allowedFields = ['libelle', 'value'];

    protected $useTimestamps = false;

    /**
     * Set a setting value. Updates if exists, inserts if not.
     *
     * @param string $key The setting key (libelle)
     * @param mixed $value The setting value
     * @return bool True if successful, false otherwise
     */
    public function setSetting(string $key, $value): bool
    {
        try {
            $existing = $this->where('libelle', $key)->first();
            
            if ($existing) {
                $id = (int) ($existing['id'] ?? 0);
                if ($id > 0) {
                    return (bool) $this->update($id, ['value' => (string) $value]);
                }
            }

            return (bool) $this->insert(['libelle' => $key, 'value' => (string) $value]);
        } catch (\Throwable $e) {
            return false;
        }
    }
}

