<?php
/** @var array $client */
/** @var float $imc */

$interpretation = 'Poids normal';
if ($imc < 18.5) {
    $interpretation = 'Poids insuffisant';
} elseif ($imc < 25) {
    $interpretation = 'Poids normal';
} elseif ($imc < 30) {
    $interpretation = 'Surpoids';
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
        <div style="margin-top:8px;color:#333;font-weight:700;"><?= esc($interpretation) ?></div>

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