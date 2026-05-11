<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AppSettingModel;

class SettingsController extends BaseController
{
    /**
     * Show settings page
     */
    public function index()
    {
        $settingsModel = new AppSettingModel();
        
        $data = [
            'gold_discount' => $settingsModel->getGoldDiscount(),
        ];
        
        return view('admin/settings', $data);
    }

    /**
     * Update settings
     */
    public function update()
    {
        $settingsModel = new AppSettingModel();
        
        $goldDiscount = $this->request->getPost('gold_discount');
        
        if (empty($goldDiscount) || !is_numeric($goldDiscount)) {
            return redirect()->back()->with('error', 'Veuillez entrer une valeur numérique valide');
        }
        
        $discount = (float) $goldDiscount;
        
        if ($discount < 0 || $discount > 100) {
            return redirect()->back()->with('error', 'La réduction doit être entre 0 et 100%');
        }
        
        if ($settingsModel->setGoldDiscount($discount)) {
            return redirect()->back()->with('success', 'Paramètres mise à jour avec succès');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour des paramètres');
        }
    }
}
