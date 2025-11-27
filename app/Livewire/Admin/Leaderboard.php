<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\QuizLevel;
use App\Models\UserQuizProgress;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class Leaderboard extends Component
{
    public $globalLeaderboard = [];
    public $userStats = [];
    public $quizStats = [];
    public $activeTab = 'global';
    public $timeFilter = 'all'; // all, weekly, monthly
    public $search = '';

    public function mount()
    {
        $this->loadGlobalLeaderboard();
        $this->loadUserStats();
        $this->loadQuizStats();
    }

    public function loadGlobalLeaderboard()
    {
        $query = User::withCount(['quizProgress as completed_levels'])
            ->withAvg('quizProgress', 'score')
            ->withMax('quizProgress', 'score')
            ->withSum('quizProgress', 'correct_answers')
            ->withSum('quizProgress', 'wrong_answers');

        // Apply time filter
        if ($this->timeFilter === 'weekly') {
            $query->whereHas('quizProgress', function($q) {
                $q->where('completed_at', '>=', now()->subWeek());
            });
        } elseif ($this->timeFilter === 'monthly') {
            $query->whereHas('quizProgress', function($q) {
                $q->where('completed_at', '>=', now()->subMonth());
            });
        }

        // Apply search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('username', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        $this->globalLeaderboard = $query->orderBy('completed_levels', 'desc')
            ->orderBy('quiz_progress_avg_score', 'desc')
            ->limit(50)
            ->get()
            ->map(function($user, $index) {
                $totalAnswers = ($user->quiz_progress_sum_correct_answers ?? 0) + ($user->quiz_progress_sum_wrong_answers ?? 0);
                $accuracy = $totalAnswers > 0 ? round(($user->quiz_progress_sum_correct_answers / $totalAnswers) * 100, 1) : 0;

                return [
                    'rank' => $index + 1,
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'avatar_url' => $user->avatar_url,
                    'completed_levels' => $user->completed_levels ?? 0,
                    'average_score' => round($user->quiz_progress_avg_score ?? 0, 1),
                    'best_score' => $user->quiz_progress_max_score ?? 0,
                    'accuracy' => $accuracy,
                    'total_correct' => $user->quiz_progress_sum_correct_answers ?? 0,
                    'total_wrong' => $user->quiz_progress_sum_wrong_answers ?? 0,
                    'activity_status' => $user->getActivityStatus(),
                    'joined_at' => $user->created_at->format('M d, Y'),
                    'is_active' => $user->is_active,
                ];
            });
    }

    public function loadUserStats()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $adminUsers = User::where('role', 'admin')->count();
        $regularUsers = User::where('role', 'user')->count();

        // Recent activity (users with quiz progress in last 7 days)
        $recentActiveUsers = User::whereHas('quizProgress', function($q) {
            $q->where('completed_at', '>=', now()->subDays(7));
        })->count();

        $this->userStats = [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'admin_users' => $adminUsers,
            'regular_users' => $regularUsers,
            'recent_active_users' => $recentActiveUsers,
            'inactive_users' => $totalUsers - $activeUsers,
        ];
    }

    public function loadQuizStats()
    {
        $totalLevels = QuizLevel::count();
        $totalQuizAttempts = UserQuizProgress::count();
        $averageScore = UserQuizProgress::avg('score') ?? 0;
        $totalCorrectAnswers = UserQuizProgress::sum('correct_answers') ?? 0;
        $totalWrongAnswers = UserQuizProgress::sum('wrong_answers') ?? 0;
        $totalAnswers = $totalCorrectAnswers + $totalWrongAnswers;
        $overallAccuracy = $totalAnswers > 0 ? round(($totalCorrectAnswers / $totalAnswers) * 100, 1) : 0;

        // Recent quiz attempts (last 7 days)
        $recentAttempts = UserQuizProgress::where('completed_at', '>=', now()->subDays(7))->count();

        $this->quizStats = [
            'total_levels' => $totalLevels,
            'total_attempts' => $totalQuizAttempts,
            'average_score' => round($averageScore, 1),
            'overall_accuracy' => $overallAccuracy,
            'total_correct_answers' => $totalCorrectAnswers,
            'total_wrong_answers' => $totalWrongAnswers,
            'recent_attempts' => $recentAttempts,
        ];
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function setTimeFilter($filter)
    {
        $this->timeFilter = $filter;
        $this->loadGlobalLeaderboard();
    }

    public function updatedSearch()
    {
        $this->loadGlobalLeaderboard();
    }

    public function toggleUserStatus($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->update([
                'is_active' => !$user->is_active
            ]);
            $this->loadGlobalLeaderboard();
            $this->loadUserStats();
        }
    }

    public function exportLeaderboard()
    {
        // Basic export functionality - bisa dikembangkan lebih lanjut
        session()->flash('message', 'Leaderboard export feature coming soon!');
    }

    public function render()
    {
        return view('livewire.admin.leaderboard');
    }
}