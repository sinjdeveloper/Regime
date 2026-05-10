<?php
/** @var array $client */
/** @var array $objectifs */
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Suivi - Vary'Ena</title>

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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .pill {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            background: #f2f2f2;
            font-weight: 700;
            color: #333;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container-page">
        <div class="page-header">
            <div>
                <h1>Mon Suivi</h1>
                <div style="opacity:.9;"><?= esc((string)($client['email'] ?? '')) ?></div>
            </div>
            <div style="display:flex; gap:10px;">
                <a class="link-btn" href="<?= site_url('/imc') ?>">IMC</a>
                <a class="link-btn" href="<?= site_url('/dashboard') ?>">Dashboard</a>
                <a class="link-btn" href="<?= site_url('/') ?>">Accueil</a>
            </div>
        </div>

        <div class="card">
            <h2 style="margin:0 0 12px 0;">Mes objectifs</h2>

            <?php if (empty($objectifs)): ?>
                <div>Aucun objectif défini pour le moment.</div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Objectif</th>
                            <th>Poids cible</th>
                            <th>Durée</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($objectifs as $obj): ?>
                            <tr>
                                <td><?= esc((string)($obj['objectif_libelle'] ?? '')) ?></td>
                                <td><span class="pill"><?= esc((string)($obj['poids_cible'] ?? '')) ?> kg</span></td>
                                <td><?= esc((string)($obj['duree'] ?? '')) ?> jours</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
