<div>
<div class="container-fluid">
    <div class="pagetitle">
        <h1>🏆 Admin Leaderboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Leaderboard</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <!-- Statistics Cards -->
        <div class="row">
            <!-- User Statistics -->
            <div class="col-lg-3 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $userStats['total_users'] }}</h6>
                                <span class="text-success small pt-1 fw-bold">{{ $userStats['active_users'] }}</span>
                                <span class="text-muted small pt-2 ps-1">active</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card revenue-card">
                    <div class="card-body">
                        <h5 class="card-title">Recent Activity</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-activity"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $userStats['recent_active_users'] }}</h6>
                                <span class="text-success small pt-1 fw-bold">users</span>
                                <span class="text-muted small pt-2 ps-1">last 7 days</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quiz Statistics -->
            <div class="col-lg-3 col-md-6">
                <div class="card info-card customers-card">
                    <div class="card-body">
                        <h5 class="card-title">Quiz Attempts</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-question-circle"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $quizStats['total_attempts'] }}</h6>
                                <span class="text-danger small pt-1 fw-bold">{{ $quizStats['recent_attempts'] }}</span>
                                <span class="text-muted small pt-2 ps-1">recent</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Overall Accuracy</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-graph-up"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $quizStats['overall_accuracy'] }}%</h6>
                                <span class="text-success small pt-1 fw-bold">{{ $quizStats['average_score'] }}</span>
                                <span class="text-muted small pt-2 ps-1">avg score</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Leaderboard Card -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card leaderboard-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title mb-0">User Leaderboard</h5>
                            <div class="d-flex gap-2">
                                <!-- Search -->
                                <div class="search-bar">
                                    <input type="text" wire:model.live="search" class="form-control" placeholder="Search users..." style="width: 250px;">
                                </div>
                                
                                <!-- Time Filter -->
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="bi bi-calendar me-1"></i>
                                        {{ ucfirst($timeFilter) }}
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" wire:click="setTimeFilter('all')">All Time</a></li>
                                        <li><a class="dropdown-item" href="#" wire:click="setTimeFilter('monthly')">This Month</a></li>
                                        <li><a class="dropdown-item" href="#" wire:click="setTimeFilter('weekly')">This Week</a></li>
                                    </ul>
                                </div>

                                <!-- Export Button -->
                                <button wire:click="exportLeaderboard" class="btn btn-success">
                                    <i class="bi bi-download me-1"></i>Export
                                </button>
                            </div>
                        </div>

                        <!-- Leaderboard Table -->
                        <div class="table-responsive">
                            <table class="table table-hover leaderboard-table">
                                <thead>
                                    <tr>
                                        <th width="80">Rank</th>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Levels</th>
                                        <th>Avg Score</th>
                                        <th>Best Score</th>
                                        <th>Accuracy</th>
                                        <th>Activity</th>
                                        <th>Status</th>
                                        <th width="100">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($globalLeaderboard as $user)
                                    <tr class="leaderboard-row {{ $user['role'] === 'admin' ? 'admin-row' : '' }}">
                                        <td>
                                            @if($user['rank'] <= 3)
                                                <div class="rank-badge rank-{{ $user['rank'] }}">
                                                    #{{ $user['rank'] }}
                                                </div>
                                            @else
                                                <span class="rank-number">#{{ $user['rank'] }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar">
                                                    <img src="{{ $user['avatar_url'] }}" 
                                                         alt="{{ $user['name'] }}"
                                                         onerror="this.src='/assets/img/default-avatar.png'">
                                                </div>
                                                <div class="user-info">
                                                    <div class="user-name">{{ $user['name'] }}</div>
                                                    <div class="user-details">
                                                        <span class="username">@{{ $user['username'] }}</span>
                                                        <span class="user-email">{{ $user['email'] }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="role-badge {{ $user['role'] === 'admin' ? 'role-admin' : 'role-user' }}">
                                                {{ ucfirst($user['role']) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="level-info">
                                                <strong>{{ $user['completed_levels'] }}</strong>
                                                <span>levels</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="score avg-score">{{ $user['average_score'] }}</span>
                                        </td>
                                        <td>
                                            <span class="score best-score">{{ $user['best_score'] }}</span>
                                        </td>
                                        <td>
                                            <div class="accuracy-container">
                                                <div class="accuracy-bar">
                                                    <div class="accuracy-progress" style="width: {{ $user['accuracy'] }}%"></div>
                                                </div>
                                                <div class="accuracy-text">
                                                    <span class="accuracy-percent">{{ $user['accuracy'] }}%</span>
                                                    <span class="accuracy-details">
                                                        {{ $user['total_correct'] }}/{{ $user['total_correct'] + $user['total_wrong'] }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="activity-info">
                                                <span class="activity-status">{{ $user['activity_status'] }}</span>
                                                <span class="join-date">Joined: {{ $user['joined_at'] }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($user['is_active'])
                                                <span class="status-badge status-active">Active</span>
                                            @else
                                                <span class="status-badge status-inactive">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                @if($user['role'] !== 'admin')
                                                    <button wire:click="toggleUserStatus({{ $user['id'] }})" 
                                                            class="btn-action {{ $user['is_active'] ? 'btn-pause' : 'btn-play' }}"
                                                            title="{{ $user['is_active'] ? 'Deactivate' : 'Activate' }}">
                                                        <i class="bi bi-{{ $user['is_active'] ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                @endif
                                                <a href="{{ route('admin.friend.profile', $user['id']) }}" 
                                                   class="btn-action btn-view"
                                                   title="View Profile">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="bi bi-people"></i>
                                                <h5>No users found</h5>
                                                <p>No users match your search criteria.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card summary-card">
                                    <div class="card-body">
                                        <h6 class="card-title">User Distribution</h6>
                                        <div class="distribution-grid">
                                            <div class="distribution-item">
                                                <h4 class="distribution-count admin-count">{{ $userStats['admin_users'] }}</h4>
                                                <span class="distribution-label">Admins</span>
                                            </div>
                                            <div class="distribution-item">
                                                <h4 class="distribution-count user-count">{{ $userStats['regular_users'] }}</h4>
                                                <span class="distribution-label">Regular Users</span>
                                            </div>
                                            <div class="distribution-item">
                                                <h4 class="distribution-count inactive-count">{{ $userStats['inactive_users'] }}</h4>
                                                <span class="distribution-label">Inactive</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card summary-card">
                                    <div class="card-body">
                                        <h6 class="card-title">Quiz Statistics</h6>
                                        <div class="distribution-grid">
                                            <div class="distribution-item">
                                                <h4 class="distribution-count levels-count">{{ $quizStats['total_levels'] }}</h4>
                                                <span class="distribution-label">Total Levels</span>
                                            </div>
                                            <div class="distribution-item">
                                                <h4 class="distribution-count attempts-count">{{ $quizStats['total_attempts'] }}</h4>
                                                <span class="distribution-label">Total Attempts</span>
                                            </div>
                                            <div class="distribution-item">
                                                <h4 class="distribution-count accuracy-count">{{ $quizStats['overall_accuracy'] }}%</h4>
                                                <span class="distribution-label">Accuracy</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
/* Main Card Styling */
.leaderboard-card {
    background: linear-gradient(135deg, rgba(45, 0, 80, 0.05), rgba(25, 0, 50, 0.02)) !important;
    border: 1px solid rgba(120, 80, 255, 0.1) !important;
    border-radius: 20px !important;
    box-shadow: 0 8px 32px rgba(186, 104, 255, 0.15) !important;
    backdrop-filter: blur(10px);
}

.leaderboard-card .card-body {
    padding: 2rem;
}

/* Card Title */
.card-title {
    background: linear-gradient(135deg, #ff0080, #7928ca, #00d2ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 700;
    font-size: 1.5rem;
}

/* Statistics Cards */
.info-card {
    border: none !important;
    border-radius: 16px !important;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.8)) !important;
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 25px rgba(186, 104, 255, 0.1) !important;
    transition: all 0.3s ease;
    border: 1px solid rgba(120, 80, 255, 0.1) !important;
}

.info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 35px rgba(186, 104, 255, 0.2) !important;
}

.info-card .card-icon {
    width: 60px;
    height: 60px;
    font-size: 1.5rem;
    border-radius: 16px !important;
    background: linear-gradient(135deg, #ff0080, #7928ca) !important;
    box-shadow: 0 4px 15px rgba(255, 0, 128, 0.3);
}

.info-card .card-title {
    color: #2c3e50 !important;
    font-size: 0.9rem;
    font-weight: 600;
    -webkit-text-fill-color: #2c3e50;
    background: none;
}

.info-card h6 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
}

/* Table Styling */
.leaderboard-table {
    border-radius: 12px;
    overflow: hidden;
    background: white;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.leaderboard-table thead {
    background: linear-gradient(135deg, #3a0072, #1a0033) !important;
}

.leaderboard-table thead th {
    color: white !important;
    font-weight: 600;
    padding: 1rem 0.75rem;
    border: none;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.leaderboard-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(120, 80, 255, 0.1);
}

.leaderboard-table tbody tr:hover {
    background: linear-gradient(135deg, rgba(255, 0, 128, 0.03), rgba(121, 40, 202, 0.03)) !important;
    transform: translateX(4px);
}

.leaderboard-row.admin-row {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 193, 7, 0.05)) !important;
    border-left: 4px solid #ffc107;
}

/* Rank Badges */
.rank-badge {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
    color: white;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.rank-1 {
    background: linear-gradient(135deg, #FFD700, #FFA500) !important;
}

.rank-2 {
    background: linear-gradient(135deg, #C0C0C0, #A0A0A0) !important;
}

.rank-3 {
    background: linear-gradient(135deg, #CD7F32, #A67C52) !important;
}

.rank-number {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.9rem;
}

/* User Avatar */
.user-avatar {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    overflow: hidden;
    margin-right: 1rem;
    border: 3px solid transparent;
    background: linear-gradient(135deg, #ff0080, #7928ca) padding-box;
    box-shadow: 0 4px 15px rgba(255, 0, 128, 0.2);
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 9px;
}

.user-name {
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.25rem;
}

.user-details {
    display: flex;
    flex-direction: column;
    gap: 0.1rem;
}

.username {
    color: #ff0080;
    font-size: 0.8rem;
    font-weight: 600;
}

.user-email {
    color: #6c757d;
    font-size: 0.75rem;
}

/* Role Badges */
.role-badge {
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.role-admin {
    background: linear-gradient(135deg, #ff0080, #ff2e8b) !important;
    color: white;
    box-shadow: 0 4px 15px rgba(255, 0, 128, 0.3);
}

.role-user {
    background: linear-gradient(135deg, #7928ca, #9d4edd) !important;
    color: white;
    box-shadow: 0 4px 15px rgba(121, 40, 202, 0.3);
}

/* Scores */
.score {
    font-weight: 700;
    font-size: 1.1rem;
}

.avg-score {
    color: #7928ca;
}

.best-score {
    color: #00d2ff;
}

/* Accuracy Bar */
.accuracy-container {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.accuracy-bar {
    width: 100%;
    height: 8px;
    background: rgba(120, 80, 255, 0.1);
    border-radius: 4px;
    overflow: hidden;
}

.accuracy-progress {
    height: 100%;
    background: linear-gradient(90deg, #00d2ff, #7928ca);
    border-radius: 4px;
    transition: width 0.3s ease;
}

.accuracy-text {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.accuracy-percent {
    font-weight: 700;
    color: #7928ca;
    font-size: 0.9rem;
}

.accuracy-details {
    color: #6c757d;
    font-size: 0.75rem;
}

/* Status Badges */
.status-badge {
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
}

.status-active {
    background: linear-gradient(135deg, #00d2ff, #0099cc) !important;
    color: white;
    box-shadow: 0 4px 15px rgba(0, 210, 255, 0.3);
}

.status-inactive {
    background: linear-gradient(135deg, #6c757d, #495057) !important;
    color: white;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    width: 35px;
    height: 35px;
    border: none;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    color: white;
    font-size: 0.8rem;
}

.btn-pause {
    background: linear-gradient(135deg, #ff0080, #ff2e8b) !important;
}

.btn-play {
    background: linear-gradient(135deg, #00d2ff, #0099cc) !important;
}

.btn-view {
    background: linear-gradient(135deg, #7928ca, #9d4edd) !important;
}

.btn-action:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

/* Summary Cards */
.summary-card {
    border: none !important;
    border-radius: 16px !important;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.8)) !important;
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 25px rgba(186, 104, 255, 0.1) !important;
    border: 1px solid rgba(120, 80, 255, 0.1) !important;
}

.summary-card .card-title {
    color: #2c3e50 !important;
    font-size: 1rem;
    font-weight: 600;
    -webkit-text-fill-color: #2c3e50;
    background: none;
}

.distribution-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    text-align: center;
}

.distribution-item {
    padding: 0.5rem;
}

.distribution-count {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.admin-count { color: #ff0080; }
.user-count { color: #7928ca; }
.inactive-count { color: #6c757d; }
.levels-count { color: #00d2ff; }
.attempts-count { color: #7928ca; }
.accuracy-count { color: #ff0080; }

.distribution-label {
    font-size: 0.75rem;
    color: #6c757d;
    font-weight: 600;
}

/* Empty State */
.empty-state {
    color: #6c757d;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h5 {
    margin-bottom: 0.5rem;
}

/* Search and Filter */
.search-bar input {
    border-radius: 20px;
    padding: 0.5rem 1rem;
    border: 1px solid rgba(120, 80, 255, 0.2);
    background: rgba(255, 255, 255, 0.9);
}

.search-bar input:focus {
    border-color: #7928ca;
    box-shadow: 0 0 0 0.2rem rgba(121, 40, 202, 0.25);
}

.btn-outline-primary {
    border-color: #7928ca;
    color: #7928ca;
}

.btn-outline-primary:hover {
    background: #7928ca;
    border-color: #7928ca;
}

.btn-success {
    background: linear-gradient(135deg, #00d2ff, #0099cc) !important;
    border: none;
    border-radius: 20px;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 210, 255, 0.4);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh leaderboard every 30 seconds
    setInterval(() => {
        Livewire.dispatch('refresh');
    }, 30000);
});
</script>