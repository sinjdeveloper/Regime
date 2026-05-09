<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\GoalPoidsModel;
use App\Models\RegimeModel;
use App\Models\SportModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $regimeModel = new RegimeModel();
        $regimes = $regimeModel->findAllRegime();

        $db = \Config\Database::connect();
        $objectifs = $db->table('objectif')
            ->select('id, libelle')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        $regimeObjectifCounts = [];
        try {
            $rows = $db->table('regimeclient rc')
                ->select('rc.regime_id, gp.objectif_id, COUNT(*) as nombre')
                ->join('goalpoids gp', 'gp.client_id = rc.client_id', 'inner')
                ->groupBy('rc.regime_id, gp.objectif_id')
                ->get()
                ->getResultArray();

            foreach ($rows as $row) {
                $regimeId = (int) ($row['regime_id'] ?? 0);
                $objectifId = (int) ($row['objectif_id'] ?? 0);
                $count = (int) ($row['nombre'] ?? 0);
                if ($regimeId > 0 && $objectifId > 0) {
                    $regimeObjectifCounts[$regimeId][$objectifId] = $count;
                }
            }
        } catch (\Throwable $e) {
            // Si la table regimeclient n'existe pas encore, on affichera des zéros.
            $regimeObjectifCounts = [];
        }

        return view('dashboard/index', [
            'objectifs' => $objectifs,
            'regimes' => $regimes,
            'regimeObjectifCounts' => $regimeObjectifCounts,
        ]);
    }

    public function getRepartition()
    {
        $goalModel = new GoalPoidsModel();
        $repartition = $goalModel->getRepartitionObjectifs();

        return response()->setJSON($repartition);
    }
    public function getRepartitionGold()
    {
        $model = new AdminModel();
        $repartition = $model->getRepartitionGold();
        return response()->setJSON($repartition);
    }
    public function getPopularRegimes(){
        $model = new RegimeModel();
        $popular = $model->getPopularRegimes();
        return response()->setJSON($popular);
    }


}