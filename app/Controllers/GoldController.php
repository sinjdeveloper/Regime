<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ClientModel;
use App\Models\TransactionModel;

class GoldController extends BaseController
{
    // Prix de l'abonnement Gold (€)
    private const GOLD_PRICE = 49.99;

    public function index()
    {
        //
    }

    /**
     * API - Subscribe to Gold membership
     * POST /api/gold/subscribe
     *
     * Logique:
     * 1. Vérifier authentification
     * 2. Charger client
     * 3. Vérifier pas déjà Gold
     * 4. Vérifier solde suffisant
     * 5. Débiter wallet
     * 6. Marquer estGold = true
     * 7. Enregistrer transaction
     *
     * @return string JSON
     */
    public function subscribe()
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
        $transactionModel = new TransactionModel();

        // Récupérer client
        $client = $clientModel->where('id_user', $userId)->first();
        if (!$client) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Client introuvable'
            ])->setStatusCode(404);
        }

        $clientId = $client['id'];

        // Vérifier déjà Gold
        if ($client['estGold']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vous avez déjà un abonnement Gold'
            ])->setStatusCode(400);
        }

        // Vérifier solde suffisant
        if ($client['argent'] < self::GOLD_PRICE) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solde insuffisant',
                'data' => [
                    'solde_actuel' => $client['argent'],
                    'prix_gold' => self::GOLD_PRICE,
                    'manque' => self::GOLD_PRICE - $client['argent']
                ]
            ])->setStatusCode(400);
        }

        // Démarrer transaction BD
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Débiter le wallet
            $nouveauSolde = $client['argent'] - self::GOLD_PRICE;
            $updateResult = $clientModel->update($clientId, [
                'argent' => $nouveauSolde,
                'estGold' => true
            ]);

            if (!$updateResult) {
                $db->transRollback();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour'
                ])->setStatusCode(500);
            }

            // 2. Enregistrer la transaction
            $transactionData = [
                'client_id' => $clientId,
                'code_id' => null,
                'type' => 'gold_subscription',
                'montant' => self::GOLD_PRICE
            ];

            if (!$transactionModel->insert($transactionData)) {
                $db->transRollback();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de l\'enregistrement de la transaction'
                ])->setStatusCode(500);
            }

            // Compléter la transaction
            $db->transComplete();

            if (!$db->transStatus()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors du traitement'
                ])->setStatusCode(500);
            }

            // Succès
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Abonnement Gold activé !',
                'data' => [
                    'nouveau_solde' => $nouveauSolde,
                    'prix_gold' => self::GOLD_PRICE,
                    'reduction_appliquee' => '15%'
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

    /**
     * Retourne le prix de l'abonnement Gold
     * Utilisé par le frontend pour affichage
     *
     * @return int Prix en euros
     */
    public static function getGoldPrice(): float
    {
        return self::GOLD_PRICE;
    }
}
