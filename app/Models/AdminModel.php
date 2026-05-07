<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table            = 'sport';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [];

    function getRegimes(){
        return $this->table('regime')->countAll();
    }
    function getUtilisateurs(){
        return $this->table('client')->countAll();
    }
    function getTransactions(){
        return $this->table('historiquetransaction')->countAll();
    }
    function getStats(){
        return [
            "regimes" => $this->getRegimes(),
            "utilisateurs" => $this->getUtilisateurs(),
            "transactions" => $this->getTransactions()
        ];
    }
}
