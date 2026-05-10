<?php

/** @var array $client */
/** @var float $imc */
/** @var array $objectif */
/** @var bool $isGold */
/** @var float $argent */

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard - Vary'Ena</title>

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

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .dashboard-header {
            color: white;
            margin-bottom: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dashboard-header h1 {
            font-size: 32px;
            margin: 0;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card h2 {
            font-size: 14px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .card-value {
            font-size: 36px;
            font-weight: 700;
            color: #663366;
            margin-bottom: 10px;
        }

        .card-label {
            color: #666;
            font-size: 14px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-gold {
            background: #ffd700;
            color: #333;
        }

        .status-regular {
            background: #e0e0e0;
            color: #333;
        }

        .gold-badge {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            color: #333;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .welcome-message {
            color: white;
            font-size: 18px;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="dashboard-header">
            <div>
                <h1>Bienvenue, <?= esc((string) ($client['email'] ?? 'User')) ?></h1>
                <p class="welcome-message">Votre tableau de bord personnel Vary'Ena</p>
            </div>
            <a href="/logout" class="logout-btn">Déconnexion</a>
        </div>

        <!-- Stats Grid -->
        <div class="dashboard-grid">
            <!-- IMC Card -->
            <div class="card">
                <h2>Votre IMC</h2>
                <div class="card-value"><?= number_format($imc, 1) ?></div>
                <div class="card-label">
                    <?php
                    if ($imc < 18.5) {
                        echo 'Poids insuffisant';
                    } elseif ($imc < 25) {
                        echo 'Poids normal';
                    } elseif ($imc < 30) {
                        echo 'Surpoids';
                    } else {
                        echo 'Obésité';
                    }
                    ?>
                </div>
            </div>

            <!-- Weight Card -->
            <div class="card">
                <h2>Poids Actuel</h2>
                <div class="card-value"><?= (int) $client['poids'] ?></div>
                <div class="card-label">kg</div>
            </div>

            <!-- Height Card -->
            <div class="card">
                <h2>Taille</h2>
                <div class="card-value"><?= (int) $client['taille'] ?></div>
                <div class="card-label">cm</div>
            </div>

            <!-- Account Status Card -->
            <div class="card">
                <h2>Statut du Compte</h2>
                <div class="card-value">
                    <span class="status-badge <?= $isGold ? 'status-gold' : 'status-regular' ?>">
                        <?= $isGold ? 'GOLD' : 'STANDARD' ?>
                    </span>
                </div>
                <div class="card-label">
                    <?= $isGold ? 'Profitez des avantages Premium' : 'Passez à Gold pour plus d\'avantages' ?>
                </div>
            </div>

            <!-- Wallet Card -->
            <div class="card">
                <h2>Mon Porte-monnaie</h2>
                <div class="card-value"><?= number_format($argent, 2) ?></div>
                <div class="card-label">Ar</div>
            </div>

            <!-- Goals Card -->
            <div class="card">
                <h2>Mes Objectifs</h2>
                <div class="card-value">
                    <?php
                    if ($objectif) {
                        echo '1+';
                    } else {
                        echo '0';
                    }
                    ?>
                </div>
                <div class="card-label">Objectifs sélectionnés</div>
            </div>
        </div>

        <!-- Additional Info -->
        <?php if ($isGold): ?>
            <div class="gold-badge">
                ⭐ Vous êtes un membre GOLD - Profitez de réductions exclusives!
            </div>
        <?php endif; ?>

        <!-- Placeholder for future sections -->
        <div class="card">
            <h2>Prochaines Étapes</h2>
            <p>
                Bienvenue dans Vary'Ena! Voici ce que vous pouvez faire ensuite:
            </p>
            <ul>
                <li>👀 Explorez nos programmes de nutrition et de sport</li>
                <li>💪 Suivez vos progrès et objectifs</li>
                <li>🏆 Déverrouille les succès et les badges</li>
                <li>💰 Gérez votre porte-monnaie et vos codes promo</li>
            </ul>
            <a href="/" class="btn btn-gold btn-large">Retourner à l'accueil</a>

        </div>

    </div>
</body>

</html>