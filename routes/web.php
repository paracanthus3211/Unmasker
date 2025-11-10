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
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Literacy as AdminLiteracy;
use App\Livewire\Admin\Quizz as AdminQuizz;
use App\Livewire\Admin\Leaderboard as AdminLeaderboard;
use App\Livewire\Admin\About as AdminAbout;
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

// Route untuk halaman fisiognomi (non-Livewire)
Route::get('/fisiognomi', function () {
    return view('Literacy.fisiognomi');
})->name('fisiognomi');

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/admin/dashboard',AdminDashboard::class )->name('admin.dashboard');
});

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/admin/literacy',AdminLiteracy::class )->name('admin.literacy');
});

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/admin/quizz',AdminQuizz::class )->name('admin.quizz');
});

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/admin/leaderboard',AdminLeaderboard::class )->name('admin.leaderboard');
});

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/admin/about',AdminAbout::class )->name('admin.about');
});

// Halaman yang butuh login
Route::middleware(['auth', 'isUser'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/leaderboard', Leaderboard::class)->name('leaderboard');
    Route::get('/quizz', Quizz::class)->name('quizz');
    Route::get('/literacy', Literacy::class)->name('literacy');
    Route::get('/quizz/level/{num}', Level::class)->whereNumber('num')->name('level');
    Route::get('/about', About::class)->name('about');
    
});