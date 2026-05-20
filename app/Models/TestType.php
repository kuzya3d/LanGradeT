<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestType extends Model
{
    protected $fillable = ['code', 'title', 'description', 'xp_reward', 'icon'];

    public function attempts()
    {
        return $this->hasMany(TestAttempt::class);
    }
}
