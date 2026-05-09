<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeModel extends Model
{
    protected $table = 'regime';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['libelle', 'description', 'variation_poids', 'pourcentage_viande', 'pourcentage_poisson', 'pourcentage_volaille', 'prix','image'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];


    // Validation
    protected $validationRules = [
        'libelle' => 'required|min_length[3]|max_length[255]|is_unique[regime.libelle]',
        'description' => 'required|string|min_length[10]',
        'variation_poids' => 'required|numeric|greater_than_equal_to[-100]|less_than_equal_to[100]',
        'pourcentage_viande' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        'pourcentage_poisson' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        'pourcentage_volaille' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        'prix' => 'required|numeric|greater_than[0]',
    ];
    protected $validationMessages = [
        'libelle' => [
            'required' => 'Le nom de la recette est obligatoire.',
            'min_length' => 'Le nom de la recette doit contenir au moins 3 caractères.',
            'max_length' => 'Le nom de la recette ne doit pas dépasser 255 caractères.',
            'is_unique' => 'Ce régime existe déjà.',
        ],

        'description' => [
            'required' => 'La description est obligatoire.',
            'min_length' => 'La description doit contenir au moins 10 caractères.',
        ],

        'variation_poids' => [
            'required' => 'La variation de poids est obligatoire.',
            'numeric' => 'La variation de poids doit être un nombre.',
            'greater_than_equal_to' => 'La variation de poids doit être supérieure ou égale à -100.',
            'less_than_equal_to' => 'La variation de poids doit être inférieure ou égale à 100.',
        ],

        'pourcentage_viande' => [
            'required' => 'Le pourcentage de viande est obligatoire.',
            'numeric' => 'Le pourcentage de viande doit être un nombre.',
            'greater_than_equal_to' => 'Le pourcentage de viande doit être supérieur ou égal à 0.',
            'less_than_equal_to' => 'Le pourcentage de viande ne peut pas dépasser 100.',
        ],

        'pourcentage_poisson' => [
            'required' => 'Le pourcentage de poisson est obligatoire.',
            'numeric' => 'Le pourcentage de poisson doit être un nombre.',
            'greater_than_equal_to' => 'Le pourcentage de poisson doit être supérieur ou égal à 0.',
            'less_than_equal_to' => 'Le pourcentage de poisson ne peut pas dépasser 100.',
        ],

        'pourcentage_volaille' => [
            'required' => 'Le pourcentage de volaille est obligatoire.',
            'numeric' => 'Le pourcentage de volaille doit être un nombre.',
            'greater_than_equal_to' => 'Le pourcentage de volaille doit être supérieur ou égal à 0.',
            'less_than_equal_to' => 'Le pourcentage de volaille ne peut pas dépasser 100.',
        ],

        'prix' => [
            'required' => 'Le prix est obligatoire.',
            'numeric' => 'Le prix doit être un nombre.',
            'greater_than' => 'Le prix doit être supérieur à 0.',
        ],
    ];
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
        return $this->orderBy('RAND()')->limit(3)->findAll();
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
