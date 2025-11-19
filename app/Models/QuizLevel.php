<?php
// app/Models/QuizLevel.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizLevel extends Model
{
    protected $fillable = ['name', 'button_image', 'time_limit', 'order'];

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function userProgress()
    {
        return $this->hasMany(UserQuizProgress::class);
    }
}