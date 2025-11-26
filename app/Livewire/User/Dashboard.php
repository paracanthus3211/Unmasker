<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\UserQuizProgress;
use App\Models\QuizLevel;
use App\Models\Article;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Dashboard extends Component
{
    public $userStats = [];
    public $quizHistory = [];

    public function mount()
    {
        $this->loadUserStats();
        $this->loadQuizHistory();
    }

    public function loadUserStats()
    {
        $user = auth()->user();

        $this->userStats = [
            'completedLevels' => $user->getCompletedLevelsCount(),
            'totalQuizAttempts' => $user->getTotalQuizAttempts(),
            'averageScore' => $user->getAverageScore(),
            'articlesRead' => 0,
            'totalLevels' => QuizLevel::count(),
            'highest_score' => $user->getBestScore(),
            'total_correct_answers' => $user->getTotalCorrectAnswers(),
        ];

        // Progress percentage
        if ($this->userStats['totalLevels'] > 0) {
            $this->userStats['progressPercentage'] = round(($this->userStats['completedLevels'] / $this->userStats['totalLevels']) * 100, 1);
        } else {
            $this->userStats['progressPercentage'] = 0;
        }

        // Calculate accuracy rate
        $totalQuestionsAttempted = $this->userStats['totalQuizAttempts'] * 5; // Assuming 5 questions per quiz
        if ($totalQuestionsAttempted > 0) {
            $this->userStats['accuracy_rate'] = round(($this->userStats['total_correct_answers'] / $totalQuestionsAttempted) * 100, 1);
        } else {
            $this->userStats['accuracy_rate'] = 0;
        }
    }

    public function loadQuizHistory()
    {
        $user = auth()->user();
        
        $this->quizHistory = $user->quizProgress()
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
                    'completed_at' => $progress->completed_at->format('d/m/Y H:i'),
                    'wrong_answers' => $progress->wrong_answers ?? 0,
                ];
            })
            ->toArray();
    }

    public function render()
    {
        return view('livewire.user.dashboard');
    }
}