<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>
Régimes
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main class="main-content">
    <div style="width: 400px; height: 400px;">
        <canvas id="repartitionChart"></canvas>
    </div>

</main>


<script>
    document.addEventListener("DOMContentLoaded", () => {

        const ctx = document.getElementById('repartitionChart').getContext('2d');
        async function fetchRepartition() {
            const res = await fetch('http://localhost:8080/index.php/admin/api/repartition', {
                method: 'GET',
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            });
            if (!res.ok) {
                throw new Error("Impossible de recuperer la repartition");
            }
            return await res.json();

        }
        async function loadPieChart() {
            const result = await fetchRepartition();
            const datas = result.repartition;
            let labels = [];
            let values = [];
            datas.forEach(element => {
                labels.push(element.libelle);
                values.push(element.nombre);
            });
            console.log(datas);
            const repartitionChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Nombre d\'utilisateurs',
                        data: values
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        title: {
                            display: true,
                            text: 'Repartition des objectifs'
                        }
                    }
                }
            })
        }
        loadPieChart();
    })



</script>

<?= $this->endSection() ?>