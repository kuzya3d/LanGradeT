<?php

namespace App\Models;

use App\Casts\EncryptedString;
use Illuminate\Database\Eloquent\Model;

class AiChatMessage extends Model
{
    protected $fillable = ['ai_chat_session_id', 'role', 'content', 'meta'];

    protected $casts = [
        'content' => EncryptedString::class,
        'meta' => 'array',
    ];

    public function session()
    {
        return $this->belongsTo(AiChatSession::class, 'ai_chat_session_id');
    }
}
