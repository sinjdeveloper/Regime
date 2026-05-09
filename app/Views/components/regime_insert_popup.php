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

        <!-- Form Content -->
        <div class="modal-body">
            <form class="recipe-form">
                <!-- Nom de la recette -->
                <div class="form-group">
                    <label class="form-label">Nom de la recette</label>
                    <input type="text" class="form-input" placeholder="Ex: Poulet grillé aux légumes">
                </div>

                <!-- Row: Variation & Prix -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Estimation de la variation (kg)</label>
                        <input type="text" class="form-input" placeholder="Ex: -2.5">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prix unitaire (€)</label>
                        <input type="number" class="form-input" placeholder="Ex: 1500">
                    </div>
                </div>

                <!-- Répartition des aliments -->
                <div class="food-section">
                    <h3 class="section-title">
                        <span class="title-bar"></span>
                        Répartition des aliments (%)
                    </h3>

                    <div class="food-grid">
                        <!-- Viande -->
                        <div class="food-card">
                            <label class="food-label">🥩 Viande</label>
                            <input type="number" class="food-input" placeholder="0" min="0" max="100">
                        </div>

                        <!-- Poisson -->
                        <div class="food-card">
                            <label class="food-label">🐟 Poisson</label>
                            <input type="number" class="food-input" placeholder="0" min="0" max="100">
                        </div>

                        <!-- Volaille -->
                        <div class="food-card">
                            <label class="food-label">🍗 Volaille</label>
                            <input type="number" class="food-input" placeholder="0" min="0" max="100">
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
            <button type="button" class="btn-cancel">Annuler</button>
            <button type="submit" class="btn-submit">
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
        const submitButton = document.querySelector('.btn-submit');
        const cancelButton = document.querySelector('.btn-cancel');

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

        if (submitButton) {
            submitButton.addEventListener('click', function (e) {
                e.preventDefault();
                alert('Recette ajoutée !');
                modalOverlay.style.display = 'none';
            });
        }

        if (cancelButton) {
            cancelButton.addEventListener('click', function () {
                modalOverlay.style.display = 'none';
            });
        }
    });
</script>