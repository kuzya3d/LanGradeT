<?php

namespace App\Http\Controllers;

use App\Models\User;

class LeaderboardController extends Controller
{
    public function index()
    {
        $leaders = User::withCount(['words', 'attempts', 'achievements'])
            ->orderByDesc('xp')
            ->orderByDesc('attempts_count')
            ->take(50)
            ->get();

        return view('leaderboard.index', compact('leaders'));
    }
}
