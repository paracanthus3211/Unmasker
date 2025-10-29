<div class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-3xl font-bold mb-6">Dashboard User Quiz</h1>

    {{-- Grafik 1: Jawaban Benar vs Salah --}}
    <div class="bg-white p-4 rounded-xl shadow mb-8">
        <h2 class="text-xl font-semibold mb-3">Hasil Jawaban</h2>
        <canvas id="answerChart"></canvas>
    </div>

    {{-- Grafik 2: Skor User vs Rata-rata --}}
    <div class="bg-white p-4 rounded-xl shadow">
        <h2 class="text-xl font-semibold mb-3">Perbandingan Skor User dan Rata-rata</h2>
        <canvas id="scoreChart"></canvas>
    </div>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('livewire:load', function () {
            // Data dari Livewire (kamu isi di komponen Dashboard.php)
            const correct = @this.correctAnswers ?? 0;
            const incorrect = @this.incorrectAnswers ?? 0;
            const userScores = @this.userScores ?? [];
            const avgScores = @this.avgScores ?? [];
            const labels = @this.quizLabels ?? [];

            // --- Grafik 1: Benar vs Salah ---
            const ctx1 = document.getElementById('answerChart').getContext('2d');
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: ['Benar', 'Salah'],
                    datasets: [{
                        label: 'Jumlah Jawaban',
                        data: [correct, incorrect],
                        backgroundColor: ['#22c55e', '#ef4444'],
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            // --- Grafik 2: Skor User vs Rata-rata ---
            const ctx2 = document.getElementById('scoreChart').getContext('2d');
            new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Skor User',
                            data: userScores,
                            borderColor: '#3b82f6',
                            tension: 0.3,
                            fill: false
                        },
                        {
                            label: 'Skor Rata-rata',
                            data: avgScores,
                            borderColor: '#f59e0b',
                            tension: 0.3,
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
    </script>
</div>
