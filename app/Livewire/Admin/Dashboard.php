<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Article;
use App\Models\QuizLevel;
use App\Models\UserQuizProgress;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class Dashboard extends Component
{
    public $stats = [];

    public function mount()
    {
        $this->loadStats();
    }

   public function loadStats()
{
    $this->stats = [
        'totalUsers' => User::count(),
        'totalArticles' => Article::count(),
        'totalQuizLevels' => QuizLevel::count(),
        'totalQuizAttempts' => UserQuizProgress::count(),
        'activeUsers' => User::where('is_active', true)->count(),
        'publishedArticles' => Article::where('is_published', true)->count(),
        'averageQuizScore' => round(UserQuizProgress::avg('score') ?? 0, 1),
    ];

    // PERBAIKAN: Gunakan scope yang sudah dibuat
    $this->stats['recentActiveUsers'] = User::recentActive()->count();
}

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}