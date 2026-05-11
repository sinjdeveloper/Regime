<?php
/** @var array $client */
/** @var bool $isGold */

$argent = (float)($client['argent'] ?? 0);
$pageTitle = 'Validation de Code - Vary\'Ena';

$statutLabel = $isGold ? 'GOLD' : 'STANDARD';

// URL API (même endpoint que ton ancienne page wallet)
$redeemUrl = site_url('wallet/redeem-code');
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Validation de Code - Vary'Ena<?= $this->endSection() ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/wallet.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('page_js') ?>
<script src="<?= base_url('assets/js/pages/wallet.js') ?>" defer></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="code-validation-page" data-redeem-url="<?= esc($redeemUrl) ?>">
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="home-icon" role="img" aria-label="Home">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </div>
                <div class="hero-text">
                    <h1 class="hero-title">Validation de Code</h1>
                    <p class="hero-subtitle">Solde: <?= number_format($argent, 0, ',', ' ') ?> Ar</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="validation-card">
                <!-- Status Badge -->
                <div class="status-badge">
                    <?= esc($statutLabel) ?>
                </div>

                <!-- Form -->
                <form class="validation-form" id="codeValidationForm">
                    <div class="form-group">
                        <label for="code-input" class="form-label">Entrez votre code de validation</label>
                        <input
                            type="text"
                            id="code-input"
                            class="form-input"
                            placeholder="Ex: ABC123XYZ"
                            maxlength="20"
                            autocomplete="off"
                        >
                    </div>

                    <button type="submit" class="submit-btn" id="btn-submit-code">
                        Envoyer pour validation
                    </button>
                </form>

                <!-- Message zone -->
                <div id="code-msg" style="display:none; margin-bottom:16px; font-size:14px;"></div>

                <!-- Info Box -->
                <div class="info-box">
                    <div class="info-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="10" cy="10" r="9"></circle>
                            <line x1="10" y1="14" x2="10" y2="10"></line>
                            <line x1="10" y1="6" x2="10.01" y2="6"></line>
                        </svg>
                    </div>
                    <div class="info-text">
                        <p><strong>Comment ça marche ?</strong></p>
                        <p>
                            Lorsque vous soumettez un code, celui-ci est envoyé à l’administrateur pour validation.
                            Votre solde sera crédité après approbation.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="footer-text">© 2026 Vary'Ena. All rights reserved.</p>
        </div>
    </footer>
</div>
<?= $this->endSection() ?>