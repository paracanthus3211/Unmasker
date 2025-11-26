<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class FriendProfile extends Component
{
    public $user;
    public $userStats = [];
    public $quizHistory = [];

    public function mount($user)
    {
        $this->user = User::findOrFail($user);
        $this->loadUserStats();
        $this->loadQuizHistory();
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

    public function render()
    {
        return view('livewire.admin.friend-profile');
    }
}