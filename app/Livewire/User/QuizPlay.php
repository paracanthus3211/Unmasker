<?php
namespace App\Livewire\User;

use Livewire\Component;
use App\Models\QuizLevel;
use App\Models\UserQuizProgress;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.quiz')]
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
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < count($this->level->questions) - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function calculateScore()
{
    $this->score = 0;
    $correctAnswers = 0;
    
    foreach ($this->level->questions as $index => $question) {
        if ($this->userAnswers[$index] === $question->correct_answer) {
            $this->score++;
            $correctAnswers++;
        }
    }
    
    // DEBUG: Cek data sebelum save
    logger("=== QUIZ SAVE DEBUG ===");
    logger("User: " . Auth::id());
    logger("Level: " . $this->levelId);
    logger("Score: " . $this->score);
    logger("Correct Answers: " . $correctAnswers);
    
    // CARA YANG LEBIH AMAN: Cek dulu apakah sudah ada
    try {
        $existingProgress = UserQuizProgress::where('user_id', Auth::id())
            ->where('quiz_level_id', $this->levelId)
            ->first();
            
        if ($existingProgress) {
            // UPDATE yang sudah ada
            logger("Updating existing progress: " . $existingProgress->id);
            $existingProgress->update([
                'score' => $this->score,
                'correct_answers' => $correctAnswers,
                'wrong_answers' => $this->level->questions->count() - $correctAnswers,
                'completion_time' => $this->level->time_limit - $this->timeLeft,
                'completed_at' => now(),
            ]);
        } else {
            // CREATE baru
            logger("Creating new progress");
            UserQuizProgress::create([
                'user_id' => Auth::id(),
                'quiz_level_id' => $this->levelId,
                'score' => $this->score,
                'correct_answers' => $correctAnswers,
                'wrong_answers' => $this->level->questions->count() - $correctAnswers,
                'completion_time' => $this->level->time_limit - $this->timeLeft,
                'completed_at' => now(),
            ]);
        }
        
        logger("Progress saved successfully!");
        
    } catch (\Exception $e) {
        logger('Error saving quiz progress: ' . $e->getMessage());
        // Fallback: try simple save tanpa kolom optional
        try {
            UserQuizProgress::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'quiz_level_id' => $this->levelId,
                ],
                [
                    'score' => $this->score,
                    'correct_answers' => $correctAnswers,
                    'completed_at' => now(),
                ]
            );
            logger("Fallback save successful!");
        } catch (\Exception $e2) {
            logger('Fallback also failed: ' . $e2->getMessage());
        }
    }
    
    $this->showResult = true;
}

    public function restartQuiz()
    {
        return redirect()->route('user.quiz.play', $this->levelId);
    }

    public function render()
    {
        return view('livewire.user.quiz-play');
    }
}