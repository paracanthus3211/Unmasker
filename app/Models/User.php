<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'is_active',
        'role',           
        'avatar_id',      
        'birth_date',     
        'phone',          
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'avatar_url',
        'display_username',
        'formatted_phone',
        'is_phone_verified',
        'age',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    // RELATIONSHIPS
    public function avatar()
    {
        return $this->belongsTo(Avatar::class);
    }

    public function quizProgress()
    {
        return $this->hasMany(UserQuizProgress::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function friendships()
    {
        return $this->hasMany(Friendship::class, 'user_id');
    }

    // ✅ PERBAIKI: Friend requests yang diterima (sebagai friend)
    public function friendRequests()
    {
        return $this->hasMany(Friendship::class, 'friend_id')->where('status', 'pending');
    }

    // ✅ PERBAIKI: Friend requests yang dikirim
    public function sentFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'user_id')->where('status', 'pending');
    }

    // ✅ PERBAIKI: Friends relationship
    public function friends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
                    ->wherePivot('status', 'accepted')
                    ->withTimestamps();
    }

    // ✅ PERBAIKI: Pending friends relationship (outgoing requests)
    public function pendingFriends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
                    ->wherePivot('status', 'pending')
                    ->withTimestamps();
    }

    // ✅ TAMBAHKAN: Method untuk mendapatkan semua friend requests (incoming)
    public function getPendingFriendRequests()
    {
        return $this->friendRequests()->with('user')->get();
    }

    // ✅ TAMBAHKAN: Method untuk mendapatkan sent friend requests
    public function getSentFriendRequests()
    {
        return $this->sentFriendRequests()->with('friend')->get();
    }

    // ACCESSORS
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            $imageUrl = $this->avatar->image_url;
            
            if (str_starts_with($imageUrl, 'http')) {
                return $imageUrl;
            }
            
            if (Storage::disk('public')->exists($imageUrl)) {
                return asset('storage/' . $imageUrl);
            }
        }
        
        return asset('/assets/img/default-avatar.png');
    }

    public function getDisplayUsernameAttribute()
    {
        return '@' . $this->username;
    }

    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone) {
            return 'Not set';
        }

        $phone = preg_replace('/[^0-9]/', '', $this->phone);
        
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        
        if (str_starts_with($phone, '62') && strlen($phone) === 12) {
            return '+62 ' . substr($phone, 2, 4) . '-' . substr($phone, 6, 4) . '-' . substr($phone, 10);
        }
        
        return $this->phone;
    }

    public function getIsPhoneVerifiedAttribute()
    {
        return !is_null($this->phone_verified_at);
    }

    public function getAgeAttribute()
    {
        if (!$this->birth_date) {
            return null;
        }
        
        return $this->birth_date->age;
    }

    // METHODS
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function isActive()
    {
        return $this->is_active;
    }

    // Quiz Progress Methods
    public function getCompletedLevelsCount()
    {
        return $this->quizProgress()->count();
    }

    public function getAverageScore()
    {
        return round($this->quizProgress()->avg('score') ?? 0, 1);
    }

    public function getTotalQuizAttempts()
    {
        return $this->quizProgress()->count();
    }

    public function getQuizHistory()
    {
        return $this->quizProgress()
            ->with('quizLevel')
            ->orderBy('completed_at', 'desc')
            ->get();
    }

    public function getCurrentLevel()
    {
        $lastProgress = $this->quizProgress()
            ->with('quizLevel')
            ->orderBy('completed_at', 'desc')
            ->first();
        
        if ($lastProgress && $lastProgress->quizLevel) {
            $nextLevelOrder = $lastProgress->quizLevel->order + 1;
            $nextLevel = QuizLevel::where('order', $nextLevelOrder)->first();
            return $nextLevel ?: $lastProgress->quizLevel;
        }
        
        return QuizLevel::orderBy('order')->first();
    }

    public function hasCompletedLevel($levelId)
    {
        return $this->quizProgress()
            ->where('quiz_level_id', $levelId)
            ->exists();
    }

    public function getOverallProgressPercentage()
    {
        $totalLevels = QuizLevel::count();
        $completedLevels = $this->getCompletedLevelsCount();
        
        return $totalLevels > 0 ? round(($completedLevels / $totalLevels) * 100, 1) : 0;
    }

    public function getBestScore()
    {
        return $this->quizProgress()->max('score') ?? 0;
    }

    public function getTotalCorrectAnswers()
    {
        return $this->quizProgress()->sum('correct_answers') ?? 0;
    }

    public function getTotalWrongAnswers()
    {
        return $this->quizProgress()->sum('wrong_answers') ?? 0;
    }

    public function getAccuracyPercentage()
    {
        $totalCorrect = $this->getTotalCorrectAnswers();
        $totalWrong = $this->getTotalWrongAnswers();
        $totalAnswers = $totalCorrect + $totalWrong;
        
        return $totalAnswers > 0 ? round(($totalCorrect / $totalAnswers) * 100, 1) : 0;
    }

    public function getRank()
    {
        $score = User::withCount(['quizProgress as total_score' => function($query) {
            $query->select(DB::raw('COALESCE(SUM(score), 0)'));
        }])->orderBy('total_score', 'desc')
           ->pluck('id')
           ->search($this->id);

        return $score !== false ? $score + 1 : 'N/A';
    }

    public function getActivityStatus()
    {
        $lastActivity = $this->quizProgress()
            ->orderBy('completed_at', 'desc')
            ->first();

        if (!$lastActivity) {
            return 'Not active yet';
        }

        $daysAgo = $lastActivity->completed_at->diffInDays(now());

        if ($daysAgo == 0) {
            return 'Active today';
        } elseif ($daysAgo == 1) {
            return 'Active yesterday';
        } elseif ($daysAgo <= 7) {
            return 'Active this week';
        } elseif ($daysAgo <= 30) {
            return 'Active this month';
        } else {
            return 'Active ' . $daysAgo . ' days ago';
        }
    }

    // Friendship Methods
    public function isFriendWith(User $user)
    {
        return $this->friends()->where('friend_id', $user->id)->exists();
    }

    public function hasPendingRequestFrom(User $user)
    {
        return $this->friendRequests()->where('user_id', $user->id)->exists();
    }

    public function hasSentRequestTo(User $user)
    {
        return $this->sentFriendRequests()->where('friend_id', $user->id)->exists();
    }

    public function getFriendshipWith(User $user)
    {
        return Friendship::betweenUsers($this->id, $user->id)->first();
    }

    public function getMutualFriendsCount(User $user)
    {
        $myFriends = $this->friends()->pluck('users.id');
        $theirFriends = $user->friends()->pluck('users.id');
        
        return $myFriends->intersect($theirFriends)->count();
    }

    // SCOPES
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function($q) use ($searchTerm) {
            $q->where('name', 'like', '%' . $searchTerm . '%')
              ->orWhere('username', 'like', '%' . $searchTerm . '%')
              ->orWhere('email', 'like', '%' . $searchTerm . '%');
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeUsers($query)
    {
        return $query->where('role', 'user');
    }

    public function scopeRecentActive($query, $days = 7)
    {
        return $query->whereHas('quizProgress', function($q) use ($days) {
            $q->where('completed_at', '>=', now()->subDays($days));
        });
    }

    public function scopeWithQuizStats($query)
    {
        return $query->withCount(['quizProgress as completed_levels_count'])
                    ->withAvg('quizProgress', 'score')
                    ->withMax('quizProgress', 'score');
    }

    // STATIC METHODS
    public static function generateUsername($name)
    {
        $baseUsername = \Illuminate\Support\Str::slug($name, '');
        $username = $baseUsername;
        $counter = 1;
        
        while (static::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        return $username;
    }

    public static function getLeaderboard($limit = 10)
    {
        return static::withQuizStats()
            ->orderBy('completed_levels_count', 'desc')
            ->orderBy('quiz_progress_avg_score', 'desc')
            ->limit($limit)
            ->get();
    }

    // EVENT HANDLERS
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->username)) {
                $user->username = static::generateUsername($user->name);
            }
        });

        static::updating(function ($user) {
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
            
            if ($user->isDirty('phone')) {
                $user->phone_verified_at = null;
            }
        });
    }
}