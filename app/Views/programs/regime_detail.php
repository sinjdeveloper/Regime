<?php
/** @var array $regime */
/** @var bool  $isPurchased */

$imageUrl = '';
if (!empty($regime['image'])) {
    $imageUrl = base_url('assets/images/programs/' . (string) $regime['image']);
}

$title = (string)($regime['libelle'] ?? 'Régime');
$regimeId = (int)($regime['id'] ?? 0);
$prix = (string)($regime['prix'] ?? '');
$desc = (string)($regime['description'] ?? '');

$variation = (string)($regime['variation_poids'] ?? '');
$viande = (string)($regime['pourcentage_viande'] ?? '');
$poisson = (string)($regime['pourcentage_poisson'] ?? '');
$volaille = (string)($regime['pourcentage_volaille'] ?? '');

$goldDiscount = \App\Services\AppSettingsService::getGoldDiscount();
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= esc($title) ?> - Vary'Ena<?= $this->endSection() ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/product-detail.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('page_js') ?>
<?php if (!$isPurchased): ?>
<script src="<?= base_url('assets/js/pages/regime_detail.js') ?>" defer></script>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="regime-detail" data-buy-url="<?= esc(site_url('/regime/buy')) ?>" data-regime-id="<?= esc((string)$regimeId) ?>">
    <main class="main-content">
        <div class="container">
            <div class="product-layout">

                <!-- Left Column - Images -->
                <div class="product-images">
                    <div class="main-image">
                        <?php if ($imageUrl): ?>
                            <img src="<?= esc($imageUrl) ?>" alt="<?= esc($title) ?>">
                        <?php endif; ?>
                    </div>

                </div>

                <!-- Right Column - Product Details -->
                <div class="product-details">
                    <div class="details-header">
                        <div class="icon-badge">
                            <svg width="16" height="16" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 4L6 12l-4-4"></path>
                            </svg>
                        </div>
                        <h2><?= $isPurchased ? 'Programme complet' : 'Aperçu du régime' ?></h2>

                        <?php if ($isPurchased): ?>
                            <span style="margin-left:8px; font-size:12px; font-weight:800; color:#166534; background:#dcfce7; border:1px solid #86efac; padding:4px 10px; border-radius:999px;">
                                ✓ Acheté
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Description -->
                    <?php if ($isPurchased): ?>
                        <p class="description"><?= esc($desc) ?></p>
                    <?php else: ?>
                        <p class="description">
                            Achetez ce pack pour accéder à la description complète, la répartition alimentaire
                            (viande / poisson / volaille) et sa variation de poids prévue.
                        </p>
                    <?php endif; ?>

                    <!-- Warning box (si pas acheté) -->
                    <?php if (!$isPurchased): ?>
                        <div class="warning-box">
                            <svg width="16" height="16" fill="none" stroke="#F54900" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="8" cy="8" r="7"></circle>
                                <path d="M8 4v4"></path>
                                <path d="M8 10.5h.01"></path>
                            </svg>
                            <div>
                                <p class="warning-title">Contenu réservé aux acheteurs</p>
                                <p class="warning-subtitle">Accédez au programme complet après l'achat</p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Stats Cards -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <svg width="20" height="20" fill="none" stroke="#636" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="10" cy="10" r="8"></circle>
                                <path d="M10 5v5l3 2"></path>
                            </svg>
                            <div class="stat-value"><?= esc($variation !== '' ? $variation : '—') ?></div>
                            <div class="stat-label">Variation (kg)</div>
                        </div>

                        <div class="stat-card">
                            <svg width="20" height="20" fill="none" stroke="#636" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 7l-6 6-4-4-6 6"></path>
                                <path d="M22 14V7h-7"></path>
                            </svg>
                            <div class="stat-value"><?= esc($viande !== '' ? $viande . '%' : '—') ?></div>
                            <div class="stat-label">Viande</div>
                        </div>

                        <div class="stat-card">
                            <svg width="20" height="20" fill="none" stroke="#636" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <div class="stat-value">
                                <?= esc(($poisson !== '' && $volaille !== '') ? ($poisson . '%/' . $volaille . '%') : '—') ?>
                            </div>
                            <div class="stat-label">Poisson/Volaille</div>
                        </div>
                    </div>

                    <!-- Pricing Box -->
                    <div class="pricing-box">
                        <div class="price-info">
                            <p class="price-label"><?= $isPurchased ? 'Prix payé' : 'Prix du programme' ?></p>
                            <p class="price-value"><?= esc($prix) ?> Ar</p>
                            <?php if (!$isPurchased): ?>
                                <p class="price-discount">Réduction de <?= (int)$goldDiscount ?>% avec option Gold</p>
                            <?php endif; ?>
                        </div>
                        <?php if (!$isPurchased): ?>
                            <div class="savings-badge">
                                <p class="savings-label">Gold</p>
                                <p class="savings-value">-<?= (int)$goldDiscount ?>%</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Action -->
                    <?php if ($isPurchased): ?>
                        <div style="display:flex; gap:12px; flex-wrap:wrap;">
                            <a class="btn-buy" href="<?= site_url('/programs/regime/' . $regimeId . '/pdf') ?>" style="text-decoration:none;">
                                Exporter PDF
                            </a>
                        </div>
                    <?php else: ?>
                        <button class="btn-buy" id="btn-buy-regime" type="button">
                            Acheter ce régime
                        </button>
                        <div id="buy-msg" style="margin-top:12px; display:none;"></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Description Section (affichée si acheté) -->
            <?php if ($isPurchased): ?>
                <div class="description-section">
                    <h2>Description détaillée</h2>
                    <p><?= esc($desc) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>
<?= $this->endSection() ?>