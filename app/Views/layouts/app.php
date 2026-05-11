<?php
/**
 * Layout global.
 *
 * Sections:
 *  - title
 *  - page_css (liens CSS spécifiques page)
 *  - head_extra (meta/links additionnels)
 *  - content
 *  - page_js (scripts spécifiques page)
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

    <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <?= $this->renderSection('page_css') ?>
    <?= $this->renderSection('head_extra') ?>
</head>

<body>
    <?= $this->renderSection('content') ?>

    <?= $this->renderSection('page_js') ?>
</body>

</html>