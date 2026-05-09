<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;

use App\Models\RegimeModel;
use App\Models\SportModel;
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
    public function sportIndex()
    {
        $model = new SportModel();
        $sports = $model->findAllSport();

        return view(
            'admin/sport/dashboard',
            ['sports' => $sports]
        );

    }
    public function createSport(){
         $model = new SportModel();
        $data = $this->request->getPost();
        
        if (!$model->addSport($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $model->errors());
        }
        return redirect()->to('/admin/sports/dashboard')
            ->with('success', 'Regime ajouté avec succès');
    }

}
