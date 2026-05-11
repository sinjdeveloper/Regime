<?php
/** @var array $regime */
/** @var string $generatedAt */
/** @var string $imageDataUri */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Export PDF - <?= esc((string)($regime['libelle'] ?? 'Régime')) ?></title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #222; font-size: 12px; }
        .header { margin-bottom: 18px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
        .title { font-size: 22px; font-weight: 700; margin: 0; color: #663366; }
        .meta { margin-top: 5px; color: #666; font-size: 11px; }
        .hero { margin: 14px 0; text-align: center; }
        .hero img { max-width: 100%; max-height: 220px; border-radius: 8px; }
        .section-title { font-size: 14px; font-weight: 700; margin-top: 14px; margin-bottom: 6px; color: #333; }
        .description { line-height: 1.6; white-space: pre-line; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f7f2f9; color: #663366; }
        .footer { margin-top: 18px; color: #888; font-size: 10px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title"><?= esc((string)($regime['libelle'] ?? 'Régime')) ?></h1>
        <div class="meta">Document généré le <?= esc($generatedAt ?? '') ?></div>
    </div>

    <?php if (!empty($imageDataUri)): ?>
        <div class="hero">
            <img src="<?= $imageDataUri ?>" alt="<?= esc((string)($regime['libelle'] ?? 'Régime')) ?>">
        </div>
    <?php endif; ?>

    <div class="section-title">Description</div>
    <div class="description"><?= esc((string)($regime['description'] ?? '')) ?></div>

    <div class="section-title">Détails du régime</div>
    <table>
        <tr>
            <th>Variation de poids</th>
            <td><?= esc((string)($regime['variation_poids'] ?? '')) ?> kg</td>
        </tr>
        <tr>
            <th>Prix</th>
            <td><?= esc((string)($regime['prix'] ?? '')) ?> Ar</td>
        </tr>
        <tr>
            <th>Répartition</th>
            <td>
                Viande: <?= esc((string)($regime['pourcentage_viande'] ?? '')) ?>% |
                Poisson: <?= esc((string)($regime['pourcentage_poisson'] ?? '')) ?>% |
                Volaille: <?= esc((string)($regime['pourcentage_volaille'] ?? '')) ?>%
            </td>
        </tr>
    </table>

    <div class="footer">Vary'Ena - Export programme nutrition</div>
</body>
</html>