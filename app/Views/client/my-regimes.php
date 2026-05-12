<?php

/** @var array $regimes */

$pageTitle = 'Mes Régimes - Vary\'Ena';
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Mes Régimes
<?= $this->endSection() ?>

<?= $this->section('page_js') ?>
<script src="<?= base_url('assets/js/global.js') ?>" defer></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section id="section-hero">
    <div class="hero">
        <div class="container hero-inner">
            <h1 class="hero-title">Mes Régimes Achetés</h1>

            <p class="hero-subtitle">
                Retrouvez tous les programmes nutritionnels que vous avez achetés
            </p>
        </div>
    </div>
</section>

<section id="section-programs">
    <div class="container programs-inner">

        <div class="program-grid">

            <?php if (!empty($regimes)): ?>

                <?php foreach ($regimes as $regime): ?>

                    <article class="card nutrition">

                        <div class="card-image">
                            <img
                                src="<?= base_url('assets/images/programs/' . $regime['image']) ?>"
                                alt="Regime">
                        </div>

                        <div class="card-content">

                            <h3 class="card-title">
                                <?= esc((string)$regime['libelle']) ?>
                            </h3>

                            <p class="card-desc">
                                <?= esc((string)$regime['description']) ?>
                            </p>

                            <div class="macros">

                                <div class="macro">
                                    <span class="macro-val">
                                        <?= esc((string)$regime['pourcentage_viande']) ?>%
                                    </span>

                                    <span class="macro-label">
                                        Viande
                                    </span>
                                </div>

                                <div class="macro">
                                    <span class="macro-val">
                                        <?= esc((string)$regime['pourcentage_poisson']) ?>%
                                    </span>

                                    <span class="macro-label">
                                        Poisson
                                    </span>
                                </div>

                                <div class="macro">
                                    <span class="macro-val">
                                        <?= esc((string)$regime['pourcentage_volaille']) ?>%
                                    </span>

                                    <span class="macro-label">
                                        Volaille
                                    </span>
                                </div>

                            </div>

                            <div class="duration">
                                Prix :
                                <?= esc((string)$regime['prix']) ?> Ar
                            </div>

                            <a
                                href="<?= site_url('programs/regime/' . $regime['id']) ?>"
                                class="btn btn-card">

                                Voir Détails

                            </a>

                        </div>

                    </article>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="empty-state">

                    <h2>Aucun régime acheté</h2>

                    <p>
                        Vous n'avez encore acheté aucun programme nutritionnel.
                    </p>

                    <a
                        href="<?= site_url('/') ?>"
                        class="btn btn-primary">

                        Découvrir les programmes

                    </a>

                </div>

            <?php endif; ?>

        </div>

    </div>
</section>

<section id="section-footer">
    <footer class="site-footer">
        <div class="container footer-inner">
            <p>© 2026 Vary'Ena. Tous droits réservés.</p>
        </div>
    </footer>
</section>

<?= $this->endSection() ?>