<div>
<div class="user-search-component">
    <!-- Search Input -->
    <div class="search-input-container mb-3">
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="search" 
                   wire:model.live="search" 
                   class="form-control border-start-0" 
                   placeholder="Search users by name or username..." <!-- ✅ UPDATE PLACEHOLDER -->
                   autocomplete="off">
        </div>
    </div>

    <!-- Flash Message -->
    @if($message)
        <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" wire:click="$set('message', '')"></button>
        </div>
    @endif

    <!-- Search Results -->
    @if($search && strlen($search) >= 2)
        <div class="search-results">
            @if(count($searchResults) > 0)
                <div class="results-list">
                    @foreach($searchResults as $user)
                        <div class="friend-result-item p-3 border-bottom">
                            <div class="d-flex align-items-center justify-content-between">
                                <!-- User Info -->
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user['avatar_url'] }}" 
                                         alt="{{ $user['name'] }}" 
                                         class="rounded-circle me-3"
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $user['name'] }}</h6>
                                        <small class="text-muted d-block">@{{ $user['username'] }}</small> <!-- ✅ TAMPILKAN USERNAME -->
                                        <small class="text-muted">
                                            {{ $user['activity_status'] }} • 
                                            {{ $user['completed_levels'] }} levels completed
                                        </small>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="action-buttons">
                                    @switch($user['friendship_status'])
                                        @case('not_friends')
                                            <button wire:click="sendFriendRequest({{ $user['id'] }})" 
                                                    class="btn btn-sm btn-primary">
                                                <i class="bi bi-person-plus"></i> Add
                                            </button>
                                            @break

                                        @case('pending')
                                            @if($user['is_pending_from_me'])
                                                <button wire:click="cancelFriendRequest({{ $user['id'] }})" 
                                                        class="btn btn-sm btn-warning">
                                                    <i class="bi bi-clock"></i> Pending
                                                </button>
                                            @else
                                                <div class="btn-group">
                                                    <button wire:click="acceptFriendRequest({{ $user['id'] }})" 
                                                            class="btn btn-sm btn-success">
                                                        <i class="bi bi-check-lg"></i> Accept
                                                    </button>
                                                    <button wire:click="rejectFriendRequest({{ $user['id'] }})" 
                                                            class="btn btn-sm btn-danger">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </div>
                                            @endif
                                            @break

                                        @case('accepted')
                                            <div class="btn-group">
                                                <a href="{{ route('admin.friend.profile', $user['id']) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                                <button wire:click="removeFriend({{ $user['id'] }})" 
                                                        class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-person-dash"></i>
                                                </button>
                                            </div>
                                            @break
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-3">
                    <i class="bi bi-search display-4 text-muted"></i>
                    <p class="text-muted mt-2">No users found for "{{ $search }}"</p>
                </div>
            @endif
        </div>
    @elseif($search && strlen($search) < 2)
        <div class="text-center py-2">
            <small class="text-muted">Type at least 2 characters</small>
        </div>
    @else
        <!-- Quick Actions -->
        <div class="recent-activity">
            <h6 class="text-muted mb-3">Quick Actions</h6>
            <div class="d-grid gap-2">
                <a href="{{ route('admin.friends') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-people me-2"></i>View All Friends
                </a>
                <a href="{{ route('admin.leaderboard') }}" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-trophy me-2"></i>View Leaderboard
                </a>
            </div>
        </div>
    @endif
</div>

<style>
.search-results {
    max-height: 300px;
    overflow-y: auto;
}

.friend-result-item {
    transition: all 0.2s ease;
}

.friend-result-item:hover {
    background-color: #f8f9fa;
    border-radius: 8px;
}

.action-buttons .btn {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.results-list {
    border: 1px solid #e9ecef;
    border-radius: 8px;
}
</style>
</div>