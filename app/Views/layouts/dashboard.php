<?php
/**
 * Layout dédié au nouveau design du dashboard
 *
 * Sections:
 *  - title
 *  - page_css
 *  - content
 *  - page_js
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS global existant -->
    <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <!-- CSS spécifique dashboard design -->
    <?= $this->renderSection('page_css') ?>
</head>
<body>
    <?= $this->renderSection('content') ?>
    <?= $this->renderSection('page_js') ?>
</body>
</html>