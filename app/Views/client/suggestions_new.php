<?php
/** @var array $suggestions */
/** @var bool $isGold */
/** @var float $argent */
/** @var array $client */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suggestions Personnalisées - Vary'Ena</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <style>
        #suggestions-intro {
            background-color: var(--primary);
            padding: 48px 0 64px;
            color: var(--text-light);
            margin-bottom: 48px;
        }

        .suggestions-intro-inner {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .suggestions-title {
            font-size: 36px;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }

        .suggestions-subtitle {
            font-size: 16px;
            color: var(--text-muted);
            margin: 0;
        }

        .suggestions-status {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            margin-top: 16px;
        }

        .status-badge {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--text-light);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 10px 18px;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0px 1px 2px -1px rgba(0, 0, 0, 0.1);
        }

        .status-badge.gold {
            background-color: var(--accent);
            color: var(--text-dark);
            border-color: var(--accent);
        }

        .status-badge strong {
            color: var(--text-light);
        }

        .status-badge.gold strong {
            color: var(--text-dark);
        }

        #section-suggestions {
            padding-top: 0;
            padding-bottom: 80px;
        }

        .program-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 24px;
        }

        .card {
            background-color: var(--text-light);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0px 2px 4px -2px rgba(0, 0, 0, 0.1), 0px 4px 6px -1px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0px 8px 10px -6px rgba(0, 0, 0, 0.1), 0px 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .card.gold-active {
            border: 2px solid var(--accent);
        }

        .card-content {
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .card-title {
            font-size: 20px;
            font-weight: 600;
            margin: 0 0 12px 0;
            color: var(--text-dark);
        }

        .card-desc {
            font-size: 14px;
            color: var(--text-gray);
            margin: 0 0 20px 0;
            line-height: 1.5;
            flex-grow: 1;
        }

        .nutrition-label {
            font-size: 12px;
            text-transform: uppercase;
            color: var(--text-gray);
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .macros {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .macro {
            display: flex;
            flex-direction: column;
            flex: 1;
            align-items: center;
            text-align: center;
        }

        .macro-val {
            color: var(--accent);
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 4px;
        }

        .macro-label {
            color: var(--text-gray);
            font-size: 12px;
            font-weight: 500;
        }

        .price-section {
            background-color: #f8fafc;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
            border: 1px solid var(--border);
        }

        .price-label {
            font-size: 12px;
            text-transform: uppercase;
            color: var(--text-gray);
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .price-display {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .price-original {
            font-size: 16px;
            color: #999;
            text-decoration: line-through;
        }

        .price-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
        }

        .price-currency {
            font-size: 14px;
            color: var(--text-gray);
            font-weight: 600;
        }

        .discount-badge {
            background-color: var(--accent);
            color: var(--text-dark);
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            display: inline-block;
        }

        .sport-info {
            background-color: #f0f4ff;
            border-left: 4px solid var(--secondary);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 16px;
            font-size: 13px;
            color: var(--text-gray);
        }

        .sport-info strong {
            color: var(--primary);
            font-weight: 600;
        }

        .duration {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-gray);
            font-size: 14px;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }

        .btn-card {
            background-color: var(--secondary);
            color: var(--text-light);
            border-radius: 10px;
            padding: 12px;
            width: 100%;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            box-sizing: border-box;
            font-weight: 500;
            transition: opacity 0.2s ease;
        }

        .btn-card:hover {
            opacity: 0.9;
        }

        .no-suggestions {
            text-align: center;
            padding: 80px 24px;
            color: var(--text-gray);
        }

        .no-suggestions-icon {
            font-size: 64px;
            margin-bottom: 24px;
        }

        .no-suggestions h2 {
            font-size: 28px;
            margin: 0 0 12px 0;
            color: var(--text-dark);
            font-weight: 700;
        }

        .no-suggestions p {
            font-size: 16px;
            margin: 0 0 24px 0;
        }

        @media (max-width: 768px) {
            .program-grid {
                grid-template-columns: 1fr;
            }

            .suggestions-title {
                font-size: 28px;
            }

            .suggestions-status {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <section id="section-header">
        <header class="site-header">
            <div class="container header-inner">
                <a href="<?= base_url('/') ?>" class="logo">
                    <span class="logo-text">Vary<span class="highlight">'</span>Ena</span>
                </a>
                <nav class="main-nav">
                    <a href="<?= base_url('/dashboard') ?>">Dashboard</a>
                    <a href="<?= base_url('/suivi') ?>">Suivi</a>
                    <a href="<?= base_url('/wallet') ?>">Wallet</a>
                </nav>
                <div class="header-actions">
                    <a href="<?= base_url('/gold') ?>" class="btn btn-gold">Offre Gold</a>
                    <a href="<?= base_url('/logout') ?>" class="btn btn-icon">👤</a>
                </div>
            </div>
        </header>
    </section>

    <!-- Suggestions Intro -->
    <section id="suggestions-intro">
        <div class="container suggestions-intro-inner">
            <h1 class="suggestions-title">Vos Suggestions Personnalisées</h1>
            <p class="suggestions-subtitle">Programmes adaptés à vos objectifs et votre profil santé</p>
            <div class="suggestions-status">
                <div class="status-badge">
                    💪 Poids actuel: <strong><?= (int)$client['poids'] ?>kg</strong>
                </div>
                <div class="status-badge">
                    📏 Taille: <strong><?= (int)$client['taille'] ?>cm</strong>
                </div>
                <?php if ($isGold): ?>
                    <div class="status-badge gold">
                        ⭐ Membre GOLD - 15% de réduction
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Suggestions Grid -->
    <section id="section-suggestions">
        <div class="container">
            <?php if (!empty($suggestions)): ?>
                <div class="program-grid">
                    <?php foreach ($suggestions as $suggestion): ?>
                        <article class="card <?= $isGold ? 'gold-active' : '' ?>">
                            <div class="card-content">
                                <!-- Title & Description -->
                                <h3 class="card-title"><?= esc($suggestion['regime']['libelle']) ?></h3>
                                <p class="card-desc"><?= esc($suggestion['regime']['description']) ?></p>

                                <!-- Nutrition -->
                                <div class="nutrition-label">Composition</div>
                                <div class="macros">
                                    <div class="macro">
                                        <span class="macro-val"><?= (int)$suggestion['regime']['pourcentage_viande'] ?>%</span>
                                        <span class="macro-label">Viande</span>
                                    </div>
                                    <div class="macro">
                                        <span class="macro-val"><?= (int)$suggestion['regime']['pourcentage_poisson'] ?>%</span>
                                        <span class="macro-label">Poisson</span>
                                    </div>
                                    <div class="macro">
                                        <span class="macro-val"><?= (int)$suggestion['regime']['pourcentage_volaille'] ?>%</span>
                                        <span class="macro-label">Volaille</span>
                                    </div>
                                </div>

                                <!-- Price -->
                                <div class="price-section">
                                    <div class="price-label">Tarif</div>
                                    <div class="price-display">
                                        <?php if ($suggestion['regime']['reduction_appliquee'] > 0): ?>
                                            <span class="price-original">
                                                <?= number_format($suggestion['regime']['prix_original'], 2) ?>€
                                            </span>
                                            <span class="discount-badge">
                                                -<?= (int)$suggestion['regime']['reduction_appliquee'] ?>%
                                            </span>
                                        <?php endif; ?>
                                        <span class="price-value">
                                            <?= number_format($suggestion['regime']['prix_final'], 2) ?>
                                        </span>
                                        <span class="price-currency">€</span>
                                    </div>
                                </div>

                                <!-- Sport -->
                                <?php if (isset($suggestion['sport'])): ?>
                                    <div class="sport-info">
                                        🏃 <strong><?= esc($suggestion['sport']['libelle']) ?></strong><br>
                                        Effet: <strong>+<?= esc($suggestion['sport']['effet']) ?></strong>
                                    </div>
                                <?php endif; ?>

                                <!-- Action Button -->
                                <button class="btn-card" onclick="selectRegime(<?= $suggestion['regime']['id'] ?>)">
                                    ✓ Choisir ce régime
                                </button>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-suggestions">
                    <div class="no-suggestions-icon">📭</div>
                    <h2>Aucune suggestion disponible</h2>
                    <p>Définissez vos objectifs pour recevoir nos recommendations personnalisées</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <section id="section-footer">
        <footer class="site-footer">
            <div class="container footer-inner">
                <p>© 2026 Vary'Ena. Tous droits réservés.</p>
            </div>
        </footer>
    </section>

    <script>
        function selectRegime(regimeId) {
            alert('Régime ' + regimeId + ' sélectionné!');
        }
    </script>
</body>
</html>
