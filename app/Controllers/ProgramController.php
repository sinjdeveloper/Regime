<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RegimeModel;
use App\Models\SportModel;
use App\Models\ClientModel;

class ProgramController extends BaseController
{
    public function regime($id)
    {
        $regimeModel = new RegimeModel();
        $regime = $regimeModel->find((int) $id);

        if (!$regime) {
            return redirect()->to('/');
        }

        // Vérifier si l'utilisateur connecté a acheté ce régime
        $isPurchased = false;
        $user = session()->get('user');
        if (!empty($user['id'])) {
            $clientModel = new ClientModel();
            $client = $clientModel->where('id_user', $user['id'])->first();
            if ($client) {
                $isPurchased = $regimeModel->hasPurchased((int) $client['id'], (int) $id);
            }
        }

        return view('programs/regime_detail', [
            'regime'      => $regime,
            'isPurchased' => $isPurchased,
        ]);
    }

    public function sport($id)
    {
        $model = new SportModel();
        $sport = $model->find((int) $id);

        if (!$sport) {
            return redirect()->to('/');
        }

        return view('programs/sport_detail', [
            'sport' => $sport,
        ]);
    }
}
