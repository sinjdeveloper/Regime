<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeClientModel extends Model
{
    protected $table = 'regimeclient';

    public function getBoughtRegimesByClient($clientId)
    {
        return $this->db->table('regimeclient rc')
            ->select('r.*')
            ->join('regime r', 'r.id = rc.regime_id')
            ->where('rc.client_id', $clientId)
            ->get()
            ->getResultArray();
    }
}
