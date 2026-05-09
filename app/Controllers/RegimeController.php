<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RegimeModel;
use CodeIgniter\HTTP\ResponseInterface;

class RegimeController extends BaseController
{
    public function index()
    {
        //
    }
    public function create()
    {
        $model = new RegimeModel();
        $data = $this->request->getPost();
        // if ($data['prix'] < 0) {
        //     return redirect()->back()->withInput()->with('errors', ['prix' => 'Le prix doit être positif']);
        // }
        // if ($data['pourcentage_viande'] <= 0) {
        //     return redirect()->back()->withInput()->with('errors', ['pourcentage_viande' => 'Le pourcentage de viande doit être positif']);
        // }
        // if ($data['pourcentage_poisson'] <= 0) {
        //     return redirect()->back()->withInput()->with('errors', ['pourcentage_poisson' => 'Le pourcentage de poisson doit être positif']);
        // }
        // if ($data['pourcentage_volaille'] <= 0) {
        //     return redirect()->back()->withInput()->with('errors', ['pourcentage_volaille' => 'Le pourcentage de volaille doit être positif']);
        // }
        if (!$model->addRegime($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $model->errors());
        }
        return redirect()->to('/admin/dashboard')
            ->with('success', 'Regime ajouté avec succès');

    }
}
