<?php
/** @var array $client */
/** @var bool $isGold */

$argent = (float) ($client['argent'] ?? 0);
?>

<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Validation de code - Vary'Ena<?= $this->endSection() ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/wallet.css') ?>">
<?= $this->endSection() ?>


<?= $this->section('content') ?>
<div class="container-page">
    <div class="page-header">
        <div>
            <h1>Validation de code</h1>
            <div style="opacity:.9;">Solde : <?= number_format($argent, 2, ',', ' ') ?> Ar</div>
        </div>

        <div style="display:flex; gap:10px;">
            <a class="link-btn" href="<?= site_url('/profile') ?>">Profil</a>
            <a class="link-btn" href="<?= site_url('/dashboard') ?>">Dashboard</a>
            <a class="link-btn" href="<?= site_url('/') ?>">Accueil</a>
        </div>
    </div>

    <div class="card">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
            <div>
                <div style="font-size:14px; color:#666; text-transform:uppercase; letter-spacing:.5px;">
                    Statut
                </div>
                <div style="margin-top:8px;">
                    <span class="badge <?= $isGold ? 'badge-gold' : 'badge-standard' ?>">
                        <?= $isGold ? 'GOLD' : 'STANDARD' ?>
                    </span>
                </div>
            </div>
        </div>

        <div style="margin-top:20px;">
            <label for="code" style="font-weight:600;">
                Entrez votre code de recharge
            </label>

            <input type="text" id="code" class="form-input" placeholder="Ex : ABC123XYZ">

            <button class="btn-action" id="btn-redeem-code" data-redeem-url="<?= site_url('wallet/redeem-code') ?>"
                type="button">
                Envoyer pour validation
            </button>

            <div id="redeem-msg" class="msg"></div>
        </div>

        <div class="info-box">
            Lorsque vous soumettez un code, celui-ci est envoyé à l’administrateur
            pour validation. Votre solde sera crédité après approbation.
            Un petit détour bureaucratique, parce que même les pièces virtuelles
            aiment les tampons administratifs.
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('page_js') ?>
<script src="<?= base_url('assets/js/pages/wallet.js') ?>" defer></script>
<?= $this->endSection() ?>