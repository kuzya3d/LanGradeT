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
        $attempts = $user->attempts()->with('type')->latest()->take(8)->get();
        $achievements = $user->achievements()->get();
        $achievementsTotal = Achievement::count();
        $passedPercent = $testResults->count() > 0 ? round($testResults->avg('score'), 2) : 0;

        $translationResultsCount = TestResult::where('user_id', $user->id)->where('type', 'input')->count();
        $translationAvgScore = TestResult::where('user_id', $user->id)->where('type', 'input')->avg('score');
        $translationAvgScore = $translationAvgScore ? round($translationAvgScore, 2) : 0;

        $compileResultsCount = TestResult::where('user_id', $user->id)->where('type', 'compile')->count();
        $compileAvgScore = TestResult::where('user_id', $user->id)->where('type', 'compile')->avg('score');
        $compileAvgScore = $compileAvgScore ? round($compileAvgScore, 2) : 0;

        return view('profile.index', compact(
            'user',
            'userWords',
            'passedPercent',
            'translationResultsCount',
            'translationAvgScore',
            'compileResultsCount',
            'compileAvgScore',
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
