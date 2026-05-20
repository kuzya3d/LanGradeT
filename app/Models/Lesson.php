<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['title', 'slug', 'level', 'type', 'summary', 'content', 'position'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
