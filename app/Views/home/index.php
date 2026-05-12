<?php
/** @var array $regimes */
/** @var array $sports */

$pageTitle = 'Accueil - Vary\'Ena';
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Generated Page<?= $this->endSection() ?>

<?= $this->section('page_js') ?>
<script src="<?= base_url('assets/js/global.js') ?>" defer></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <!-- IMPORTANT:
         J'ai supprimé ton <section id="section-header">...navbar...</section>
         car la navbar est maintenant dans le layout.
    -->

    <section id="section-hero">
        <div class="hero">
            <div class="container hero-inner">
                <h1 class="hero-title">Atteignez vos objectifs avec Vary'Ena</h1>
                <p class="hero-subtitle">Des programmes personnalisés de nutrition et de sport pour transformer votre vie</p>
                <div class="hero-buttons">
                    <?php
                        // On refait la logique de liens protégés ici aussi (comme avant), ou tu peux utiliser ceux du layout.
                        $userSession = session()->get('user');
                        $isLoggedIn = is_array($userSession) && ! empty($userSession['id']);
                        $isAdmin = $isLoggedIn && (($userSession['role'] ?? null) === 'admin');
                        $loginUrl = site_url('/user/login');
                        $adminHomeUrl = site_url('/admin/dashboard');
                        $imcUrl = $isAdmin ? $adminHomeUrl : ($isLoggedIn ? site_url('/dashboard') : $loginUrl);
                    ?>
                    <a href="<?= $imcUrl ?>" class="btn btn-primary">Calculer mon IMC <img src="<?= base_url('assets/images/images7_416.svg') ?>" alt=""></a>
                    <a href="#section-programs" class="btn btn-outline">Découvrir les programmes</a>
                </div>
            </div>
        </div>
    </section>

    <section id="section-programs">
        <div class="container programs-inner">
            <div class="filters">
                <button class="filter-btn active" data-filter="all">
                    <img src="<?= base_url('assets/images/images/7_427.svg') ?>" alt="">
                    Tous les programmes
                </button>

                <button class="filter-btn" data-filter="nutrition">
                    <img src="<?= base_url('assets/images/images/7_431.svg') ?>" alt="">
                    Nutrition
                </button>

                <button class="filter-btn" data-filter="sport">
                    <img src="<?= base_url('assets/images/images/7_437.svg') ?>" alt="">
                    Sport
                </button>

                <button class="filter-btn" data-filter="combo">
                    <img src="<?= base_url('assets/images/images/7_445.svg') ?>" alt="">
                    Nutrition + Sport
                </button>
            </div>

            <div class="program-grid">
                <?php
                    $signupUrl = site_url('/signup');
                    $ctaUrl = $isAdmin ? $adminHomeUrl : ($isLoggedIn ? site_url('/dashboard') : $signupUrl);
                ?>

                <?php if (!empty($regimes)): ?>
                    <?php foreach ($regimes as $regime): ?>
                        <article class="card nutrition">
                            <div class="card-image">
                                <img src="<?= base_url('assets/images/programs/' . $regime['image']) ?>" alt="Regime">
                            </div>

                            <div class="card-content">
                                <h3 class="card-title"><?= esc((string)$regime['libelle']) ?></h3>

                                <p class="card-desc">
                                    <?= esc((string)$regime['description']) ?>
                                </p>

                                <div class="macros">
                                    <div class="macro">
                                        <span class="macro-val"><?= esc((string)$regime['pourcentage_viande']) ?>%</span>
                                        <span class="macro-label">Viande</span>
                                    </div>

                                    <div class="macro">
                                        <span class="macro-val"><?= esc((string)$regime['pourcentage_poisson']) ?>%</span>
                                        <span class="macro-label">Poisson</span>
                                    </div>

                                    <div class="macro">
                                        <span class="macro-val"><?= esc((string)$regime['pourcentage_volaille']) ?>%</span>
                                        <span class="macro-label">Volaille</span>
                                    </div>
                                </div>

                                <div class="duration">
                                    Prix : <?= esc((string)$regime['prix']) ?> Ar
                                </div>

                                <a href="<?= $isLoggedIn ? site_url('programs/regime/' . $regime['id']) : $loginUrl ?>" class="btn btn-card">
                                    Voir Détails
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if (!empty($sports)): ?>
                    <?php foreach ($sports as $sport): ?>
                        <article class="card sport">
                            <div class="card-image">
                                <img src="<?= base_url('assets/images/programs/' . $sport['image']) ?>" alt="Sport">
                            </div>

                            <div class="card-content">
                                <h3 class="card-title"><?= esc((string)$sport['libelle']) ?></h3>

                                <p class="card-desc">
                                    Réduction : <?= esc((string)$sport['pourcentage_reduction']) ?>%
                                </p>

                                <a href="<?= $isLoggedIn ? site_url('programs/sport/' . $sport['id']) : $loginUrl ?>" class="btn btn-card">
                                    Voir Détails
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="section-cta">
        <div class="container">
            <div class="cta-box">
                <h2 class="cta-title">Prêt à commencer votre transformation ?</h2>
                <p class="cta-desc">Rejoignez des milliers de personnes qui ont atteint leurs objectifs avec Vary'Ena</p>
                <a href="<?= $ctaUrl ?>" class="btn btn-gold btn-large">Démarrer gratuitement</a>
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