<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ClientModel;
use App\Models\CodeModel;
use App\Models\ObjectifModel;
use App\Models\RegimeModel;
use App\Models\SportModel;
use App\Models\TransactionModel;
use App\Models\SuggestionModel;
use App\Models\GoalPoidsModel;
use App\Helpers\HealthHelper;
use App\Helpers\PricingHelper;

class ClientController extends BaseController
{
    private function getClientOrRedirect()
    {
        $userSession = session()->get('user');
        if (!is_array($userSession) || empty($userSession['id'])) {
            return redirect()->to('/user/login');
        }

        $clientModel = new ClientModel();
        $client = $clientModel->where('id_user', (int) $userSession['id'])->first();

        if (!$client) {
            return redirect()->to('/user/login');
        }

        return [$userSession, $client];
    }

    public function dashboard()
    {
        $userSession = session()->get('user');

        if (!$userSession) {
            return redirect()->to('/user/login');
        }
        $userId = $userSession['id'];

        $clientModel = new ClientModel();
        $client = $clientModel->where('id_user', $userId)->first();

        if (!$client) {
            return redirect()->to('/user/login');
        }

        $clientId = $client['id'];
        $poids = $client['poids'];
        $taille = $client['taille'];
        $estGold = $client['estGold'];

        $imc = $poids / (($taille / 100) ** 2);

        $goalPoidsModel = new GoalPoidsModel();
        $objectif = $goalPoidsModel->where('client_id', $clientId)->first();
        $isGold = $estGold == 1;
        $argent = $client['argent'];

        $data = [
            'client' => $client,
            'imc' => round($imc, 2),
            'objectif' => $objectif,
            'isGold' => $isGold,
            'argent' => $argent
        ];
        return view('client/dashboard', $data);
    }

    public function imc()
    {
        $result = $this->getClientOrRedirect();
        if (!is_array($result)) {
            return $result;
        }
        [$userSession, $client] = $result;

        $poids = (float) $client['poids'];
        $taille = (float) $client['taille'];
        $imc = $poids / (($taille / 100) ** 2);

        return view('client/imc', [
            'client' => $client,
            'imc' => round($imc, 2),
        ]);
    }

    public function suivi()
    {
        $result = $this->getClientOrRedirect();
        if (!is_array($result)) {
            return $result;
        }
        [$userSession, $client] = $result;

        $goalPoidsModel = new GoalPoidsModel();
        $objectifs = $goalPoidsModel->getClientGoalsWithDetails((int) $client['id']);

        return view('client/suivi', [
            'client' => $client,
            'objectifs' => $objectifs,
        ]);
    }

    public function gold()
    {
        $result = $this->getClientOrRedirect();
        if (!is_array($result)) {
            return $result;
        }
        [$userSession, $client] = $result;

        return view('client/gold', [
            'client' => $client,
            'isGold' => (bool) ($client['estGold'] ?? false),
        ]);
    }
    public function wallet()
    {
        $result = $this->getClientOrRedirect();
        if (!is_array($result)) {
            return $result;
        }
        [$userSession, $client] = $result;

        return view('client/wallet', [
            'client' => $client,
            'isGold' => (bool) ($client['estGold'] ?? false),
        ]);
    }

    public function suggestions()
    {
        $result = $this->getClientOrRedirect();
        if (!is_array($result)) {
            return $result;
        }
        [$userSession, $client] = $result;

        $clientId = $client['id'];
        $poids = $client['poids'];
        $taille = $client['taille'];
        $isGold = (bool) $client['estGold'];

        // Récupérer les objectifs
        $goalPoidsModel = new GoalPoidsModel();
        $objectif = $goalPoidsModel->where('client_id', $clientId)->first();

        $suggestions = [];

        if ($objectif) {
            // Calcul de l'écart de poids
            $tolerance = ($poids * 10) / 100;
            $poidsObjectif = $objectif['poids_cible'];
            $ecart = abs($poids - $poidsObjectif);

            // Récupérer régimes adaptés
            $regimeModel = new RegimeModel();
            $regimes = $regimeModel
                ->where('variation_poids >=', $ecart - $tolerance)
                ->where('variation_poids <=', $ecart + $tolerance)
                ->findAll();

            // Récupérer meilleur sport
            $sportModel = new SportModel();
            $bestSport = $sportModel->orderBy('pourcentage_reduction', 'DESC')->first();

            // Formater les suggestions
            foreach ($regimes as $regime) {
                $prix = $regime['prix'];
                $reduction = 0;
                $prixFinal = $prix;

                if ($isGold) {
                    $reduction = 15;
                    $prixFinal = round($prix * 0.85, 2);
                }

                $suggestions[] = [
                    'regime' => [
                        'id' => $regime['id'],
                        'libelle' => $regime['libelle'],
                        'description' => $regime['description'],
                        'pourcentage_viande' => $regime['pourcentage_viande'],
                        'pourcentage_poisson' => $regime['pourcentage_poisson'],
                        'pourcentage_volaille' => $regime['pourcentage_volaille'],
                        'prix_original' => $prix,
                        'prix_final' => $prixFinal,
                        'reduction_appliquee' => $reduction
                    ],
                    'sport' => $bestSport ? [
                        'id' => $bestSport['id'],
                        'libelle' => $bestSport['libelle'],
                        'effet' => $bestSport['pourcentage_reduction'] . '%'
                    ] : null
                ];
            }
        }

        return view('client/suggestions_new', [
            'client' => $client,
            'suggestions' => $suggestions,
            'isGold' => $isGold,
            'objectif' => $objectif
        ]);
    }


    public function profile()
    {
        $result = $this->getClientOrRedirect();
        if (!is_array($result)) {
            return $result;
        }
        [$userSession, $client] = $result;

        return view('client/profile', [
            'client' => $client,
            'user' => $userSession,
        ]);
    }

    /**
     * API - Retourne profil du client avec IMC et interprétation
     * GET /api/client/profile
     *
     * @return string JSON
     */
    public function getProfilePopup()
    {
        // Vérifier authentification
        $userSession = session()->get('user');
        if (!$userSession) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Non authentifié'
            ])->setStatusCode(401);
        }

        $userId = $userSession['id'];
        $clientModel = new ClientModel();
        $userModel = new \App\Models\UserModel();

        // Récupérer client
        $client = $clientModel->where('id_user', $userId)->first();
        if (!$client) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Client introuvable'
            ])->setStatusCode(404);
        }

        // Récupérer user
        $user = $userModel->find($userId);
        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Utilisateur introuvable'
            ])->setStatusCode(404);
        }

        // Calculer IMC
        $poids = $client['poids'];
        $taille = $client['taille'];
        $imc = \App\Helpers\HealthHelper::calculateIMC($poids, $taille);
        $interpretation = \App\Helpers\HealthHelper::interpretIMC($imc);

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'email' => $client['email'],
                'poids' => $poids,
                'taille' => $taille,
                'imc' => $imc,
                'interpretation' => $interpretation,
                'estGold' => (bool) $client['estGold'],
                'argent' => $client['argent']
            ]
        ]);
    }
}
