<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>
Codes
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= base_url('assets/js/admin/bar_chart.js') ?>" defer></script>
<main class="main-content">

    <?php $success = session('success'); ?>
    <?php $errors = session('errors'); ?>

    <?php if (!empty($success)): ?>
        <div style="margin: 0 0 16px 0; padding: 10px 12px; border: 1px solid #badbcc; background: #d1e7dd; color: #0f5132; border-radius: 8px;">
            <?= esc($success) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div style="margin: 0 0 16px 0; padding: 10px 12px; border: 1px solid #f5c2c7; background: #f8d7da; color: #842029; border-radius: 8px;">
            <?php if (is_array($errors)): ?>
                <?php foreach ($errors as $error): ?>
                    <div><?= esc($error) ?></div>
                <?php endforeach; ?>
            <?php else: ?>
                <?= esc($errors) ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title-group">
            <h1 class="page-title">Codes en Attente de Validation</h1>
            <p class="page-subtitle">Gérez vos programmes de nutrition et de sport</p>
        </div>
    </div>
    <!-- Table Section -->
    <div class="table-section">
  

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>CLIENT</th>
                        <th>CODE</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($codes as $code) { ?>
                        
                    <?= $code['id']?><tr>
                            <td>
                                <div class="diet-info">
                                    <div class="diet-details">
                                        <div class="diet-name"><?= $code['email'] ?></div>
                                        <div class="diet-date">Créé le 2026-04-15</div>
                                    </div>
                                </div>
                            </td>
                            <td class="variation"><?= $code['token'] ?></td>
                            <td>
                                <div class="actions">

                                    <form action="<?= site_url('admin/codes/validate/' . $code['id']) ?>" method="post">
                                        <button class="action-btn" type="submit"><img
                                                src="<?= base_url('assets/images/admin') ?>/check.svg"
                                                alt="Validate"></button>
                                    </form>
                                    <form action="<?= site_url('admin/codes/delete/' . $code['id']) ?>" method="post">
                                        <button class="action-btn" type="submit"><img
                                                src="<?= base_url('assets/images/admin') ?>/20_669.svg"
                                                alt="Delete"></button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</main>


<?= $this->endSection() ?>