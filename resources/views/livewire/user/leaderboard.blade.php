<div>
<div class="container-fluid">
    <div class="pagetitle">
        <h1>🏆 Leaderboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Leaderboard</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <!-- User Stats Card -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Your Stats</h5>
                        <div class="stats-grid">
                            <div class="stat-item text-center p-3 border rounded">
                                <h3 class="text-primary mb-0">#{{ $userStats['rank'] }}</h3>
                                <small class="text-muted">Global Rank</small>
                            </div>
                            <div class="stat-item text-center p-3 border rounded">
                                <h3 class="text-success mb-0">{{ $userStats['completed_levels'] }}</h3>
                                <small class="text-muted">Levels Completed</small>
                            </div>
                            <div class="stat-item text-center p-3 border rounded">
                                <h3 class="text-warning mb-0">{{ $userStats['average_score'] }}</h3>
                                <small class="text-muted">Avg Score</small>
                            </div>
                            <div class="stat-item text-center p-3 border rounded">
                                <h3 class="text-info mb-0">{{ $userStats['accuracy'] }}%</h3>
                                <small class="text-muted">Accuracy</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leaderboard Content -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <!-- Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab === 'global' ? 'active' : '' }}" 
                                        wire:click="setActiveTab('global')">
                                    <i class="bi bi-globe me-1"></i>Global Leaderboard
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab === 'friends' ? 'active' : '' }}" 
                                        wire:click="setActiveTab('friends')">
                                    <i class="bi bi-people me-1"></i>Friends Comparison
                                    <span class="badge bg-primary ms-1">{{ count($friendsLeaderboard) }}</span>
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content pt-3">
                            <!-- Global Leaderboard -->
                            @if($activeTab === 'global')
                            <div class="tab-pane fade show active">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Rank</th>
                                                <th>User</th>
                                                <th>Levels</th>
                                                <th>Avg Score</th>
                                                <th>Best Score</th>
                                                <th>Accuracy</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($leaderboard as $user)
                                            <tr class="{{ $user->id === auth()->id() ? 'table-primary' : '' }}">
                                                <td>
                                                    @if($user->rank <= 3)
                                                    <span class="badge bg-warning">#{{ $user->rank }}</span>
                                                    @else
                                                    <span class="text-muted">#{{ $user->rank }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $user->avatar_url }}" 
                                                             class="rounded-circle me-2"
                                                             style="width: 32px; height: 32px; object-fit: cover;">
                                                        <div>
                                                            <div class="fw-bold">{{ $user->name }}</div>
                                                            <small class="text-muted d-block">{{ $user['username'] }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $user->completed_levels }}</td>
                                                <td>{{ $user->average_score }}</td>
                                                <td>{{ $user->best_score }}</td>
                                                <td>
                                                    <div class="progress" style="height: 8px; width: 80px;">
                                                        <div class="progress-bar bg-success" 
                                                             style="width: {{ $user->accuracy }}%">
                                                        </div>
                                                    </div>
                                                    <small>{{ $user->accuracy }}%</small>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif

                            <!-- Friends Comparison -->
                            @if($activeTab === 'friends')
                            <div class="tab-pane fade show active">
                                @if(count($friendsLeaderboard) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Rank</th>
                                                <th>User</th>
                                                <th>Levels</th>
                                                <th>Avg Score</th>
                                                <th>Progress</th>
                                                <th>Accuracy</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($friendsLeaderboard as $user)
                                            <tr class="{{ isset($user->is_current_user) ? 'table-primary' : '' }}">
                                                <td>
                                                    @if($user->rank <= 3)
                                                    <span class="badge bg-warning">#{{ $user->rank }}</span>
                                                    @else
                                                    <span class="text-muted">#{{ $user->rank }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $user->avatar_url }}" 
                                                             class="rounded-circle me-2"
                                                             style="width: 32px; height: 32px; object-fit: cover;">
                                                        <div>
                                                            <div class="fw-bold">
                                                                {{ $user->name }}
                                                                @if(isset($user->is_current_user))
                                                                <span class="badge bg-primary ms-1">You</span>
                                                                @endif
                                                            </div>
                                                            <small class="text-muted">@{{ $user->username }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $user->completed_levels }}</td>
                                                <td>{{ $user->average_score }}</td>
                                                <td>
                                                    <div class="progress" style="height: 8px; width: 100px;">
                                                        <div class="progress-bar bg-info" 
                                                             style="width: {{ $user->overall_progress }}%">
                                                        </div>
                                                    </div>
                                                    <small>{{ $user->overall_progress }}%</small>
                                                </td>
                                                <td>{{ $user->accuracy }}%</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="text-center py-5">
                                    <i class="bi bi-people display-1 text-muted"></i>
                                    <h5 class="text-muted mt-3">No friends yet</h5>
                                    <p class="text-muted">Add friends to compare your progress!</p>
                                    <a href="{{ route('user.friends') }}" class="btn btn-primary">
                                        <i class="bi bi-person-plus me-2"></i>Find Friends
                                    </a>
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.stat-item {
    background: #f8f9fa;
}
.progress {
    background-color: #e9ecef;
}
</style>