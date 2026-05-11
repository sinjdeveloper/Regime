<?php
/** @var array $suggestions */
/** @var bool $isGold */
/** @var float $argent */
/** @var array $client */
?>

<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Suggestions Personnalisées - Vary'Ena<?= $this->endSection() ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/suggestions_new.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('page_js') ?>
<script src="<?= base_url('assets/js/pages/suggestions_new.js') ?>" defer></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Navbar -->
<nav class="navbar">
    <div class="navbar-container">
        <!-- Logo -->
        <a href="<?= base_url('/') ?>" class="navbar-logo">
            Vary<span class="highlight">'</span>Ena
        </a>

        <!-- Center Links -->
        <div class="navbar-center">
            <a href="<?= base_url('/dashboard') ?>">Programmes</a>
            <a href="<?= base_url('/imc') ?>">Mon IMC</a>
            <a href="<?= base_url('/suivi') ?>">Suivi</a>
        </div>

        <!-- Right Actions -->
        <div class="navbar-right">
            <button class="btn-gold-offer" onclick="openGoldModal()">Offre Gold</button>
            <button class="btn-code" onclick="openCodeModal()">💳 Code</button>
            <div class="profile-icon" onclick="openProfileModal()">👤</div>
        </div>
    </div>
</nav>

<!-- Profile Modal -->
<div id="profileModal" class="modal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeProfileModal()">✕</button>
        <h2 class="modal-title">Mon Profil</h2>

        <div class="modal-section">
            <label>Utilisateur</label>
            <p><?= esc($client['name'] ?? 'Client') ?></p>
        </div>

        <div class="modal-section">
            <label>Email</label>
            <p><?= esc($client['email'] ?? 'N/A') ?></p>
        </div>

        <div class="modal-section">
            <label>Poids Actuel</label>
            <p><?= (int)$client['poids'] ?>kg</p>
        </div>

        <div class="modal-section">
            <label>Taille</label>
            <p><?= (int)$client['taille'] ?>cm</p>
        </div>

        <div class="modal-section">
            <label>Statut</label>
            <p><?= $isGold ? '⭐ Membre GOLD' : 'Membre Standard' ?></p>
        </div>

        <div class="modal-section">
            <label>Porte-monnaie</label>
            <p><?= number_format($argent ?? 0, 2) ?>€</p>
        </div>

        <div class="modal-actions">
            <button class="btn-secondary" onclick="closeProfileModal()">Fermer</button>
            <button class="btn-primary" onclick="window.location.href='<?= base_url('/logout') ?>'">Déconnexion</button>
        </div>
    </div>
</div>

<!-- Gold Modal -->
<div id="goldModal" class="modal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeGoldModal()">✕</button>
        <h2 class="modal-title">Offre GOLD</h2>

        <div class="gold-badge">✨ Devenir Membre GOLD</div>

        <div class="modal-section">
            <label>Avantages GOLD</label>
            <ul style="margin: 8px 0 0 0; padding-left: 20px; color: var(--text-dark);">
                <li><?= \App\Services\AppSettingsService::getGoldDiscount() ?>% de réduction sur tous les régimes</li>
                <li>Accès prioritaire aux nouveaux programmes</li>
                <li>Suivi personnalisé illimité</li>
                <li>Support client 24/7</li>
            </ul>
        </div>

        <div class="modal-section">
            <label>Prix</label>
            <div class="price-gold"><?= number_format(\App\Controllers\GoldController::getGoldPrice(), 2) ?>€</div>
            <p style="font-size: 14px; color: #999;">Accès illimité pendant 1 mois</p>
        </div>

        <?php if (!$isGold): ?>
            <div class="modal-section">
                <label>Votre Porte-monnaie</label>
                <p><?= number_format($argent ?? 0, 2) ?>€</p>
            </div>
        <?php endif; ?>

        <div class="modal-actions">
            <button class="btn-secondary" onclick="closeGoldModal()">Annuler</button>
            <?php if ($isGold): ?>
                <button class="btn-primary" style="background-color: #999; cursor: not-allowed;" disabled>Déjà GOLD ✓</button>
            <?php else: ?>
                <button class="btn-primary" onclick="subscribeToGold()">S'abonner Maintenant</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Code Promo Modal -->
