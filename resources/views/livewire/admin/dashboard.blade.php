<div class="d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="container mt-5 pt-5">
        <h2 class="text-center mb-4 fw-bold">📊 Statistic Unmasker</h2>

        <div class="card shadow-sm p-4">
            <canvas id="dummyChart" height="100"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('dummyChart').getContext('2d');

    const dummyChart = new Chart(ctx, {
        type: 'bar', // tipe chart: bar, line, pie, dll.
        data: {
            labels: ['Pengguna Aktif', 'Total Kuis', 'Kuis Selesai', 'Nilai Rata-rata', 'Kuis Terpopuler'],
            datasets: [{
                label: 'Data Sementara',
                data: [120, 15, 98, 82, 10],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 205, 86, 0.6)',
                    'rgba(54, 162, 235, 0.6)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 205, 86, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Data Dummy untuk Tampilan Dashboard'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
});
</script>
