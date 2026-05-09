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
                <h2 id="sport-modal-title">Ajouter un sport</h2>
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
            <form
                id="sport-form"
                class="recipe-form"
                action="<?= site_url('admin/sports/store') ?>"
                method="post"
                enctype="multipart/form-data"
                data-store-action="<?= site_url('admin/sports/store') ?>"
                data-update-action-base="<?= site_url('admin/sports/update') ?>"
            >
                <?= csrf_field() ?>

                <input type="hidden" name="id" value="<?= old('id') ?>">

                <div class="form-group">
                    <label class="form-label">Nom du sport</label>
                    <input type="text" class="form-input <?= session('errors.libelle') ? 'is-invalid' : '' ?>"
                        placeholder="Ex: Musculation" name="libelle" value="<?= old('libelle') ?>">

                    <?php if (session('errors.libelle')): ?>
                        <small class="text-danger"><?= session('errors.libelle') ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Réduction (%)</label>
                    <input type="number"
                        class="form-input <?= session('errors.pourcentage_reduction') ? 'is-invalid' : '' ?>"
                        placeholder="Ex: 10" min="0" max="100" name="pourcentage_reduction"
                        value="<?= old('pourcentage_reduction') ?>">

                    <?php if (session('errors.pourcentage_reduction')): ?>
                        <small class="text-danger"><?= session('errors.pourcentage_reduction') ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Image (optionnel)</label>
                    <input type="file" class="form-input <?= session('errors.image') ? 'is-invalid' : '' ?>"
                        name="image" accept="image/*">

                    <?php if (session('errors.image')): ?>
                        <small class="text-danger"><?= session('errors.image') ?></small>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn-cancel">Annuler</button>
            <button type="submit" class="btn-submit" form="sport-form">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M4.16667 10H15.8333" stroke="white" stroke-width="1.66667" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M10 4.16667V15.8333" stroke="white" stroke-width="1.66667" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <span id="sport-modal-submit-text">Ajouter le sport</span>
            </button>
        </div>
    </div>
</div>
<script>

    document.addEventListener('DOMContentLoaded', function () {

        const modalOverlay = document.querySelector('.modal-overlay');
        const openButton = document.getElementById('btn-ajout-sport') || document.getElementById('btn-ajout') || document.getElementById('btn-ajouter');
        const closeButton = document.querySelector('.close-btn');
        const cancelButton = document.querySelector('.btn-cancel');
        const form = document.getElementById('sport-form');
        const titleEl = document.getElementById('sport-modal-title');
        const submitTextEl = document.getElementById('sport-modal-submit-text');

        const idInput = form?.querySelector('input[name="id"]');
        const libelleInput = form?.querySelector('input[name="libelle"]');
        const reductionInput = form?.querySelector('input[name="pourcentage_reduction"]');
        const imageInput = form?.querySelector('input[name="image"]');

        function setModeCreate() {
            if (!form) return;

            const storeAction = form.dataset.storeAction;
            form.action = storeAction || form.action;

            if (titleEl) titleEl.textContent = 'Ajouter un sport';
            if (submitTextEl) submitTextEl.textContent = 'Ajouter le sport';

            if (idInput) idInput.value = '';
            if (libelleInput) libelleInput.value = '';
            if (reductionInput) reductionInput.value = '';
            if (imageInput) imageInput.value = '';
        }

        function setModeUpdate(sport) {
            if (!form) return;

            const updateBase = form.dataset.updateActionBase;
            if (updateBase && sport.id) {
                form.action = `${updateBase}/${sport.id}`;
            }

            if (titleEl) titleEl.textContent = 'Modifier le sport';
            if (submitTextEl) submitTextEl.textContent = 'Mettre à jour';

            if (idInput) idInput.value = sport.id ?? '';
            if (libelleInput) libelleInput.value = sport.libelle ?? '';
            if (reductionInput) reductionInput.value = sport.pourcentage_reduction ?? '';
        }

        <?php if (session('errors')): ?>
            if (modalOverlay) {
                modalOverlay.style.display = 'flex';
            }
        <?php endif; ?>
        if (!modalOverlay) return;

        if (form && idInput && idInput.value) {
            const updateBase = form.dataset.updateActionBase;
            if (updateBase) {
                form.action = `${updateBase}/${idInput.value}`;
            }
            if (titleEl) titleEl.textContent = 'Modifier le sport';
            if (submitTextEl) submitTextEl.textContent = 'Mettre à jour';
        }

        if (openButton) {
            openButton.addEventListener('click', function () {
                setModeCreate();
                modalOverlay.style.display = 'flex';
            });
        }

        document.addEventListener('click', function (e) {
            const editBtn = e.target.closest('.js-edit-sport');
            if (!editBtn) return;

            const sport = {
                id: editBtn.dataset.id,
                libelle: editBtn.dataset.libelle,
                pourcentage_reduction: editBtn.dataset.pourcentageReduction,
            };

            setModeUpdate(sport);
            modalOverlay.style.display = 'flex';
        });

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