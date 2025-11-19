<?php
// app/Models/UserQuizProgress.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserQuizProgress extends Model
{
    protected $fillable = [
        'user_id', 'quiz_level_id', 'score', 'correct_answers',
        'wrong_answers', 'completion_time', 'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(QuizLevel::class, 'quiz_level_id');
    }
}