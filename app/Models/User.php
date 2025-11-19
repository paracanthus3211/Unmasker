<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relationship dengan quiz results
     */
    public function quizResults()
    {
        return $this->hasMany(QuizResult::class);
    }

    /**
     * Relationship dengan user progress
     */
    public function userProgress()
    {
        return $this->hasMany(UserQuizProgress::class);
    }

    /**
     * Scope untuk user aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Attribute untuk jumlah level yang diselesaikan
     */
    public function getCompletedLevelsCountAttribute()
    {
        return $this->quizResults()->distinct('level_id')->count();
    }

    /**
     * Attribute untuk rata-rata score
     */
    public function getAverageScoreAttribute()
    {
        return $this->quizResults()->avg('score') ?? 0;
    }

    /**
     * Attribute untuk total kuis yang diselesaikan
     */
    public function getTotalQuizzesCompletedAttribute()
    {
        return $this->quizResults()->count();
    }

    /**
     * Cek apakah user sudah menyelesaikan level tertentu
     */
    public function hasCompletedLevel($levelId)
    {
        return $this->quizResults()
            ->where('level_id', $levelId)
            ->exists();
    }

    /**
     * Get score user untuk level tertentu
     */
    public function getLevelScore($levelId)
    {
        $result = $this->quizResults()
            ->where('level_id', $levelId)
            ->first();
            
        return $result ? $result->score : null;
    }
}