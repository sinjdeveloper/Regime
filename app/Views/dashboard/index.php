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

</main>

<?= $this->endSection() ?>