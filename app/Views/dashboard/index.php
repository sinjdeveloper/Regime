<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>
Régimes
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= base_url('assets/js/admin/chart.js') ?>" defer></script>

<main class="main-content">
    <div class="charts-row">
        <div class="chart-box">
            <canvas id="repartitionChart"></canvas>
        </div>
        <div class="chart-box">
            <canvas id="goldChart"></canvas>
        </div>
    </div>
    <div class="table-section">
        <div class="table-toolbar">
            <div class="filters">
                <button class="filter-btn active">Régime × Objectif</button>
            </div>
        </div>

        <div class="table-container">
            <?php if (empty($objectifs)): ?>
                <div style="padding: 16px;">Aucun objectif trouvé.</div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>RÉGIME</th>
                            <?php foreach ($objectifs as $objectif): ?>
                                <th><?= esc($objectif['libelle']) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($regimes as $regime): ?>
                            <tr>
                                <td><?= esc($regime['libelle']) ?></td>
                                <?php foreach ($objectifs as $objectif): ?>
                                    <?php
                                    $regimeId = (int) ($regime['id'] ?? 0);
                                    $objectifId = (int) ($objectif['id'] ?? 0);
                                    $count = 0;
                                    if ($regimeId && $objectifId && !empty($regimeObjectifCounts[$regimeId][$objectifId])) {
                                        $count = (int) $regimeObjectifCounts[$regimeId][$objectifId];
                                    }
                                    ?>
                                    <td><?= $count ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    </div>

</main>

<?= $this->endSection() ?>