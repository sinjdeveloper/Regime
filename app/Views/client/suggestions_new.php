<?php
/** @var array $suggestions */
/** @var bool $isGold */
/** @var float $argent */
/** @var array $client */
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Suggestions Personnalisées - Vary'Ena<?= $this->endSection() ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/suggestions_new.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('page_js') ?>
<script src="<?= base_url('assets/js/pages/suggestions_new.js') ?>" defer></script>
<script src="<?= base_url('assets/js/pages/suggestions_buy.js') ?>" defer></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Navbar -->
<!-- Suggestions Intro -->
<section id="suggestions-intro">
    <div class="container suggestions-intro-inner">
        <h1 class="suggestions-title">Vos Suggestions Personnalisées</h1>
        <p class="suggestions-subtitle">Programmes adaptés à vos objectifs et votre profil santé</p>
        <div class="suggestions-status">
            <div class="status-badge">
                 Poids actuel: <strong><?= (int) $client['poids'] ?>kg</strong>
            </div>
            <div class="status-badge">
                Taille: <strong><?= (int) $client['taille'] ?>cm</strong>
            </div>
            <?php if ($isGold): ?>
                <div class="status-badge gold">
                    Membre GOLD - <?= \App\Services\AppSettingsService::getGoldDiscount() ?>% de réduction
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
                    <article class="card <?= $isGold ? 'gold-active' : '' ?>"
                        data-regime-id="<?= (int) $suggestion['regime']['id'] ?>">
                        <div class="card-image">
                            <img src="<?= base_url('assets/images/programs/' . esc($suggestion['regime']['image'] ?? '')) ?>"
                                alt="<?= esc($suggestion['regime']['libelle'] ?? 'Régime') ?>"
                                onerror="this.closest('.card-image').style.display='none';">
                        </div>

                        <div class="card-content">
                            <h3 class="card-title"><?= esc($suggestion['regime']['libelle']) ?></h3>
                            <p class="card-desc"><?= esc($suggestion['regime']['description']) ?></p>

                            <div class="macros">
                                <div class="macro">
                                    <span class="macro-val"><?= (int) $suggestion['regime']['pourcentage_viande'] ?>%</span>
                                    <span class="macro-label">Viande</span>
                                </div>
                                <div class="macro">
                                    <span class="macro-val"><?= (int) $suggestion['regime']['pourcentage_poisson'] ?>%</span>
                                    <span class="macro-label">Poisson</span>
                                </div>
                                <div class="macro">
                                    <span class="macro-val"><?= (int) $suggestion['regime']['pourcentage_volaille'] ?>%</span>
                                    <span class="macro-label">Volaille</span>
                                </div>
                            </div>

                            <div class="duration">
                                Prix :
                                <strong><?= number_format((float) ($suggestion['regime']['prix_final'] ?? $suggestion['regime']['prix'] ?? 0), 0, ',', ' ') ?></strong>
                                Ar
                                <?php if (!empty($suggestion['regime']['reduction_appliquee'])): ?>
                                    <span style="margin-left:8px; font-size:12px; opacity:.8;">
                                        (-<?= (int) $suggestion['regime']['reduction_appliquee'] ?>%)
                                    </span>
                                <?php endif; ?>
                            </div>

                            <?php if (isset($suggestion['sport'])): ?>
                                <div class="sport-info">
                                    🏃 <strong><?= esc($suggestion['sport']['libelle']) ?></strong><br>
                                    Effet: <strong>+<?= esc($suggestion['sport']['effet']) ?></strong>
                                </div>
                            <?php endif; ?>

                            <!-- Message achat -->
                            <div class="buy-msg" style="display:none; margin-bottom:12px; font-size:14px;"></div>

                            <!-- Actions -->
                            <div style="display:flex; gap:10px; flex-direction:column;">
                                <a class="btn btn-card"
                                    href="<?= site_url('programs/regime/' . (int) $suggestion['regime']['id']) ?>"
                                    style="text-decoration:none;">
                                    Voir détails
                                </a>

                            </div>
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