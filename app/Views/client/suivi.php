<?php
/** @var array $client */
/** @var array $objectifs */
?>

<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Mon Suivi - Vary'Ena<?= $this->endSection() ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/suivi.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
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
<?= $this->endSection() ?>