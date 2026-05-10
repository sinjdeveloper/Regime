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

    public function subscribe()
    {
        $userSession = session()->get('user');

        $userId = (int) $userSession['id'];
        $clientModel = new ClientModel();
        $transactionModel = new TransactionModel();

        $client = $clientModel->where('id_user', $userId)->first();
        if (!$client) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Client introuvable'
            ])->setStatusCode(404);
        }

        $clientId = $client['id'];

        if ($client['estGold'] == 1) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vous avez déjà un abonnement Gold'
            ])->setStatusCode(400);
        }

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

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $nouveauSolde = $client['argent'] - self::GOLD_PRICE;
            $updateResult = $clientModel->update($clientId, [
                'argent' => $nouveauSolde,
                'estGold' => 1
            ]);

            if (!$updateResult) {
                $db->transRollback();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour'
                ])->setStatusCode(500);
            }

            // 2. Enregistrer la transaction
            // $transactionData = [
            //     'client_id' => $clientId,
            //     'code_id' => null,
            //     'type' => 'debit',
            //     'montant' => self::GOLD_PRICE
            // ];

            // if (!$transactionModel->insert($transactionData)) {
            //     $db->transRollback();
            //     return $this->response->setJSON([
            //         'success' => false,
            //         'message' => 'Erreur lors de l\'enregistrement de la transaction'
            //     ])->setStatusCode(500);
            // }

            $db->transComplete();

            if (!$db->transStatus()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors du traitement'
                ])->setStatusCode(500);
            }

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
