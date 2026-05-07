<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ClientModel;

class ClientController extends BaseController
{
    public function dashboard()
    {
        if(!$this->session->has('user_id')){
            return redirect()->to('/login');
        } else {
            
            return view('client/dashboard');
        }
    }
}
