<?php

namespace App\Models;

use CodeIgniter\Model;

class SportModel extends Model
{
    protected $table            = 'sport';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['libelle', 'pourcentage_reduction','image'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];


    // Validation
    protected $validationRules      = [
        'libelle'              => 'required|string|min_length[3]|max_length[255]',
        'pourcentage_reduction' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
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

    public function getDemo()
    {
        return $this->orderBy('RAND()')->limit(3)->findAll();
    }
}
