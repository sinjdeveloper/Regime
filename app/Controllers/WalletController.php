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
        $updated = $codeModel->update($codeRecord['id'], [
            'statut_code_id' => 2
        ]);
        if ($updated === false) {
            $db->transRollback();

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Impossible de mettre à jour le statut du code.',
                'errors'  => $codeModel->errors(),
            ])->setStatusCode(400);
        }

        // 2. Enregistrer la demande
        $inserted = $historiqueModel->insert([
            'client_id' => $client['id'],
            'code_id'   => $codeRecord['id'],

        ]);
        if ($inserted === false) {
            $db->transRollback();

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Impossible d’enregistrer la demande.',
                'errors'  => $historiqueModel->errors(),
            ])->setStatusCode(400);
        }

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
            'message' => $e->getMessage(),
            'record' => $codeRecord
        ])->setStatusCode(500);
    }
}
}
