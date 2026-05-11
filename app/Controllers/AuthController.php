<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\ClientModel;
use App\Services\SessionService;
use App\Models\ObjectifModel;

class AuthController extends BaseController
{
    public function loginClient() {}
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
        return redirect()->to('/user/login');
    }

    public function showSignup()
    {
        session()->remove('user');

        if (!session()->get('signup_draft')) {
            session()->set('signup_draft', ['user' => [], 'health' => []]);
        }

        // TEMP: dump all flash data
        // var_dump(session()->getFlashdata('errors'));

        return view('signup/user-info');
    }

    public function storeUserInfo()
    {
        $data = $this->request->getPost();
        $clientModel = new \App\Models\ClientModel();
        $result = $clientModel->validateUserInfo($data);

        if ($result['status'] === false) {
            return redirect()->back()->withInput()
                ->with('errors', $result['errors'])
                ->with('field_errors', $result['field_errors'] ?? []);
        }

        // Check email uniqueness
        $existing = $clientModel->where('email', $data['email'])->first();
        if ($existing) {
            return redirect()->back()->withInput()
                ->with('errors', ['Cet email est déjà utilisé.'])
                ->with('field_errors', ['email' => true]);
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
            return redirect()->back()->withInput()->with('errors', $flatErrors)
                ->with('field_errors', $result['field_errors'] ?? []);
        }

        $draft = session()->get('signup_draft') ?? [];
        $draft['health'] = $result['data'];
        session()->set('signup_draft', $draft);

        return redirect()->to('/signup/goals');
    }

    public function completeSignup()
    {
        $draft         = session()->get('signup_draft') ?? [];
        $userSession   = $draft['user']   ?? [];
        $healthSession = $draft['health'] ?? [];

        if (empty($userSession) || empty($healthSession)) {
            return redirect()->to('/signup')
                ->with('errors', ['Session expirée, veuillez recommencer.']);
        }

        $selectedId = $this->request->getPost('goals_selected');
        $poidsCible = $this->request->getPost('goals_poids_cible');
        $duree      = $this->request->getPost('goals_duree');

        if (empty($selectedId)) {
            return redirect()->to('/signup/goals')
                ->with('errors', ['Veuillez sélectionner un objectif.']);
        }

        // Validate goal logic before touching the DB
        $objectifModel = new ObjectifModel();
        $objectif      = $objectifModel->find((int)$selectedId);
        $libelle = strtolower(trim($objectif['libelle'] ?? ''));

        $isImcGoal = str_contains($libelle, 'imc');
        $needsPoids = !$isImcGoal;

        $poidsActuel = $healthSession['poids'] ?? 0;

        // validate poids only for gain/loss goals
        if ($needsPoids) {

            if (empty($poidsCible) || (float)$poidsCible <= 0) {
                return redirect()->to('/signup/goals')
                    ->with('errors', ['Poids cible invalide.']);
            }

            if ((float)$poidsCible < 20 || (float)$poidsCible > 300) {
                return redirect()->to('/signup/goals')
                    ->with('errors', ['Poids cible irréaliste (entre 20 et 300 kg).']);
            }

            if (str_contains($libelle, 'réduire') && (float)$poidsCible >= $poidsActuel) {
                return redirect()->to('/signup/goals')
                    ->with('errors', [
                        'Pour une perte de poids, le poids cible doit être inférieur à votre poids actuel (' . $poidsActuel . ' kg).'
                    ]);
            }

            if (
                (str_contains($libelle, 'augmenter') || str_contains($libelle, 'masse'))
                && (float)$poidsCible <= $poidsActuel
            ) {
                return redirect()->to('/signup/goals')
                    ->with('errors', [
                        'Pour une prise de masse, le poids cible doit être supérieur à votre poids actuel (' . $poidsActuel . ' kg).'
                    ]);
            }
        }

        // duration always required
        if (empty($duree) || (int)$duree <= 0) {
            return redirect()->to('/signup/goals')
                ->with('errors', ['Durée invalide.']);
        }

        if ((int)$duree > 730) {
            return redirect()->to('/signup/goals')
                ->with('errors', ['Durée irréaliste (maximum 730 jours).']);
        }

        $userModel   = new UserModel();
        $clientModel = new ClientModel();

        try {
            $prenom = $userSession['prenom'] ?? '';
            $nom    = $userSession['nom']    ?? '';

            if (!empty($prenom) && !empty($nom)) {
                $base = strtolower(
                    preg_replace('/[^a-z0-9]/i', '', $prenom) . '.' .
                        preg_replace('/[^a-z0-9]/i', '', $nom)
                );
            } else {
                $base = strtolower(preg_replace('/[^a-z0-9.]/i', '', explode('@', $userSession['email'])[0]));
            }

            $base     = $base ?: 'user';
            $username = $base;
            $counter  = 1;
            while ($userModel->where('username', $username)->first()) {
                $username = $base . $counter;
                $counter++;
            }

            $userId = $userModel->insert([
                'username'      => $username,
                'password_hash' => $userSession['password_hash'],
                'role'          => 'user'
            ]);

            if (!$userId) {
                throw new \Exception('Erreur création utilisateur: ' . implode(', ', $userModel->errors()));
            }

            $clientModel->skipValidation(true)->insert([
                'id_user'       => $userId,
                'email'         => $userSession['email'],
                'genre'         => $userSession['genre'],
                'dateNaissance' => '2000-01-01',
                'poids'         => $healthSession['poids'],
                'taille'        => $healthSession['taille'],
                'estGold'       => 0,
                'argent'        => 0
            ]);
            $clientId = $clientModel->getInsertID();

            if (!$clientId) {
                throw new \Exception('Erreur création client: ' . implode(', ', $clientModel->errors()));
            }

            $objectifModel->db->table('goalpoids')->insert([
                'client_id'   => $clientId,
                'objectif_id' => (int)$selectedId,
                'poids_cible' => $needsPoids ? (float)$poidsCible : 0,
                'duree' => (int)$duree,
            ]);

            session()->set('user', [
                'id'        => $userId,
                'client_id' => $clientId,
                'role'      => 'user'
            ]);

            session()->remove('signup_draft');

            return redirect()->to('/dashboard');
        } catch (\Exception $e) {
            log_message('error', 'Signup error: ' . $e->getMessage());
            return redirect()->back()->with('errors', [$e->getMessage()]);
        }
    }
}
