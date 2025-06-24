<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    protected $fillable = ['english', 'russian', 'image'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_words')->withTimestamps();
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'collection_word')->withTimestamps();
    }
}
