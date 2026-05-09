<?php

namespace App\Models;

use CodeIgniter\Model;

class ObjectifModel extends Model
{
    protected $table            = 'objectif';
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
        return $this->findAll(); // uses $this->table, no mismatch possible
    }

    public function validateMax3($goals)
    {
        $errors = [];

        // Handle null or missing goals
        if (empty($goals) || !is_array($goals)) {
            $goals = [];
        }

        // Check max 3 goals
        if (count($goals) > 3) {
            $errors[] = "Vous ne pouvez sélectionner que 3 objectifs maximum";
        }

        return [
            'status' => empty($errors),
            'errors' => $errors,
            'data' => is_array($goals) ? $goals : []
        ];
    }

    public function storeDraft($goals)
    {
        return [
            'goals' => $goals
        ];
    }

    public function attachToClient($clientId, $goalIds)
    {
        foreach ($goalIds as $goalId) {
            $this->db->table('goalpoids')->insert([
                'client_id' => $clientId,
                'objectif_id' => $goalId
            ]);
        }
    }
}
