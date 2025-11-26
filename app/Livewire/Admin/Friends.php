<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Friendship;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class Friends extends Component
{
    public $activeTab = 'friends';
    public $friends = [];
    public $pendingRequests = [];
    public $sentRequests = [];

    public function mount()
    {
        $this->loadFriends();
        $this->loadPendingRequests();
        $this->loadSentRequests();
    }

    public function loadFriends()
    {
        // ✅ PERBAIKI: Simple query untuk friends
        $this->friends = auth()->user()->friends()
            ->get()
            ->map(function($friend) {
                $friend->completed_levels = $friend->getCompletedLevelsCount();
                $friend->activity_status = $friend->getActivityStatus();
                return $friend;
            });
    }

    public function loadPendingRequests()
    {
        // ✅ PERBAIKI: Simple query untuk pending requests
        $this->pendingRequests = auth()->user()->friendRequests()
            ->with(['user' => function($query) {
                // Load basic user data
            }])
            ->get()
            ->map(function($request) {
                $request->user->completed_levels = $request->user->getCompletedLevelsCount();
                $request->user->activity_status = $request->user->getActivityStatus();
                return $request;
            });
    }

    public function loadSentRequests()
    {
        // ✅ PERBAIKI: Simple query untuk sent requests
        $this->sentRequests = auth()->user()->sentFriendRequests()
            ->with(['friend' => function($query) {
                // Load basic friend data
            }])
            ->get()
            ->map(function($request) {
                $request->friend->completed_levels = $request->friend->getCompletedLevelsCount();
                $request->friend->activity_status = $request->friend->getActivityStatus();
                return $request;
            });
    }

    public function acceptRequest($friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);
        
        if ($friendship->friend_id === auth()->id()) {
            $friendship->update(['status' => 'accepted']);
            
            $this->loadFriends();
            $this->loadPendingRequests();
            
            session()->flash('message', 'Friend request accepted!');
        }
    }

    public function rejectRequest($friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);
        
        if ($friendship->friend_id === auth()->id()) {
            $friendship->delete();
            
            $this->loadPendingRequests();
            session()->flash('message', 'Friend request rejected');
        }
    }

    public function cancelRequest($friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);
        
        if ($friendship->user_id === auth()->id()) {
            $friendship->delete();
            
            $this->loadSentRequests();
            session()->flash('message', 'Friend request cancelled');
        }
    }

    public function removeFriend($friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);
        
        if (in_array(auth()->id(), [$friendship->user_id, $friendship->friend_id])) {
            $friendship->delete();
            
            $this->loadFriends();
            session()->flash('message', 'Friend removed');
        }
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.admin.friends');
    }
}