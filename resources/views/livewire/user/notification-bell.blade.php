<div>
<div class="dropdown">
    <button class="btn btn-outline-light position-relative border-0" 
            type="button" 
            wire:click="toggleNotifications"
            id="notificationDropdown"
            data-bs-toggle="dropdown"
            aria-expanded="false">
        <i class="bi bi-bell fs-5"></i>
        @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $unreadCount }}
                <span class="visually-hidden">unread notifications</span>
            </span>
        @endif
    </button>

    <div class="dropdown-menu dropdown-menu-end p-0" 
         style="width: 380px; max-height: 500px; overflow-y: auto;"
         aria-labelledby="notificationDropdown">
        
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center p-3 border-bottom bg-light">
            <h6 class="mb-0 fw-bold">Notifications</h6>
            <div class="d-flex gap-2">
                @if($unreadCount > 0)
                    <button wire:click="markAllAsRead" 
                            class="btn btn-sm btn-outline-primary"
                            title="Mark all as read">
                        <i class="bi bi-check-all"></i>
                    </button>
                @endif
                @if(count($notifications) > 0)
                    <button wire:click="clearAllNotifications" 
                            class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('Clear all notifications?')"
                            title="Clear all">
                        <i class="bi bi-trash"></i>
                    </button>
                @endif
            </div>
        </div>

        <!-- Notifications List -->
        <div class="notification-list">
            @forelse($notifications as $notification)
                @php
                    $isUnread = is_null($notification->read_at);
                    $data = $notification->data;
                    $type = $data['type'] ?? 'unknown';
                @endphp
                
                <div class="notification-item border-bottom {{ $isUnread ? 'bg-light' : '' }}">
                    <div class="p-3">
                        <div class="d-flex align-items-start gap-2">
                            <!-- Avatar -->
                            @if($type === 'friend_request' && isset($data['sender_avatar']))
                                <img src="{{ $data['sender_avatar'] }}" 
                                     class="rounded-circle flex-shrink-0"
                                     style="width: 40px; height: 40px; object-fit: cover;"
                                     alt="{{ $data['sender_name'] ?? 'User' }}">
                            @else
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width: 40px; height: 40px;">
                                    <i class="bi bi-bell text-white"></i>
                                </div>
                            @endif

                            <!-- Content -->
                            <div class="flex-grow-1">
                                <!-- Message -->
                                <p class="mb-1 small">
                                    {{ $data['message'] ?? 'Notification' }}
                                </p>

                                <!-- Actions for Friend Requests -->
                                @if($type === 'friend_request' && $isUnread)
                                    <div class="d-flex gap-1 mt-2">
                                        <button wire:click="acceptFriendRequest('{{ $notification->id }}')" 
                                                class="btn btn-sm btn-success">
                                            <i class="bi bi-check-lg me-1"></i>Accept
                                        </button>
                                        <button wire:click="rejectFriendRequest('{{ $notification->id }}')" 
                                                class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-x-lg"></i>Reject
                                        </button>
                                    </div>
                                @endif

                                <!-- Timestamp -->
                                <small class="text-muted d-block mt-1">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </div>

                            <!-- Read Status -->
                            @if($isUnread)
                                <button wire:click="markAsRead('{{ $notification->id }}')" 
                                        class="btn btn-sm btn-outline-secondary flex-shrink-0"
                                        title="Mark as read">
                                    <i class="bi bi-check"></i>
                                </button>
                            @else
                                <span class="badge bg-secondary flex-shrink-0">Read</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="text-center py-5">
                    <i class="bi bi-bell display-4 text-muted"></i>
                    <p class="text-muted mt-3 mb-0">No notifications</p>
                    <small class="text-muted">Notifications will appear here</small>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        @if(count($notifications) > 0)
            <div class="p-2 border-top bg-light text-center">
                <small class="text-muted">
                    Showing {{ count($notifications) }} notifications
                </small>
            </div>
        @endif
    </div>
</div>

<style>
.notification-item {
    transition: background-color 0.2s ease;
}

.notification-item:hover {
    background-color: #f8f9fa !important;
}

.dropdown-menu {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.notification-list {
    max-height: 400px;
    overflow-y: auto;
}

/* Custom scrollbar */
.notification-list::-webkit-scrollbar {
    width: 6px;
}

.notification-list::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.notification-list::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.notification-list::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notificationDropdown');
        const dropdownMenu = dropdown.nextElementSibling;
        
        if (!dropdown.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.remove('show');
        }
    });
});
</script>
</div>