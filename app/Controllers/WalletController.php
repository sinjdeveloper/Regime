<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ClientModel;
use App\Models\CodeModel;
use App\Models\TransactionModel;

class WalletController extends BaseController
{
    public function index()
    {
        //
    }

    /**
     * API - Affiche formulaire pour entrer un code promo
     * GET /api/wallet/code-popup
     *
     * @return string JSON
     */
    public function showCodePopup()
    {
        // Vérifier authentification
        if (!$this->session->has('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Non authentifié'
            ])->setStatusCode(401);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'form' => [
                    'input_placeholder' => 'Entrez votre code promo',
                    'button_label' => 'Appliquer le code',
                    'help_text' => 'Vous recevrez des crédits immédiatement après validation'
                ]
            ]
        ]);
    }

    /**
     * API - Valide et applique un code promo
     * POST /api/wallet/redeem-code
     *
     * Body JSON:
     * {
     *   "code": "PROMO2024"
     * }
     *
     * Logique:
     * 1. Valider le code
     * 2. Vérifier existence du code
     * 3. Vérifier non utilisé
     * 4. Ajouter montant au solde
     * 5. Enregistrer transaction
     * 6. Marquer code comme utilisé
     *
     * @return string JSON
     */
    public function redeemCode()
    {
        // Vérifier authentification
        if (!$this->session->has('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Non authentifié'
            ])->setStatusCode(401);
        }

        // Récupérer les données POST
        $request = $this->request->getJSON();
        $code = $request->code ?? '';

        // Validation: code requis
        if (empty($code)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Code promo requis'
            ])->setStatusCode(400);
        }

        $userId = $this->session->get('user_id');
        $clientModel = new ClientModel();
        $codeModel = new CodeModel();
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

        // Vérifier existence du code
        $codeRecord = $codeModel->where('token', $code)->first();
        if (!$codeRecord) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Code promo invalide'
            ])->setStatusCode(400);
        }

        // Vérifier non utilisé
        if ($codeRecord['utilisé']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ce code a déjà été utilisé'
            ])->setStatusCode(400);
        }

        // Démarrer transaction BD
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Ajouter le montant au solde
            $montant = $codeRecord['montant'];
            $nouveauSolde = $client['argent'] + $montant;

            $updateResult = $clientModel->update($clientId, [
                'argent' => $nouveauSolde
            ]);

            if (!$updateResult) {
                $db->transRollback();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du solde'
                ])->setStatusCode(500);
            }

            // 2. Enregistrer la transaction
            $transactionData = [
                'client_id' => $clientId,
                'code_id' => $codeRecord['id'],
                'type' => 'code_redemption',
                'montant' => $montant
            ];

            if (!$transactionModel->insert($transactionData)) {
                $db->transRollback();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de l\'enregistrement de la transaction'
                ])->setStatusCode(500);
            }

            // 3. Marquer le code comme utilisé
            $codeUpdateResult = $codeModel->update($codeRecord['id'], [
                'utilisé' => true
            ]);

            if (!$codeUpdateResult) {
                $db->transRollback();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du code'
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
                'message' => 'Code appliqué avec succès !',
                'data' => [
                    'montant_ajoute' => $montant,
                    'nouveau_solde' => $nouveauSolde,
                    'ancien_solde' => $client['argent']
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
