<?php

namespace App\Http\Middleware;

use App\Services\AchievementService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserStreak
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($user = $request->user()) {
            $user->promoteEnglishLevelFromXp();

            $today = now()->toDateString();
            $lastSeen = optional($user->last_seen_on)->toDateString();

            if ($lastSeen !== $today) {
                $yesterday = now()->subDay()->toDateString();

                $user->forceFill([
                    'streak_days' => $lastSeen === $yesterday ? $user->streak_days + 1 : 1,
                    'last_seen_on' => $today,
                ])->save();

                app(AchievementService::class)->sync($user);
            }
        }

        return $next($request);
    }
}
