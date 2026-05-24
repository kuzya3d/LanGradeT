<?php

namespace App\Models;

use App\Casts\EncryptedString;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['conversation_id', 'user_id', 'body'];

    protected $casts = [
        'body' => EncryptedString::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
