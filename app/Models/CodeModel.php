<?php

namespace App\Models;

use CodeIgniter\Model;

class CodeModel extends Model
{
    protected $table = 'code';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['token', 'montant', 'utilisé', 'statut_code_id'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];



    // Validation
    protected $validationRules = [
        'token' => 'required|string|is_unique[code.token]|min_length[5]',
        'montant' => 'required|numeric|greater_than[0]',
        'utilisé' => 'boolean',
        'statut_code_id' => 'permit_empty|integer',
    ];
    protected $validationMessages = [
        'token' => ['is_unique' => 'Ce code existe déjà'],
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


    public function getClientCode($id)
    {
        return $this->join('historiquetransaction', 'historiquetransaction.code_id = code.id')
            ->join('client', 'client.id = historiquetransaction.client_id')
            ->select('client.*')
            ->where('code.id', $id)  
            ->first();
    }
    public function findCodesAttente()
    {
        $codes = $this->join('historiquetransaction', 'historiquetransaction.code_id=code.id')
            ->join('client', 'client.id= historiquetransaction.client_id')
            ->select('code.* , historiquetransaction.id as idt, historiquetransaction.client_id, historiquetransaction.code_id,client.email')
            ->where('statut_code_id', 2)
            ->get()
            ->getResultArray();
        return $codes;
    }
}
