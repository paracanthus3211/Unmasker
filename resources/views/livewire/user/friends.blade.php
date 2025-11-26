<div class="container-fluid">
    <div class="pagetitle">
        <h1>👥 My Friends</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Friends</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab === 'friends' ? 'active' : '' }}" wire:click="setActiveTab('friends')">
                                    <i class="bi bi-people me-1"></i>Friends <span class="badge bg-primary ms-1">{{ count($friends) }}</span>
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab === 'requests' ? 'active' : '' }}" wire:click="setActiveTab('requests')">
                                    <i class="bi bi-clock me-1"></i>Requests <span class="badge bg-warning ms-1">{{ count($pendingRequests) }}</span>
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab === 'sent' ? 'active' : '' }}" wire:click="setActiveTab('sent')">
                                    <i class="bi bi-send me-1"></i>Sent <span class="badge bg-info ms-1">{{ count($sentRequests) }}</span>
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab === 'search' ? 'active' : '' }}" wire:click="setActiveTab('search')">
                                    <i class="bi bi-search me-1"></i>Find Friends
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content pt-3">
                            @if($activeTab === 'friends')
                            <div class="tab-pane fade show active">
                                @if(count($friends) > 0)
                                <div class="row">
                                    @foreach($friends as $friend)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body text-center">
                                                <img src="{{ $friend->avatar_url }}" alt="{{ $friend->name }}" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                                                <h5 class="card-title">{{ $friend->name }}</h5>
                                                <p class="text-muted small mb-2">@{{ $friend->username }}</p>
                                                <p class="text-muted small mb-2"><i class="bi bi-graph-up me-1"></i>{{ $friend->completed_levels }} levels completed</p>
                                                <p class="text-muted small mb-3"><i class="bi bi-activity me-1"></i>{{ $friend->activity_status }}</p>
                                                <div class="btn-group w-100">
                                                    <a href="{{ route('user.friend.profile', $friend->id) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye me-1"></i>View Profile</a>
                                                    @php $friendship = \App\Models\Friendship::betweenUsers(auth()->id(), $friend->id)->first(); @endphp
                                                    @if($friendship)
                                                    <button wire:click="removeFriend({{ $friendship->id }})" class="btn btn-outline-danger btn-sm" onclick="return confirm('Remove {{ $friend->name }} from friends?')"><i class="bi bi-person-dash"></i></button>
                                                    @endif
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
                                    <button wire:click="setActiveTab('search')" class="btn btn-primary"><i class="bi bi-search me-2"></i>Find Friends</button>
                                </div>
                                @endif
                            </div>
                            @endif

                            @if($activeTab === 'requests')
                            <div class="tab-pane fade show active">
                                @if(count($pendingRequests) > 0)
                                <div class="row">
                                    @foreach($pendingRequests as $request)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body text-center">
                                                <img src="{{ $request->user->avatar_url }}" alt="{{ $request->user->name }}" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                                                <h5 class="card-title">{{ $request->user->name }}</h5>
                                                <p class="text-muted small mb-2">@{{ $request->user->username }}</p>
                                                <p class="text-muted small mb-2">{{ $request->user->activity_status }}</p>
                                                <p class="text-muted small mb-3">{{ $request->user->completed_levels }} levels completed</p>
                                                <div class="btn-group w-100">
                                                    <button wire:click="acceptRequest({{ $request->id }})" class="btn btn-success btn-sm"><i class="bi bi-check-lg me-1"></i>Accept</button>
                                                    <button wire:click="rejectRequest({{ $request->id }})" class="btn btn-outline-danger btn-sm"><i class="bi bi-x-lg"></i></button>
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
                                    <p class="text-muted">Friend requests will appear here when someone sends you one.</p>
                                </div>
                                @endif
                            </div>
                            @endif

                            @if($activeTab === 'sent')
                            <div class="tab-pane fade show active">
                                @if(count($sentRequests) > 0)
                                <div class="row">
                                    @foreach($sentRequests as $request)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body text-center">
                                                <img src="{{ $request->friend->avatar_url }}" alt="{{ $request->friend->name }}" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                                                <h5 class="card-title">{{ $request->friend->name }}</h5>
                                                <p class="text-muted small mb-2">@{{ $request->friend->username }}</p>
                                                <p class="text-muted small mb-2">{{ $request->friend->activity_status }}</p>
                                                <p class="text-muted small mb-3">{{ $request->friend->completed_levels }} levels completed</p>
                                                <button wire:click="cancelRequest({{ $request->id }})" class="btn btn-warning btn-sm w-100" onclick="return confirm('Cancel friend request to {{ $request->friend->name }}?')"><i class="bi bi-clock me-1"></i>Cancel Request</button>
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

                            @if($activeTab === 'search')
                            <div class="tab-pane fade show active">
                                <div class="mb-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                                        <input type="search" wire:model.live="search" class="form-control border-start-0" placeholder="Search users by name or username..." autocomplete="off">
                                    </div>
                                </div>

                                @if($search && strlen($search) >= 2)
                                <div style="max-height: 400px; overflow-y: auto;">
                                    @if(count($searchResults) > 0)
                                    <div style="border: 1px solid #e9ecef; border-radius: 8px; padding: 10px;">
                                        @foreach($searchResults as $result)
                                        <div class="p-3 border rounded mb-3" style="transition: all 0.2s ease;">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $result['avatar_url'] }}" alt="{{ $result['name'] }}" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $result['name'] }}</h6>
                                                        <small class="text-muted d-block">@{{ $result['username'] }}</small>
                                                        <small class="text-muted">{{ $result['activity_status'] }} • {{ $result['completed_levels'] }} levels completed</small>
                                                    </div>
                                                </div>
                                                <div>
                                                    @switch($result['friendship_status'])
                                                        @case('not_friends')
                                                        <button wire:click="sendFriendRequest({{ $result['id'] }})" class="btn btn-sm btn-primary"><i class="bi bi-person-plus me-1"></i>Add Friend</button>
                                                        @break
                                                        @case('pending')
                                                        @if($result['is_pending_from_me'])
                                                        <span class="badge bg-warning">Request Sent</span>
                                                        @else
                                                        <span class="badge bg-info">Pending Your Response</span>
                                                        @endif
                                                        @break
                                                        @case('accepted')
                                                        <span class="badge bg-success">Friends</span>
                                                        @break
                                                    @endswitch
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="text-center py-4">
                                        <i class="bi bi-search display-4 text-muted"></i>
                                        <p class="text-muted mt-2">No users found for "{{ $search }}"</p>
                                    </div>
                                    @endif
                                </div>
                                @elseif($search && strlen($search) < 2)
                                <div class="text-center py-3">
                                    <small class="text-muted">Type at least 2 characters to search</small>
                                </div>
                                @else
                                <div class="text-center py-4">
                                    <i class="bi bi-search display-4 text-muted"></i>
                                    <p class="text-muted mt-3">Search for users by name or username</p>
                                    <small class="text-muted">Type at least 2 characters to start searching</small>
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