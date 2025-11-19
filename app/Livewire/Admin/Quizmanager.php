<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\QuizLevel;
use Livewire\Attributes\Layout;
use App\Models\QuizQuestion;

#[Layout('components.layouts.admin')]
class QuizManager extends Component
{
    use WithFileUploads;

    public $levelName = '';
    public $buttonImage = null;
    public $timeLimit = 300;
    public $questions = [];

    public function mount()
    {
        // Initialize dengan 1 soal kosong
        $this->addQuestion();
    }

    public function addQuestion()
    {
        $this->questions[] = [
            'question_text' => '',
            'question_image' => null,
            'option_a' => '', 'option_a_image' => null,
            'option_b' => '', 'option_b_image' => null,
            'option_c' => '', 'option_c_image' => null,
            'option_d' => '', 'option_d_image' => null,
            'correct_answer' => '',
        ];
    }

    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
    }

    public function saveLevel()
    {
        // Validasi
        $this->validate([
            'levelName' => 'required|string|max:255',
            'buttonImage' => 'nullable|image|max:2048',
            'timeLimit' => 'required|integer|min:30',
            'questions.*.question_text' => 'required|string',
            'questions.*.correct_answer' => 'required|in:A,B,C,D',
        ]);

        // Simpan Level
        $level = QuizLevel::create([
            'name' => $this->levelName,
            'time_limit' => $this->timeLimit,
            'order' => QuizLevel::count() + 1,
        ]);

        // Upload gambar button jika ada
        if ($this->buttonImage) {
            $level->button_image = $this->buttonImage->store('level-buttons', 'public');
            $level->save();
        }

        // Simpan semua soal
        foreach ($this->questions as $questionData) {
            $question = new QuizQuestion($questionData);
            
            // Upload gambar soal jika ada
            if ($questionData['question_image']) {
                $question->question_image = $questionData['question_image']->store('question-images', 'public');
            }
            
            // Upload gambar opsi jika ada
            foreach (['a', 'b', 'c', 'd'] as $option) {
                $imageField = "option_{$option}_image";
                if ($questionData[$imageField]) {
                    $question->$imageField = $questionData[$imageField]->store('option-images', 'public');
                }
            }
            
            $level->questions()->save($question);
        }

        session()->flash('message', 'Level berhasil disimpan!');
        return redirect()->route('admin.quizz');
    }

    public function render()
    {
        return view('livewire.admin.quiz-manager');
    }
}