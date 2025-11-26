<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuizProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_level_id',
        'correct_answers',
        'score',
        'completed_at',
        'wrong_answers',      // ← TAMBAH INI
        'completion_time',    // ← TAMBAH INI
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quizLevel()
    {
        return $this->belongsTo(QuizLevel::class);
    }

    // METHOD BARU UNTUK DASHBOARD
    public function getAccuracyPercentage()
    {
        $totalQuestions = $this->correct_answers + $this->wrong_answers;
        if ($totalQuestions > 0) {
            return round(($this->correct_answers / $totalQuestions) * 100, 1);
        }
        return 0;
    }

    public function getFormattedCompletionTime()
    {
        if ($this->completion_time < 60) {
            return $this->completion_time . ' detik';
        } else {
            $minutes = floor($this->completion_time / 60);
            $seconds = $this->completion_time % 60;
            return $minutes . ' menit ' . $seconds . ' detik';
        }
    }

    public function isPassed($passingScore = 3)
    {
        return $this->score >= $passingScore;
    }
}