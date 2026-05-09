<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>
Sports
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<main class="main-content">

    <div class="page-header">
        <div class="page-title-group">
            <h1 class="page-title">Sports</h1>
            <p class="page-subtitle">Gérez vos programmes de nutrition et de sport</p>
        </div>
    </div>

    <?= $this->include('components/sport_insert_popup.php') ?>

    <div class="table-section">
        <div class="table-toolbar">
            <div class="filters">
                <button class="filter-btn icon-only">
                    <img src="<?= base_url('assets/images/admin') ?>/20_616.svg" alt="Filter">
                </button>
                <button class="filter-btn active">Tous</button>
            </div>

            <button class="btn-primary btn-new" type="button" id="btn-ajout-sport" >
                + Nouveau sport
            </button>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>NOM DU SPORT</th>
                        <th>RÉDUCTION</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sports as $sport): ?>
                        <tr>
                            <td>
                                <div class="diet-info">
                                    <div class="diet-avatar">S</div>
                                    <div class="diet-details">
                                        <div class="diet-name"><?= esc($sport['libelle']) ?></div>
                                        <div class="diet-date">Créé le 2026-04-15</div>
                                    </div>
                                </div>
                            </td>

                            <td class="variation"><?= esc($sport['pourcentage_reduction']) ?> %</td>

                            <td>
                                <div class="actions">
                                    <a class="action-btn" href="#" aria-disabled="true">
                                        <img src="<?= base_url('assets/images/admin') ?>/20_661.svg" alt="View">
                                    </a>
                                    <button type="button" class="action-btn" disabled>
                                        <img src="<?= base_url('assets/images/admin') ?>/20_665.svg" alt="Edit">
                                    </button>
                                    <button type="button" class="action-btn" disabled>
                                        <img src="<?= base_url('assets/images/admin') ?>/20_669.svg" alt="Delete">
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
</main>

<a class="fab-public" href="<?= site_url('/logout') ?>">
    <img src="<?= base_url('assets/images/admin') ?>/20_863.svg" alt="Public">
    Public
</a>
<?= $this->endSection() ?>