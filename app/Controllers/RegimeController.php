<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RegimeModel;
use CodeIgniter\HTTP\ResponseInterface;

class RegimeController extends BaseController
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

        // Permettre l'update sans bloquer sur l'unicité du libellé (même en gardant la valeur inchangée)
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
