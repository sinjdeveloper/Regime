<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\ClientModel;
use App\Services\SessionService;

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




    public function showSignup()
    {
        SessionService::initSignupDraft();

        return view('signup/user-info');
    }

    public function storeUserInfo()
    {
        $data = $this->request->getPost();

        $clientModel = new \App\Models\ClientModel();

        $result = $clientModel->validateUserInfo($data);

        if ($result['status'] === false) {

            $flatErrors = [];

            foreach ($result['errors'] as $error) {
                if (is_array($error)) {
                    foreach ($error as $msg) {
                        $flatErrors[] = (string)$msg;
                    }
                } else {
                    $flatErrors[] = (string)$error;
                }
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $flatErrors);
        }

        SessionService::initSignupDraft();

        SessionService::setNested(
            'signup_draft.user',
            $result['data']
        );

        return redirect()->to('/signup/health');
    }

    public function showHealthForm()
    {
        SessionService::initSignupDraft();

        $user = SessionService::getNested('signup_draft.user');

        if (empty($user)) {
            return redirect()->to('/signup');
        }

        return view('signup/health');
    }

    public function storeHealthInfo()
    {
        $data = $this->request->getPost();

        $clientModel = new \App\Models\ClientModel();

        $result = $clientModel->storeHealthDraft($data);

        if ($result['status'] === false) {

            $flatErrors = [];

            foreach ($result['errors'] as $error) {
                if (is_array($error)) {
                    foreach ($error as $msg) {
                        $flatErrors[] = (string) $msg;
                    }
                } else {
                    $flatErrors[] = (string) $error;
                }
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $flatErrors);
        }

        SessionService::setNested(
            'signup_draft.health',
            $result['data']
        );

        return redirect()->to('/signup/goals');
    }
}
