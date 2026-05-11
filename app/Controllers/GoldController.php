<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ClientModel;
use App\Models\TransactionModel;
use App\Services\AppSettingsService;

class GoldController extends BaseController
{
    private static function goldPrice(): float
    {
        return AppSettingsService::getGoldPrice();
    }

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

        $goldPrice = self::goldPrice();

        if ($client['argent'] < $goldPrice) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solde insuffisant',
                'data' => [
                    'solde_actuel' => $client['argent'],
                    'prix_gold' => $goldPrice,
                    'manque' => $goldPrice - $client['argent']
                ]
            ])->setStatusCode(400);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $nouveauSolde = $client['argent'] - $goldPrice;
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
                    'prix_gold' => $goldPrice,
                    'reduction_appliquee' => \App\Services\AppSettingsService::getGoldDiscount() . '%'
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
        return self::goldPrice();
    }
}
