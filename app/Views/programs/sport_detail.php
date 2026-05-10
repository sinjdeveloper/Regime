<?php
/** @var array $sport */

$imageUrl = '';
if (!empty($sport['image'])) {
    $imageUrl = base_url('assets/images/programs/' . (string) $sport['image']);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc((string)($sport['libelle'] ?? 'Sport')) ?> - Vary'Ena</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <style>
        body {
            background: linear-gradient(135deg, #663366 0%, #333333 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container-page {
            max-width: 1000px;
            margin: 0 auto;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            color: white;
            margin-bottom: 20px;
        }

        .top h1 {
            margin: 0;
            font-size: 28px;
        }

        .link-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid white;
            padding: 10px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
        }

        .card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .hero {
            height: 280px;
            background: #eee;
        }

        .hero img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .content {
            padding: 18px;
        }

        .pill {
            margin-top: 14px;
            background: #f7f7f7;
            border-radius: 10px;
            padding: 12px;
        }

        .pill .label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .pill .value {
            margin-top: 6px;
            font-size: 18px;
            font-weight: 800;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="container-page">
        <div class="top">
            <div>
                <h1><?= esc((string)($sport['libelle'] ?? 'Sport')) ?></h1>
                <div style="opacity:.9;">Programme Sport</div>
            </div>
            <div style="display:flex; gap:10px;">
                <a class="link-btn" href="<?= site_url('/suivi') ?>">Suivi</a>
                <a class="link-btn" href="<?= site_url('/') ?>">Accueil</a>
            </div>
        </div>

        <div class="card">
            <div class="hero">
                <?php if ($imageUrl): ?>
                    <img src="<?= esc($imageUrl) ?>" alt="<?= esc((string)($sport['libelle'] ?? 'Sport')) ?>">
                <?php endif; ?>
            </div>

            <div class="content">
                <div style="font-weight:700; color:#333;">Réduction</div>
                <div class="pill">
                    <div class="label">Pourcentage</div>
                    <div class="value"><?= esc((string)($sport['pourcentage_reduction'] ?? '')) ?> %</div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
