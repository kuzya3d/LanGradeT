<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

public function words()
{
    return $this->belongsToMany(\App\Models\Word::class, 'user_words')->withTimestamps();
}

public function testResults()
{
    return $this->hasMany(\App\Models\TestResult::class);
}

}
