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
        $errors = [];

        // Validate and update gold_price
        $goldPriceRaw = (string) $this->request->getPost('gold_price');
        $goldPriceRaw = str_replace(',', '.', trim($goldPriceRaw));

        if ($goldPriceRaw === '' || !is_numeric($goldPriceRaw)) {
            $errors[] = 'Prix Gold invalide.';
        } else {
            $goldPrice = (float) $goldPriceRaw;
            if ($goldPrice <= 0) {
                $errors[] = 'Le prix Gold doit être supérieur à 0.';
            } else {
                $ok = AppSettingsService::setGoldPrice($goldPrice);
                if (!$ok) {
                    $errors[] = 'Impossible d\'enregistrer le prix Gold.';
                }
            }
        }

        // Validate and update gold_discount
        $goldDiscountRaw = (string) $this->request->getPost('gold_discount');
        $goldDiscountRaw = trim($goldDiscountRaw);

        if ($goldDiscountRaw === '' || !is_numeric($goldDiscountRaw)) {
            $errors[] = 'Réduction Gold invalide.';
        } else {
            $goldDiscount = (int) $goldDiscountRaw;
            if ($goldDiscount < 0 || $goldDiscount > 100) {
                $errors[] = 'La réduction Gold doit être entre 0 et 100.';
            } else {
                $ok = AppSettingsService::setGoldDiscount($goldDiscount);
                if (!$ok) {
                    $errors[] = 'Impossible d\'enregistrer la réduction Gold.';
                }
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        return redirect()->to('admin/settings')->with('success', 'Paramètres enregistrés.');
    }
}
