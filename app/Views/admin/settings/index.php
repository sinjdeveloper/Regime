<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>
Paramètres
<?= $this->endSection() ?>

<?= $this->section('content') ?>
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

    <div class="page-header">
        <div class="page-title-group">
            <h1 class="page-title">Paramètres</h1>
            <p class="page-subtitle">Configurer les paramètres de l'application</p>
        </div>
    </div>

    <div class="table-section">
        <div class="table-container" style="padding: 16px;">
            <form action="<?= site_url('admin/settings') ?>" method="post" style="max-width: 520px;">
                <?= csrf_field() ?>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label for="gold_price" style="display:block; font-weight: 700; margin-bottom: 6px;">Prix abonnement Gold</label>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        id="gold_price"
                        name="gold_price"
                        value="<?= esc(old('gold_price') ?? ($settings['gold_price'] ?? '')) ?>"
                        style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.15);"
                    />
                    <div style="margin-top: 6px; font-size: 12px; opacity: .8;">Valeur utilisée pour l'activation Gold.</div>
                </div>

                <button type="submit" class="btn-submit" style="padding: 10px 14px; border-radius: 8px; border: 0; font-weight: 800; cursor: pointer;">
                    Enregistrer
                </button>
            </form>
        </div>
    </div>
</main>
<?= $this->endSection() ?>
