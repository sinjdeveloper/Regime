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
        
    }
    
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
     */
public function redeemCode()
{
    $request = $this->request->getJSON();

    if (!$request || empty($request->code)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Code promo requis'
        ])->setStatusCode(400);
    }

    $token = trim($request->code);

    $clientModel = new ClientModel();
    $codeModel = new CodeModel();
    $historiqueModel = new TransactionModel();

    // Client connecté
    $client = $clientModel
        ->where('id_user', session()->get('user')['id'])
        ->first();

    if (!$client) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Client introuvable'
        ])->setStatusCode(404);
    }

    $codeRecord = $codeModel
        ->where('token', $token)
        ->first();

    if (!$codeRecord) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Code promo invalide'
        ])->setStatusCode(400);
    }

    /*
        1 = Valide
        2 = En attente de validation
        3 = Utilisé
    */

    if ((int)$codeRecord['statut_code_id'] === 2) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Ce code est déjà en attente de validation.'
        ])->setStatusCode(400);
    }

    if ((int)$codeRecord['statut_code_id'] === 3) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Ce code a déjà été utilisé.'
        ])->setStatusCode(400);
    }

    if ((int)$codeRecord['statut_code_id'] !== 1) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Ce code ne peut pas être utilisé.'
        ])->setStatusCode(400);
    }

    $db = \Config\Database::connect();
    $db->transBegin();

    try {
        // 1. Passer le code en attente
        $codeModel->update($codeRecord['id'], [
            'statut_code_id' => 2
        ]);

        // 2. Enregistrer la demande
        $historiqueModel->insert([
            'client_id' => $client['id'],
            'code_id'   => $codeRecord['id']
        ]);

        if (!$db->transStatus()) {
            $db->transRollback();

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de l’enregistrement.'
            ])->setStatusCode(500);
        }

        $db->transCommit();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Votre demande a été envoyée. Un administrateur doit valider ce code.'
        ]);

    } catch (\Exception $e) {
        $db->transRollback();

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Erreur interne du serveur.'
        ])->setStatusCode(500);
    }
}
}
