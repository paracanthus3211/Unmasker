<?php

use App\Http\Controllers\TestController;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Livewire\Leaderboard;
use App\Livewire\Quizz;
use App\Livewire\Literacy;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard',Dashboard::class);
Route::get('/leaderboard',Leaderboard::class);
Route::get('/quizz',Quizz::class);
Route::get('/literacy',Literacy::class);



