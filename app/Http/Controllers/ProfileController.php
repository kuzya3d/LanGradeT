<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\TestResult;
use App\Models\User;
use App\Services\AchievementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        app(AchievementService::class)->sync($user);
        $user->refresh();
        $user->load(['favoriteLessons', 'favoriteCollections', 'friends']);

        $userWords = $user->words()->get();
        $testResults = $user->testResults;
        $allAttempts = $user->attempts()->with('type')->latest()->get();
        $attempts = $allAttempts->take(8);
        $achievements = $user->achievements()->get();
        $achievementsTotal = Achievement::count();
        $passedPercent = $testResults->count() > 0 ? round($testResults->avg('score'), 2) : 0;

        $totalAttempts = $allAttempts->count();
        $averageAttemptScore = $totalAttempts > 0 ? round($allAttempts->avg('score'), 2) : 0;
        $bestAttemptScore = $totalAttempts > 0 ? (int) $allAttempts->max('score') : 0;
        $totalAnswered = (int) $allAttempts->sum('total_questions');
        $totalCorrect = (int) $allAttempts->sum('correct_answers');
        $accuracyPercent = $totalAnswered > 0 ? round(($totalCorrect / $totalAnswered) * 100, 2) : 0;
        $totalXpDelta = (int) $allAttempts->sum(fn ($attempt) => $attempt->payload['xp_delta'] ?? $attempt->xp_earned);
        $bestMode = $allAttempts
            ->groupBy(fn ($attempt) => $attempt->type->title ?? 'Тест')
            ->map(fn ($items, $title) => [
                'title' => $title,
                'average' => round($items->avg('score'), 2),
                'count' => $items->count(),
            ])
            ->sortByDesc('average')
            ->first();
        $testModeStats = $allAttempts
            ->groupBy(fn ($attempt) => $attempt->type->title ?? 'Тест')
            ->map(fn ($items, $title) => [
                'title' => $title,
                'count' => $items->count(),
                'average' => round($items->avg('score'), 2),
                'best' => (int) $items->max('score'),
                'xp' => (int) $items->sum(fn ($attempt) => $attempt->payload['xp_delta'] ?? $attempt->xp_earned),
                'last_at' => optional($items->first()->created_at)->format('d.m.Y H:i'),
            ])
            ->sortBy('title')
            ->values();

        return view('profile.index', compact(
            'user',
            'userWords',
            'passedPercent',
            'totalAttempts',
            'averageAttemptScore',
            'bestAttemptScore',
            'totalAnswered',
            'totalCorrect',
            'accuracyPercent',
            'totalXpDelta',
            'bestMode',
            'testModeStats',
            'attempts',
            'achievements',
            'achievementsTotal'
        ));
    }

    public function show(User $user)
    {
        $user->loadCount(['words', 'attempts', 'achievements']);
        $achievementsTotal = Achievement::count();
        $isFriend = Auth::check() && Auth::user()->friends()->where('friend_id', $user->id)->exists();

        return view('profile.show', compact('user', 'isFriend', 'achievementsTotal'));
    }

    public function avatar(Request $request)
    {
        $data = $request->validate([
            'avatar' => ['required', 'string', 'max:20'],
        ]);

        Auth::user()->update(['avatar' => $data['avatar']]);

        return back()->with('success', 'Аватар обновлен.');
    }

    public function bio(Request $request)
    {
        $data = $request->validate([
            'bio' => ['nullable', 'string', 'max:500'],
        ]);

        Auth::user()->update(['bio' => $data['bio']]);

        return back()->with('success', 'Био обновлено.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
