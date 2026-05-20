<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    protected $fillable = [
        'english',
        'russian',
        'transcription',
        'part_of_speech',
        'difficulty',
        'example_en',
        'example_ru',
        'user_id',
    ];

    public function scopeVisibleTo($query, $user = null)
    {
        $userId = is_numeric($user) ? $user : $user?->id;

        return $query->where(function ($query) use ($userId) {
            $query->whereNull('user_id');

            if ($userId) {
                $query->orWhere('user_id', $userId);
            }
        });
    }

    public function scopePublicWords($query)
    {
        return $query->whereNull('user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_words')->withTimestamps();
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'collection_word')->withTimestamps();
    }
}
