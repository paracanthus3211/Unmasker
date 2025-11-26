<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\User\ArticleController as UserArticleController;

// Livewire Components
use App\Livewire\Login;
use App\Livewire\Register;
use App\Livewire\User\Dashboard;
use App\Livewire\User\Leaderboard;
use App\Livewire\User\Quizz as UserQuizz;
use App\Livewire\User\Literacy;
use App\Livewire\User\About;
use App\Livewire\User\QuizPlay as UserQuizPlay;
use App\Livewire\User\Profile as UserProfile;
use App\Livewire\User\Friends as UserFriends;
use App\Livewire\User\FriendProfile as UserFriendProfile;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Literacy as AdminLiteracy;
use App\Livewire\Admin\Quizz as AdminQuizz;
use App\Livewire\Admin\Leaderboard as AdminLeaderboard;
use App\Livewire\Admin\About as AdminAbout;
use App\Livewire\Admin\QuizManager;
use App\Livewire\Admin\Profile as AdminProfile;
use App\Livewire\Admin\QuizPlay as AdminQuizPlay;
use App\Livewire\Admin\Friends as AdminFriends;
use App\Livewire\Admin\FriendProfile as AdminFriendProfile;
use App\Livewire\Admin\UserSearch;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');

// Logout route
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login')->with('success', 'Kamu sudah logout.');
})->name('logout');

// PUBLIC ARTICLE ROUTES (bisa diakses tanpa login)
Route::get('/article/{article:slug}', [ArticleController::class, 'show'])->name('articles.public.show');

// ADMIN ROUTES
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard & Main Features
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/literacy', AdminLiteracy::class)->name('literacy');
    Route::get('/quizz', AdminQuizz::class)->name('quizz');
    Route::get('/leaderboard', AdminLeaderboard::class)->name('leaderboard');
    Route::get('/about', AdminAbout::class)->name('about');
    Route::get('/quiz-manager', QuizManager::class)->name('quiz.manager');
    Route::get('/profile', AdminProfile::class)->name('profile');
    Route::get('/quiz/play/{levelId}', AdminQuizPlay::class)->name('quiz.play');
    Route::get('/user-search', UserSearch::class)->name('user.search');
    
    // FRIENDS SYSTEM ROUTES - UNTUK ADMIN
    Route::get('/friends', AdminFriends::class)->name('friends');
    Route::get('/friend/profile/{user}', AdminFriendProfile::class)->name('friend.profile');
    
    // ARTICLES MANAGEMENT ROUTES (RESTful)
    Route::prefix('articles')->name('articles.')->group(function () {
        Route::get('/', [ArticleController::class, 'index'])->name('index');
        Route::get('/create', [ArticleController::class, 'create'])->name('create');
        Route::post('/', [ArticleController::class, 'store'])->name('store');
        Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('edit');
        Route::put('/{article}', [ArticleController::class, 'update'])->name('update');
        Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('destroy');
        Route::get('/{article:slug}', [ArticleController::class, 'show'])->name('show');
        Route::post('/{article}/toggle-publish', [ArticleController::class, 'togglePublish'])->name('toggle-publish');
    });
});

// USER ROUTES
Route::middleware(['auth', 'isUser'])->prefix('user')->name('user.')->group(function () {
    // Main Features
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/leaderboard', Leaderboard::class)->name('leaderboard');
    Route::get('/quizz', UserQuizz::class)->name('quizz');
    Route::get('/literacy', Literacy::class)->name('literacy');
    Route::get('/about', About::class)->name('about');
    Route::get('/profile', UserProfile::class)->name('profile');
    Route::get('/quiz/play/{levelId}', UserQuizPlay::class)->name('quiz.play');
    
    // ✅ ENABLE USER FRIENDS SYSTEM ROUTES
    Route::get('/friends', UserFriends::class)->name('friends');
    Route::get('/friend/profile/{user}', UserFriendProfile::class)->name('friend.profile');
    
    // USER ARTICLES ROUTES (Read Only)
    Route::prefix('articles')->name('articles.')->group(function () {
        Route::get('/', [UserArticleController::class, 'index'])->name('index');
        Route::get('/{article:slug}', [UserArticleController::class, 'show'])->name('show');
    });
});

// API ROUTES FOR FRIENDS SYSTEM (AJAX) - UNTUK SEMUA USER YANG LOGIN
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    Route::prefix('friends')->name('friends.')->group(function () {
        Route::post('/send-request/{user}', function ($user) {
            $user = \App\Models\User::findOrFail($user);
            $currentUser = auth()->user();
            
            // Cek apakah sudah ada friendship
            $existingFriendship = \App\Models\Friendship::betweenUsers($currentUser->id, $user->id)->first();
            
            if ($existingFriendship) {
                return response()->json([
                    'success' => false,
                    'message' => 'Friend request already exists!'
                ]);
            }
            
            // Buat friend request baru
            $friendship = \App\Models\Friendship::create([
                'user_id' => $currentUser->id,
                'friend_id' => $user->id,
                'status' => 'pending'
            ]);
            
            // ✅ KIRIM NOTIFIKASI
            $user->notify(new \App\Notifications\FriendRequestNotification($currentUser, $friendship->id));
            
            return response()->json([
                'success' => true,
                'message' => 'Friend request sent to ' . $user->name
            ]);
        })->name('send-request');
        
        Route::post('/accept-request/{friendship}', function ($friendship) {
            $friendship = \App\Models\Friendship::findOrFail($friendship);
            
            if ($friendship->friend_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action!'
                ]);
            }
            
            $friendship->update(['status' => 'accepted']);
            
            return response()->json([
                'success' => true,
                'message' => 'Friend request accepted!'
            ]);
        })->name('accept-request');
        
        Route::post('/reject-request/{friendship}', function ($friendship) {
            $friendship = \App\Models\Friendship::findOrFail($friendship);
            
            if ($friendship->friend_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action!'
                ]);
            }
            
            $friendship->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Friend request rejected'
            ]);
        })->name('reject-request');
        
        Route::delete('/remove-friend/{friendship}', function ($friendship) {
            $friendship = \App\Models\Friendship::findOrFail($friendship);
            
            if (!in_array(auth()->id(), [$friendship->user_id, $friendship->friend_id])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action!'
                ]);
            }
            
            $friendship->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Friend removed successfully'
            ]);
        })->name('remove-friend');
        
        Route::get('/search', function () {
            $search = request('q');
            $users = \App\Models\User::where(function($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                          ->orWhere('username', 'like', '%' . $search . '%');
                })
                ->where('id', '!=', auth()->id())
                ->where('role', 'user')
                ->limit(10)
                ->get()
                ->map(function($user) {
                    $friendship = \App\Models\Friendship::betweenUsers(auth()->id(), $user->id)->first();
                    
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'avatar_url' => $user->avatar_url,
                        'activity_status' => $user->getActivityStatus(),
                        'completed_levels' => $user->getCompletedLevelsCount(),
                        'friendship_status' => $friendship ? $friendship->status : 'not_friends',
                        'is_pending_from_me' => $friendship && $friendship->user_id === auth()->id() && $friendship->status === 'pending'
                    ];
                });
            
            return response()->json($users);
        })->name('search');
    });
});