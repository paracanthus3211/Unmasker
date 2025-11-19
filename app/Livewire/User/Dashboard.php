<?php

namespace App\Livewire\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Dashboard extends Component
{
    public $correctAnswers = 15;
    public $incorrectAnswers = 5;

    public $quizLabels = ['Quiz 1', 'Quiz 2', 'Quiz 3', 'Quiz 4'];
    public $userScores = [80, 75, 90, 85];
    public $avgScores = [70, 78, 82, 80];

    public function render()
    {
        return view('livewire.user.dashboard');
    }
}

