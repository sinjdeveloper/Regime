<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>
Régimes
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= base_url('assets/js/admin/chart.js') ?>" defer></script>

<main class="main-content">
    <div style="width: 400px; height: 400px;">
        <canvas id="repartitionChart"></canvas>
    </div>
    <div style="width: 400px; height: 400px;">
        <canvas id="goldChart"></canvas>
    </div>

</main>

<?= $this->endSection() ?>