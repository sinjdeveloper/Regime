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

    public function update($id)
    {
        $model = new RegimeModel();
        $data = $this->request->getPost();

        unset($data['id']);

        if (! $model->updateRegime($id, $data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $model->errors());
        }

        return redirect()->to('/admin/dashboard')
            ->with('success', 'Regime mis à jour avec succès');
    }

    public function delete($id){
        $model = new RegimeModel();
        if(!$model->deleteRegime($id)){
            return redirect()->back()
                ->withInput()
                ->with('errors', $model->errors()); 
        }
        return redirect()->to('/admin/dashboard')
            ->with('success', 'Regime supprimé avec succès');
    }
    public function create()
    {
        $model = new RegimeModel();
        $data = $this->request->getPost();
        
        if (!$model->addRegime($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $model->errors());
        }
        return redirect()->to('/admin/dashboard')
            ->with('success', 'Regime ajouté avec succès');

    }
}
