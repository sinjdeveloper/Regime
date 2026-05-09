<?php

namespace App\Controllers;

use App\Models\GoalPoidsModel;
use App\Models\RegimeModel;
use App\Models\SportModel;

class DashboardController extends BaseController
{
    public function index()
    {

        return view('dashboard/index');
    }

    public function getRepartition()
    {
        $goalModel = new GoalPoidsModel();
        $repartition = $goalModel->getRepartitionObjectifs();
        
        return response()->setJSON($repartition);
    }



}