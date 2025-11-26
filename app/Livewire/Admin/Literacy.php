<?php

namespace App\Livewire\Admin;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Article;

#[Layout('components.layouts.admin')]
class Literacy extends Component
{
    public function render()
    {
        $articles = Article::latest()->get();
        
        return view('livewire.admin.literacy', [
            'articles' => $articles
        ]);
    }
}