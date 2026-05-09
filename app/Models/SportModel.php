<?php

namespace App\Models;

use CodeIgniter\Model;

class SportModel extends Model
{
    protected $table = 'sport';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['libelle', 'pourcentage_reduction', 'image'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];


    // Validation
    protected $validationRules = [
        'libelle' => 'required|string|min_length[3]|max_length[255]|is_unique[sport.libelle]',
        'pourcentage_reduction' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
    ];
    protected $validationMessages = [
        'libelle' => [
            'required' => 'Le libellé est obligatoire.',
            'min_length' => 'Le libellé doit contenir au moins 3 caractères.',
            'max_length' => 'Le libellé ne doit pas dépasser 255 caractères.',
            'is_unique' => 'Ce sport existe déjà.',
        ],

        'pourcentage_reduction' => [
            'required' => 'Le pourcentage de réduction est obligatoire.',
            'numeric' => 'Le pourcentage de réduction doit être un nombre.',
            'greater_than_equal_to' => 'Le pourcentage de réduction doit être supérieur ou égal à 0.',
            'less_than_equal_to' => 'Le pourcentage de réduction ne peut pas dépasser 100.',
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
    public function addSport($data)
    {
        return $this->insert($data);
    }

    public function deleteSport($id)
    {
        return $this->delete($id);
    }

    public function updateSport($id, $data)
    {
        return $this->update($id, $data);
    }

    public function findAllSport()
    {
        return $this->findAll();
    }
}
