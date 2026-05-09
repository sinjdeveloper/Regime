<link rel="stylesheet" href="<?= base_url('assets/css/regime-popup.css') ?>">
<div class="modal-overlay">
    <!-- Modal Container -->
    <div class="modal-container">
        <!-- Header -->
        <div class="modal-header">
            <div class="header-content">
                <div class="header-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M5 12H19" stroke="white" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M12 5V19" stroke="white" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
                <h2>Ajouter une recette</h2>
            </div>
            <button class="close-btn">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M18 6L6 18" stroke="white" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M6 6L18 18" stroke="white" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
        </div>

        <div class="modal-body">
            <form id="regime-form" class="recipe-form" action="<?= site_url('admin/regimes/store') ?>" method="post">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label">Nom de la recette</label>
                    <input type="text" class="form-input <?= session('errors.libelle') ? 'is-invalid' : '' ?>"
                        placeholder="Ex: Poulet grillé aux légumes" name="libelle" value="<?= old('libelle') ?>">

                    <?php if (session('errors.libelle')): ?>
                        <small class="text-danger"><?= session('errors.libelle') ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Description de la recette</label>
                    <input type="text" class="form-input <?= session('errors.description') ? 'is-invalid' : '' ?>"
                        placeholder="Description" name="description" value="<?= old('description') ?>">

                    <?php if (session('errors.description')): ?>
                        <small class="text-danger"><?= session('errors.description') ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Estimation de la variation (kg)</label>
                        <input type="text"
                            class="form-input <?= session('errors.variation_poids') ? 'is-invalid' : '' ?>"
                            placeholder="Ex: -2.5" name="variation_poids" value="<?= old('variation_poids') ?>">

                        <?php if (session('errors.variation_poids')): ?>
                            <small class="text-danger"><?= session('errors.variation_poids') ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Prix unitaire (€)</label>
                        <input type="number" class="form-input <?= session('errors.prix') ? 'is-invalid' : '' ?>"
                            placeholder="Ex: 1500" name="prix" value="<?= old('prix') ?>">

                        <?php if (session('errors.prix')): ?>
                            <small class="text-danger"><?= session('errors.prix') ?></small>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="food-section">
                    <h3 class="section-title">
                        <span class="title-bar"></span>
                        Répartition des aliments (%)
                    </h3>

                    <div class="food-grid">
                        <div class="food-card">
                            <label class="food-label">🥩 Viande</label>
                            <input type="number"
                                class="food-input <?= session('errors.pourcentage_viande') ? 'is-invalid' : '' ?>"
                                placeholder="0" min="0" max="100" name="pourcentage_viande"
                                value="<?= old('pourcentage_viande') ?>">

                            <?php if (session('errors.pourcentage_viande')): ?>
                                <small class="text-danger"><?= session('errors.pourcentage_viande') ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="food-card">
                            <label class="food-label">🐟 Poisson</label>
                            <input type="number"
                                class="food-input <?= session('errors.pourcentage_poisson') ? 'is-invalid' : '' ?>"
                                placeholder="0" min="0" max="100" name="pourcentage_poisson"
                                value="<?= old('pourcentage_poisson') ?>">

                            <?php if (session('errors.pourcentage_poisson')): ?>
                                <small class="text-danger"><?= session('errors.pourcentage_poisson') ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="food-card">
                            <label class="food-label">🍗 Volaille</label>
                            <input type="number"
                                class="food-input <?= session('errors.pourcentage_volaille') ? 'is-invalid' : '' ?>"
                                placeholder="0" min="0" max="100" name="pourcentage_volaille"
                                value="<?= old('pourcentage_volaille') ?>">

                            <?php if (session('errors.pourcentage_volaille')): ?>
                                <small class="text-danger"><?= session('errors.pourcentage_volaille') ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn-cancel">Annuler</button>
            <button type="submit" class="btn-submit" form="regime-form">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M4.16667 10H15.8333" stroke="white" stroke-width="1.66667" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M10 4.16667V15.8333" stroke="white" stroke-width="1.66667" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                Ajouter la recette
            </button>
        </div>
    </div>
</div>
<script>

    document.addEventListener('DOMContentLoaded', function () {

        const modalOverlay = document.querySelector('.modal-overlay');
        const openButton = document.getElementById('btn-ajout') || document.getElementById('btn-ajouter');
        const closeButton = document.querySelector('.close-btn');
        const cancelButton = document.querySelector('.btn-cancel');

        <?php if (session('errors')): ?>
            if (modalOverlay) {
                modalOverlay.style.display = 'flex';
            }
        <?php endif; ?>
        if (!modalOverlay) return;

        if (openButton) {
            openButton.addEventListener('click', function () {
                modalOverlay.style.display = 'flex';
            });
        }

        if (closeButton) {
            closeButton.addEventListener('click', function () {
                modalOverlay.style.display = 'none';
            });
        }

        modalOverlay.addEventListener('click', function (e) {
            if (e.target === this) {
                this.style.display = 'none';
            }
        });

        if (cancelButton) {
            cancelButton.addEventListener('click', function () {
                modalOverlay.style.display = 'none';
            });
        }
    });
</script>