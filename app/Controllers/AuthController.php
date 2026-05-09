<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\ClientModel;
use App\Services\SessionService;

class AuthController extends BaseController
{
    public function loginClient(){

    }
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

    public function completeSignup()
    {
        SessionService::initSignupDraft();

        $draft = SessionService::get('signup_draft');

        // 1. Check completeness
        if (
            empty($draft['user']) ||
            empty($draft['health']) ||
            empty($draft['goals'])
        ) {
            return redirect()->to('/signup')
                ->with('errors', ['Inscription incomplète']);
        }

        $userModel = new \App\Models\UserModel();
        $clientModel = new \App\Models\ClientModel();
        $objectifModel = new \App\Models\ObjectifModel();

        try {
            // 2. Create user
            $userId = $userModel->insert([
                'username' => $draft['user']['email'], // or custom username
                'password_hash' => $draft['user']['password_hash'],
                'role' => 'user'
            ]);

            if (!$userId) {
                throw new \Exception("User creation failed");
            }

            // 3. Create client
            $clientId = $clientModel->insert([
                'id_user' => $userId,
                'email' => $draft['user']['email'],
                'genre' => $draft['user']['genre'],
                'dateNaissance' => null,
                'poids' => $draft['health']['weight'],
                'taille' => $draft['health']['height'],
                'estGold' => 0,
                'argent' => 0
            ]);

            if (!$clientId) {
                throw new \Exception("Client creation failed");
            }

            // 4. Attach goals (max 3 already validated earlier)
            foreach ($draft['goals'] as $goalId) {
                $objectifModel->db->table('goalpoids')->insert([
                    'client_id' => $clientId,
                    'objectif_id' => $goalId,
                    'poids_cible' => null,
                    'duree' => null
                ]);
            }

            // 5. Create session
            SessionService::set('user', [
                'id' => $userId,
                'client_id' => $clientId,
                'role' => 'user'
            ]);

            // 6. Clear draft
            SessionService::remove('signup_draft');

            return redirect()->to('/dashboard');
        } catch (\Exception $e) {
            return redirect()->back()->with('errors', [$e->getMessage()]);
        }
    }
}
