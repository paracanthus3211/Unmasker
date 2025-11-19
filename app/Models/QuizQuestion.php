<?php
// app/Models/QuizQuestion.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizQuestion extends Model
{
    protected $fillable = [
        'quiz_level_id', 'question_text', 'question_image',
        'option_a', 'option_a_image', 'option_b', 'option_b_image',
        'option_c', 'option_c_image', 'option_d', 'option_d_image',
        'correct_answer'
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(QuizLevel::class, 'quiz_level_id');
    }
}