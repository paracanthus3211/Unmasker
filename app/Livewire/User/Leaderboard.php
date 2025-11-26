<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Leaderboard extends Component
{
    public $leaderboard = [];
    public $userRank = 0;
    public $userStats = [];
    public $friendsLeaderboard = [];
    public $activeTab = 'global';

    public function mount()
    {
        $this->loadLeaderboard();
        $this->loadUserStats();
        $this->loadFriendsLeaderboard();
    }

    public function loadLeaderboard()
    {
        // Global leaderboard
        $this->leaderboard = User::withCount(['quizProgress as completed_levels'])
            ->withAvg('quizProgress', 'score')
            ->withMax('quizProgress', 'score')
            ->having('completed_levels', '>', 0)
            ->orderBy('completed_levels', 'desc')
            ->orderBy('quiz_progress_avg_score', 'desc')
            ->limit(20)
            ->get()
            ->map(function($user, $index) {
                $user->rank = $index + 1;
                $user->average_score = round($user->quiz_progress_avg_score, 1);
                $user->best_score = $user->quiz_progress_max_score;
                $user->accuracy = $user->getAccuracyPercentage();
                return $user;
            });

        // Find current user rank
        $currentUser = auth()->user();
        $allUsers = User::withCount(['quizProgress as completed_levels'])
            ->withAvg('quizProgress', 'score')
            ->having('completed_levels', '>', 0)
            ->orderBy('completed_levels', 'desc')
            ->orderBy('quiz_progress_avg_score', 'desc')
            ->get();

        $this->userRank = $allUsers->search(function($user) use ($currentUser) {
            return $user->id === $currentUser->id;
        }) + 1;
    }

    public function loadUserStats()
    {
        $user = auth()->user();
        $this->userStats = [
            'rank' => $this->userRank,
            'completed_levels' => $user->getCompletedLevelsCount(),
            'average_score' => $user->getAverageScore(),
            'best_score' => $user->getBestScore(),
            'total_attempts' => $user->getTotalQuizAttempts(),
            'accuracy' => $user->getAccuracyPercentage(),
            'overall_progress' => $user->getOverallProgressPercentage(),
        ];
    }

    public function loadFriendsLeaderboard()
    {
        $user = auth()->user();
        
        // Get friends + current user for comparison
        $friends = $user->friends()
            ->withCount(['quizProgress as completed_levels'])
            ->withAvg('quizProgress', 'score')
            ->withMax('quizProgress', 'score')
            ->get()
            ->map(function($friend) {
                $friend->average_score = round($friend->quiz_progress_avg_score, 1);
                $friend->best_score = $friend->quiz_progress_max_score;
                $friend->accuracy = $friend->getAccuracyPercentage();
                $friend->overall_progress = $friend->getOverallProgressPercentage();
                return $friend;
            });

        // Add current user to friends leaderboard
        $currentUserData = (object) [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'avatar_url' => $user->avatar_url,
            'completed_levels' => $user->getCompletedLevelsCount(),
            'average_score' => $user->getAverageScore(),
            'best_score' => $user->getBestScore(),
            'accuracy' => $user->getAccuracyPercentage(),
            'overall_progress' => $user->getOverallProgressPercentage(),
            'is_current_user' => true,
        ];

        $this->friendsLeaderboard = $friends->push($currentUserData)
            ->sortByDesc('completed_levels')
            ->sortByDesc('average_score')
            ->values()
            ->map(function($user, $index) {
                $user->rank = $index + 1;
                return $user;
            });
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.user.leaderboard');
    }
}