<div id="codeModal" class="modal" data-validate-code-url="<?= base_url('/validate-code') ?>">
    <div class="modal-content">
        <button class="modal-close" onclick="closeCodeModal()">✕</button>
        <h2 class="modal-title">Ajouter du Crédit</h2>

        <div id="codeMessage"></div>

        <div class="modal-section">
            <label>Porte-monnaie Actuel</label>
            <p><strong><?= number_format($argent ?? 0, 2) ?>€</strong></p>
        </div>

        <div class="modal-section">
            <label>Code Promo</label>
            <div class="modal-input-group">
                <input
                    type="text"
                    id="codeInput"
                    placeholder="Entrez votre code promo"
                    onkeypress="if(event.key === 'Enter') validateCode()"
                >
                <button onclick="validateCode()">Valider</button>
            </div>
            <p style="font-size: 12px; color: #999; margin: 8px 0 0 0;">
                📌 Ex: CODE100, CODE50, WELCOME20...
            </p>
        </div>

        <div class="modal-actions">
            <button class="btn-secondary" onclick="closeCodeModal()">Fermer</button>
        </div>
    </div>
</div>

<!-- Suggestions Intro -->
<section id="suggestions-intro">
    <div class="container suggestions-intro-inner">
        <h1 class="suggestions-title">Vos Suggestions Personnalisées</h1>
        <p class="suggestions-subtitle">Programmes adaptés à vos objectifs et votre profil santé</p>
        <div class="suggestions-status">
            <div class="status-badge">
                💪 Poids actuel: <strong><?= (int)$client['poids'] ?>kg</strong>
            </div>
            <div class="status-badge">
                📏 Taille: <strong><?= (int)$client['taille'] ?>cm</strong>
            </div>
            <?php if ($isGold): ?>
                <div class="status-badge gold">
                    ⭐ Membre GOLD - <?= \App\Services\AppSettingsService::getGoldDiscount() ?>% de réduction
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Suggestions Grid -->
<section id="section-suggestions">
    <div class="container">
        <?php if (!empty($suggestions)): ?>
            <div class="program-grid">
                <?php foreach ($suggestions as $suggestion): ?>
                    <article class="card <?= $isGold ? 'gold-active' : '' ?>">
                        <div class="card-content">
                            <!-- Title & Description -->
                            <h3 class="card-title"><?= esc($suggestion['regime']['libelle']) ?></h3>
                            <p class="card-desc"><?= esc($suggestion['regime']['description']) ?></p>

                            <!-- Nutrition -->
                            <div class="nutrition-label">Composition</div>
                            <div class="macros">
                                <div class="macro">
                                    <span class="macro-val"><?= (int)$suggestion['regime']['pourcentage_viande'] ?>%</span>
                                    <span class="macro-label">Viande</span>
                                </div>
                                <div class="macro">
                                    <span class="macro-val"><?= (int)$suggestion['regime']['pourcentage_poisson'] ?>%</span>
                                    <span class="macro-label">Poisson</span>
                                </div>
                                <div class="macro">
                                    <span class="macro-val"><?= (int)$suggestion['regime']['pourcentage_volaille'] ?>%</span>
                                    <span class="macro-label">Volaille</span>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="price-section">
                                <div class="price-label">Tarif</div>
                                <div class="price-display">
                                    <?php if ($suggestion['regime']['reduction_appliquee'] > 0): ?>
                                        <span class="price-original">
                                            <?= number_format($suggestion['regime']['prix_original'], 2) ?> Ar
                                        </span>
                                        <span class="discount-badge">
                                            -<?= (int)$suggestion['regime']['reduction_appliquee'] ?>%
                                        </span>
                                    <?php endif; ?>
                                    <span class="price-value">
                                        <?= number_format($suggestion['regime']['prix_final'], 2) ?>
                                    </span>
                                    <span class="price-currency">Ar</span>
                                </div>
                            </div>

                            <!-- Sport -->
                            <?php if (isset($suggestion['sport'])): ?>
                                <div class="sport-info">
                                    🏃 <strong><?= esc($suggestion['sport']['libelle']) ?></strong><br>
                                    Effet: <strong>+<?= esc($suggestion['sport']['effet']) ?></strong>
                                </div>
                            <?php endif; ?>

                            <!-- Action Button -->
                            <button class="btn-card" onclick="selectRegime(<?= $suggestion['regime']['id'] ?>)">
                                ✓ Choisir ce régime
                            </button>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-suggestions">
                <div class="no-suggestions-icon">📭</div>
                <h2>Aucune suggestion disponible</h2>
                <p>Définissez vos objectifs pour recevoir nos recommendations personnalisées</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Footer -->
<section id="section-footer">
    <footer class="site-footer">
        <div class="container footer-inner">
            <p>© 2026 Vary'Ena. Tous droits réservés.</p>
        </div>
    </footer>
</section>
<?= $this->endSection() ?>