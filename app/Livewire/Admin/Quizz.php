<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\QuizLevel;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class Quizz extends Component
{
    public $levels;

    public function mount()
    {
        $this->loadLevels();
    }

    public function loadLevels()
    {
        $this->levels = QuizLevel::withCount('questions')
            ->orderBy('order')
            ->get();
    }

    public function playLevel($levelId)
    {
        // Redirect ke halaman play quiz
        return redirect()->route('admin.quiz.play', ['levelId' => $levelId]);
    }

    public function deleteLevel($levelId)
    {
        try {
            $level = QuizLevel::findOrFail($levelId);
            $levelName = $level->name;
            $level->delete();
            
            session()->flash('success', "Level '{$levelName}' berhasil dihapus!");
            $this->loadLevels();
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus level: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.quizz');
    }
}