<?php
/** @var array $client */
/** @var bool $isGold */

$argent = (float)($client['argent'] ?? 0);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offre Gold - Vary'Ena</title>

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

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 800;
            font-size: 12px;
        }

        .badge-gold {
            background: #ffd700;
            color: #333;
        }

        .badge-standard {
            background: #eee;
            color: #333;
        }

        .btn-action {
            display: inline-block;
            margin-top: 14px;
            background: #663366;
            color: white;
            padding: 12px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 800;
            border: none;
            cursor: pointer;
        }

        .msg {
            margin-top: 12px;
            padding: 10px 12px;
            border-radius: 8px;
            background: #f7f7f7;
            display: none;
        }
    </style>
</head>

<body>
    <div class="container-page">
        <div class="page-header">
            <div>
                <h1>Offre Gold</h1>
                <div style="opacity:.9;">Solde: <?= number_format($argent, 2) ?> Ar</div>
            </div>
            <div style="display:flex; gap:10px;">
                <a class="link-btn" href="<?= site_url('/profile') ?>">Profil</a>
                <a class="link-btn" href="<?= site_url('/dashboard') ?>">Dashboard</a>
                <a class="link-btn" href="<?= site_url('/') ?>">Accueil</a>
            </div>
        </div>

        <div class="card">
            <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
                <div>
                    <div style="font-size:14px; color:#666; text-transform:uppercase; letter-spacing:.5px;">Statut</div>
                    <div style="margin-top:8px;">
                        <span class="badge <?= $isGold ? 'badge-gold' : 'badge-standard' ?>">
                            <?= $isGold ? 'GOLD' : 'STANDARD' ?>
                        </span>
                    </div>
                </div>
                <div style="text-align:right; color:#333; font-weight:700;">Réduction Gold: 15%</div>
            </div>

            <div style="margin-top:14px; color:#333;">
                <div style="font-weight:800;">Avantages</div>
                <ul style="margin:8px 0 0 18px;">
                    <li>Réduction automatique sur les suggestions</li>
                    <li>Accès Premium</li>
                </ul>
            </div>

            <?php if ($isGold): ?>
                <div style="margin-top:14px; font-weight:800; color:#333;">Votre abonnement Gold est actif.</div>
            <?php else: ?>
                <button class="btn-action" id="btn-subscribe-gold" type="button">Activer Gold</button>
                <div class="msg" id="gold-msg"></div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        (function () {
            const btn = document.getElementById('btn-subscribe-gold');
            const msg = document.getElementById('gold-msg');
            if (!btn || !msg) return;

            btn.addEventListener('click', async function () {
                btn.disabled = true;
                msg.style.display = 'block';
                msg.textContent = 'Traitement...';

                try {
                    const res = await fetch('<?= site_url('api/gold/subscribe') ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({})
                    });
                    const data = await res.json().catch(() => null);

                    if (!res.ok || !data) {
                        msg.textContent = 'Erreur pendant l\'activation.';
                        btn.disabled = false;
                        return;
                    }

                    msg.textContent = data.message || 'Terminé.';
                    if (data.success) {
                        setTimeout(() => window.location.reload(), 800);
                    } else {
                        btn.disabled = false;
                    }
                } catch (e) {
                    msg.textContent = 'Erreur réseau.';
                    btn.disabled = false;
                }
            });
        })();
    </script>
</body>

</html>
