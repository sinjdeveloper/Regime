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
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->checkViaEmail($email, $password);

        if ($user) {
            // Set session data like AuthController does
            session()->set('user', [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
            ]);

            return redirect()->to('/dashboard');
        } else {
            return redirect()->back()->with('error', 'Invalid username or password');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
