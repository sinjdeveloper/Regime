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

        /* Navbar Styles */
        nav.navbar {
            background: linear-gradient(135deg, #663366 0%, #4d2d4d 100%);
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .navbar-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .navbar-logo {
            color: white;
            font-size: 24px;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-logo .highlight {
            color: var(--accent);
        }

        .navbar-center {
            display: flex;
            gap: 32px;
            flex: 1;
            justify-content: center;
        }

        .navbar-center a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: opacity 0.2s;
        }

        .navbar-center a:hover {
            opacity: 0.8;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .btn-gold-offer {
            background-color: var(--accent);
            color: #333;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: opacity 0.2s;
        }

        .btn-gold-offer:hover {
            opacity: 0.9;
        }

        .profile-icon {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .profile-icon:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            padding: 32px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 16px;
            right: 16px;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #999;
        }

        .modal-close:hover {
            color: #333;
        }

        .modal-title {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 24px 0;
            color: var(--text-dark);
        }

        .modal-section {
            margin-bottom: 24px;
        }

        .modal-section label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-gray);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modal-section p {
            margin: 0;
            color: var(--text-dark);
            font-size: 16px;
            font-weight: 500;
        }

        .modal-section input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            margin-top: 32px;
        }

        .modal-actions button {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .btn-secondary {
            background-color: #f0f0f0;
            color: #333;
        }

        .btn-secondary:hover {
            opacity: 0.8;
        }

        .gold-badge {
            display: inline-block;
            background-color: var(--accent);
            color: #333;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .price-gold {
            font-size: 32px;
            color: var(--primary);
            font-weight: 700;
            margin: 8px 0;
        }

        .btn-code {
            background-color: #f0f0f0;
            color: #333;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: opacity 0.2s;
            margin-left: 8px;
        }

        .btn-code:hover {
            opacity: 0.9;
        }

        .modal-input-group {
            display: flex;
            gap: 8px;
            margin-bottom: 16px;
        }

        .modal-input-group input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .modal-input-group button {
            padding: 12px 24px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .modal-input-group button:hover {
            opacity: 0.9;
        }

        .code-message {
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .code-message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .code-message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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

            .navbar-center {
                display: none;
            }

            .modal-input-group {
                flex-direction: column;
            }

            .modal-input-group button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <!-- Logo -->
            <a href="<?= base_url('/') ?>" class="navbar-logo">
                Vary<span class="highlight">'</span>Ena
            </a>

            <!-- Center Links -->
            <div class="navbar-center">
                <a href="<?= base_url('/dashboard') ?>">Programmes</a>
                <a href="<?= base_url('/imc') ?>">Mon IMC</a>
                <a href="<?= base_url('/suivi') ?>">Suivi</a>
            </div>

            <!-- Right Actions -->
            <div class="navbar-right">
                <button class="btn-gold-offer" onclick="openGoldModal()">Offre Gold</button>
                <button class="btn-code" onclick="openCodeModal()">💳 Code</button>
                <div class="profile-icon" onclick="openProfileModal()">👤</div>
            </div>
        </div>
    </nav>

    <!-- Profile Modal -->
    <div id="profileModal" class="modal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeProfileModal()">✕</button>
            <h2 class="modal-title">Mon Profil</h2>

            <div class="modal-section">
                <label>Utilisateur</label>
                <p><?= esc($client['name'] ?? 'Client') ?></p>
            </div>

            <div class="modal-section">
                <label>Email</label>
                <p><?= esc($client['email'] ?? 'N/A') ?></p>
            </div>

            <div class="modal-section">
                <label>Poids Actuel</label>
                <p><?= (int)$client['poids'] ?>kg</p>
            </div>

            <div class="modal-section">
                <label>Taille</label>
                <p><?= (int)$client['taille'] ?>cm</p>
            </div>

            <div class="modal-section">
                <label>Statut</label>
                <p><?= $isGold ? '⭐ Membre GOLD' : 'Membre Standard' ?></p>
            </div>

            <div class="modal-section">
                <label>Porte-monnaie</label>
                <p><?= number_format($argent ?? 0, 2) ?>€</p>
            </div>

            <div class="modal-actions">
                <button class="btn-secondary" onclick="closeProfileModal()">Fermer</button>
                <button class="btn-primary" onclick="window.location.href='<?= base_url('/logout') ?>'">Déconnexion</button>
            </div>
        </div>
    </div>

    <!-- Gold Modal -->
    <div id="goldModal" class="modal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeGoldModal()">✕</button>
            <h2 class="modal-title">Offre GOLD</h2>

            <div class="gold-badge">✨ Devenir Membre GOLD</div>

            <div class="modal-section">
                <label>Avantages GOLD</label>
                <ul style="margin: 8px 0 0 0; padding-left: 20px; color: var(--text-dark);">
                    <li>15% de réduction sur tous les régimes</li>
                    <li>Accès prioritaire aux nouveaux programmes</li>
                    <li>Suivi personnalisé illimité</li>
                    <li>Support client 24/7</li>
                </ul>
            </div>

            <div class="modal-section">
                <label>Prix</label>
                <div class="price-gold"><?= number_format(\App\Controllers\GoldController::getGoldPrice(), 2) ?>€</div>
                <p style="font-size: 14px; color: #999;">Accès illimité pendant 1 mois</p>
            </div>

            <?php if (!$isGold): ?>
                <div class="modal-section">
                    <label>Votre Porte-monnaie</label>
                    <p><?= number_format($argent ?? 0, 2) ?>€</p>
                </div>
            <?php endif; ?>

            <div class="modal-actions">
                <button class="btn-secondary" onclick="closeGoldModal()">Annuler</button>
                <?php if ($isGold): ?>
                    <button class="btn-primary" style="background-color: #999; cursor: not-allowed;" disabled>Déjà GOLD ✓</button>
                <?php else: ?>
                    <button class="btn-primary" onclick="subscribeToGold()">S'abonner Maintenant</button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Code Promo Modal -->
    <div id="codeModal" class="modal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeCodeModal()">✕</button>
            <h2 class="modal-title">Ajouter du Crédit</h2>

            <div id="codeMessage"></div>

            <div class="modal-section">
                <label>Porte-monnaie Actuel</label>
                <p><strong><?= number_format($argent ?? 0, 2) ?>€</strong></p>
            </div>

            <div class="modal-section">
                <label>Code Promo</label>
                <div class="modal-input-group">
                    <input 
                        type="text" 
                        id="codeInput" 
                        placeholder="Entrez votre code promo"
                        onkeypress="if(event.key === 'Enter') validateCode()"
                    >
                    <button onclick="validateCode()">Valider</button>
                </div>
                <p style="font-size: 12px; color: #999; margin: 8px 0 0 0;">
                    📌 Ex: CODE100, CODE50, WELCOME20...
                </p>
            </div>

            <div class="modal-actions">
                <button class="btn-secondary" onclick="closeCodeModal()">Fermer</button>
            </div>
        </div>
    </div>

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
                                                <?= number_format($suggestion['regime']['prix_original'], 2) ?> Ar
                                            </span>
                                            <span class="discount-badge">
                                                -<?= (int)$suggestion['regime']['reduction_appliquee'] ?>%
                                            </span>
                                        <?php endif; ?>
                                        <span class="price-value">
                                            <?= number_format($suggestion['regime']['prix_final'], 2) ?>
                                        </span>
                                        <span class="price-currency">Ar</span>
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
        // Modal Functions
        function openProfileModal() {
            document.getElementById('profileModal').classList.add('active');
        }

        function closeProfileModal() {
            document.getElementById('profileModal').classList.remove('active');
        }

        function openGoldModal() {
            document.getElementById('goldModal').classList.add('active');
        }

        function closeGoldModal() {
            document.getElementById('goldModal').classList.remove('active');
        }

        function openCodeModal() {
            document.getElementById('codeModal').classList.add('active');
            document.getElementById('codeMessage').innerHTML = '';
            document.getElementById('codeInput').value = '';
        }

        function closeCodeModal() {
            document.getElementById('codeModal').classList.remove('active');
        }

        function subscribeToGold() {
            alert('Redirection vers la page de paiement pour l\'abonnement GOLD...');
            // window.location.href = '<?= base_url('/subscribe-gold') ?>';
        }

        function validateCode() {
            const code = document.getElementById('codeInput').value.trim();
            const messageDiv = document.getElementById('codeMessage');

            if (!code) {
                messageDiv.innerHTML = '<div class="code-message error">❌ Veuillez entrer un code promo</div>';
                return;
            }

            // Appel AJAX pour valider le code
            fetch('<?= base_url('/validate-code') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ code: code })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageDiv.innerHTML = `<div class="code-message success">✓ ${data.message}<br><strong>+${data.amount}€ ajoutés!</strong></div>`;
                    document.getElementById('codeInput').value = '';
                    // Rafraîchir la page après 2 secondes
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    messageDiv.innerHTML = `<div class="code-message error">❌ ${data.message}</div>`;
                }
            })
            .catch(error => {
                messageDiv.innerHTML = '<div class="code-message error">❌ Erreur lors de la validation</div>';
                console.error('Error:', error);
            });
        }

        // Close modals when clicking outside
        document.getElementById('profileModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeProfileModal();
            }
        });

        document.getElementById('goldModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeGoldModal();
            }
        });

        document.getElementById('codeModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeCodeModal();
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeProfileModal();
                closeGoldModal();
                closeCodeModal();
            }
        });
    </script>
</body>
</html>
