<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>
Régimes
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= base_url('assets/js/admin/bar_chart.js') ?>" defer></script>
<main class="main-content">

  <!-- Page Header -->
  <div class="page-header">
    <div class="page-title-group">
      <h1 class="page-title">Régimes</h1>
      <p class="page-subtitle">Gérez vos programmes de nutrition et de sport</p>
    </div>
  </div>

  <!-- Stats Cards -->
  <div style="width: 400px; min-height: 200px;">
    <canvas id="regimeChart"></canvas>
  </div>
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon-wrapper">
        <img src="<?= base_url('assets/images/admin') ?>/20_868.svg" alt="Total régimes">
      </div>
      <div class="stat-info">
        <div class="stat-value"><?= $stats['regimes'] ?></div>
        <div class="stat-label">Total régimes</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon-wrapper">
        <img src="<?= base_url('assets/images/admin') ?>/20_870.svg" alt="Utilisateurs">
      </div>
      <div class="stat-info">
        <div class="stat-value"><?= $stats['utilisateurs'] ?></div>
        <div class="stat-label">Utilisateurs</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon-wrapper opacity-48">
        <img src="<?= base_url('assets/images/admin') ?>/20_872.svg" alt="Transactions">
      </div>
      <div class="stat-info">
        <div class="stat-value"><?= $stats['transactions'] ?></div>
        <div class="stat-label">Transactions</div>
      </div>
    </div>
  </div>

  <?= $this->include('components/regime_insert_popup.php') ?>

  <!-- Table Section -->
  <div class="table-section">
    <div class="table-toolbar">
      <div class="filters">
        <button class="filter-btn icon-only"><img src="<?= base_url('assets/images/admin') ?>/20_616.svg"
            alt="Filter"></button>
        <button class="filter-btn active">Tous</button>
        <button class="filter-btn">Perte</button>
        <button class="filter-btn">Gain</button>
      </div>
      <button class="btn-primary btn-new" id="btn-ajout">
        + Nouveau régime
      </button>
    </div>

    <div class="table-container">
      <table class="data-table">
        <thead>
          <tr>
            <th>NOM DU RÉGIME</th>
            <th>TYPE</th>
            <th>VARIATION</th>
            <th>PRIX</th>
            <th>ACTIONS</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($regimes as $regime) { ?>
            <tr>
              <td>
                <div class="diet-info">
                  <div class="diet-avatar">P</div>
                  <div class="diet-details">
                    <div class="diet-name"><?= $regime['libelle'] ?></div>
                    <div class="diet-date">Créé le 2026-04-15</div>
                  </div>
                </div>
              </td>
              <?php if ($regime['variation_poids'] < 0) { ?>
                <td><span class="badge-type type-perte"><img src="<?= base_url('assets/images/admin') ?>/20_647.svg"
                      alt="">Perte</span></td>
              <?php } else { ?>
                <td><span class="badge-type type-gain"><img src="<?= base_url('assets/images/admin') ?>/20_685.svg"
                      alt="">Gain</span></td>
              <?php } ?>
              <td class="variation"><?= $regime['variation_poids'] ?> kg</td>
              <td class="price"><?= $regime['prix'] ?> Ar</td>
              <td>
                <div class="actions">
                  <a class="action-btn"><img src="<?= base_url('assets/images/admin') ?>/20_661.svg" alt="View"></a>

                  <button type="button" class="action-btn js-edit-regime" data-id="<?= esc($regime['id']) ?>"
                    data-libelle="<?= esc($regime['libelle']) ?>" data-description="<?= esc($regime['description']) ?>"
                    data-variation-poids="<?= esc($regime['variation_poids']) ?>" data-prix="<?= esc($regime['prix']) ?>"
                    data-pourcentage-viande="<?= esc($regime['pourcentage_viande']) ?>"
                    data-pourcentage-poisson="<?= esc($regime['pourcentage_poisson']) ?>"
                    data-pourcentage-volaille="<?= esc($regime['pourcentage_volaille']) ?>">
                    <img src="<?= base_url('assets/images/admin') ?>/20_665.svg" alt="Edit">
                  </button>
                  <form action="<?= site_url('admin/regimes/delete/' . $regime['id']) ?>" method="post">
                    <button class="action-btn" type="submit"><img src="<?= base_url('assets/images/admin') ?>/20_669.svg"
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