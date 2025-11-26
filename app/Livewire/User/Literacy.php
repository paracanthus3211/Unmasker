<?php
namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Article;

#[Layout('components.layouts.app')]
class Literacy extends Component
{
    public function render()
    {
        $articles = Article::where('is_published', true)
            ->latest()
            ->get();
        
        return view('livewire.user.literacy', [
            'articles' => $articles
        ]);
    }
}