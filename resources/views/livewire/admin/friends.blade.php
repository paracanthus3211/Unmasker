<div>
    <div class="pagetitle">
        <h1>👥 My Friends</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Friends</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Tabs Navigation -->
                        <ul class="nav nav-tabs nav-tabs-bordered" id="friendsTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeTab === 'friends' ? 'active' : '' }}" 
                                        wire:click="setActiveTab('friends')"
                                        type="button">
                                    <i class="bi bi-people me-1"></i>
                                    Friends
                                    <span class="badge bg-primary ms-1">{{ count($friends) }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeTab === 'requests' ? 'active' : '' }}" 
                                        wire:click="setActiveTab('requests')"
                                        type="button">
                                    <i class="bi bi-clock me-1"></i>
                                    Requests
                                    <span class="badge bg-warning ms-1">{{ count($pendingRequests) }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeTab === 'sent' ? 'active' : '' }}" 
                                        wire:click="setActiveTab('sent')"
                                        type="button">
                                    <i class="bi bi-send me-1"></i>
                                    Sent
                                    <span class="badge bg-info ms-1">{{ count($sentRequests) }}</span>
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content pt-3">
                            <!-- Friends Tab -->
                            @if($activeTab === 'friends')
                                <div class="tab-pane fade show active">
                                    @if(count($friends) > 0)
                                        <div class="row">
                                            @foreach($friends as $friend)
                                                <div class="col-md-6 col-lg-4 mb-3">
                                                    <div class="card friend-card h-100">
                                                        <div class="card-body text-center">
                                                            <img src="{{ $friend->avatar_url }}" 
                                                                 alt="{{ $friend->name }}"
                                                                 class="rounded-circle mb-3"
                                                                 style="width: 80px; height: 80px; object-fit: cover;">
                                                            <h5 class="card-title">{{ $friend->name }}</h5>
                                                            <p class="text-muted small mb-2">
                                                                <i class="bi bi-graph-up me-1"></i>
                                                                {{ $friend->getCompletedLevelsCount() }} levels completed
                                                            </p>
                                                            <p class="text-muted small mb-3">
                                                                <i class="bi bi-activity me-1"></i>
                                                                {{ $friend->getActivityStatus() }}
                                                            </p>
                                                            <div class="btn-group w-100">
                                                                <a href="{{ route('admin.friend.profile', $friend->id) }}" 
                                                                   class="btn btn-outline-primary btn-sm">
                                                                    <i class="bi bi-eye me-1"></i>View Profile
                                                                </a>
                                                                <button wire:click="removeFriend({{ $friend->pivot->id }})" 
                                                                        class="btn btn-outline-danger btn-sm"
                                                                        onclick="return confirm('Remove {{ $friend->name }} from friends?')">
                                                                    <i class="bi bi-person-dash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <i class="bi bi-people display-1 text-muted"></i>
                                            <h5 class="text-muted mt-3">No friends yet</h5>
                                            <p class="text-muted">Start adding friends to see their progress!</p>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Pending Requests Tab -->
                            @if($activeTab === 'requests')
                                <div class="tab-pane fade show active">
                                    @if(count($pendingRequests) > 0)
                                        <div class="row">
                                            @foreach($pendingRequests as $request)
                                                <div class="col-md-6 col-lg-4 mb-3">
                                                    <div class="card request-card h-100">
                                                        <div class="card-body text-center">
                                                            <img src="{{ $request->user->avatar_url }}" 
                                                                 alt="{{ $request->user->name }}"
                                                                 class="rounded-circle mb-3"
                                                                 style="width: 80px; height: 80px; object-fit: cover;">
                                                            <h5 class="card-title">{{ $request->user->name }}</h5>
                                                            <p class="text-muted small mb-2">
                                                                {{ $request->user->getActivityStatus() }}
                                                            </p>
                                                            <p class="text-muted small mb-3">
                                                                {{ $request->user->getCompletedLevelsCount() }} levels completed
                                                            </p>
                                                            <div class="btn-group w-100">
                                                                <button wire:click="acceptRequest({{ $request->id }})" 
                                                                        class="btn btn-success btn-sm">
                                                                    <i class="bi bi-check-lg me-1"></i>Accept
                                                                </button>
                                                                <button wire:click="rejectRequest({{ $request->id }})" 
                                                                        class="btn btn-outline-danger btn-sm">
                                                                    <i class="bi bi-x-lg"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <i class="bi bi-inbox display-1 text-muted"></i>
                                            <h5 class="text-muted mt-3">No friend requests</h5>
                                            <p class="text-muted">Friend requests will appear here.</p>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Sent Requests Tab -->
                            @if($activeTab === 'sent')
                                <div class="tab-pane fade show active">
                                    @if(count($sentRequests) > 0)
                                        <div class="row">
                                            @foreach($sentRequests as $request)
                                                <div class="col-md-6 col-lg-4 mb-3">
                                                    <div class="card sent-card h-100">
                                                        <div class="card-body text-center">
                                                            <img src="{{ $request->friend->avatar_url }}" 
                                                                 alt="{{ $request->friend->name }}"
                                                                 class="rounded-circle mb-3"
                                                                 style="width: 80px; height: 80px; object-fit: cover;">
                                                            <h5 class="card-title">{{ $request->friend->name }}</h5>
                                                            <p class="text-muted small mb-2">
                                                                {{ $request->friend->getActivityStatus() }}
                                                            </p>
                                                            <p class="text-muted small mb-3">
                                                                {{ $request->friend->getCompletedLevelsCount() }} levels completed
                                                            </p>
                                                            <button wire:click="cancelRequest({{ $request->id }})" 
                                                                    class="btn btn-warning btn-sm w-100"
                                                                    onclick="return confirm('Cancel friend request to {{ $request->friend->name }}?')">
                                                                <i class="bi bi-clock me-1"></i>Cancel
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <i class="bi bi-send display-1 text-muted"></i>
                                            <h5 class="text-muted mt-3">No sent requests</h5>
                                            <p class="text-muted">Friend requests you've sent will appear here.</p>
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