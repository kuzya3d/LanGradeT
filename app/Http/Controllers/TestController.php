<?php

namespace App\Http\Controllers;

use App\Models\TestResult;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function index()
    {
        return view('tests.index');
    }

    // Тест "Сборка слова"
    public function compileWord()
    {
        $words = Word::inRandomOrder()->limit(1)->get()->map(function ($word) {
            return [
                'id' => $word->id,
                'english' => $word->english,
                'russian' => $word->russian,
            ];
        });

        return view('tests.compile-word', compact('words'));
    }

    // Обработчик: Сборка слова — сохранение результата
    public function submitCompileWord(Request $request)
    {
        $correctCount = (int) $request->input('correct', 0);
        $total = (int) $request->input('total', 0);

        $score = $total > 0 ? round(($correctCount / $total) * 100) : 0;

        TestResult::create([
            'user_id' => Auth::id(),
            'type' => 'compile',
            'score' => $score,
        ]);

        return redirect('tests')->with('success', 'Результат теста сохранён!');
    }

    // Тест "Перевод" (ввод перевода)
    public function translation()
    {
        $words = Word::inRandomOrder()->limit(1)->get();
        return view('tests.translation', compact('words'));
    }

    public function submitTranslation(Request $request)
    {
        $answers = $request->input('answers', []);
        $wordIds = array_keys($answers);
        $words = Word::whereIn('id', $wordIds)->get();

        $correctCount = 0;
        $total = count($answers);

        foreach ($words as $word) {
            $userAnswer = trim($answers[$word->id] ?? '');
            if (mb_strtolower($userAnswer) === mb_strtolower($word->russian)) {
                $correctCount++;
            }
        }

        $score = $total > 0 ? round(($correctCount / $total) * 100) : 0;

        TestResult::create([
            'user_id' => Auth::id(),
            'type' => 'input',
            'score' => $score,
        ]);

        return view('tests.translation_result', [
            'score' => $correctCount,
            'total' => $total,
            'words' => $words,
            'answers' => $answers,
        ]);
    }

    // Тест "Флеш-карточки"
    public function flashcards()
    {
        $words = Word::whereNotNull('image')->inRandomOrder()->take(15)->get();
        return view('tests.flashcards', compact('words'));
    }
}
