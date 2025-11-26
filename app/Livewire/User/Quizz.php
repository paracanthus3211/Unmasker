<?php
namespace App\Livewire\User;

use Livewire\Component;
use App\Models\QuizLevel;
use App\Models\UserQuizProgress;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Quizz extends Component
{
    public $levels;
    public $userProgress = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->levels = QuizLevel::orderBy('order')->get();
        
        if (Auth::check()) {
            $this->userProgress = UserQuizProgress::where('user_id', Auth::id())
                ->get()
                ->keyBy('quiz_level_id')
                ->toArray();
        }
    }

    public function isLevelUnlocked($levelId, $index)
    {
        // Level 1 selalu terbuka
        if ($index === 0) return true;
        
        // Level berikutnya terbuka jika level sebelumnya completed
        $previousLevelIndex = $index - 1;
        if (isset($this->levels[$previousLevelIndex])) {
            $previousLevelId = $this->levels[$previousLevelIndex]->id;
            return isset($this->userProgress[$previousLevelId]);
        }
        
        return false;
    }

    public function isLevelCompleted($levelId)
    {
        return isset($this->userProgress[$levelId]);
    }

    // Refresh data ketika kembali ke halaman ini
    public function refreshData()
    {
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.user.quizz');
    }
}