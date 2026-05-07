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

class ClientController extends BaseController
{
    public function dashboard()
    {
        if(!$this->session->has('user_id')){
            return redirect()->to('/login');
        }
        $userId = $this->session->get('user_id');

        $clientModel = new ClientModel();
        $client = $clientModel->where('id_user', $userId)->first();

        if(!$client){
            return redirect()->to('/login');
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

    /**
     * API - Retourne profil du client avec IMC et interprétation
     * GET /api/client/profile
     *
     * @return string JSON
     */
    public function getProfilePopup()
    {
        // Vérifier authentification
        if (!$this->session->has('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Non authentifié'
            ])->setStatusCode(401);
        }

        $userId = $this->session->get('user_id');
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
                'estGold' => (bool)$client['estGold'],
                'argent' => $client['argent']
            ]
        ]);
    }
}
