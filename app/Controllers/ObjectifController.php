<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ObjectifModel;
use App\Models\GoalPoidsModel;
use App\Models\ClientModel;

class ObjectifController extends BaseController
{
    public function index()
    {
        //
    }

    /**
     * API - Affiche formulaire avec objectifs disponibles et actuels du client
     * GET /api/objectif/popup
     *
     * @return string JSON
     */
    public function showPopup()
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
        $goalPoidsModel = new GoalPoidsModel();
        $objectifModel = new ObjectifModel();

        // Récupérer client
        $client = $clientModel->where('id_user', $userId)->first();
        if (!$client) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Client introuvable'
            ])->setStatusCode(404);
        }

        $clientId = $client['id'];

        // Récupérer tous les objectifs disponibles
        $objectifsDisponibles = $objectifModel->findAll();

        // Récupérer les objectifs actuels du client
        $objectifsActuels = $goalPoidsModel->getClientGoalsWithDetails($clientId);

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'objectifs_disponibles' => $objectifsDisponibles,
                'objectifs_actuels' => $objectifsActuels,
                'nombre_max' => 3,
                'nombre_actuel' => count($objectifsActuels)
            ]
        ]);
    }

    /**
     * API - Met à jour les objectifs du client
     * POST /api/objectif/update
     * 
     * Body JSON:
     * {
     *   "objectifs": [
     *     {"objectif_id": 1, "poids_cible": 75, "duree": 90},
     *     {"objectif_id": 2, "poids_cible": 80, "duree": 180}
     *   ]
     * }
     *
     * @return string JSON
     */
    public function updateGoal()
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
        $goalPoidsModel = new GoalPoidsModel();

        // Récupérer client
        $client = $clientModel->where('id_user', $userId)->first();
        if (!$client) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Client introuvable'
            ])->setStatusCode(404);
        }

        $clientId = $client['id'];

        // Récupérer les données POST
        $request = $this->request->getJSON();
        $objectifs = $request->objectifs ?? [];

        // Validation: au moins 1 objectif, max 3
        if (empty($objectifs)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Au moins 1 objectif est requis'
            ])->setStatusCode(400);
        }

        if (count($objectifs) > 3) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Maximum 3 objectifs autorisés'
            ])->setStatusCode(400);
        }

        // Démarrer une transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Supprimer les anciens objectifs
            $goalPoidsModel->deleteClientGoals($clientId);

            // 2. Insérer les nouveaux objectifs
            foreach ($objectifs as $obj) {
                $data = [
                    'client_id' => $clientId,
                    'objectif_id' => (int)$obj->objectif_id,
                    'poids_cible' => (float)$obj->poids_cible,
                    'duree' => (int)$obj->duree
                ];

                // Valider les données
                if (!$goalPoidsModel->validate($data)) {
                    $db->transRollback();
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Erreur de validation: ' . implode(', ', $goalPoidsModel->errors())
                    ])->setStatusCode(400);
                }

                // Insérer
                if (!$goalPoidsModel->insert($data)) {
                    $db->transRollback();
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Erreur lors de l\'insertion des objectifs'
                    ])->setStatusCode(500);
                }
            }

            // 3. Compléter la transaction
            $db->transComplete();

            if (!$db->transStatus()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour'
                ])->setStatusCode(500);
            }

            // 4. Déclencher rafraîchissement des suggestions
            $suggestionController = new SuggestionController();
            $suggestionController->refreshSuggestions($clientId);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Objectifs mis à jour avec succès',
                'data' => [
                    'nombre_objectifs' => count($objectifs)
                ]
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur serveur: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}
