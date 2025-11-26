<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class FriendProfile extends Component
{
    public $user;
    public $userStats = [];
    public $quizHistory = [];
    public $friendshipStatus = '';

    public function mount($user)
    {
        $this->user = User::findOrFail($user);
        $this->loadUserStats();
        $this->loadQuizHistory();
        $this->loadFriendshipStatus();
    }

    public function loadUserStats()
    {
        $this->userStats = [
            'completedLevels' => $this->user->getCompletedLevelsCount(),
            'totalQuizAttempts' => $this->user->getTotalQuizAttempts(),
            'averageScore' => $this->user->getAverageScore(),
            'bestScore' => $this->user->getBestScore(),
            'totalCorrectAnswers' => $this->user->getTotalCorrectAnswers(),
            'progressPercentage' => $this->user->getOverallProgressPercentage(),
            'activityStatus' => $this->user->getActivityStatus(),
        ];
    }

    public function loadQuizHistory()
    {
        $this->quizHistory = $this->user->quizProgress()
            ->with('quizLevel')
            ->orderBy('completed_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($progress) {
                return [
                    'level_name' => $progress->quizLevel->name ?? 'Unknown Level',
                    'score' => $progress->score,
                    'correct_answers' => $progress->correct_answers,
                    'completion_time' => $progress->completion_time,
                    'completed_at' => $progress->completed_at->format('d M Y H:i'),
                    'wrong_answers' => $progress->wrong_answers ?? 0,
                ];
            });
    }

    public function loadFriendshipStatus()
    {
        $friendship = \App\Models\Friendship::betweenUsers(auth()->id(), $this->user->id)->first();
        $this->friendshipStatus = $friendship ? $friendship->status : 'not_friends';
    }

    public function sendFriendRequest()
    {
        $existingFriendship = \App\Models\Friendship::betweenUsers(auth()->id(), $this->user->id)->first();
        
        if ($existingFriendship) {
            session()->flash('message', 'Friend request already exists!');
            return;
        }

        \App\Models\Friendship::create([
            'user_id' => auth()->id(),
            'friend_id' => $this->user->id,
            'status' => 'pending'
        ]);

        $this->loadFriendshipStatus();
        session()->flash('message', 'Friend request sent to ' . $this->user->name);
    }

    public function removeFriend()
    {
        $friendship = \App\Models\Friendship::betweenUsers(auth()->id(), $this->user->id)->first();
        
        if ($friendship) {
            $friendship->delete();
            $this->loadFriendshipStatus();
            session()->flash('message', 'Friend removed');
        }
    }

    public function render()
    {
        return view('livewire.user.friend-profile');
    }
}