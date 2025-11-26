<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'button_image',
        'time_limit', 
        'order'
    ];

    // RELATIONSHIPS
    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function userProgress()
    {
        return $this->hasMany(UserQuizProgress::class);
    }

    // METHOD BARU UNTUK DASHBOARD
    public function getTotalQuestionsCount()
    {
        return $this->questions()->count();
    }

    public function getAverageScore()
    {
        return round($this->userProgress()->avg('score') ?? 0, 1);
    }

    public function getCompletionCount()
    {
        return $this->userProgress()->count();
    }

    public function getSuccessRate()
    {
        $totalAttempts = $this->getCompletionCount();
        $passedAttempts = $this->userProgress()->where('score', '>=', 3)->count();
        
        if ($totalAttempts > 0) {
            return round(($passedAttempts / $totalAttempts) * 100, 1);
        }
        return 0;
    }

    // Untuk tampilan di quizz page
    public function getButtonImageUrl()
    {
        if ($this->button_image) {
            return asset('storage/' . $this->button_image);
        }
        return null;
    }
}