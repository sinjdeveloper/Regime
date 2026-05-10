<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goal Selection</title>

    <link rel="stylesheet" href="<?= base_url('assets/css/insc-goal.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

    <section id="registration-section">
        <div class="split-layout">

            <!-- LEFT -->
            <div class="left-panel"
                style="background: linear-gradient(rgba(102, 51, 102, 0.83), rgba(102, 51, 102, 0.83)),
         url('<?= base_url('assets/images/signup/c7860d66b83eb9d06c543aa34ea9bc9aa4727194.png') ?>') center/cover no-repeat;">

                <div class="left-content">
                    <h1 class="logo-large">
                        <span class="text-white">Vary</span>
                        <span class="text-yellow">'</span>
                        <span class="text-white">Ena</span>
                    </h1>
                    <p class="subtitle">Rejoignez notre communauté et atteignez vos objectifs</p>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="right-panel">
                <div class="registration-card">

                    <div class="card-header">
                        <div class="logo-small">
                            <span class="text-white">Vary</span>
                            <span class="text-yellow">'</span>
                            <span class="text-white">Ena</span>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="pagination">
                            <span class="dot active"></span>
                            <span class="dot"></span>
                        </div>

                        <!-- ERROR DISPLAY -->
                        <?php if (!empty(session()->getFlashdata('errors'))): ?>
                            <div style="color: red; margin-bottom: 15px;">
                                <?php foreach (session()->getFlashdata('errors') as $msg): ?>
                                    <p><?= esc((string)$msg) ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>


                        <h2 class="form-title">Choisissez vos objectifs</h2>
                        <p class="form-subtitle">Sélectionnez votre objectif</p>

                        <form method="POST" action="<?= base_url('/signup/complete') ?>" id="goals-form">
                            <?= csrf_field() ?>

                            <div class="options-list">
                                <?php foreach (($goals ?? []) as $i => $goal): ?>
                                    <label class="goal-option">
                                        <span class="option-text">
                                            <?= esc((string)($goal['libelle'] ?? '')) ?>
                                        </span>
                                        <input type="radio"
                                            name="goals_selected"
                                            value="<?= $goal['id'] ?>"
                                            class="hidden-checkbox"
                                            data-needs-details="<?= strtolower(trim($goal['libelle'] ?? '')) !== 'calculer imc idéal' ? '1' : '0' ?>">
                                        <span class="custom-checkbox">
                                            <span class="checkbox-inner"></span>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>

                            <!-- Single shared extra fields div -->
                            <div class="goal-extra" id="shared-extra" style="display:none;">
                                <input type="number"
                                    name="goals_poids_cible"
                                    step="0.1" min="20" max="300"
                                    placeholder="Poids cible (kg)">
                                <input type="number"
                                    name="goals_duree"
                                    min="1" max="730"
                                    placeholder="Durée (jours)">
                            </div>

                            <button type="submit" class="btn-primary">
                                Créer Compte
                            </button>

                        </form>

                    </div>
                </div>
            </div>

        </div>
    </section>
    <script>
        document.querySelectorAll('.hidden-checkbox').forEach(function(radio) {
            radio.addEventListener('change', function() {
                const extra = document.getElementById('shared-extra');
                if (this.dataset.needsDetails === '1') {
                    extra.style.display = 'grid';
                } else {
                    extra.style.display = 'none';
                    // Clear values so they don't interfere
                    extra.querySelectorAll('input').forEach(i => i.value = '');
                }
            });
        });

        document.getElementById('goals-form').addEventListener('submit', function(e) {
            const selected = document.querySelector('.hidden-checkbox:checked');
            if (!selected) {
                e.preventDefault();
                alert('Veuillez sélectionner un objectif.');
                return;
            }

            if (selected.dataset.needsDetails === '1') {
                const poids = document.querySelector('input[name="goals_poids_cible"]');
                const duree = document.querySelector('input[name="goals_duree"]');
                if (!poids.value || parseFloat(poids.value) <= 0) {
                    e.preventDefault();
                    alert('Veuillez saisir un poids cible valide.');
                    poids.focus();
                    return;
                }
                if (!duree.value || parseInt(duree.value) <= 0) {
                    e.preventDefault();
                    alert('Veuillez saisir une durée valide en jours.');
                    duree.focus();
                    return;
                }
            }
        });
    </script>

</body>

</html>