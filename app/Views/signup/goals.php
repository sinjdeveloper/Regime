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

                                        <input type="checkbox"
                                            name="goals[<?= $i ?>][selected]"
                                            value="1"
                                            class="hidden-checkbox">

                                        <span class="custom-checkbox">
                                            <span class="checkbox-inner"></span>
                                        </span>

                                    </label>

                                    <div class="goal-extra" id="extra-<?= $i ?>">
                                        <input type="number"
                                            name="goals[<?= $i ?>][poids_cible]"
                                            step="0.1" min="20" max="300"
                                            placeholder="Poids cible (kg)">
                                        <input type="number"
                                            name="goals[<?= $i ?>][duree]"
                                            min="1" max="730"
                                            placeholder="Durée (jours)">
                                        <input type="hidden" name="goals[<?= $i ?>][objectif_id]" value="<?= $goal['id'] ?>">
                                    </div>

                                <?php endforeach; ?>

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
    document.getElementById('goals-form').addEventListener('submit', function(e) {
        // TEMP: disabled for testing
    });
</script>

</body>

</html>