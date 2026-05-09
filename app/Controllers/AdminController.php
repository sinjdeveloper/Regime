<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;

use App\Models\RegimeModel;
use CodeIgniter\HTTP\ResponseInterface;

class AdminController extends BaseController
{
    public function loginPage()
    {
        return view('login-admin');
    }
    public function index()
    {
        $model = new AdminModel();
        $regime_model = new RegimeModel();
        $data = $model->getStats();
        $regimes = $regime_model->findAllRegime();

        return view('admin/dashboard', [
            'stats' => $data,
            'regimes' => $regimes
        ]);
    }

}
