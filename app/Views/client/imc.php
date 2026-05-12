<?php
/** @var array $client */
/** @var float $imc */

$interpretation = 'Ideal Weight';
if ($imc < 18.5) {
    $interpretation = 'Underweight';
} elseif ($imc < 25) {
    $interpretation = 'Ideal Weight';
} elseif ($imc < 30) {
    $interpretation = 'Overweight';
} else {
    $interpretation = 'Obésité';
}
?>

<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Mon IMC - Vary'Ena<?= $this->endSection() ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/imc.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-page">
    <div class="page-header">
        <div>
            <h1>Mon IMC</h1>
            <div style="opacity:.9;"><?= esc((string)($client['email'] ?? '')) ?></div>
        </div>
        <div class="nav-actions">
            <a class="link-btn" href="<?= site_url('/suivi') ?>">Suivi</a>
            <a class="link-btn" href="<?= site_url('/dashboard') ?>">Dashboard</a>
            <a class="link-btn" href="<?= site_url('/') ?>">Accueil</a>
        </div>
    </div>

    <div class="card">
        <h2 style="margin:0 0 8px 0;">Indice de Masse Corporelle</h2>
        <div style="font-size:40px;font-weight:800;color:#663366;line-height:1;">
            <?= number_format((float)$imc, 1) ?>
        </div>
        
        <?php
            // Determine badge color based on IMC value
            $badgeColor = '#666';
            $badgeBg = '#f0f0f0';
            
            if ($imc < 18.5) {
                $badgeColor = '#0066cc';
                $badgeBg = '#e6f2ff';
            } elseif ($imc < 25) {
                $badgeColor = '#008000';
                $badgeBg = '#e6ffe6';
            } elseif ($imc < 30) {
                $badgeColor = '#ff8800';
                $badgeBg = '#fff3e6';
            } else {
                $badgeColor = '#cc0000';
                $badgeBg = '#ffe6e6';
            }
        ?>
        
        <div style="margin-top:12px; display: inline-block; padding: 8px 16px; border-radius: 20px; background-color: <?= $badgeBg ?>; color: <?= $badgeColor ?>; font-weight: 700; font-size: 14px;">
            <?= esc($interpretation) ?>
        </div>

        <div class="metric">
            <div class="item">
                <div class="label">Poids</div>
                <div class="value"><?= esc((string)($client['poids'] ?? '')) ?> kg</div>
            </div>
            <div class="item">
                <div class="label">Taille</div>
                <div class="value"><?= esc((string)($client['taille'] ?? '')) ?> cm</div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>