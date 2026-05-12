<?php
/** @var array $client */
/** @var bool $isGold */

$argent = (float)($client['argent'] ?? 0);

$goldDiscount = \App\Services\AppSettingsService::getGoldDiscount();
$goldPrice = \App\Controllers\GoldController::getGoldPrice(); // tu l'utilises déjà ailleurs
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Offre Gold - Vary'Ena<?= $this->endSection() ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/gold.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('page_js') ?>
<script src="<?= base_url('assets/js/pages/gold.js') ?>" defer></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="gold-page">
    <main class="main-content">
        <div class="container">
        

            <!-- Gold Offer Card -->
            <div class="gold-card">
                <div class="card-header">
                    <div class="status-section">
                        <span class="status-badge"><?= $isGold ? 'GOLD' : 'STANDARD' ?></span>
                    </div>

                    <div class="price-badge">
                        <?= number_format((float)$goldPrice, 0, '.', ' ') ?> Ar
                    </div>
                </div>

                <div class="benefits-section">
                    <div class="benefit-left">
                        <p class="benefit-title">Réduction Gold: <?= (int)$goldDiscount ?>%</p>
                    </div>
                    <div class="benefit-right">
                        <p class="benefit-title">Avantages</p>
                        <ul class="benefit-list">
                            <li>Réduction automatique sur les suggestions</li>
                            <li>Accès Premium</li>
                        </ul>
                    </div>
                </div>

                <?php if ($isGold): ?>
                    <button class="btn-activate" type="button" disabled style="opacity:.75; cursor:not-allowed;">
                        Déjà GOLD ✓
                    </button>
                <?php else: ?>
                    <button
                        class="btn-activate"
                        id="btn-subscribe-gold"
                        data-subscribe-url="<?= site_url('api/gold/subscribe') ?>"
                        type="button">
                        Activer Gold
                    </button>
                    <div id="gold-msg" style="margin-top:12px; display:none;"></div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>
<?= $this->endSection() ?>