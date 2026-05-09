<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'sport';
    protected $primaryKey = 'id';
    protected $allowedFields = [];

    function getRegimes()
    {
        return $this->db->table('regime')->countAllResults();
    }
    function getUtilisateurs()
    {
        return $this->db->table('client')->countAllResults();
    }
    function getTransactions()
    {
        return $this->db->table('historiquetransaction')->countAllResults();
    }
    function getStats()
    {
        return [
            "regimes" => $this->getRegimes(),
            "utilisateurs" => $this->getUtilisateurs(),
            "transactions" => $this->getTransactions()
        ];
    }
    function getRepartitionGold()
    {
        $gold = $this->db->table('client')->select("
        CASE
            WHEN estGold = 1 THEN 'Gold'
            ELSE 'Standard'
        END AS libelle,
        COUNT(*) AS nombre
    ")
            ->groupBy('estGold')
            ->get()
            ->getResultArray();
        return [
            'repartition' => $gold
        ];


    }
}
