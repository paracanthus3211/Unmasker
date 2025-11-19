<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Livewire Components
use App\Livewire\Login;
use App\Livewire\Register;
use App\Livewire\User\Dashboard;
use App\Livewire\User\Leaderboard;
use App\Livewire\User\Quizz as UserQuizz;
use App\Livewire\User\Literacy;
use App\Livewire\User\About;
use App\Livewire\User\QuizPlay;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Literacy as AdminLiteracy;
use App\Livewire\Admin\Quizz as AdminQuizz;
use App\Livewire\Admin\Leaderboard as AdminLeaderboard;
use App\Livewire\Admin\About as AdminAbout;
use App\Livewire\Admin\QuizManager;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect('/login');
});

// Public Routes
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

// ADMIN ROUTES
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/literacy', AdminLiteracy::class)->name('literacy');
    Route::get('/quizz', AdminQuizz::class)->name('quizz');
    Route::get('/leaderboard', AdminLeaderboard::class)->name('leaderboard');
    Route::get('/about', AdminAbout::class)->name('about');
    Route::get('/quiz-manager', QuizManager::class)->name('quiz.manager');
    Route::get('/quiz/play/{levelId}', QuizPlay::class)->name('quiz.play'); // Admin bisa play quiz
});

// USER ROUTES
Route::middleware(['auth', 'isUser'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/leaderboard', Leaderboard::class)->name('leaderboard');
    Route::get('/quizz', UserQuizz::class)->name('quizz');
    Route::get('/literacy', Literacy::class)->name('literacy');
    Route::get('/about', About::class)->name('about');
    Route::get('/quiz/play/{levelId}', QuizPlay::class)->name('quiz.play');
});

// HAPUS route duplikat ini - sudah ada di group user di atas
// Route::middleware(['auth', 'isUser'])->group(function () {
//     Route::get('/quizz', UserQuizz::class)->name('user.quizz.alt');
// });

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');