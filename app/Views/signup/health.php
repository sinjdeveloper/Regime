<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Signup</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('assets/css/insc-global.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/insc-health.css') ?>">
</head>

<body>
    <section id="section-onboarding">
        <div class="split-layout">

            <!-- LEFT -->
            <div class="left-panel"
                style="background-image:
                    linear-gradient(rgba(102, 51, 102, 0.83), rgba(102, 51, 102, 0.83)),
                    url('<?= base_url('assets/images/signup/c7860d66b83eb9d06c543aa34ea9bc9aa4727194.png') ?>');
                    background-size: cover;
                    background-position: center;">
                <div class="brand-large">
                    <span>Vary</span><span class="highlight">'</span><span>Ena</span>
                </div>
                <p class="subtitle">
                    Rejoignez notre communauté et atteignez vos objectifs de santé
                </p>
            </div>

            <!-- RIGHT -->
            <div class="right-panel">

                <div class="card">

                    <!-- HEADER -->
                    <div class="card-header">

                        <div class="logo-icon-merged" style="position: relative; width: 40px; height: 40px;">
                            <img src="<?= base_url('assets/images/signup/76_241.svg') ?>" style="position:absolute; left:1px; top:1px; width:38px; height:39px;">
                            <img src="<?= base_url('assets/images/signup/76_244.svg') ?>" style="position:absolute; left:15px; top:16.5px; width:8px; height:13.5px;">
                            <img src="<?= base_url('assets/images/signup/76_249.svg') ?>" style="position:absolute; left:16px; top:12px; width:3px; height:6.5px;">
                            <img src="<?= base_url('assets/images/signup/76_252.svg') ?>" style="position:absolute; left:24px; top:11.5px; width:3.75px; height:4.625px;">
                            <img src="<?= base_url('assets/images/signup/76_255.svg') ?>" style="position:absolute; left:13px; top:25px; width:2px; height:2px;">
                            <img src="<?= base_url('assets/images/signup/76_258.svg') ?>" style="position:absolute; left:19px; top:22px; width:2px; height:2px;">
                            <img src="<?= base_url('assets/images/signup/76_261.svg') ?>" style="position:absolute; left:25px; top:20.5px; width:2px; height:2px;">
                        </div>

                        <div class="brand-small">
                            <span>Vary</span><span class="highlight">'</span><span>Ena</span>
                        </div>
                    </div>

                    <!-- BODY -->
                    <div class="card-body">

                        <!-- PROGRESS -->
                        <div class="pagination">
                            <div class="dot"></div>
                            <div class="dot active"></div>
                        </div>

                        <h2 class="heading">
                            Help us match you to the perfect lifestyle
                        </h2>

                        <!-- ERRORS -->
                        <?php if (session()->getFlashdata('errors')): ?>
                            <div class="error-box">
                                <?php foreach (session()->getFlashdata('errors') as $msg): ?>
                                    <p><?= esc((string) $msg) ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php $fieldErrors = session()->getFlashdata('field_errors') ?? []; ?>

                        <form class="form-grid" method="POST" action="<?= base_url('/signup/health') ?>">

                            <div class="form-group">
                                <label class="form-label">Height (cm)</label>
                                <div class="input-wrapper <?= isset($fieldErrors['taille']) ? 'input-error' : '' ?>">
                                    <div class="input-icon">
                                        <img src="<?= base_url('assets/images/signup/76_290.svg') ?>">
                                    </div>
                                    <input type="number" name="taille" class="form-input"
                                        placeholder="175" value="<?= old('taille') ?>">
                                </div>
                                <?php if (isset($fieldErrors['taille'])): ?>
                                    <span class="field-error-msg">Taille irréaliste</span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Weight (kg)</label>
                                <div class="input-wrapper <?= isset($fieldErrors['poids']) ? 'input-error' : '' ?>">
                                    <div class="input-icon">
                                        <img src="<?= base_url('assets/images/signup/76_301.svg') ?>">
                                    </div>
                                    <input type="number" name="poids" class="form-input"
                                        placeholder="70" value="<?= old('poids') ?>">
                                </div>
                                <?php if (isset($fieldErrors['poids'])): ?>
                                    <span class="field-error-msg">Poids irréaliste</span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Age</label>
                                <div class="input-wrapper <?= isset($fieldErrors['age']) ? 'input-error' : '' ?>">
                                    <div class="input-icon">
                                        <img src="<?= base_url('assets/images/signup/76_312.svg') ?>">
                                    </div>
                                    <input type="number" name="age" class="form-input"
                                        placeholder="25" value="<?= old('age') ?>">
                                </div>
                                <?php if (isset($fieldErrors['age'])): ?>
                                    <span class="field-error-msg">Âge irréaliste</span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Activity Level</label>
                                <div class="input-wrapper">
                                    <select name="activity_level" class="form-select">
                                        <option value="" disabled selected></option>
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                    </select>
                                    <div class="select-icon">
                                        <img src="<?= base_url('assets/images/signup/76_323.svg') ?>">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn-primary">Continue</button>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>
</body>

</html>