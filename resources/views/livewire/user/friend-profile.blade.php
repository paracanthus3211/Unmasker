<div>
    <div class="pagetitle">
        <h1>👤 Profil Teman</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.friends') }}">Teman</a></li>
                <li class="breadcrumb-item active">Profil</li>
            </ol>
        </nav>
    </div>

    <section class="section profile">
        <div class="row">
            <!-- Profile Card -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <img src="{{ $user->avatar_url }}" alt="Profile" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                        <h2 class="mt-3">{{ $user->name }}</h2>
                        <h3 class="text-muted">{{ $user->getActivityStatus() }}</h3>
                        
                        <!-- Friendship Status & Actions -->
                        <div class="mt-3">
                            @switch($friendshipStatus)
                                @case('accepted')
                                    <span class="badge bg-success fs-6">
                                        <i class="bi bi-check-circle me-1"></i>Teman
                                    </span>
                                    <button wire:click="removeFriend" class="btn btn-outline-danger btn-sm ms-2">
                                        <i class="bi bi-person-dash"></i> Hapus
                                    </button>
                                    @break
                                    
                                @case('pending')
                                    <span class="badge bg-warning text-dark fs-6">
                                        <i class="bi bi-clock me-1"></i>Permintaan Dikirim
                                    </span>
                                    @break
                                    
                                @case('not_friends')
                                    <button wire:click="sendFriendRequest" class="btn btn-primary">
                                        <i class="bi bi-person-plus me-1"></i>Tambah Teman
                                    </button>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>

                <!-- Progress Stats -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">📊 Statistik Progress</h5>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="mb-3">
                                    <h6 class="text-muted mb-0">Level Selesai</h6>
                                    <h4 class="fw-bold text-primary">{{ $userStats['completedLevels'] }}</h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <h6 class="text-muted mb-0">Rata-rata Nilai</h6>
                                    <h4 class="fw-bold text-success">{{ $userStats['averageScore'] }}</h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <h6 class="text-muted mb-0">Total Kuis</h6>
                                    <h4 class="fw-bold text-info">{{ $userStats['totalQuizAttempts'] }}</h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <h6 class="text-muted mb-0">Progress</h6>
                                    <h4 class="fw-bold text-warning">{{ $userStats['progressPercentage'] }}%</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Section -->
            <div class="col-xl-8">
                <!-- Overall Progress -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">🎯 Progress Keseluruhan</h5>
                        <div class="progress mb-3" style="height: 20px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $userStats['progressPercentage'] }}%"
                                 aria-valuenow="{{ $userStats['progressPercentage'] }}" 
                                 aria-valuemin="0" aria-valuemax="100">
                                {{ $userStats['progressPercentage'] }}%
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="border rounded p-2">
                                    <small class="text-muted">Nilai Tertinggi</small>
                                    <h6 class="mb-0 fw-bold text-success">{{ $userStats['bestScore'] }}</h6>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded p-2">
                                    <small class="text-muted">Jawaban Benar</small>
                                    <h6 class="mb-0 fw-bold text-primary">{{ $userStats['totalCorrectAnswers'] }}</h6>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded p-2">
                                    <small class="text-muted">Status</small>
                                    <h6 class="mb-0 fw-bold text-info">{{ $userStats['activityStatus'] }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Quiz History -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">📋 Riwayat Kuis Terbaru</h5>
                        @if(count($quizHistory) > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Level</th>
                                            <th>Skor</th>
                                            <th>Benar</th>
                                            <th>Salah</th>
                                            <th>Waktu</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($quizHistory as $history)
                                        <tr>
                                            <td class="fw-bold">{{ $history['level_name'] }}</td>
                                            <td>
                                                <span class="badge bg-{{ $history['score'] >= 3 ? 'success' : 'warning' }}">
                                                    {{ $history['score'] }}/5
                                                </span>
                                            </td>
                                            <td class="text-success fw-bold">{{ $history['correct_answers'] }}</td>
                                            <td class="text-danger">{{ $history['wrong_answers'] }}</td>
                                            <td>{{ $history['completion_time'] }}s</td>
                                            <td>{{ $history['completed_at'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-clock-history display-4 text-muted"></i>
                                <p class="text-muted mt-3">Belum ada riwayat kuis</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Flash Message -->
    @if(session()->has('message'))
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div class="toast show" role="alert">
                <div class="toast-header">
                    <i class="bi bi-info-circle text-primary me-2"></i>
                    <strong class="me-auto">Info</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    {{ session('message') }}
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.profile-card {
    text-align: center;
}

.progress {
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
}

.toast {
    z-index: 1055;
}
</style>

<script>
// Auto-hide toast after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const toast = document.querySelector('.toast');
    if (toast) {
        setTimeout(() => {
            const bsToast = new bootstrap.Toast(toast);
            bsToast.hide();
        }, 5000);
    }
});
</script>
</div>