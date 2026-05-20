<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestAnswer extends Model
{
    protected $fillable = [
        'test_attempt_id',
        'word_id',
        'sentence_id',
        'question',
        'user_answer',
        'correct_answer',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];
}
