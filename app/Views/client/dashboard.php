<?php
/** @var array $client */
/** @var float $imc */
/** @var array $objectif */
/** @var bool $isGold */
/** @var float $argent */

$pageTitle = 'Dashboard - Vary\'Ena';

$email  = (string)($client['email'] ?? 'User');
$poids  = (string)($client['poids'] ?? '');
$taille = (string)($client['taille'] ?? '');

$statutLabel = $isGold ? 'GOLD' : 'STANDARD';

$objectifLabel = '';
$objectifPoidsCible = '';
$objectifDuree = '';

if (!empty($objectif)) {
    $first = $objectif;
    if (array_is_list($objectif)) {
        $first = $objectif[0] ?? [];
    }
    if (is_array($first)) {
        $objectifLabel = (string)($first['libelle'] ?? $first['objectif_libelle'] ?? '');
        $objectifPoidsCible = (string)($first['poids_cible'] ?? $first['poidsCible'] ?? '');
        $objectifDuree = (string)($first['duree'] ?? '');
    }
}
if ($objectifLabel === '' && !empty($objectif)) {
    $objectifLabel = 'Objectif sélectionné';
}
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Dashboard - Vary'Ena<?= $this->endSection() ?>

<?= $this->section('page_js') ?>
<script src="<?= base_url('assets/js/pages/dashboard_new.js') ?>" defer></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Main Content -->
<main class="main-content">
    <div class="container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <button class="home-icon-btn" type="button" onclick="window.location.href='<?= site_url('/') ?>'">
                <svg width="26" height="21" viewBox="0 0 26 21" fill="none">
                    <path d="M1 7.8L13 1L25 7.8V18.2C25 18.7835 24.7682 19.3431 24.3556 19.7556C23.9431 20.1682 23.3835 20.4 22.8 20.4H3.2C2.61652 20.4 2.05695 20.1682 1.64437 19.7556C1.23179 19.3431 1 18.7835 1 18.2V7.8Z" fill="#663366" stroke="#663366" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9.39999 20.4V10.6H16.6V20.4" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div class="welcome-text">
                <h1>Bienvenue, <?= esc($email) ?></h1>
                <p>Votre tableau de bord personnel Vary'Ena</p>
            </div>

            <button class="btn-disconnect" type="button" onclick="window.location.href='<?= site_url('/logout') ?>'">
                Déconnexion
            </button>
        </div>

        <!-- Cards Container -->
        <div class="cards-container">

            <!-- Card 1 -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title">A propos de vous</span>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Statut Compte</label>
                        <div class="input-with-badge">
                            <input type="text" class="form-input" value="" readonly>
                            <span class="badge-standard"><?= esc($statutLabel) ?></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Poids Actuel</label>
                            <div class="input-with-unit">
                                <input type="text" class="form-input" value="<?= esc($poids) ?>" readonly>
                                <span class="unit">kg</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Taille</label>
                            <div class="input-with-unit">
                                <input type="text" class="form-input" value="<?= esc($taille) ?>" readonly>
                                <span class="unit">cm</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Votre IMC</label>
                            <input type="text" class="form-input" value="<?= esc(number_format((float)$imc, 1)) ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mon Porte-Monnaie</label>
                        <div class="input-with-unit">
                            <input type="text" class="form-input" value="<?= esc(number_format((float)$argent, 2, '.', '')) ?>" readonly>
                            <span class="unit">Ar</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Mes Objectifs</span>
                </div>

                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Objectif</label>
                            <div class="input-with-icon">
                                <input type="text" class="form-input" value="<?= esc($objectifLabel) ?>" readonly>
                                <svg class="edit-icon" id="btn-edit-objectif" width="16" height="16" viewBox="0 0 16 16" fill="none" style="cursor:pointer;">
                                    <path d="M7.33333 2.66667H2.66667C2.31304 2.66667 1.97391 2.80714 1.72386 3.05719C1.47381 3.30724 1.33333 3.64638 1.33333 4V13.3333C1.33333 13.687 1.47381 14.0261 1.72386 14.2761C1.97391 14.5262 2.31304 14.6667 2.66667 14.6667H12C12.3536 14.6667 12.6928 14.5262 12.9428 14.2761C13.1929 14.0261 13.3333 13.687 13.3333 13.3333V8.66667" stroke="#B5B5B5" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12.3333 1.66665C12.5985 1.40144 12.9582 1.25244 13.3333 1.25244C13.7085 1.25244 14.0681 1.40144 14.3333 1.66665C14.5985 1.93187 14.7475 2.29158 14.7475 2.66665C14.7475 3.04173 14.5985 3.40144 14.3333 3.66665L8 9.99999L5.33333 10.6667L6 7.99999L12.3333 1.66665Z" stroke="#B5B5B5" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Poids Cible</label>
                            <div class="input-with-icon">
                                <input type="text" class="form-input" value="<?= esc($objectifPoidsCible) ?>" readonly>
                                <svg class="edit-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M7.33333 2.66667H2.66667C2.31304 2.66667 1.97391 2.80714 1.72386 3.05719C1.47381 3.30724 1.33333 3.64638 1.33333 4V13.3333C1.33333 13.687 1.47381 14.0261 1.72386 14.2761C1.97391 14.5262 2.31304 14.6667 2.66667 14.6667H12C12.3536 14.6667 12.6928 14.5262 12.9428 14.2761C13.1929 14.0261 13.3333 13.687 13.3333 13.3333V8.66667" stroke="#B5B5B5" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12.3333 1.66665C12.5985 1.40144 12.9582 1.25244 13.3333 1.25244C13.7085 1.25244 14.0681 1.40144 14.3333 1.66665C14.5985 1.93187 14.7475 2.29158 14.7475 2.66665C14.7475 3.04173 14.5985 3.40144 14.3333 3.66665L8 9.99999L5.33333 10.6667L6 7.99999L12.3333 1.66665Z" stroke="#B5B5B5" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Durée</label>
                            <div class="input-with-icon">
                                <input type="text" class="form-input" value="<?= esc($objectifDuree !== '' ? ($objectifDuree . ' jours') : '') ?>" readonly>
                                <svg class="edit-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M7.33333 2.66667H2.66667C2.31304 2.66667 1.97391 2.80714 1.72386 3.05719C1.47381 3.30724 1.33333 3.64638 1.33333 4V13.3333C1.33333 13.687 1.47381 14.0261 1.72386 14.2761C1.97391 14.5262 2.31304 14.6667 2.66667 14.6667H12C12.3536 14.6667 12.6928 14.5262 12.9428 14.2761C13.1929 14.0261 13.3333 13.687 13.3333 13.3333V8.66667" stroke="#B5B5B5" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12.3333 1.66665C12.5985 1.40144 12.9582 1.25244 13.3333 1.25244C13.7085 1.25244 14.0681 1.40144 14.3333 1.66665C14.5985 1.93187 14.7475 2.29158 14.7475 2.66665C14.7475 3.04173 14.5985 3.40144 14.3333 3.66665L8 9.99999L5.33333 10.6667L6 7.99999L12.3333 1.66665Z" stroke="#B5B5B5" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="action-buttons">
            <button class="btn-back" type="button" onclick="window.location.href='<?= site_url('/') ?>'">
                Retour vers l'accueil
            </button>
        </div>
    </div>
</main>
<?= $this->endSection() ?>