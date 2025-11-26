<div>
    <div class="pagetitle">
        <h1>🎯 Dashboard Saya</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            
            <!-- Stats Cards -->
            <div class="col-lg-3 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Kuis Dikerjakan</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-play-circle"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $userStats['totalQuizAttempts'] ?? 0 }}</h6>
                                <span class="text-success small pt-1 fw-bold">{{ $userStats['completedLevels'] ?? 0 }}</span>
                                <span class="text-muted small pt-2 ps-1">selesai</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card revenue-card">
                    <div class="card-body">
                        <h5 class="card-title">Rata-rata Nilai</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-graph-up"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $userStats['averageScore'] ?? 0 }}</h6>
                                <span class="text-success small pt-1 fw-bold">{{ $userStats['highest_score'] ?? 0 }}</span>
                                <span class="text-muted small pt-2 ps-1">tertinggi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card customers-card">
                    <div class="card-body">
                        <h5 class="card-title">Akurasi</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-bullseye"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $userStats['accuracy_rate'] ?? 0 }}%</h6>
                                <span class="text-success small pt-1 fw-bold">{{ $userStats['total_correct_answers'] ?? 0 }}</span>
                                <span class="text-muted small pt-2 ps-1">jawaban benar</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Progress</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-trophy"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $userStats['completedLevels'] ?? 0 }}/{{ $userStats['totalLevels'] ?? 0 }}</h6>
                                <span class="text-success small pt-1 fw-bold">Active</span>
                                <span class="text-muted small pt-2 ps-1">Learner</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Quick Actions -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">📊 Progress Level Completion</h5>
                        <div class="chart-container" style="position: relative; height: 350px; width: 100%;">
                            <canvas id="userProgressChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">⚡ Aksi Cepat</h5>
                        <div class="d-grid gap-2">
                            <a href="{{ route('user.quizz') }}" 
                               class="btn btn-primary btn-lg d-flex align-items-center justify-content-start">
                                <i class="bi bi-play-circle me-2"></i>
                                <div class="text-start">
                                    <div class="fw-bold">Mulai Kuis Baru</div>
                                    <small class="opacity-75">Jelajahi kuis tersedia</small>
                                </div>
                            </a>
                            
                            <a href="{{ route('user.literacy') }}" 
                               class="btn btn-success btn-lg d-flex align-items-center justify-content-start">
                                <i class="bi bi-book me-2"></i>
                                <div class="text-start">
                                    <div class="fw-bold">Pelajari Materi</div>
                                    <small class="opacity-75">Tingkatkan pengetahuan</small>
                                </div>
                            </a>
                            
                            <a href="{{ route('user.profile') }}" 
                               class="btn btn-info btn-lg d-flex align-items-center justify-content-start">
                                <i class="bi bi-person me-2"></i>
                                <div class="text-start">
                                    <div class="fw-bold">Edit Profil</div>
                                    <small class="opacity-75">Perbarui informasi</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-12">
                <div class="card recent-sales overflow-auto">
                    <div class="card-body">
                        <h5 class="card-title">📋 Aktivitas Terbaru</h5>
                        <div class="table-responsive">
                            <table class="table table-borderless datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">Level</th>
                                        <th scope="col">Skor</th>
                                        <th scope="col">Detail</th>
                                        <th scope="col">Waktu</th>
                                        <th scope="col">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($quizHistory as $history)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $history['level_name'] }}</div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress me-2" style="width: 80px; height: 8px;">
                                                    <div class="progress-bar 
                                                        {{ $history['score'] >= 3 ? 'bg-success' : 
                                                           ($history['score'] >= 2 ? 'bg-warning' : 'bg-danger') }}" 
                                                        style="width: {{ ($history['score'] / 5) * 100 }}%">
                                                    </div>
                                                </div>
                                                <span class="fw-bold 
                                                    {{ $history['score'] >= 3 ? 'text-success' : 
                                                       ($history['score'] >= 2 ? 'text-warning' : 'text-danger') }}">
                                                    {{ $history['score'] }}/5
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-success fw-bold">{{ $history['correct_answers'] }}</span> benar<br>
                                            <span class="text-danger">{{ $history['wrong_answers'] }}</span> salah
                                        </td>
                                        <td>{{ $history['completion_time'] }}s</td>
                                        <td>{{ $history['completed_at'] }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                                Belum ada riwayat kuis
                                                <div class="mt-2">
                                                    <a href="{{ route('user.quizz') }}" class="btn btn-primary btn-sm">
                                                        Mulai kuis pertama Anda
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('userProgressChart').getContext('2d');

    const userChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Remaining'],
            datasets: [{
                data: [
                    {{ $userStats['completedLevels'] ?? 0 }},
                    {{ max(0, ($userStats['totalLevels'] ?? 0) - ($userStats['completedLevels'] ?? 0)) }}
                ],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(200, 200, 200, 0.8)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(200, 200, 200, 1)'
                ],
                borderWidth: 2,
                borderRadius: 5,
                spacing: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1.5,
            cutout: '60%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
});
</script>