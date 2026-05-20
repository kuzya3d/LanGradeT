<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'test_type_id',
        'score',
        'correct_answers',
        'total_questions',
        'xp_earned',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function type()
    {
        return $this->belongsTo(TestType::class, 'test_type_id');
    }

    public function answers()
    {
        return $this->hasMany(TestAnswer::class);
    }
}
