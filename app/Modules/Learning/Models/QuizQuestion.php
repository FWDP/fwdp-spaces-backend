<?php

namespace App\Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $fillable = [
        'quiz_id',
        'question',
        'options',
        'correct_answer',
    ];

    protected $casts = [
        'options' => 'array',
    ];
}
