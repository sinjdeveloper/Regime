<?php
/** @var array $client */
/** @var bool $isGold */

$argent = (float) ($client['argent'] ?? 0);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation de code - Vary'Ena</title>

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

        .btn-action:disabled {
            opacity: .6;
            cursor: not-allowed;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-top: 10px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .msg {
            margin-top: 12px;
            padding: 10px 12px;
            border-radius: 8px;
            background: #f7f7f7;
            display: none;
        }

        .msg.success {
            background: #eafaf1;
            color: #1e7e34;
            border: 1px solid #c3e6cb;
        }

        .msg.error {
            background: #fdeaea;
            color: #b02a37;
            border: 1px solid #f5c2c7;
        }

        .info-box {
            margin-top: 16px;
            padding: 14px;
            border-radius: 8px;
            background: #f8f9fa;
            color: #555;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <div class="container-page">
        <div class="page-header">
            <div>
                <h1>Validation de code</h1>
                <div style="opacity:.9;">Solde : <?= number_format($argent, 2, ',', ' ') ?> Ar</div>
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
                    <div style="font-size:14px; color:#666; text-transform:uppercase; letter-spacing:.5px;">
                        Statut
                    </div>
                    <div style="margin-top:8px;">
                        <span class="badge <?= $isGold ? 'badge-gold' : 'badge-standard' ?>">
                            <?= $isGold ? 'GOLD' : 'STANDARD' ?>
                        </span>
                    </div>
                </div>
            </div>

            <div style="margin-top:20px;">
                <label for="code" style="font-weight:600;">
                    Entrez votre code de recharge
                </label>

                <input
                    type="text"
                    id="code"
                    class="form-input"
                    placeholder="Ex : ABC123XYZ">

                <button
                    class="btn-action"
                    id="btn-redeem-code"
                    type="button">
                    Envoyer pour validation
                </button>

                <div id="redeem-msg" class="msg"></div>
            </div>

            <div class="info-box">
                Lorsque vous soumettez un code, celui-ci est envoyé à l’administrateur
                pour validation. Votre solde sera crédité après approbation.
                Un petit détour bureaucratique, parce que même les pièces virtuelles
                aiment les tampons administratifs.
            </div>
        </div>
    </div>

    <script>
        (function () {
            const btn = document.getElementById('btn-redeem-code');
            const input = document.getElementById('code');
            const msg = document.getElementById('redeem-msg');

            if (!btn || !input || !msg) {
                return;
            }

            async function submitCode() {
                const code = input.value.trim();

                msg.className = 'msg';
                msg.style.display = 'block';

                if (!code) {
                    msg.classList.add('error');
                    msg.textContent = 'Veuillez saisir un code.';
                    return;
                }

                btn.disabled = true;
                msg.textContent = 'Envoi de la demande...';

                try {
                    const response = await fetch('<?= site_url('api/redeem-code') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            code: code
                        })
                    });

                    const data = await response.json().catch(() => null);

                    if (!data) {
                        msg.classList.add('error');
                        msg.textContent = 'Réponse invalide du serveur.';
                        btn.disabled = false;
                        return;
                    }

                    if (data.success) {
                        msg.classList.add('success');
                        msg.textContent = data.message || 'Demande envoyée avec succès.';
                        input.value = '';
                    } else {
                        msg.classList.add('error');
                        msg.textContent = data.message || 'Une erreur est survenue.';
                    }

                } catch (e) {
                    msg.classList.add('error');
                    msg.textContent = 'Erreur réseau.';
                } finally {
                    btn.disabled = false;
                }
            }

            btn.addEventListener('click', submitCode);

            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    submitCode();
                }
            });
        })();
    </script>
</body>

</html>