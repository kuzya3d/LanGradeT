<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\AiChatMessage;
use App\Models\User;
use Illuminate\Support\Collection;

class AchievementService
{
    public function sync(User $user): Collection
    {
        $user = $user->fresh();
        $earned = collect();
        $stats = $this->statsFor($user);

        Achievement::all()->each(function (Achievement $achievement) use ($user, $stats, $earned) {
            if (($stats[$achievement->condition_type] ?? 0) < $achievement->condition_value) {
                return;
            }

            if ($user->achievements()->where('achievement_id', $achievement->id)->exists()) {
                return;
            }

            $user->achievements()->attach($achievement->id, ['earned_at' => now()]);
            $user->increment('xp', $achievement->xp_bonus);
            $user->refresh()->promoteEnglishLevelFromXp();
            $earned->push($achievement);
        });

        return $earned;
    }

    private function statsFor(User $user): array
    {
        return [
            'xp' => (int) $user->xp,
            'attempts' => $user->attempts()->count(),
            'words' => $user->words()->count(),
            'perfect_tests' => $user->attempts()->where('score', 100)->count(),
            'streak_days' => (int) $user->streak_days,
            'favorite_lessons' => $user->favoriteLessons()->count(),
            'favorite_collections' => $user->favoriteCollections()->count(),
            'friends' => $user->friends()->count(),
            'ai_messages' => AiChatMessage::whereHas('session', fn ($query) => $query->where('user_id', $user->id))
                ->where('role', 'user')
                ->count(),
        ];
    }
}
