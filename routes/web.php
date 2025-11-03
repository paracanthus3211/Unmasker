<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\Leaderboard;
use App\Livewire\Quizz;
use App\Livewire\Literacy;
use App\Livewire\Login;
use App\Livewire\Register;
use App\Livewire\Level;
use App\Livewire\About;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');

// Logout route
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login')->with('success', 'Kamu sudah logout.');
})->name('logout');

// Halaman yang butuh login
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/leaderboard', Leaderboard::class)->name('leaderboard');
    Route::get('/quizz', Quizz::class)->name('quizz');
    Route::get('/literacy', Literacy::class)->name('literacy');
    Route::get('/quizz/level{num}', Level::class)->whereNumber('num')->name('level');
    Route::get('/about', About::class)->name('about');
});


