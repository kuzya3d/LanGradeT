<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'english_level', 'xp', 'streak_days', 'last_seen_on', 'bio',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'last_seen_on' => 'date',
    ];

    public const LEVEL_ORDER = ['A0', 'A1', 'A2', 'B1', 'B2', 'C1'];

    public const XP_LEVELS = [
        'A0' => 0,
        'A1' => 5000,
        'A2' => 10000,
        'B1' => 20000,
        'B2' => 35000,
        'C1' => 50000,
    ];

    public static function levelRank(string $level): int
    {
        $rank = array_search($level, self::LEVEL_ORDER, true);

        return $rank === false ? 0 : $rank;
    }

    public static function higherLevel(string $first, string $second): string
    {
        return self::levelRank($second) > self::levelRank($first) ? $second : $first;
    }

    public static function levelFromXp(int $xp): string
    {
        $level = 'A0';

        foreach (self::XP_LEVELS as $candidate => $requiredXp) {
            if ($xp >= $requiredXp) {
                $level = $candidate;
            }
        }

        return $level;
    }

    public function promoteEnglishLevelFromXp(): void
    {
        $xpLevel = self::levelFromXp((int) $this->xp);
        $nextLevel = self::higherLevel($this->english_level ?? 'A0', $xpLevel);

        if ($nextLevel !== $this->english_level) {
            $this->forceFill(['english_level' => $nextLevel])->save();
        }
    }

public function words()
{
    return $this->belongsToMany(\App\Models\Word::class, 'user_words')->withTimestamps();
}

public function testResults()
{
    return $this->hasMany(\App\Models\TestResult::class);
}

public function attempts()
{
    return $this->hasMany(\App\Models\TestAttempt::class);
}

public function achievements()
{
    return $this->belongsToMany(\App\Models\Achievement::class)->withPivot('earned_at');
}

public function conversations()
{
    return $this->belongsToMany(\App\Models\Conversation::class)->withPivot('last_read_at')->withTimestamps();
}

public function favoriteLessons()
{
    return $this->belongsToMany(\App\Models\Lesson::class)->withTimestamps();
}

public function favoriteCollections()
{
    return $this->belongsToMany(\App\Models\Collection::class)->withTimestamps();
}

public function friends()
{
    return $this->belongsToMany(self::class, 'friendships', 'user_id', 'friend_id')
        ->wherePivot('status', 'accepted')
        ->withTimestamps();
}

}
