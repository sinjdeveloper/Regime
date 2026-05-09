<?php

namespace App\Controllers;

use App\Models\RegimeModel;
use App\Models\SportModel;

class HomeController extends BaseController
{
    public function index()
    {
        $regimeModel = new RegimeModel();
        $sportModel = new SportModel();

        $data = [
            'regimes' => $regimeModel->getDemo(),
            'sports'  => $sportModel->getDemo()
        ];

        return view('home/index', $data);
    }
}