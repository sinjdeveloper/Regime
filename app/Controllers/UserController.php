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
            // Set session data or token for authenticated user
            session()->set('user_id', $user['id']);
            session()->set('username', $user['username']);
            session()->set('role', $user['role']);

            return redirect()->to('/dashboard'); // Redirect to a protected area
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
