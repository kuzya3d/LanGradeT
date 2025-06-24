<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TestResult;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $userWords = $user->words()->get();

        $testResults = $user->testResults;

        // Средний общий результат
        $passedPercent = 0;
        if ($testResults->count() > 0) {
            $passedPercent = round($testResults->avg('score'), 2);
        }

// Тест на перевод (input)
$translationResultsCount = TestResult::where('user_id', $user->id)
    ->where('type', 'input')
    ->count();

$translationAvgScore = TestResult::where('user_id', $user->id)
    ->where('type', 'input')
    ->avg('score');
$translationAvgScore = $translationAvgScore ? round($translationAvgScore, 2) : 0;

// Тест на сборку слова (compile)
$compileResultsCount = TestResult::where('user_id', $user->id)
    ->where('type', 'compile')
    ->count();

$compileAvgScore = TestResult::where('user_id', $user->id)
    ->where('type', 'compile')
    ->avg('score');
$compileAvgScore = $compileAvgScore ? round($compileAvgScore, 2) : 0;

        return view('profile.index', compact(
            'user',
            'userWords',
            'passedPercent',
            'translationResultsCount',
            'translationAvgScore',
            'compileResultsCount',
            'compileAvgScore'
        ));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
