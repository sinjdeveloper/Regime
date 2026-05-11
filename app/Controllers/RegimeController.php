<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RegimeModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ClientModel;
use App\Models\TransactionModel;
use App\Services\AppSettingsService;

class RegimeController extends BaseController
{

    public function acheterRegime(){
        $request = $this->request;

        $regimeId = (int) $request->getPost('regime_id');
        if ($regimeId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Régime invalide'])->setStatusCode(400);
        }

        $user = session()->get('user');
        if (empty($user) || empty($user['id'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Utilisateur non connecté'])->setStatusCode(401);
        }

        $clientModel = new ClientModel();
        $regimeModel = new RegimeModel();
        $transactionModel = new TransactionModel();

        $client = null;
        if (!empty($user['client_id'])) {
            $client = $clientModel->find((int) $user['client_id']);
        }
        if (!$client) {
            $client = $clientModel->where('id_user', $user['id'])->first();
        }

        if (!$client) {
            return $this->response->setJSON(['success' => false, 'message' => 'Client introuvable'])->setStatusCode(404);
        }

        $regime = $regimeModel->find($regimeId);
        if (!$regime) {
            return $this->response->setJSON(['success' => false, 'message' => 'Régime introuvable'])->setStatusCode(404);
        }

        $prix = (float) ($regime['prix'] ?? 0);

        $isGold = (int) ($client['estGold'] ?? 0) === 1;
        if ($isGold) {
            $discountPercent = \App\Services\AppSettingsService::getGoldDiscount();
            $multiplier = 1 - ($discountPercent / 100);
            $finalPrice = round($prix * $multiplier, 2);
        } else {
            $finalPrice = $prix;
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $ok = $clientModel->updateBalance((int)$client['id'], -$finalPrice);
            if (!$ok) {
                $db->transRollback();
                return $this->response->setJSON(['success' => false, 'message' => 'Solde insuffisant'])->setStatusCode(400);
            }

            $db->table('regimeclient')->insert([
                'client_id' => (int)$client['id'],
                'regime_id' => (int)$regimeId,
            ]);

            $txOk = $transactionModel->insert([
                'client_id' => (int)$client['id'],
                'code_id' => null,
                'type' => 'debit',
                'montant' => $finalPrice,
            ]);

            if ($txOk === false) {
                $db->transRollback();
                return $this->response->setJSON(['success' => false, 'message' => 'Erreur en enregistrant la transaction'])->setStatusCode(500);
            }

            if (!$db->transStatus()) {
                $db->transRollback();
                return $this->response->setJSON(['success' => false, 'message' => 'Erreur lors du traitement'])->setStatusCode(500);
            }

            $db->transCommit();

            $clientAfter = $clientModel->find((int)$client['id']);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Régime acheté avec succès',
                'data' => [
                    'regime_id' => $regimeId,
                    'montant' => $finalPrice,
                    'nouveau_solde' => $clientAfter['argent'] ?? null,
                ]
            ]);

        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Achat régime error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Erreur serveur'])->setStatusCode(500);
        }
    }




    private function storeUploadedImage(string $fieldName, string $prefix): ?string
    {
        $file = $this->request->getFile($fieldName);
        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return null;
        }

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $mimeType = $file->getMimeType();
        if ($mimeType && ! in_array($mimeType, $allowedMimeTypes, true)) {
            throw new \RuntimeException("Type de fichier non autorisé (image uniquement)");
        }

        $relativeDir = 'assets/images/programs';
        $absoluteDir = rtrim(FCPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
            . str_replace('/', DIRECTORY_SEPARATOR, $relativeDir);

        if (! is_dir($absoluteDir)) {
            mkdir($absoluteDir, 0775, true);
        }

        $newName = $prefix . '_' . $file->getRandomName();
        $file->move($absoluteDir, $newName);

        // En base on ne stocke que le nom du fichier.
        return $newName;
    }

    private function deletePublicFile(?string $relativePath): void
    {
        if (! $relativePath) {
            return;
        }

        if (strpos($relativePath, '/') !== false) {
            // Compat: on accepte l'ancien dossier uploads/ et le nouveau assets/images/programs/
            $allowedPrefixes = ['uploads/', 'assets/images/programs/'];
            $isAllowed = false;
            foreach ($allowedPrefixes as $prefix) {
                if (strpos($relativePath, $prefix) === 0) {
                    $isAllowed = true;
                    break;
                }
            }
            if (! $isAllowed) {
                return;
            }

            $absolutePath = FCPATH . $relativePath;
        } else {
            $fileName = basename($relativePath);
            $absolutePath = FCPATH . 'assets/images/programs/' . $fileName;
        }

        if (is_file($absolutePath)) {
            @unlink($absolutePath);
        }
    }

    public function index()
    {
        //
    }

    public function update($id)
    {
        $model = new RegimeModel();
        $data = $this->request->getPost();

        $model->setValidationRule('libelle', 'required|min_length[3]|max_length[255]|is_unique[regime.libelle,id,' . (int) $id . ']');

        unset($data['id']);

        try {
            $newImagePath = $this->storeUploadedImage('image', 'regime');
            if ($newImagePath) {
                $existing = $model->find($id);
                if (is_array($existing) && array_key_exists('image', $existing)) {
                    $this->deletePublicFile($existing['image']);
                }
                $data['image'] = $newImagePath;
            }
        } catch (\RuntimeException $e) {
            return redirect()->back()
                ->withInput()
                ->with('errors', ['image' => $e->getMessage()]);
        }

        if (! $model->updateRegime($id, $data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $model->errors());
        }

        return redirect()->to('/admin/dashboard')
            ->with('success', 'Regime mis à jour avec succès');
    }

    public function delete($id){
        $model = new RegimeModel();
        $existing = $model->find($id);
        if(!$model->deleteRegime($id)){
            return redirect()->back()
                ->withInput()
                ->with('errors', $model->errors()); 
        }

        if (is_array($existing) && array_key_exists('image', $existing)) {
            $this->deletePublicFile($existing['image']);
        }
        return redirect()->to('/admin/dashboard')
            ->with('success', 'Regime supprimé avec succès');
    }
    public function create()
    {
        $model = new RegimeModel();
        $data = $this->request->getPost();

        try {
            $newImagePath = $this->storeUploadedImage('image', 'regime');
            if ($newImagePath) {
                $data['image'] = $newImagePath;
            }
        } catch (\RuntimeException $e) {
            return redirect()->back()
                ->withInput()
                ->with('errors', ['image' => $e->getMessage()]);
        }
        
        if (!$model->addRegime($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $model->errors());
        }
        return redirect()->to('/admin/dashboard')
            ->with('success', 'Regime ajouté avec succès');

    }
}
