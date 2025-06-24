<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InitialTestController extends Controller
{
    public function show()
    {
        $words = [
            'apple', 'dog', 'computer', 'freedom', 'philosophy', 'envelope', 'architecture',
            'reliable', 'predictable', 'awkward', 'knowledge', 'priority', 'urban', 'subtle',
            'venture', 'furniture', 'schedule', 'nurture', 'economics', 'tragedy'
        ];

        return view('tests.initial', compact('words'));
    }

    public function submit(Request $request)
    {
        $knownWords = $request->input('known_words', []);
        $count = count($knownWords);

        // Примерная оценка уровня по количеству известных слов
        $level = match (true) {
            $count >= 16 => 'Advanced (C1-C2)',
            $count >= 11 => 'Upper Intermediate (B2)',
            $count >= 6  => 'Intermediate (B1)',
            $count >= 3  => 'Elementary (A2)',
            default      => 'Beginner (A1)'
        };

        return view('tests.result', compact('count', 'level'));
    }
}
