<?php

/** @var array $sport */

$imageUrl = '';
if (!empty($sport['image'])) {
    $imageUrl = base_url('assets/images/programs/' . (string) $sport['image']);
}

$title = (string)($sport['libelle'] ?? 'Sport');
$reduction = (string)($sport['pourcentage_reduction'] ?? '');
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= esc($title) ?> - Vary'Ena<?= $this->endSection() ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/product-detail.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="regime-detail">
    <main class="main-content">
        <div class="container">
            <div class="product-layout">

                <!-- LEFT: IMAGE -->
                <div class="product-images">
                    <div class="main-image">
                        <?php if ($imageUrl): ?>
                            <img src="<?= esc($imageUrl) ?>" alt="<?= esc($title) ?>">
                        <?php else: ?>
                            <div style="height:280px; display:flex; align-items:center; justify-content:center; background:#eee;">
                                <span style="color:#999;">Aucune image</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- RIGHT: DETAILS -->
                <div class="product-details">
                    <div class="details-header">
                        <div class="icon-badge">
                            <svg width="16" height="16" fill="none" stroke="white" stroke-width="1.5">
                                <circle cx="8" cy="8" r="7"></circle>
                            </svg>
                        </div>
                        <h2>Programme Sport</h2>
                    </div>

                    <!-- Description -->
                    <p class="description">
                        Ce programme sportif permet d'améliorer vos performances et de compléter votre régime alimentaire.
                    </p>

                    <!-- Stats -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <svg width="20" height="20" fill="none" stroke="#636" stroke-width="1.8">
                                <path d="M3 12h18"></path>
                                <path d="M12 3v18"></path>
                            </svg>
                            <div class="stat-value"><?= esc($reduction !== '' ? $reduction . '%' : '—') ?></div>
                            <div class="stat-label">Réduction</div>
                        </div>
                    </div>

                    <!-- Action -->
                    <div style="margin-top:20px;">
                        <a class="btn-buy" href="<?= site_url('/') ?>" style="text-decoration:none;">
                            Retour accueil
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>
<?= $this->endSection() ?>