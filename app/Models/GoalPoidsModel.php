<?php

namespace App\Models;

use CodeIgniter\Model;

class GoalPoidsModel extends Model
{
    protected $table            = 'goalpoids';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['client_id', 'objectif_id', 'poids_cible', 'duree'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];


    // Validation
    protected $validationRules      = [
        'client_id'    => 'required|integer|greater_than[0]',
        'objectif_id'  => 'required|integer|greater_than[0]',
        'poids_cible'  => 'required|numeric|greater_than[0]',
        'duree'        => 'required|integer|greater_than[0]|less_than_equal_to[365]',
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

    /**
     * Récupère tous les objectifs d'un client avec détails
     *
     * @param int $clientId ID du client
     * @return array Tableau des objectifs avec info client
     */
    public function getClientGoalsWithDetails(int $clientId): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select('goalpoids.*,objectif.libelle as objectif_libelle');
        $builder->join('objectif', 'objectif.id = goalpoids.objectif_id', 'left');
        $builder->where('goalpoids.client_id', $clientId);
        return $builder->get()->getResultArray();
    }

    /**
     * Supprime tous les objectifs d'un client
     *
     * @param int $clientId ID du client
     * @return bool Succès de la suppression
     */
    public function deleteClientGoals(int $clientId): bool
    {
        return $this->where('client_id', $clientId)->delete();
    }

    /**
     * Compte le nombre d'objectifs actifs d'un client
     *
     * @param int $clientId ID du client
     * @return int Nombre d'objectifs
     */
    public function countClientGoals(int $clientId): int
    {
        return $this->where('client_id', $clientId)->countAllResults();
    }
}
