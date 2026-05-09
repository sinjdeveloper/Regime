document.addEventListener("DOMContentLoaded", () => {

    const ctx = document.getElementById('regimeChart').getContext('2d');

    async function fetchRegimes() {
        const res = await fetch('http://localhost:8080/index.php/admin/api/regimes', {
            method: 'GET',
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        });
        if (!res.ok) {
            throw new Error("Impossible de recuperer les regimes");
        }
        return await res.json();

    }
   
    async function loadBarChart(result,ctx,titre,label) {
        const labels = result.map(item => item.libelle);
        const values = result.map(item => Number(item.nombre));
        const repartitionChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: values
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
    async function loadRegimeChart() {
        const result = await fetchRegimes();
        loadBarChart(result,ctx ,"Meilleurs Regimes",'Nombre d\'achats');
    }
    loadRegimeChart();
})
