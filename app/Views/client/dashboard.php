<?php

/** @var array $client */
/** @var float $imc */
/** @var array $objectif */
/** @var bool $isGold */
/** @var float $argent */

$email = (string) ($client['email'] ?? 'User');
$poids = (string) ($client['poids'] ?? '');
$taille = (string) ($client['taille'] ?? '');

$statutLabel = $isGold ? 'GOLD' : 'STANDARD';

// Objectif (robuste)
$objectifLabel = '';
$objectifPoidsCible = '';
$objectifDuree = '';

if (!empty($objectif)) {
    $first = $objectif;
    if (array_is_list($objectif)) {
        $first = $objectif[0] ?? [];
    }
    if (is_array($first)) {
        $objectifLabel = (string) ($first['libelle'] ?? $first['objectif_libelle'] ?? '');
        $objectifPoidsCible = (string) ($first['poids_cible'] ?? $first['poidsCible'] ?? '');
        $objectifDuree = (string) ($first['duree'] ?? '');
    }
}
if ($objectifLabel === '' && !empty($objectif)) {
    $objectifLabel = 'Objectif sélectionné';
}
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Dashboard - Vary'Ena<?= $this->endSection() ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/dashboard.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('page_js') ?>
<script src="<?= base_url('assets/js/pages/dashboard.js') ?>" defer></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- HERO (v2) -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <a href="<?= base_url('/') ?>">
                <div class="hero-icon" aria-hidden="true">
                    <svg width="32" height="28" viewBox="0 0 26 21" fill="none">
                        <path
                            d="M1 7.8L13 1L25 7.8V18.2C25 18.7835 24.7682 19.3431 24.3556 19.7556C23.9431 20.1682 23.3835 20.4 22.8 20.4H3.2C2.61652 20.4 2.05695 20.1682 1.64437 19.7556C1.23179 19.3431 1 18.7835 1 18.2V7.8Z"
                            fill="white" stroke="white" stroke-width="2" />
                        <path d="M9.39999 20.4V10.6H16.6V20.4" stroke="#636" stroke-width="2" />
                    </svg>
                </div>
                <div class="hero-text">
                    <h1>Bienvenue, <?= esc($email) ?></h1>
                    <p>Votre tableau de bord personnel Vary'Ena</p>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- MAIN (v2) -->
<main class="main-content">
    <div class="container">
        <!-- Modal: Modifier Objectif -->
        <div id="goalModal" class="modal" style="display:none;">
            <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="goalModalTitle">
                <button type="button" class="close-modal" aria-label="Fermer">✕</button>

                <h2 id="goalModalTitle" class="modal-title">Modifier mon objectif</h2>

                <form id="updateGoalForm">
                    <div id="goalsContainer">
                        <!-- Rempli par JS -->
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn-secondary" id="closeGoalModal">Annuler</button>
                        <button type="submit" class="btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>


        <div class="dashboard-grid">
            <!-- Card 1 -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">À propos de vous</h2>
                </div>

                <div class="card-content">
                    <div class="info-section">
                        <label class="info-label">Statut Compte</label>
                        <div class="status-badge-container">
                            <div class="status-badge"><?= esc($statutLabel) ?></div>
                        </div>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-item">
                            <label class="stat-label">Poids Actuel</label>
                            <div class="stat-value-container">
                                <span class="stat-value"><?= esc($poids) ?></span>
                                <span class="stat-unit">kg</span>
                            </div>
                        </div>

                        <div class="stat-item">
                            <label class="stat-label">Taille</label>
                            <div class="stat-value-container">
                                <span class="stat-value"><?= esc($taille) ?></span>
                                <span class="stat-unit">cm</span>
                            </div>
                        </div>

                        <div class="stat-item">
                            <label class="stat-label">Votre IMC</label>
                            <div class="stat-value-container">
                                <span class="stat-value"><?= esc(number_format((float) $imc, 1)) ?></span>
                                <?php
                                    $imcInterpretation = 'Ideal Weight';
                                    $imcColor = '#008000';
                                    if ((float) $imc < 18.5) {
                                        $imcInterpretation = 'Underweight';
                                        $imcColor = '#0066cc';
                                    } elseif ((float) $imc < 25) {
                                        $imcInterpretation = 'Ideal Weight';
                                        $imcColor = '#008000';
                                    } elseif ((float) $imc < 30) {
                                        $imcInterpretation = 'Overweight';
                                        $imcColor = '#ff8800';
                                    } else {
                                        $imcInterpretation = 'Obésité';
                                        $imcColor = '#cc0000';
                                    }
                                ?>
                                <span style="font-size: 12px; color: <?= $imcColor ?>; font-weight: 700; margin-top: 4px;"><?= $imcInterpretation ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="info-section">
                        <label class="info-label">Mon Porte-Monnaie</label>
                        <div class="stat-value-container">
                            <span
                                class="stat-value secondary"><?= esc(number_format((float) $argent, 2, '.', '')) ?></span>
                            <span class="stat-unit">Ar</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Mes Objectifs</h2>
                </div>

                <div class="card-content">
                    <div class="objectives-grid">
                        <div class="objective-item">
                            <label class="objective-label">Objectif</label>
                            <div class="objective-value">
                                <span><?= esc($objectifLabel) ?></span>
                            </div>
                        </div>

                        <div class="objective-item">
                            <label class="objective-label">Poids Cible</label>
                            <div class="objective-value">
                                <span><?= esc($objectifPoidsCible !== '' ? ($objectifPoidsCible . ' kg') : '') ?></span>
                            </div>
                        </div>

                        <div class="objective-item">
                            <label class="objective-label">Durée</label>
                            <div class="objective-value">
                                <span><?= esc($objectifDuree !== '' ? ($objectifDuree . ' jours') : '') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="action-section">
            <button class="btn-secondary" type="button" onclick="window.location.href='<?= site_url('/') ?>'">
                Retour vers l'accueil
            </button>
            <button class="btn-primary" type="button" onclick="window.location.href='<?= site_url('/logout') ?>'">
                Déconnexion
            </button>
        </div>

    </div>
</main>

<footer class="footer">
    <div class="container">
        <p>&copy; 2026 Vary'Ena. Tous droits réservés.</p>
    </div>
</footer>

<?= $this->endSection() ?>