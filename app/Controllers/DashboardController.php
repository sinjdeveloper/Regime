<?php

namespace App\Controllers;

use App\Models\AdminModel;
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
    public function getRepartitionGold()
    {
        $model = new AdminModel();
        $repartition = $model->getRepartitionGold();
        return response()->setJSON($repartition);
    }
    public function getPopularRegimes(){
        $model = new RegimeModel();
        $popular = $model->getPopularRegimes();
        return response()->setJSON($popular);
    }


}