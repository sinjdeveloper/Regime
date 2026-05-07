<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Generated Page</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/globals.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/login.css') ?>">

</head>

<body>
  <section id="section-login">
    <div class="login-page-wrapper">
      <div class="login-content">
        <div class="login-card">
          <div class="card-header">
            <div class="brand-logo">
              <img src="<?= base_url('assets/images/login') ?>/28_96.svg" alt="Vary'Ena Logo" class="logo-icon">
              <h1 class="brand-text">
                <span class="text-white">Vary</span><span class="text-accent">'</span><span
                  class="text-white">Ena</span>
              </h1>
            </div>
          </div>
          <div class="card-body">
            <form action="<?= site_url('admin/login') ?>" class="login-form" method="post">
              <div class="form-group">
                <label for="email">Nom d'utilisateur</label>
                <div class="input-wrapper">
                  <img src="<?= base_url('assets/images/login') ?>/28_120.svg" alt="" class="icon-left">
                  <input type="text" id="email" placeholder="admin" name="username">
                </div>
              </div>
              <div class="form-group">
                <label for="password">Mot de passe</label>
                <div class="input-wrapper">
                  <img src="<?= base_url('assets/images/login') ?>/28_130.svg" alt="" class="icon-left">
                  <input type="password" id="password" placeholder="Mot de Passe" name="password">
                  <button type="button" class="icon-btn icon-right" aria-label="Toggle password visibility">
                    <img src="<?= base_url('assets/images/login') ?>/28_134.svg" alt="">
                  </button>
                </div>
              </div>
              <div class="form-actions">
                <a href="#" class="forgot-password">Forgot password?</a>
              </div>
              <button type="submit" class="btn-submit">Sign in</button>
            </form>

            <div class="demo-credentials">
              Demo: demo@varyena.com / demo123
            </div>

            <div class="signup-prompt">
              <span class="text-muted">Don't have an account? </span>
              <a href="#" class="link-primary">Sign up</a>
            </div>
          </div>
        </div>
        <div class="copyright-text">
          © 2026 Vary'Ena. All rights reserved.
          <?= password_hash('admin', PASSWORD_DEFAULT) ?>

        </div>
      </div>

      <button class="btn-admin">
        <img src="<?= base_url('assets/images/login') ?>/28_160.svg" alt="" class="admin-icon">
        <span>Admin</span>
      </button>
    </div>
  </section>
</body>

</html>