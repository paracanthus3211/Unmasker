<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Friendship;

class UserSearch extends Component
{
    public $search = '';
    public $searchResults = [];
    public $message = '';

    public function updatedSearch($value)
    {
        $this->message = '';
        
        if (strlen($value) >= 2) {
            $this->searchResults = User::where(function($query) use ($value) {
                    $query->where('name', 'like', '%' . $value . '%')
                          ->orWhere('username', 'like', '%' . $value . '%'); // ✅ TAMBAH SEARCH BY USERNAME
                })
                ->where('id', '!=', auth()->id())
                ->where('role', 'user')
                ->limit(8)
                ->get()
                ->map(function($user) {
                    $friendship = Friendship::betweenUsers(auth()->id(), $user->id)->first();
                    
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username, // ✅ TAMBAH USERNAME DI RESULTS
                        'avatar_url' => $user->avatar_url,
                        'activity_status' => $user->getActivityStatus(),
                        'completed_levels' => $user->getCompletedLevelsCount(),
                        'friendship_status' => $friendship ? $friendship->status : 'not_friends',
                        'friendship_id' => $friendship ? $friendship->id : null,
                        'is_pending_from_me' => $friendship && $friendship->user_id === auth()->id() && $friendship->status === 'pending'
                    ];
                })
                ->toArray();
        } else {
            $this->searchResults = [];
        }
    }

    public function sendFriendRequest($userId)
{
    $user = User::findOrFail($userId);
    
    $existingFriendship = Friendship::betweenUsers(auth()->id(), $userId)->first();
    
    if ($existingFriendship) {
        if ($existingFriendship->status === 'pending') {
            $this->message = 'Friend request already sent!';
        } elseif ($existingFriendship->status === 'accepted') {
            $this->message = 'You are already friends!';
        }
        return;
    }

    // Buat friend request baru
    $friendship = Friendship::create([
        'user_id' => auth()->id(),
        'friend_id' => $userId,
        'status' => 'pending'
    ]);

    // ✅ KIRIM NOTIFIKASI
    try {
        $user->notify(new \App\Notifications\FriendRequestNotification(auth()->user(), $friendship->id));
    } catch (\Exception $e) {
        // Log error but don't break the flow
        \Log::error('Failed to send notification: ' . $e->getMessage());
    }

    $this->message = 'Friend request sent to ' . $user->name;
    $this->updatedSearch($this->search);
}

    public function cancelFriendRequest($userId)
    {
        Friendship::betweenUsers(auth()->id(), $userId)->delete();
        $this->message = 'Friend request cancelled';
        $this->updatedSearch($this->search);
    }

    public function acceptFriendRequest($userId)
    {
        $friendship = Friendship::where('user_id', $userId)
            ->where('friend_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if ($friendship) {
            $friendship->update(['status' => 'accepted']);
            $this->message = 'Friend request accepted!';
            $this->updatedSearch($this->search);
        }
    }

    public function rejectFriendRequest($userId)
    {
        Friendship::where('user_id', $userId)
            ->where('friend_id', auth()->id())
            ->where('status', 'pending')
            ->delete();
            
        $this->message = 'Friend request rejected';
        $this->updatedSearch($this->search);
    }

    public function removeFriend($userId)
    {
        Friendship::betweenUsers(auth()->id(), $userId)->delete();
        $this->message = 'Friend removed';
        $this->updatedSearch($this->search);
    }

    public function render()
    {
        return view('livewire.admin.user-search');
    }
}