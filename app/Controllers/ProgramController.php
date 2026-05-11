<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RegimeModel;
use App\Models\SportModel;
use App\Models\ClientModel;

class ProgramController extends BaseController
{
    public function regime($id)
    {
        $regimeModel = new RegimeModel();
        $regime = $regimeModel->find((int) $id);

        if (!$regime) {
            return redirect()->to('/');
        }

        // Vérifier si l'utilisateur connecté a acheté ce régime
        $isPurchased = false;
        $user = session()->get('user');
        if (!empty($user['id'])) {
            $clientModel = new ClientModel();
            $client = $clientModel->where('id_user', $user['id'])->first();
            if ($client) {
                $isPurchased = $regimeModel->hasPurchased((int) $client['id'], (int) $id);
            }
        }

        return view('programs/regime_detail', [
            'regime' => $regime,
            'isPurchased' => $isPurchased,
        ]);
    }

     public function regimePdf($id)
    {
        $regimeModel = new RegimeModel();
        $regime = $regimeModel->find((int) $id);

        if (!$regime) {
            return redirect()->to('/');
        }

        $user = session()->get('user');
        if (empty($user['id'])) {
            return redirect()->to('/login');
        }

        $clientModel = new ClientModel();
        $client = $clientModel->where('id_user', $user['id'])->first();

        if (!$client || !$regimeModel->hasPurchased((int) $client['id'], (int) $id)) {
            return redirect()->to('/programs/regime/' . (int) $id)
                ->with('error', 'Vous devez acheter ce régime avant de l’exporter en PDF.');
        }

        try {
            $this->loadFpdf();

            $pdf = new \FPDF('P', 'mm', 'A4');
            $pdf->SetAutoPageBreak(true, 15);
            $pdf->AddPage();

            // Titre
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->SetTextColor(102, 51, 102);
            $pdf->Cell(0, 10, $this->toLatin('Programme Régime'), 0, 1, 'L');

            $pdf->SetTextColor(80, 80, 80);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 6, $this->toLatin('Généré le : ' . date('d/m/Y H:i')), 0, 1, 'L');
            $pdf->Ln(2);

            // Image (optionnel)
            if (!empty($regime['image'])) {
                $imagePath = FCPATH . 'assets/images/programs/' . basename((string) $regime['image']);
                if (is_file($imagePath)) {
                    $ext = strtolower((string) pathinfo($imagePath, PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
                        $pdf->Image($imagePath, 15, $pdf->GetY(), 180);
                        $pdf->Ln(95);
                    }
                }
            }

            // Nom
            $pdf->SetTextColor(30, 30, 30);
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->MultiCell(0, 8, $this->toLatin((string) ($regime['libelle'] ?? 'Régime')));
            $pdf->Ln(2);

            // Description
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(0, 7, $this->toLatin('Description'), 0, 1);

            $pdf->SetFont('Arial', '', 11);
            $pdf->MultiCell(0, 6, $this->toLatin((string) ($regime['description'] ?? '')));
            $pdf->Ln(3);

            // Détails
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(0, 7, $this->toLatin('Détails du régime'), 0, 1);

            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(70, 8, $this->toLatin('Variation de poids'), 1);
            $pdf->Cell(120, 8, $this->toLatin((string) ($regime['variation_poids'] ?? '') . ' kg'), 1, 1);

            $pdf->Cell(70, 8, $this->toLatin('Prix'), 1);
            $pdf->Cell(120, 8, $this->toLatin((string) ($regime['prix'] ?? '') . ' Ar'), 1, 1);

            $repartition = sprintf(
                'Viande: %s%% | Poisson: %s%% | Volaille: %s%%',
                (string) ($regime['pourcentage_viande'] ?? ''),
                (string) ($regime['pourcentage_poisson'] ?? ''),
                (string) ($regime['pourcentage_volaille'] ?? '')
            );

            $pdf->Cell(70, 10, $this->toLatin('Répartition'), 1);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(120, 10, $this->toLatin($repartition), 1);
            $pdf->SetXY($x + 120, $y);

            $slug = strtolower(trim((string) preg_replace('/[^A-Za-z0-9]+/', '-', (string) ($regime['libelle'] ?? 'regime')), '-'));
            if ($slug === '') {
                $slug = 'regime-' . (int) $id;
            }

            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $slug . '.pdf"')
                ->setBody($pdf->Output('S'));
        } catch (\Throwable $e) {
            log_message('error', 'Erreur export PDF régime (FPDF): ' . $e->getMessage());
            return redirect()->to('/programs/regime/' . (int) $id)
                ->with('error', 'Impossible de générer le PDF pour le moment.');
        }
    }
    public function sport($id)
    {
        $model = new SportModel();
        $sport = $model->find((int) $id);

        if (!$sport) {
            return redirect()->to('/');
        }

        return view('programs/sport_detail', [
            'sport' => $sport,
        ]);
    }
     private function loadFpdf(): void
    {
        if (class_exists('\FPDF')) {
            return;
        }

        $candidates = [
            ROOTPATH . 'fpdf/fpdf.php',
            APPPATH . 'ThirdParty/fpdf/fpdf.php',
            ROOTPATH . 'app/ThirdParty/fpdf/fpdf.php',
            ROOTPATH . 'public/fpdf/fpdf.php',
        ];

        foreach ($candidates as $path) {
            if (is_file($path)) {
                require_once $path;
                if (class_exists('\FPDF')) {
                    return;
                }
            }
        }

        throw new \RuntimeException('FPDF introuvable dans le repo.');
    }

    private function toLatin(string $text): string
    {
        $converted = @iconv('UTF-8', 'windows-1252//TRANSLIT//IGNORE', $text);
        return $converted !== false ? $converted : utf8_decode($text);
    }
}