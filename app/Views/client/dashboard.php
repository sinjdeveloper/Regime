<?php

/** @var array $client */
/** @var float $imc */
/** @var array $objectif */
/** @var bool $isGold */
/** @var float $argent */

?>

<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Client Dashboard - Vary'Ena<?= $this->endSection() ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/dashboard.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('page_js') ?>
<script src="<?= base_url('assets/js/pages/dashboard.js') ?>" defer></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <div>
            <h1>Bienvenue, <?= esc((string) ($client['email'] ?? 'User')) ?></h1>
            <p class="welcome-message">Votre tableau de bord personnel Vary'Ena</p>
        </div>
        <a href="/logout" class="logout-btn">Déconnexion</a>
    </div>

    <!-- Stats Grid -->
    <div class="dashboard-grid">
        <!-- IMC Card -->
        <div class="card">
            <h2>Votre IMC</h2>
            <div class="card-value"><?= number_format($imc, 1) ?></div>
            <div class="card-label">
                <?php
                if ($imc < 18.5) {
                    echo 'Poids insuffisant';
                } elseif ($imc < 25) {
                    echo 'Poids normal';
                } elseif ($imc < 30) {
                    echo 'Surpoids';
                } else {
                    echo 'Obésité';
                }
                ?>
            </div>
        </div>

        <!-- Weight Card -->
        <div class="card">
            <h2>Poids Actuel</h2>
            <div class="card-value"><?= (int) $client['poids'] ?></div>
            <div class="card-label">kg</div>
        </div>

        <!-- Height Card -->
        <div class="card">
            <h2>Taille</h2>
            <div class="card-value"><?= (int) $client['taille'] ?></div>
            <div class="card-label">cm</div>
        </div>

        <!-- Account Status Card -->
        <div class="card">
            <h2>Statut du Compte</h2>
            <div class="card-value">
                <span class="status-badge <?= $isGold ? 'status-gold' : 'status-regular' ?>">
                    <?= $isGold ? 'GOLD' : 'STANDARD' ?>
                </span>
            </div>
            <div class="card-label">
                <?= $isGold ? 'Profitez des avantages Premium' : 'Passez à Gold pour plus d\'avantages' ?>
            </div>
        </div>

        <!-- Wallet Card -->
        <div class="card">
            <h2>Mon Porte-monnaie</h2>
            <div class="card-value"><?= number_format($argent, 2) ?></div>
            <div class="card-label">Ar</div>
        </div>

        <!-- Goals Card -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <h2>Mes Objectifs</h2>
                <button
                    class="btn btn-gold btn-small"
                    id="openGoalModal"
                    data-popup-url="<?= base_url('api/objectif/popup') ?>"
                    data-update-url="<?= base_url('api/objectif/update') ?>"
                    style="padding: 5px 10px; font-size: 12px; cursor: pointer;">
                    Modifier
                </button>
            </div>
            <div class="card-value">
                <?php
                if ($objectif) {
                    echo count($objectif);
                } else {
                    echo '0';
                }
                ?>
            </div>
            <div class="card-label">Objectifs sélectionnés</div>
        </div>
    </div>

    <!-- Modal pour modification d'objectifs -->
    <div id="goalModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="modal-header">
                <h3>Modifier mes objectifs</h3>
                <p style="font-size: 14px; color: #666; margin-top: 5px;">Sélectionnez jusqu'à 3 objectifs (poids cible et durée).</p>
            </div>
            <form id="updateGoalForm">
                <div id="goalsContainer">
                    <p style="text-align: center;">Chargement des objectifs...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" id="closeGoalModal">Annuler</button>
                    <button type="submit" class="btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Additional Info -->
    <?php if ($isGold): ?>
        <div class="gold-badge">
            ⭐ Vous êtes un membre GOLD - Profitez de réductions exclusives!
        </div>
    <?php endif; ?>

    <!-- Placeholder for future sections -->
    <div class="card">
        <h2>Prochaines Étapes</h2>
        <p>
            Bienvenue dans Vary'Ena! Voici ce que vous pouvez faire ensuite:
        </p>
        <ul>
            <li>👀 Explorez nos programmes de nutrition et de sport</li>
            <li>💪 Suivez vos progrès et objectifs</li>
            <li>🏆 Déverrouille les succès et les badges</li>
            <li>💰 Gérez votre porte-monnaie et vos codes promo</li>
        </ul>
        <a href="/" class="btn btn-gold btn-large">Retourner à l'accueil</a>

    </div>

</div>
<?= $this->endSection() ?>