<?php
/** @var array $client */
/** @var bool $isGold */

$argent = (float)($client['argent'] ?? 0);
?>

<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Offre Gold - Vary'Ena<?= $this->endSection() ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/gold.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('page_js') ?>
<script src="<?= base_url('assets/js/pages/gold.js') ?>" defer></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-page">
    <div class="page-header">
        <div>
            <h1>Offre Gold</h1>
            <div style="opacity:.9;">Solde: <?= number_format($argent, 2) ?> Ar</div>
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
                <div style="font-size:14px; color:#666; text-transform:uppercase; letter-spacing:.5px;">Statut</div>
                <div style="margin-top:8px;">
                    <span class="badge <?= $isGold ? 'badge-gold' : 'badge-standard' ?>">
                        <?= $isGold ? 'GOLD' : 'STANDARD' ?>
                    </span>
                </div>
            </div>
            <div style="text-align:right; color:#333; font-weight:700;">Réduction Gold: <?= \App\Services\AppSettingsService::getGoldDiscount() ?>%</div>
        </div>

        <div style="margin-top:14px; color:#333;">
            <div style="font-weight:800;">Avantages</div>
            <ul style="margin:8px 0 0 18px;">
                <li>Réduction automatique sur les suggestions</li>
                <li>Accès Premium</li>
            </ul>
        </div>

        <?php if ($isGold): ?>
            <div style="margin-top:14px; font-weight:800; color:#333;">Votre abonnement Gold est actif.</div>
        <?php else: ?>
            <button
                class="btn-action"
                id="btn-subscribe-gold"
                data-subscribe-url="<?= site_url('api/gold/subscribe') ?>"
                type="button">Activer Gold</button>
            <div class="msg" id="gold-msg"></div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>