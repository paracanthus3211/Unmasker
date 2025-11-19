<?php
namespace App\Livewire\User;

use Livewire\Component;
use App\Models\QuizLevel;
use Livewire\Attributes\Layout;
use App\Models\UserQuizProgress;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
class Quizz extends Component
{
    public $levels;
    public $userProgress = [];

    public function mount()
    {
        $this->levels = QuizLevel::orderBy('order')->get();
        
        // Ambil progress user
        if (Auth::check()) {
            $this->userProgress = UserQuizProgress::where('user_id', Auth::id())
                ->get()
                ->keyBy('quiz_level_id')
                ->toArray();
        }
    }

    public function isLevelUnlocked($levelId, $index)
    {
        // Level pertama selalu terbuka
        if ($index === 0) return true;
        
        // Level berikutnya terbuka jika level sebelumnya selesai
        $previousLevel = $this->levels[$index - 1] ?? null;
        if ($previousLevel && isset($this->userProgress[$previousLevel->id])) {
            return true;
        }
        
        return false;
    }

    public function isLevelCompleted($levelId)
    {
        return isset($this->userProgress[$levelId]);
    }

    public function render()
    {
        return view('livewire.user.quizz');
    }
}