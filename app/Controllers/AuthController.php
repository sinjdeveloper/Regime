<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function loginAdmin()
    {
        $model = new UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $model->checkAccess((string) $username, (string) $password);

        if (!$user) {
            return redirect()->back()->withInput()->with('errors', ['Username ou mot de passe incorrect']);
        }

        session()->set('user', [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role'],
        ]);

        return redirect()->to('/admin/dashboard');
    }
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
