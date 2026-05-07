<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AdminController extends BaseController
{
    public function loginPage()
    {
        return view('login-admin');
    }
    public function index(){
        return view('admin/dashboard');
    }
}
