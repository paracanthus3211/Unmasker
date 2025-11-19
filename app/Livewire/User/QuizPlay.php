<?php
namespace App\Livewire\User;

use Livewire\Component;
use App\Models\QuizLevel;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.quiz')] // Gunakan layout khusus quiz
class QuizPlay extends Component
{
    public $levelId;
    public $level;
    public $currentQuestionIndex = 0;
    public $userAnswers = [];
    public $showResult = false;
    public $score = 0;
    public $timeLeft;

    public function mount($levelId)
    {
        $this->levelId = $levelId;
        $this->level = QuizLevel::with('questions')->findOrFail($levelId);
        $this->timeLeft = $this->level->time_limit;
        $this->userAnswers = array_fill(0, $this->level->questions->count(), null);
    }

    public function answerQuestion($answer)
    {
        $this->userAnswers[$this->currentQuestionIndex] = $answer;
        
        if ($this->currentQuestionIndex < count($this->level->questions) - 1) {
            $this->currentQuestionIndex++;
        } else {
            $this->calculateScore();
            $this->showResult = true;
        }
    }

    public function calculateScore()
    {
        $this->score = 0;
        foreach ($this->level->questions as $index => $question) {
            if ($this->userAnswers[$index] === $question->correct_answer) {
                $this->score++;
            }
        }
    }

    public function restartQuiz()
    {
        $this->currentQuestionIndex = 0;
        $this->userAnswers = array_fill(0, $this->level->questions->count(), null);
        $this->showResult = false;
        $this->score = 0;
        $this->timeLeft = $this->level->time_limit;
    }

    public function render()
    {
        return view('livewire.user.quiz-play');
    }
}