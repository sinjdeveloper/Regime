<?php

namespace App\Controllers;

use App\Models\RegimeClientModel;

class RegimeClientController extends BaseController
{
    public function myRegimes()
    {
        $user = session()->get('user');

        if (!$user || !isset($user['client_id'])) {
            return redirect()->to('/login');
        }

        $clientId = $user['client_id'];

        $model = new RegimeClientModel();

        $regimes = $model->getBoughtRegimesByClient($clientId);

        return view('client/my-regimes', [
            'regimes' => $regimes
        ]);
    }
}
