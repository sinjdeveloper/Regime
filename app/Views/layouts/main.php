<?php
$userSession = session()->get('user');
$isLoggedIn = is_array($userSession) && ! empty($userSession['id']);
$isAdmin = $isLoggedIn && (($userSession['role'] ?? null) === 'admin');

$loginUrl  = site_url('/user/login');
$signupUrl = site_url('/signup');
$adminHomeUrl = site_url('/admin/dashboard');

$imcUrl     = $isAdmin ? $adminHomeUrl : ($isLoggedIn ? site_url('/imc') : $loginUrl);
$suiviUrl   = $isAdmin ? $adminHomeUrl : ($isLoggedIn ? site_url('/suivi') : $loginUrl);
$goldUrl    = $isAdmin ? $adminHomeUrl : ($isLoggedIn ? site_url('/gold') : $loginUrl);
$profileUrl = $isAdmin ? $adminHomeUrl : ($isLoggedIn ? site_url('/profile') : $loginUrl);

$ctaUrl = $isAdmin ? $adminHomeUrl : ($isLoggedIn ? site_url('/dashboard') : $signupUrl);

$homeUrl = site_url('/');
$programmesUrl = $ctaUrl;

$walletUrl = site_url('/wallet');
$mesRegimesUrl = $isAdmin ? $adminHomeUrl : ($isLoggedIn ? site_url('/mes-regimes') : $loginUrl);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= trim($this->renderSection('title')) !== '' ? $this->renderSection('title') : 'Vary\'Ena' ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <!-- CSS du template dashboard (navbar incluse) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/pages/dashboard.css') ?>">

    <?= $this->renderSection('page_css') ?>

    <!-- JS global (contient profile-popup + filtres) -->
    <script src="<?= base_url('assets/js/global.js') ?>" defer></script>
</head>

<body
    data-auth="<?= $isLoggedIn ? '1' : '0' ?>"
    data-role="<?= esc((string)($userSession['role'] ?? '')) ?>"
    data-username="<?= esc((string)($userSession['username'] ?? '')) ?>"
>
    <!-- Navbar (design dashboard) -->
    <header class="header">
        <div class="header-container">

            <!-- Logo -->
            <a class="logo" href="<?= $homeUrl ?>" style="text-decoration:none;">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                    <circle cx="20" cy="20" r="19" stroke="url(#logoGradient)" stroke-width="1.5" stroke-dasharray="3 1.5" opacity="0.6"/>
                    <path d="M 15 22.5 Q 16 18.5 19 16.5 Q 22 18.5 23 22.5 L 22 26.5 Q 21 29 19 30 Q 17 29 16 26.5 Z" fill="white" opacity="0.9"/>
                    <path d="M 19 16.5 Q 18 13.5 16.5 12 L 16 12.5 Q 17.5 14.5 18.5 17" stroke="white" stroke-width="1.25" stroke-linecap="round" fill="none"/>
                    <path d="M 24 14 Q 25.5 12 27.5 11.5 Q 28 13 27.5 15 Q 26 16.5 24 16 Z" fill="#10B981"/>
                    <circle cx="13.5" cy="26" r="1" fill="#60A5FA"/>
                    <circle cx="19" cy="23" r="1" fill="#10B981"/>
                    <circle cx="26" cy="21.5" r="1" fill="#FBBF24"/>
                    <defs>
                        <linearGradient id="logoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#60A5FA"/>
                            <stop offset="50%" stop-color="#10B981"/>
                            <stop offset="100%" stop-color="#FBBF24"/>
                        </linearGradient>
                    </defs>
                </svg>
                <div class="logo-text">
                    <span class="logo-vary">Vary</span>
                    <span class="logo-apostrophe">'</span>
                    <span class="logo-ena">Ena</span>
                </div>
            </a>

            <nav class="nav">
                <a href="<?= $homeUrl ?>" class="nav-link">Accueil</a>
                <a href="<?= $programmesUrl ?>" class="nav-link">Programmes</a>
                <a href="<?= $imcUrl ?>" class="nav-link">Mon IMC</a>
                <a href="<?= $suiviUrl ?>" class="nav-link">Suivi</a>
                <a href="<?= $mesRegimesUrl ?>" class="nav-link">Mes Regimes</a>
            </nav>

            <div class="header-actions">
                <a href="<?= $goldUrl ?>" class="btn-gold" style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">
                    Offre Gold
                </a>

                <a href="<?= $profileUrl ?>" class="btn-icon btn-user" aria-label="User Profile">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M16.6667 17.5V15.8333C16.6667 14.9493 16.3155 14.1014 15.6904 13.4763C15.0652 12.8512 14.2174 12.5 13.3333 12.5H6.66667C5.78261 12.5 4.93477 12.8512 4.30964 13.4763C3.68452 14.1014 3.33333 14.9493 3.33333 15.8333V17.5" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10 9.16667C11.8409 9.16667 13.3333 7.67428 13.3333 5.83333C13.3333 3.99238 11.8409 2.5 10 2.5C8.15905 2.5 6.66667 3.99238 6.66667 5.83333C6.66667 7.67428 8.15905 9.16667 10 9.16667Z" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <?= $this->renderSection('content') ?>
    <?= $this->renderSection('page_js') ?>
</body>
</html>