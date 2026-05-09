<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\ClientModel;
use App\Services\SessionService;
use App\Models\ObjectifModel;

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
        // Initialize draft only if not already started
        if (!session()->get('signup_draft')) {
            session()->set('signup_draft', ['user' => [], 'health' => []]);
        }

        return view('signup/user-info');
    }

    public function storeUserInfo()
    {
        $data = $this->request->getPost();
        $clientModel = new \App\Models\ClientModel();
        $result = $clientModel->validateUserInfo($data);

        if ($result['status'] === false) {
            return redirect()->back()->withInput()
                ->with('errors', $result['errors']);
        }

        $draft = session()->get('signup_draft') ?? [];
        $draft['user'] = $result['data'];
        session()->set('signup_draft', $draft);

        return redirect()->to('/signup/health');
    }

    public function showHealthForm()
    {
        $draft = session()->get('signup_draft') ?? [];
        if (empty($draft['user'])) {
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
                $flatErrors[] = (string)$error;
            }
            return redirect()->back()->withInput()->with('errors', $flatErrors);
        }

        // FIX: same nested array approach
        $draft = session()->get('signup_draft') ?? [];
        $draft['health'] = $result['data'];
        session()->set('signup_draft', $draft);

        return redirect()->to('/signup/goals');
    }

    public function completeSignup()
    {

        dd([
            'session' => session()->get('signup_draft'),
            'post'    => $this->request->getPost('goals')
        ]);
        $draft         = session()->get('signup_draft') ?? [];
        $userSession   = $draft['user']   ?? [];
        $healthSession = $draft['health'] ?? [];

        // Debug — remove after confirming it works
        log_message('debug', 'signup_draft: ' . json_encode($draft));

        if (empty($userSession) || empty($healthSession)) {
            return redirect()->to('/signup')
                ->with('errors', ['Session expirée, veuillez recommencer.']);
        }

        // Filter only selected goals
        $rawGoals = $this->request->getPost('goals') ?? [];
        $goals = array_values(array_filter($rawGoals, fn($g) => !empty($g['selected']) && $g['selected'] === '1'));

        if (empty($goals)) {
            return redirect()->to('/signup/goals')
                ->with('errors', ['Veuillez sélectionner au moins 1 objectif.']);
        }

        if (count($goals) > 3) {
            return redirect()->to('/signup/goals')
                ->with('errors', ['Maximum 3 objectifs autorisés.']);
        }

        foreach ($goals as $i => $g) {
            if (empty($g['poids_cible']) || (float)$g['poids_cible'] <= 0) {
                return redirect()->to('/signup/goals')
                    ->with('errors', ["Objectif #" . ($i + 1) . " : poids cible invalide."]);
            }
            if (empty($g['duree']) || (int)$g['duree'] <= 0) {
                return redirect()->to('/signup/goals')
                    ->with('errors', ["Objectif #" . ($i + 1) . " : durée invalide."]);
            }
        }

        $userModel     = new UserModel();
        $clientModel   = new ClientModel();
        $objectifModel = new ObjectifModel();

        try {
            // Build username — fallback to email prefix if prenom/nom missing
            $prenom = $userSession['prenom'] ?? '';
            $nom    = $userSession['nom']    ?? '';

            if (!empty($prenom) && !empty($nom)) {
                $base = strtolower(
                    preg_replace('/[^a-z0-9]/i', '', $prenom) . '.' .
                        preg_replace('/[^a-z0-9]/i', '', $nom)
                );
            } else {
                // Fallback: use part before @ in email
                $base = strtolower(preg_replace('/[^a-z0-9.]/i', '', explode('@', $userSession['email'])[0]));
            }

            $base = $base ?: 'user'; // last resort

            // Guarantee uniqueness
            do {
                $username = $base . '_' . rand(1000, 9999);
            } while ($userModel->where('username', $username)->first());

            // Insert user
            $userId = $userModel->insert([
                'username'      => $username,
                'password_hash' => $userSession['password_hash'],
                'role'          => 'user'
            ]);

            if (!$userId) {
                throw new \Exception('Erreur création utilisateur: ' . implode(', ', $userModel->errors()));
            }

            // Insert client
            $clientId = $clientModel->skipValidation(true)->insert([
                'id_user'       => $userId,
                'email'         => $userSession['email'],
                'genre'         => $userSession['genre'],
                'dateNaissance' => '2000-01-01',
                'poids'         => $healthSession['poids'],
                'taille'        => $healthSession['taille'],
                'estGold'       => 0,
                'argent'        => 0
            ]);

            if (!$clientId) {
                throw new \Exception('Erreur création client: ' . implode(', ', $clientModel->errors()));
            }

            // Insert goals
            foreach ($goals as $g) {
                $objectifModel->db->table('goalpoids')->insert([
                    'client_id'   => $clientId,
                    'objectif_id' => (int)$g['objectif_id'],
                    'poids_cible' => (float)$g['poids_cible'],
                    'duree'       => (int)$g['duree']
                ]);
            }

            // Set session
            session()->set('user', [
                'id'        => $userId,
                'client_id' => $clientId,
                'role'      => 'user'
            ]);

            session()->remove('signup_draft');

            return redirect()->to('/');
        } catch (\Exception $e) {
            log_message('error', 'Signup error: ' . $e->getMessage());
            return redirect()->back()->with('errors', [$e->getMessage()]);
        }
    }
}
