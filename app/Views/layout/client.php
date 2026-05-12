<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $pageTitle = trim($this->renderSection('title')); ?>
    <title><?= esc($pageTitle ? $pageTitle . ' | Vary\'Ena' : 'Vary\'Ena') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/globals.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/client.css') ?>">

    <?= $this->renderSection('head') ?>

    <script src="<?= base_url('assets/js/global.js') ?>" defer></script>
</head>

<?php
$userSession = session()->get('user');
$isLoggedIn = is_array($userSession) && !empty($userSession['id']);
?>

<?php
// Provide common URLs and flags expected by converted client views
$isAdmin = $isLoggedIn && (($userSession['role'] ?? null) === 'admin');
$loginUrl = site_url('/user/login');
$signupUrl = site_url('/signup');
$adminHomeUrl = site_url('/admin/dashboard');
$imcUrl = $isAdmin ? $adminHomeUrl : ($isLoggedIn ? site_url('/imc') : $loginUrl);
$suiviUrl = $isAdmin ? $adminHomeUrl : ($isLoggedIn ? site_url('/suivi') : $loginUrl);
$goldUrl = $isAdmin ? $adminHomeUrl : ($isLoggedIn ? site_url('/gold') : $loginUrl);
$profileUrl = $isAdmin ? $adminHomeUrl : ($isLoggedIn ? site_url('/profile') : $loginUrl);
$ctaUrl = $isAdmin ? $adminHomeUrl : ($isLoggedIn ? site_url('/dashboard') : $signupUrl);
?>

<body data-auth="<?= $isLoggedIn ? '1' : '0' ?>" data-username="<?= esc((string) ($userSession['username'] ?? '')) ?>">
    <header class="client-header">
        <div class="header-inner">
            <div class="logo">
                <img src="<?= base_url('assets/images/admin') ?>/20_831.svg" alt="Vary'Ena">
                <span class="logo-text">Vary<span class="logo-highlight">'</span>Ena</span>
            </div>
            <nav class="client-nav">
                <a href="<?= site_url('/') ?>">Accueil</a>
                <a href="<?= site_url('/suggestions') ?>">Suggestions</a>
                <a href="<?= site_url('/suivi') ?>">Suivi</a>
                <a href="<?= site_url('/wallet') ?>">Wallet</a>
            </nav>
            <div class="header-actions">
                <?php if ($isLoggedIn): ?>
                    <a class="btn small" href="<?= site_url('/profile') ?>"><?= esc((string) ($userSession['username'] ?? 'Profil')) ?></a>
                    <a class="btn small ghost" href="<?= site_url('/logout') ?>">Déconnexion</a>
                <?php else: ?>
                    <a class="btn small" href="<?= site_url('/login') ?>">Se connecter</a>
                    <a class="btn small ghost" href="<?= site_url('/signup') ?>">S'inscrire</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="client-main">
        <div class="client-container">
            <?= view('partials/flash_messages') ?>
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <footer class="client-footer">
        <div class="client-container">&copy; <?= date('Y') ?> Vary'Ena</div>
    </footer>

    <?= $this->renderSection('scripts') ?>

</body>

</html>
