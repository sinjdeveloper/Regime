<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $pageTitle = trim($this->renderSection('title')); ?>
    <title><?= esc($pageTitle ? $pageTitle . ' | Administration' : 'Administration') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/globals.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">

    <script src="<?= base_url('assets/js/global.js') ?>" defer></script>
</head>


<body>
    <section id="section-header" class="app-wrapper">
        <header class="admin-header">
            <div class="header-left">
                <div class="logo">
                    <img src="<?= base_url('assets/images/admin') ?>/20_831.svg" alt="Vary'Ena Logo">
                    <span class="logo-text">Vary<span class="logo-highlight">'</span>Ena</span>
                </div>
                <span class="admin-text">Administration</span>
            </div>
            <div class="header-right">
                <button class="icon-btn">
                    <img src="<?= base_url('assets/images/admin') ?>/20_849.svg" alt="Notifications">
                    <span class="badge"></span>
                </button>
                <button class="icon-btn">
                    <img src="<?= base_url('assets/images/admin') ?>/20_854.svg" alt="Search">
                </button>
                <div class="user-profile">
                    <div class="avatar">A</div>
                    <span class="user-name">Admin</span>
                </div>
            </div>
        </header>
    </section>
    <?php
        $path = (string) service('uri')->getPath();
        $isDashboard = strpos($path, 'admin/dashboard') === 0;
        $isSports = strpos($path, 'admin/sports') === 0;
    ?>

    <section id="section-dashboard" class="app-wrapper">
        <div class="dashboard-layout">
            <aside class="sidebar">
                <nav class="sidebar-nav">
                    <a href="<?= site_url('admin/dashboard') ?>" class="nav-item<?= $isDashboard ? ' active' : '' ?>">
                        <img src="<?= base_url('assets/images/admin') ?>/20_531.svg" alt="Dashboard">
                        <span>Dashboard</span>
                    </a>
                    <a href="<?= site_url('admin/dashboard') ?>" class="nav-item<?= $isDashboard ? ' active' : '' ?>">
                        <img src="<?= base_url('assets/images/admin') ?>/20_539.svg" alt="Régimes">
                        <span>Régimes</span>
                    </a>
                    <a href="<?= site_url('admin/sports/index') ?>" class="nav-item<?= $isSports ? ' active' : '' ?>">
                        <img src="<?= base_url('assets/images/admin') ?>/20_546.svg" alt="Sports">
                        <span>Sports</span>
                    </a>
                    <a href="#" class="nav-item">
                        <img src="<?= base_url('assets/images/admin') ?>/20_555.svg" alt="Paramètres">
                        <span>Paramètres</span>
                    </a>
                </nav>
                <div class="sidebar-footer">
                    <a href="<?= site_url('logout') ?>" class="nav-item logout-item">
                        <img src="<?= base_url('assets/images/admin') ?>/20_562.svg" alt="Déconnexion">
                        <span>Déconnexion</span>
                    </a>
                </div>
            </aside>

            <?= $this->renderSection('content') ?>
        </div>
    </section>

</body>

</html>