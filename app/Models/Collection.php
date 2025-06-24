<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'image'];

    public function words()
    {
        return $this->belongsToMany(Word::class, 'collection_word')->withTimestamps();
    }
}
