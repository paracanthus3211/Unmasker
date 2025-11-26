<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::where('is_published', true)
            ->latest()
            ->get();
            
        return view('user.articles.index', compact('articles'));
    }

    public function show(Article $article)
    {
        // Pastikan hanya artikel yang published yang bisa dilihat user
        if (!$article->is_published) {
            abort(404);
        }
        
        // Tambah view count
        $article->increment('views_count');
        
        return view('user.articles.show', compact('article'));
    }
}