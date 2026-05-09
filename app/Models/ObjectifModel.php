<?php

namespace App\Models;

use CodeIgniter\Model;

class ObjectifModel extends Model
{
    protected $table            = 'objectifs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['libelle'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];



    // Validation
    protected $validationRules      = [
        'libelle' => 'required|string|min_length[3]|max_length[255]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getAvailableGoals()
    {
        $db = db_connect();
        return $db->table('objectif')->get()->getResultArray();
    }

    public function validateMax3($goals)
    {
        $errors = [];

        if (!is_array($goals)) {
            $errors[] = "Invalid data format";
            return ['status' => false, 'errors' => $errors];
        }

        if (count($goals) > 1) {
            $errors[] = "Vous ne pouvez sélectionner que 1 objectif maximum";
        }

        return [
            'status' => empty($errors),
            'errors' => $errors,
            'data' => $goals
        ];
    }

    public function storeDraft($goals)
    {
        return [
            'goals' => $goals
        ];
    }

}
