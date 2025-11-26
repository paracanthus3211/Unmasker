<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Friendship;

class NotificationBell extends Component
{
    public $notifications;
    public $unreadCount = 0;
    public $showNotifications = false;

    protected $listeners = [
        'refreshNotifications' => 'loadNotifications',
        'friendRequestAccepted' => 'loadNotifications',
        'notificationAdded' => 'loadNotifications'
    ];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->notifications = $user->notifications()
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            $this->unreadCount = $user->unreadNotifications()->count();
        }
    }

    public function toggleNotifications()
    {
        $this->showNotifications = !$this->showNotifications;
        if ($this->showNotifications) {
            $this->loadNotifications();
        }
    }

    public function acceptFriendRequest($notificationId)
    {
        $notification = Auth::user()->notifications()->where('id', $notificationId)->first();
        
        if ($notification && $notification->type === 'App\Notifications\FriendRequestNotification') {
            $data = $notification->data;
            $friendshipId = $data['friendship_id'] ?? null;
            
            if ($friendshipId) {
                $friendship = Friendship::find($friendshipId);
                if ($friendship && $friendship->friend_id === Auth::id()) {
                    $friendship->update(['status' => 'accepted']);
                    
                    // Mark notification as read
                    $notification->markAsRead();
                    
                    $this->loadNotifications();
                    $this->dispatch('friendAccepted');
                    
                    session()->flash('success', 'Friend request accepted! 🎉');
                }
            }
        }
    }

    public function rejectFriendRequest($notificationId)
    {
        $notification = Auth::user()->notifications()->where('id', $notificationId)->first();
        
        if ($notification && $notification->type === 'App\Notifications\FriendRequestNotification') {
            $data = $notification->data;
            $friendshipId = $data['friendship_id'] ?? null;
            
            if ($friendshipId) {
                Friendship::find($friendshipId)?->delete();
                $notification->markAsRead();
                $this->loadNotifications();
                
                session()->flash('success', 'Friend request rejected.');
            }
        }
    }

    public function markAsRead($notificationId)
    {
        Auth::user()->notifications()->where('id', $notificationId)->update(['read_at' => now()]);
        $this->loadNotifications();
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        $this->loadNotifications();
    }

    public function clearAllNotifications()
    {
        Auth::user()->notifications()->delete();
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.user.notification-bell');
    }
}