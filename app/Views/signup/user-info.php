<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('assets/css/insc-global.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/signup.css') ?>">
</head>

<body>

    <section id="section-signup" class="signup-section">

        <!-- LEFT SIDE -->
        <div class="signup-left" style="background:
            linear-gradient(rgba(102, 51, 102, 0.83), rgba(102, 51, 102, 0.83)),
            url('<?= base_url('assets/images/signup/c7860d66b83eb9d06c543aa34ea9bc9aa4727194.png') ?>')
            center/cover no-repeat;">
            <div class="brand-content">
                <h1 class="brand-title">
                    <span>Vary</span><span class="highlight">'</span><span>Ena</span>
                </h1>
                <p class="brand-subtitle">
                    Rejoignez notre communauté et atteignez vos objectifs de santé
                </p>
            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="signup-right">
            <div class="signup-card-wrapper">

                <div class="signup-card">

                    <!-- HEADER -->
                    <div class="card-header">

                        <div class="merged-logo">
                            <img src="<?= base_url('assets/images/signup/33_33.svg') ?>" class="logo-part" style="left:1px; top:1px; width:38px; height:38px;">
                            <img src="<?= base_url('assets/images/signup/33_36.svg') ?>" class="logo-part" style="left:15px; top:16.5px;">
                            <img src="<?= base_url('assets/images/signup/33_40.svg') ?>" class="logo-part" style="left:16px; top:12px;">
                            <img src="<?= base_url('assets/images/signup/33_43.svg') ?>" class="logo-part" style="left:24px; top:11.5px;">
                            <img src="<?= base_url('assets/images/signup/33_46.svg') ?>" class="logo-part" style="left:13px; top:25px;">
                            <img src="<?= base_url('assets/images/signup/33_49.svg') ?>" class="logo-part" style="left:19px; top:22px;">
                            <img src="<?= base_url('assets/images/signup/33_52.svg') ?>" class="logo-part" style="left:25px; top:20.5px;">
                        </div>

                        <h2 class="card-brand">
                            <span>Vary</span><span class="highlight">'</span><span>Ena</span>
                        </h2>
                    </div>

                    <!-- BODY -->
                    <div class="card-body">

                        <h3 class="form-title">Créer un compte</h3>

                        <!-- ERROR DISPLAY -->
                        <?php if (!empty(session()->getFlashdata('errors'))): ?>
                            <div class="error-box">
                                <?php foreach (session()->getFlashdata('errors') as $msg): ?>
                                    <p><?= esc((string)$msg) ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="/signup/user" class="signup-form">

                            <div class="form-row split">

                                <div class="form-group">
                                    <label>Prénom</label>
                                    <div class="input-wrapper">
                                        <img src="<?= base_url('assets/images/signup/33_72.svg') ?>" class="input-icon">
                                        <input type="text" name="prenom"
                                            value="<?= old('prenom') ?>"
                                            placeholder="Jean" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Nom</label>
                                    <div class="input-wrapper">
                                        <img src="<?= base_url('assets/images/signup/33_82.svg') ?>" class="input-icon">
                                        <input type="text" name="nom"
                                            value="<?= old('nom') ?>"
                                            placeholder="Dupont" required>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <div class="input-wrapper">
                                    <img src="<?= base_url('assets/images/signup/33_92.svg') ?>" class="input-icon">
                                    <input type="email" name="email"
                                        value="<?= old('email') ?>"
                                        placeholder="jean@email.com" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Genre</label>
                                <div class="input-wrapper select-wrapper">
                                    <select name="genre" required>
                                        <option value="" disabled <?= old('genre') ? '' : 'selected' ?>>Choisir</option>
                                        <option value="homme" <?= old('genre') == 'homme' ? 'selected' : '' ?>>Homme</option>
                                        <option value="femme" <?= old('genre') == 'femme' ? 'selected' : '' ?>>Femme</option>
                                        <option value="autre" <?= old('genre') == 'autre' ? 'selected' : '' ?>>Autre</option>
                                    </select>
                                    <img src="<?= base_url('assets/images/signup/33_101.svg') ?>" class="select-icon">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Mot de passe</label>
                                <div class="input-wrapper">
                                    <img src="<?= base_url('assets/images/signup/33_110.svg') ?>" class="input-icon">
                                    <input type="password" name="password" required>
                                    <img src="<?= base_url('assets/images/signup/33_114.svg') ?>" class="input-icon-right">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Confirmer le mot de passe</label>
                                <div class="input-wrapper">
                                    <img src="<?= base_url('assets/images/signup/33_124.svg') ?>" class="input-icon">
                                    <input type="password" name="password_confirm" required>
                                    <img src="<?= base_url('assets/images/signup/33_128.svg') ?>" class="input-icon-right">
                                </div>
                            </div>

                            <button type="submit" class="submit-btn">
                                S'inscrire
                            </button>

                        </form>

                        <div class="login-link">
                            <span>Vous avez déjà un compte? </span>
                            <a href="/login">Se connecter</a>
                        </div>

                    </div>
                </div>

                <div class="footer-copy">
                    © 2026 Vary'Ena. All rights reserved.
                </div>

            </div>
        </div>

    </section>

</body>

</html>