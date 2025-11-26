<?php

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

    // METHOD BARU UNTUK FUNCTIONALITY
    public function getQuestionImageUrl()
    {
        if ($this->question_image) {
            // Cek jika sudah full URL atau path storage
            if (str_starts_with($this->question_image, 'http')) {
                return $this->question_image;
            }
            return asset('storage/' . $this->question_image);
        }
        return null;
    }

    public function getOptionImageUrl($option)
    {
        $imageField = 'option_' . strtolower($option) . '_image';
        if ($this->$imageField) {
            // Cek jika sudah full URL atau path storage
            if (str_starts_with($this->$imageField, 'http')) {
                return $this->$imageField;
            }
            return asset('storage/' . $this->$imageField);
        }
        return null;
    }

    public function getOptionText($option)
    {
        $textField = 'option_' . strtolower($option);
        return $this->$textField ?? '';
    }

    public function isCorrectAnswer($selectedOption)
    {
        return $this->correct_answer === strtoupper($selectedOption);
    }

    public function getOptions()
    {
        return [
            'A' => [
                'text' => $this->option_a,
                'image' => $this->option_a_image,
                'image_url' => $this->getOptionImageUrl('A')
            ],
            'B' => [
                'text' => $this->option_b,
                'image' => $this->option_b_image,
                'image_url' => $this->getOptionImageUrl('B')
            ],
            'C' => [
                'text' => $this->option_c,
                'image' => $this->option_c_image,
                'image_url' => $this->getOptionImageUrl('C')
            ],
            'D' => [
                'text' => $this->option_d,
                'image' => $this->option_d_image,
                'image_url' => $this->getOptionImageUrl('D')
            ]
        ];
    }

    // Untuk debug dan development
    public function getDebugInfo()
    {
        return [
            'question_id' => $this->id,
            'question_text' => $this->question_text,
            'question_image' => $this->question_image,
            'question_image_url' => $this->getQuestionImageUrl(),
            'correct_answer' => $this->correct_answer,
            'options' => $this->getOptions()
        ];
    }

    // Scope untuk query yang sering digunakan
    public function scopeForLevel($query, $levelId)
    {
        return $query->where('quiz_level_id', $levelId);
    }

    public function scopeWithImages($query)
    {
        return $query->where(function($q) {
            $q->whereNotNull('question_image')
              ->orWhereNotNull('option_a_image')
              ->orWhereNotNull('option_b_image')
              ->orWhereNotNull('option_c_image')
              ->orWhereNotNull('option_d_image');
        });
    }
}