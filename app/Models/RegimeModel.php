<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeModel extends Model
{
    protected $table = 'regime';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['libelle', 'description', 'variation_poids', 'pourcentage_viande', 'pourcentage_poisson', 'pourcentage_volaille', 'prix'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];


    // Validation
    protected $validationRules = [
        'libelle' => 'required|string|min_length[3]|max_length[255]',
        'description' => 'required|string|min_length[10]',
        'variation_poids' => 'required|numeric|greater_than[0]|less_than[100]',
        'pourcentage_viande' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        'pourcentage_poisson' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        'pourcentage_volaille' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        'prix' => 'required|numeric|greater_than[0]',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function getDemo()
    {
        return $this->limit(3)->findAll();
    }
    public function addRegime($data)
    {
        return $this->insert($data);
    }

    public function deleteRegime($id)
    {
        return $this->delete($id);
    }

    public function updateRegime($id, $data)
    {
        return $this->update($id, $data);
    }

    public function findAllRegime()
    {
        return $this->findAll();
    }
}
