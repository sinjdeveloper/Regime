<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RegimeModel;
use App\Models\SportModel;

class ProgramController extends BaseController
{
    public function regime($id)
    {
        $model = new RegimeModel();
        $regime = $model->find((int) $id);

        if (!$regime) {
            return redirect()->to('/');
        }

        return view('programs/regime_detail', [
            'regime' => $regime,
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
