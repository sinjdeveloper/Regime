<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;

use App\Models\RegimeModel;
use App\Models\SportModel;
use CodeIgniter\HTTP\ResponseInterface;

class AdminController extends BaseController
{
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

        // Si on a déjà un chemin, on le supprime prudemment.
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
            // Nouveau format DB: on ne stocke que le nom de fichier.
            $fileName = basename($relativePath);
            $absolutePath = FCPATH . 'assets/images/programs/' . $fileName;
        }

        if (is_file($absolutePath)) {
            @unlink($absolutePath);
        }
    }

    public function loginPage()
    {
        return view('login-admin');
    }
    public function index()
    {
        $model = new AdminModel();
        $regime_model = new RegimeModel();
        $data = $model->getStats();
        $regimes = $regime_model->findAllRegime();

        // Tableau croisé Régime x Objectif (nombre de clients)
        $db = \Config\Database::connect();
        $objectifs = $db->table('objectif')
            ->select('id, libelle')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        $regimeObjectifCounts = [];
        try {
            $rows = $db->table('regimeclient rc')
                ->select('rc.regime_id, gp.objectif_id, COUNT(*) as nombre')
                ->join('goalpoids gp', 'gp.client_id = rc.client_id', 'inner')
                ->groupBy('rc.regime_id, gp.objectif_id')
                ->get()
                ->getResultArray();

            foreach ($rows as $row) {
                $regimeId = (int) ($row['regime_id'] ?? 0);
                $objectifId = (int) ($row['objectif_id'] ?? 0);
                $count = (int) ($row['nombre'] ?? 0);
                if ($regimeId > 0 && $objectifId > 0) {
                    $regimeObjectifCounts[$regimeId][$objectifId] = $count;
                }
            }
        } catch (\Throwable $e) {
            // Si la table regimeclient n'existe pas encore sur l'environnement, on n'affiche que des zéros.
            $regimeObjectifCounts = [];
        }

        return view('admin/dashboard', [
            'stats' => $data,
            'regimes' => $regimes,
            'objectifs' => $objectifs,
            'regimeObjectifCounts' => $regimeObjectifCounts,
        ]);
    }
    public function sportIndex()
    {
        $model = new SportModel();
        $sports = $model->findAllSport();

        return view(
            'admin/sport/dashboard',
            ['sports' => $sports]
        );

    }
    public function createSport()
    {
        $model = new SportModel();
        $data = $this->request->getPost();

        try {
            $newImagePath = $this->storeUploadedImage('image', 'sport');
            if ($newImagePath) {
                $data['image'] = $newImagePath;
            }
        } catch (\RuntimeException $e) {
            return redirect()->back()
                ->withInput()
                ->with('errors', ['image' => $e->getMessage()]);
        }

        if (!$model->addSport($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $model->errors());
        }
        return redirect()->to('/admin/sports/index')
            ->with('success', 'Sport ajouté avec succès');
    }
    public function updateSport($id)
    {
        $model = new SportModel();
        $data = $this->request->getPost();

        unset($data['id']);

        try {
            $newImagePath = $this->storeUploadedImage('image', 'sport');
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

        if (!$model->updateSport($id, $data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $model->errors());
        }

        return redirect()->to('/admin/sports/index')
            ->with('success', 'Sport mis à jour avec succès');

    }
    public function deleteSport($id)
    {
        $model = new SportModel();
        $existing = $model->find($id);
        if (!$model->deleteSport($id)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $model->errors());
        }

        if (is_array($existing) && array_key_exists('image', $existing)) {
            $this->deletePublicFile($existing['image']);
        }
        return redirect()->to('/admin/sports/index')
            ->with('success', 'Sport supprimé avec succès');
    }
}
