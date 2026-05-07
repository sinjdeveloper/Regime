<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;

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
        $data = $model->getStats();

        return view('admin/dashboard',$data);
    }

}
