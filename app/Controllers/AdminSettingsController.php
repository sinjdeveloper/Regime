<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\AppSettingsService;

class AdminSettingsController extends BaseController
{
    public function index()
    {
        return view('admin/settings/index', [
            'settings' => AppSettingsService::getAll(),
        ]);
    }

    public function update()
    {
        $goldPriceRaw = (string) $this->request->getPost('gold_price');
        $goldPriceRaw = str_replace(',', '.', trim($goldPriceRaw));

        if ($goldPriceRaw === '' || !is_numeric($goldPriceRaw)) {
            return redirect()->back()->withInput()->with('errors', ['Prix Gold invalide.']);
        }

        $goldPrice = (float) $goldPriceRaw;
        if ($goldPrice <= 0) {
            return redirect()->back()->withInput()->with('errors', ['Le prix Gold doit être supérieur à 0.']);
        }

        $ok = AppSettingsService::setGoldPrice($goldPrice);
        if (!$ok) {
            return redirect()->back()->withInput()->with('errors', ['Impossible d\'enregistrer les paramètres.']);
        }

        return redirect()->to('admin/settings')->with('success', 'Paramètres enregistrés.');
    }
}
