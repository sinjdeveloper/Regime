<?php
/** @var array $regime */
/** @var bool  $isPurchased */
$imageUrl = '';
if (!empty($regime['image'])) {
    $imageUrl = base_url('assets/images/programs/' . (string) $regime['image']);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc((string) ($regime['libelle'] ?? 'Régime')) ?> - Vary'Ena</title>
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

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 14px;
        }

        .pill {
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

        .locked-overlay {
            position: relative;
        }

        .locked-overlay .lock-msg {
            background: rgba(102, 51, 102, 0.08);
            border: 2px dashed #663366;
            border-radius: 10px;
            padding: 20px;
            margin-top: 14px;
            text-align: center;
            color: #663366;
            font-weight: 600;
        }

        .badge-purchased {
            display: inline-block;
            background: #22c55e;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            margin-left: 10px;
            vertical-align: middle;
        }

        @media (max-width: 800px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container-page">
        <div class="top">
            <div>
                <h1>
                    <?= esc((string) ($regime['libelle'] ?? 'Régime')) ?>
                    <?php if ($isPurchased): ?>
                        <span class="badge-purchased">✓ Acheté</span>
                    <?php endif; ?>
                </h1>
                <div style="opacity:.9;">Programme Nutrition</div>
            </div>
            <div style="display:flex; gap:10px;">
                <?php if ($isPurchased): ?>
                    <a class="link-btn"
                        href="<?= site_url('/programs/regime/' . (int) ($regime['id'] ?? 0) . '/pdf') ?>">Exporter PDF</a>
                <?php endif; ?>
                <a class="link-btn" href="<?= site_url('/suivi') ?>">Suivi</a>
                <a class="link-btn" href="<?= site_url('/') ?>">Accueil</a>
            </div>
        </div>
        <div class="card">
            <div class="hero">
                <?php if ($imageUrl): ?>
                    <img src="<?= esc($imageUrl) ?>" alt="<?= esc((string) ($regime['libelle'] ?? 'Régime')) ?>">
                <?php endif; ?>
            </div>
            <div class="content">
                <?php if ($isPurchased): ?>
                    <!-- Contenu complet : réservé aux acheteurs -->
                    <div style="font-weight:700; color:#333;">Description</div>
                    <div style="margin-top:8px; color:#444;">
                        <?= esc((string) ($regime['description'] ?? '')) ?>
                    </div>
                    <div class="grid">
                        <div class="pill">
                            <div class="label">Variation de poids</div>
                            <div class="value"><?= esc((string) ($regime['variation_poids'] ?? '')) ?> kg</div>
                        </div>
                        <div class="pill">
                            <div class="label">Prix payé</div>
                            <div class="value" id="regime-price"><?= esc((string) ($regime['prix'] ?? '')) ?> Ar</div>
                        </div>
                        <div class="pill">
                            <div class="label">Répartition (viande / poisson / volaille)</div>
                            <div class="value">
                                <?= esc((string) ($regime['pourcentage_viande'] ?? '')) ?>%
                                / <?= esc((string) ($regime['pourcentage_poisson'] ?? '')) ?>%
                                / <?= esc((string) ($regime['pourcentage_volaille'] ?? '')) ?>%
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Aperçu limité : visible avant achat -->
                    <div class="locked-overlay">
                        <div style="font-weight:700; color:#333; margin-bottom:6px;">Aperçu du régime</div>
                        <div style="color:#444;">
                            Achetez ce régime pour accéder à la description complète, la répartition
                            alimentaire (viande / poisson / volaille) et la variation de poids prévue.
                        </div>
                        <div class="lock-msg">
                            <span role="img" aria-label="Contenu verrouillé">🔒</span> Contenu réservé aux acheteurs
                        </div>
                        <div style="margin-top:16px; display:flex; align-items:center; gap:12px;">
                            <span style="font-size:22px; font-weight:800; color:#663366;">
                                <?= esc((string) ($regime['prix'] ?? '')) ?> Ar
                            </span>
                            <span style="font-size:13px; color:#888;">(réduction 15% avec option Gold)</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div style="padding:18px;">
                <?php if ($isPurchased): ?>
                    <div
                        style="background:#f0fdf4; border:1px solid #86efac; border-radius:8px; padding:12px; color:#166534; font-weight:600;">
                        ✓ Vous avez déjà acheté ce régime. Profitez de votre programme !
                    </div>
                <?php else: ?>
                    <button id="btn-buy-regime" class="btn-action" data-id="<?= (int) ($regime['id'] ?? 0) ?>"
                        style="background:#663366;color:#fff;border:0;padding:10px 14px;border-radius:8px;font-weight:800;">
                        Acheter ce régime
                    </button>
                    <div id="buy-msg" style="margin-top:10px; display:none;
                        padding:10px; border-radius:8px;
                        background:#f7f7f7;">
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if (!$isPurchased): ?>
        <script>
            (function () {
                const btn = document.getElementById('btn-buy-regime');
                const msg = document.getElementById('buy-msg');
                if (!btn) return;
                btn.addEventListener('click', async function () {
                    const id = btn.getAttribute('data-id');
                    btn.disabled = true;
                    msg.style.display = 'block';
                    msg.textContent = 'Traitement...';
                    try {
                        const res = await fetch('<?= site_url('/regime/buy') ?>', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: 'regime_id=' + encodeURIComponent(id)
                        });
                        const data = await res.json().catch(() => null);
                        if (!res.ok || !data) {
                            msg.textContent = 'Erreur lors de l\'achat.';
                            btn.disabled = false;
                            return;
                        }
                        if (data.success) {
                            msg.textContent = data.message || 'Achat réussi';
                            setTimeout(() => location.reload(), 800);
                        } else {
                            msg.textContent = data.message || 'Achat échoué';
                            btn.disabled = false;
                        }
                    } catch (e) {
                        msg.textContent = 'Erreur réseau';
                        btn.disabled = false;
                    }
                });
            })();
        </script>
    <?php endif; ?>
</body>

</html>