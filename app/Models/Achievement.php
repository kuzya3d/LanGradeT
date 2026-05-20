<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = [
        'code',
        'title',
        'description',
        'icon',
        'xp_bonus',
        'condition_type',
        'condition_value',
    ];

    public function getDisplayIconAttribute(): string
    {
        return match ($this->icon) {
            'star' => '⭐',
            'target' => '🎯',
            'book' => '📚',
            'trophy' => '🏆',
            'zap' => '⚡',
            'fire' => '🔥',
            'calendar' => '📅',
            'heart' => '💚',
            'users' => '👥',
            'chat' => '💬',
            'brain' => '🧠',
            'medal' => '🥇',
            default => $this->icon ?: '⭐',
        };
    }
}
