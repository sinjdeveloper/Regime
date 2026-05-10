<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientModel;
use App\Models\CodeModel;
use App\Models\RegimeModel;
use App\Models\TransactionModel;
use CodeIgniter\HTTP\ResponseInterface;

class CodeController extends BaseController
{

    public function index()
    {
        $model = new CodeModel();
        $codes = $model->findCodesAttente();
        return view('admin/codes/code', ['codes' => $codes]);
    }
    public function validateCode($id)
    {
        $db = \Config\Database::connect();
        $db->transBegin();

        $codeModel = new CodeModel();
        $clientModel = new ClientModel();
        $transactionModel = new TransactionModel();

        try {
            $code = $codeModel->find((int) $id);
            if (!$code) {
                $db->transRollback();
                return redirect()->to('/admin/codes/index')
                    ->with('errors', ['Code introuvable.']);
            }

            if ((int) ($code['statut_code_id'] ?? 0) !== 2) {
                $db->transRollback();
                return redirect()->to('/admin/codes/index')
                    ->with('errors', ['Ce code n’est pas en attente de validation.']);
            }

            $client = $codeModel->getClientCode((int) $id);
            if (!$client) {
                $db->transRollback();
                return redirect()->to('/admin/codes/index')
                    ->with('errors', ['Client associé à ce code introuvable.']);
            }

            $nouveauSolde = (float) ($client['argent'] ?? 0) + (float) ($code['montant'] ?? 0);

            $updateResult = $clientModel->update((int) $client['id'], [
                'argent' => $nouveauSolde,
            ]);
            if ($updateResult === false) {
                $db->transRollback();

                return redirect()->to('/admin/codes/index')
                    ->with('errors', array_values($clientModel->errors() ?: ['Impossible de créditer le solde du client.']));
            }

            $codeUpdate = $codeModel->update((int) $code['id'], [
                'statut_code_id' => 3,
            ]);
            if ($codeUpdate === false) {
                $db->transRollback();

                return redirect()->to('/admin/codes/index')
                    ->with('errors', array_values($codeModel->errors() ?: ['Impossible de mettre à jour le statut du code.']));
            }

            $existing = $transactionModel
                ->where('client_id', (int) $client['id'])
                ->where('code_id', (int) $code['id'])
                ->first();


            $txOk = $transactionModel->insert([
                'client_id' => (int) $client['id'],
                'code_id' => (int) $code['id'],
            ]);

            if ($txOk === false) {
                $db->transRollback();

                return redirect()->to('/admin/codes/index')
                    ->with('errors', array_values($transactionModel->errors() ?: ['Impossible d\'enregistrer la transaction.']));
            }

            if (!$db->transStatus()) {
                $db->transRollback();

                return redirect()->to('/admin/codes/index')
                    ->with('errors', ['Erreur lors de la validation du code.']);
            }

            $db->transCommit();

            return redirect()->to('/admin/codes/index')
                ->with('success', 'Code validé et solde crédité avec succès.');

        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', $e->getMessage());

            return redirect()->to('/admin/codes/index')
                ->with('errors', ['Erreur serveur: ' . $e->getMessage()]);
        }




    }



}
