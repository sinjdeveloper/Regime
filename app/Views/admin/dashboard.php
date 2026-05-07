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
  <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">

  <script src="global.js" defer></script>
</head>

<body>
  <section id="section-header" class="app-wrapper">
    <header class="admin-header">
      <div class="header-left">
        <div class="logo">
          <img src="<?= base_url('assets/images/admin') ?>/20_831.svg" alt="Vary'Ena Logo">
          <span class="logo-text">Vary<span class="logo-highlight">'</span>Ena</span>
        </div>
        <span class="admin-text">Administration</span>
      </div>
      <div class="header-right">
        <button class="icon-btn">
          <img src="<?= base_url('assets/images/admin') ?>/20_849.svg" alt="Notifications">
          <span class="badge"></span>
        </button>
        <button class="icon-btn">
          <img src="<?= base_url('assets/images/admin') ?>/20_854.svg" alt="Search">
        </button>
        <div class="user-profile">
          <div class="avatar">A</div>
          <span class="user-name">Admin</span>
        </div>
      </div>
    </header>
  </section>
  <section id="section-dashboard" class="app-wrapper">
    <div class="dashboard-layout">

      <!-- Sidebar -->
      <aside class="sidebar">
        <nav class="sidebar-nav">
          <a href="#" class="nav-item">
            <img src="<?= base_url('assets/images/admin') ?>/20_531.svg" alt="Dashboard">
            <span>Dashboard</span>
          </a>
          <a href="#" class="nav-item active">
            <img src="<?= base_url('assets/images/admin') ?>/20_539.svg" alt="Régimes">
            <span>Régimes</span>
          </a>
          <a href="#" class="nav-item">
            <img src="<?= base_url('assets/images/admin') ?>/20_546.svg" alt="Sports">
            <span>Sports</span>
          </a>
          <a href="#" class="nav-item">
            <img src="<?= base_url('assets/images/admin') ?>/20_555.svg" alt="Paramètres">
            <span>Paramètres</span>
          </a>
        </nav>
        <div class="sidebar-footer">
          <a href="#" class="nav-item logout-item">
            <img src="<?= base_url('assets/images/admin') ?>/20_562.svg" alt="Déconnexion">
            <span>Déconnexion</span>
          </a>
        </div>
      </aside>

      <!-- Main Content -->
      <main class="main-content">

        <!-- Page Header -->
        <div class="page-header">
          <div class="page-title-group">
            <h1 class="page-title">Régimes</h1>
            <p class="page-subtitle">Gérez vos programmes de nutrition et de sport</p>
          </div>
          <button class="btn-primary">
            <img src="<?= base_url('assets/images/admin') ?>/20_576.svg" alt="Ajouter">
            Ajouter
          </button>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-icon-wrapper">
              <img src="<?= base_url('assets/images/admin') ?>/20_868.svg" alt="Total régimes">
            </div>
            <div class="stat-info">
              <div class="stat-value"><?= $regimes?></div>
              <div class="stat-label">Total régimes</div>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon-wrapper">
              <img src="<?= base_url('assets/images/admin') ?>/20_870.svg" alt="Utilisateurs">
            </div>
            <div class="stat-info">
              <div class="stat-value"><?= $utilisateurs ?></div>
              <div class="stat-label">Utilisateurs</div>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon-wrapper opacity-48">
              <img src="<?= base_url('assets/images/admin') ?>/20_872.svg" alt="Transactions">
            </div>
            <div class="stat-info">
              <div class="stat-value"><?= $transactions ?></div>
              <div class="stat-label">Transactions</div>
            </div>
          </div>
        </div>

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
            <button class="btn-primary btn-new">
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
                  <th>DURÉE</th>
                  <th>STATUS</th>
                  <th>ACTIONS</th>
                </tr>
              </thead>
              <tbody>
                <!-- Row 1 -->
                <tr>
                  <td>
                    <div class="diet-info">
                      <div class="diet-avatar">P</div>
                      <div class="diet-details">
                        <div class="diet-name">Poulet grillé méditerranéen</div>
                        <div class="diet-date">Créé le 2026-04-15</div>
                      </div>
                    </div>
                  </td>
                  <td><span class="badge-type type-perte"><img src="<?= base_url('assets/images/admin') ?>/20_647.svg"
                        alt="">Perte</span></td>
                  <td class="variation">-2.5 kg</td>
                  <td class="price">12.50 €</td>
                  <td class="duration">30 jours</td>
                  <td><span class="badge-status status-actif">Actif</span></td>
                  <td>
                    <div class="actions">
                      <button class="action-btn"><img src="<?= base_url('assets/images/admin') ?>/20_661.svg"
                          alt="View"></button>
                      <button class="action-btn"><img src="<?= base_url('assets/images/admin') ?>/20_665.svg"
                          alt="Edit"></button>
                      <button class="action-btn"><img src="<?= base_url('assets/images/admin') ?>/20_669.svg"
                          alt="Delete"></button>
                    </div>
                  </td>
                </tr>
                <!-- Row 2 -->
                <tr>
                  <td>
                    <div class="diet-info">
                      <div class="diet-avatar">S</div>
                      <div class="diet-details">
                        <div class="diet-name">Saumon protéiné complet</div>
                        <div class="diet-date">Créé le 2026-04-10</div>
                      </div>
                    </div>
                  </td>
                  <td><span class="badge-type type-gain"><img src="<?= base_url('assets/images/admin') ?>/20_685.svg"
                        alt="">Gain</span></td>
                  <td class="variation">+1.8 kg</td>
                  <td class="price">18.00 €</td>
                  <td class="duration">45 jours</td>
                  <td><span class="badge-status status-actif">Actif</span></td>
                  <td>
                    <div class="actions">
                      <button class="action-btn"><img src="<?= base_url('assets/images/admin') ?>/20_699.svg"
                          alt="View"></button>
                      <button class="action-btn"><img src="<?= base_url('assets/images/admin') ?>/20_703.svg"
                          alt="Edit"></button>
                      <button class="action-btn"><img src="<?= base_url('assets/images/admin') ?>/20_707.svg"
                          alt="Delete"></button>
                    </div>
                  </td>
                </tr>
                <!-- Row 3 -->
                <tr>
                  <td>
                    <div class="diet-info">
                      <div class="diet-avatar">B</div>
                      <div class="diet-details">
                        <div class="diet-name">Bowl végétarien équilibré</div>
                        <div class="diet-date">Créé le 2026-04-20</div>
                      </div>
                    </div>
                  </td>
                  <td><span class="badge-type type-perte"><img src="<?= base_url('assets/images/admin') ?>/20_723.svg"
                        alt="">Perte</span></td>
                  <td class="variation">-1.2 kg</td>
                  <td class="price">9.50 €</td>
                  <td class="duration">21 jours</td>
                  <td><span class="badge-status status-brouillon">Brouillon</span></td>
                  <td>
                    <div class="actions">
                      <button class="action-btn"><img src="<?= base_url('assets/images/admin') ?>/20_737.svg"
                          alt="View"></button>
                      <button class="action-btn"><img src="<?= base_url('assets/images/admin') ?>/20_741.svg"
                          alt="Edit"></button>
                      <button class="action-btn"><img src="<?= base_url('assets/images/admin') ?>/20_745.svg"
                          alt="Delete"></button>
                    </div>
                  </td>
                </tr>
                <!-- Row 4 -->
                <tr>
                  <td>
                    <div class="diet-info">
                      <div class="diet-avatar">B</div>
                      <div class="diet-details">
                        <div class="diet-name">Bœuf aux légumes verts</div>
                        <div class="diet-date">Créé le 2026-03-25</div>
                      </div>
                    </div>
                  </td>
                  <td><span class="badge-type type-perte"><img src="<?= base_url('assets/images/admin') ?>/20_761.svg"
                        alt="">Perte</span></td>
                  <td class="variation">-3.0 kg</td>
                  <td class="price">15.00 €</td>
                  <td class="duration">28 jours</td>
                  <td><span class="badge-status status-actif">Actif</span></td>
                  <td>
                    <div class="actions">
                      <button class="action-btn"><img src="<?= base_url('assets/images/admin') ?>/20_775.svg"
                          alt="View"></button>
                      <button class="action-btn"><img src="<?= base_url('assets/images/admin') ?>/20_779.svg"
                          alt="Edit"></button>
                      <button class="action-btn"><img src="<?= base_url('assets/images/admin') ?>/20_783.svg"
                          alt="Delete"></button>
                    </div>
                  </td>
                </tr>
                <!-- Row 5 -->
                <tr>
                  <td>
                    <div class="diet-info">
                      <div class="diet-avatar">P</div>
                      <div class="diet-details">
                        <div class="diet-name">Pâtes complètes sportif</div>
                        <div class="diet-date">Créé le 2026-03-18</div>
                      </div>
                    </div>
                  </td>
                  <td><span class="badge-type type-gain"><img src="<?= base_url('assets/images/admin') ?>/20_799.svg"
                        alt="">Gain</span></td>
                  <td class="variation">+2.5 kg</td>
                  <td class="price">11.00 €</td>
                  <td class="duration">60 jours</td>
                  <td><span class="badge-status status-actif">Actif</span></td>
                  <td>
                    <div class="actions">
                      <button class="action-btn"><img src="<?= base_url('assets/images/admin') ?>/20_813.svg"
                          alt="View"></button>
                      <button class="action-btn"><img src="<?= base_url('assets/images/admin') ?>/20_817.svg"
                          alt="Edit"></button>
                      <button class="action-btn"><img src="<?= base_url('assets/images/admin') ?>/20_821.svg"
                          alt="Delete"></button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </main>

      <!-- Floating Action Button -->
      <a class="fab-public" href="<?= site_url('/logout')?>">
        <img src="<?= base_url('assets/images/admin') ?>/20_863.svg" alt="Public">
        Public
      </a>

    </div>
  </section>
</body>

</html>