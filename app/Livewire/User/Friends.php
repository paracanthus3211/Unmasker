<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;
use App\Models\Friendship;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Friends extends Component
{
    public $activeTab = 'friends';
    public $friends = [];
    public $pendingRequests = [];
    public $sentRequests = [];
    public $search = '';
    public $searchResults = [];

    protected $listeners = [
        'friendAccepted' => 'loadFriendsData',
        'refreshFriends' => 'loadFriendsData'
    ];

    public function mount()
    {
        $this->loadFriendsData();
    }

    public function loadFriendsData()
    {
        $this->loadFriends();
        $this->loadPendingRequests();
        $this->loadSentRequests();
    }

    public function loadFriends()
    {
        $this->friends = auth()->user()->friends()
            ->orderBy('name')
            ->get()
            ->map(function($friend) {
                $friend->completed_levels = $friend->getCompletedLevelsCount();
                $friend->activity_status = $friend->getActivityStatus();
                return $friend;
            });
    }

    public function loadPendingRequests()
    {
        $this->pendingRequests = auth()->user()->friendRequests()
            ->with(['user'])
            ->get()
            ->map(function($request) {
                $request->user->completed_levels = $request->user->getCompletedLevelsCount();
                $request->user->activity_status = $request->user->getActivityStatus();
                return $request;
            });
    }

    public function loadSentRequests()
    {
        $this->sentRequests = auth()->user()->sentFriendRequests()
            ->with(['friend'])
            ->get()
            ->map(function($request) {
                $request->friend->completed_levels = $request->friend->getCompletedLevelsCount();
                $request->friend->activity_status = $request->friend->getActivityStatus();
                return $request;
            });
    }

    public function updatedSearch($value)
    {
        if (strlen($value) >= 2) {
            $this->searchResults = User::where(function($query) use ($value) {
                    $query->where('name', 'like', '%' . $value . '%')
                          ->orWhere('username', 'like', '%' . $value . '%');
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
                        'username' => $user->username,
                        'avatar_url' => $user->avatar_url,
                        'activity_status' => $user->getActivityStatus(),
                        'completed_levels' => $user->getCompletedLevelsCount(),
                        'friendship_status' => $friendship ? $friendship->status : 'not_friends',
                        'friendship_id' => $friendship ? $friendship->id : null,
                        'is_pending_from_me' => $friendship && $friendship->user_id === auth()->id() && $friendship->status === 'pending'
                    ];
                });
        } else {
            $this->searchResults = [];
        }
    }

    public function acceptRequest($friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);
        
        if ($friendship->friend_id === auth()->id()) {
            $friendship->update(['status' => 'accepted']);
            
            $this->loadFriendsData();
            $this->dispatch('friendAccepted');
            
            session()->flash('success', 'Friend request accepted! 🎉');
        }
    }

    public function rejectRequest($friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);
        
        if ($friendship->friend_id === auth()->id()) {
            $friendship->delete();
            
            $this->loadPendingRequests();
            session()->flash('success', 'Friend request rejected.');
        }
    }

    public function cancelRequest($friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);
        
        if ($friendship->user_id === auth()->id()) {
            $friendship->delete();
            
            $this->loadSentRequests();
            session()->flash('success', 'Friend request cancelled.');
        }
    }

    public function removeFriend($friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);
        
        if (in_array(auth()->id(), [$friendship->user_id, $friendship->friend_id])) {
            $friendship->delete();
            
            $this->loadFriends();
            session()->flash('success', 'Friend removed successfully.');
        }
    }

    public function sendFriendRequest($userId)
    {
        $user = User::findOrFail($userId);
        
        $existingFriendship = Friendship::betweenUsers(auth()->id(), $userId)->first();
        
        if ($existingFriendship) {
            session()->flash('error', 'Friend request already exists!');
            return;
        }

        Friendship::create([
            'user_id' => auth()->id(),
            'friend_id' => $userId,
            'status' => 'pending'
        ]);

        $this->loadSentRequests();
        $this->updatedSearch($this->search);
        
        session()->flash('success', 'Friend request sent to ' . $user->name . '!');
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->search = '';
        $this->searchResults = [];
        
        // Refresh data when switching tabs
        if ($tab === 'friends') {
            $this->loadFriends();
        } elseif ($tab === 'requests') {
            $this->loadPendingRequests();
        } elseif ($tab === 'sent') {
            $this->loadSentRequests();
        }
    }

    public function render()
    {
        return view('livewire.user.friends');
    }
}