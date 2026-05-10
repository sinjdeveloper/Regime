<?php
namespace App\Controllers;

use App\Models\UserModel;
class UserController extends BaseController
{
    public function loginPage()
    {
        return view('user/login');
    }

    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->checkAccess($username, $password);

        if ($user) {
            // Set session data
            session()->set('user', [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
            ]);

            return redirect()->to('/dashboard');
        } else {
            return redirect()->back()->with('error', 'Identifiant ou mot de passe incorrect');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
