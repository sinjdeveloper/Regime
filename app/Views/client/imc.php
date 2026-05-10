<?php
/** @var array $client */
/** @var float $imc */

$interpretation = 'Poids normal';
if ($imc < 18.5) {
    $interpretation = 'Poids insuffisant';
} elseif ($imc < 25) {
    $interpretation = 'Poids normal';
} elseif ($imc < 30) {
    $interpretation = 'Surpoids';
} else {
    $interpretation = 'Obésité';
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon IMC - Vary'Ena</title>

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
            max-width: 900px;
            margin: 0 auto;
        }

        .page-header {
            color: white;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .page-header h1 {
            font-size: 28px;
            margin: 0;
        }

        .nav-actions {
            display: flex;
            gap: 10px;
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
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .metric {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-top: 14px;
        }

        .metric .item {
            background: #f7f7f7;
            border-radius: 8px;
            padding: 14px;
        }

        .metric .label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .metric .value {
            font-size: 22px;
            font-weight: 700;
            color: #663366;
            margin-top: 6px;
        }
    </style>
</head>

<body>
    <div class="container-page">
        <div class="page-header">
            <div>
                <h1>Mon IMC</h1>
                <div style="opacity:.9;"><?= esc((string)($client['email'] ?? '')) ?></div>
            </div>
            <div class="nav-actions">
                <a class="link-btn" href="<?= site_url('/suivi') ?>">Suivi</a>
                <a class="link-btn" href="<?= site_url('/dashboard') ?>">Dashboard</a>
                <a class="link-btn" href="<?= site_url('/') ?>">Accueil</a>
            </div>
        </div>

        <div class="card">
            <h2 style="margin:0 0 8px 0;">Indice de Masse Corporelle</h2>
            <div style="font-size:40px;font-weight:800;color:#663366;line-height:1;">
                <?= number_format((float)$imc, 1) ?>
            </div>
            <div style="margin-top:8px;color:#333;font-weight:700;"><?= esc($interpretation) ?></div>

            <div class="metric">
                <div class="item">
                    <div class="label">Poids</div>
                    <div class="value"><?= esc((string)($client['poids'] ?? '')) ?> kg</div>
                </div>
                <div class="item">
                    <div class="label">Taille</div>
                    <div class="value"><?= esc((string)($client['taille'] ?? '')) ?> cm</div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
