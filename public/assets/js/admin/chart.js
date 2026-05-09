document.addEventListener("DOMContentLoaded", () => {

    const ctx = document.getElementById('repartitionChart').getContext('2d');
    const ctxGold = document.getElementById('goldChart').getContext('2d');

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
    async function fetchRepartitionGold() {
        const res = await fetch('http://localhost:8080/index.php/admin/api/gold', {
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

    async function loadPieChart(result,ctx,titre,label) {
        const datas = result.repartition;
        const labels = datas.map(item => item.libelle);
        const values = datas.map(item => Number(item.nombre));
        console.log(datas);
        const repartitionChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
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
                        text: titre
                    }
                }
            }
        })
    }
    async function loadGoldChart() {
        const result = await fetchRepartitionGold();
        loadPieChart(result,ctx ,"Repartition des utilisateurs GOLD",'Nombre d\'utilisateurs');
    }
    async function loadObjectifsChart() {
        const result = await fetchRepartition();
        loadPieChart(result,ctxGold,'Repartition des objectifs','Nombre d\'utilisateurs');
    }
    loadGoldChart();
    loadObjectifsChart();
})
