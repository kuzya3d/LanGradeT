<?php

namespace App\Http\Controllers;

use App\Models\Word;
use App\Support\RecordsPractice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    use RecordsPractice;

    public function index(Request $request)
    {
        $includePhrases = $this->rememberIncludePhrases($request);

        return view('tests.index', compact('includePhrases'));
    }

    public function compileWord(Request $request)
    {
        $words = $this->wordsForLegacy($request)->take(8)->map(fn (Word $word) => [
            'id' => $word->id,
            'english' => $word->english,
            'russian' => $word->russian,
            'transcription' => $word->transcription,
            'part_of_speech' => $word->part_of_speech,
        ])->values();
        $submissionToken = $this->issuePracticeSubmissionToken();

        return view('tests.compile-word', compact('words', 'submissionToken'));
    }

    public function submitCompileWord(Request $request)
    {
        if ($redirect = $this->duplicatePracticeSubmissionRedirect($request)) {
            return $redirect;
        }

        $answers = $request->input('answers', []);
        $correct = $request->input('correct_words', []);
        $translations = $request->input('translations', []);
        $rows = [];

        foreach ($correct as $index => $expected) {
            $rows[] = [
                'question' => $translations[$index] ?? 'Соберите слово',
                'user_answer' => $answers[$index] ?? '',
                'correct_answer' => $expected,
                'is_phrase' => (bool) ($request->input("is_phrase.{$index}") ?? false),
                'is_correct' => $this->answerMatches($answers[$index] ?? '', $expected),
            ];
        }

        [$attempt, $earned] = $this->recordPracticeAttempt('compile-word', 'Сборка слова', 'compile', $rows, 14, $this->includePhrases($request) ? 2 : 0);
        $this->markPracticeSubmissionUsed($request, $attempt);

        return redirect()
            ->route('tests.attempt-result', $attempt)
            ->with('earned_achievement_ids', $earned->pluck('id')->all());
    }

    public function translation(Request $request)
    {
        $words = $this->wordsForLegacy($request)->take(8);
        $submissionToken = $this->issuePracticeSubmissionToken();

        return view('tests.translation', compact('words', 'submissionToken'));
    }

    public function submitTranslation(Request $request)
    {
        if ($redirect = $this->duplicatePracticeSubmissionRedirect($request)) {
            return $redirect;
        }

        $answers = $request->input('answers', []);
        $words = $this->visibleWordQuery()->whereIn('id', array_keys($answers))->get();
        $rows = [];

        foreach ($words as $word) {
            $rows[] = [
                'word_id' => $word->id,
                'question' => $word->english,
                'user_answer' => $answers[$word->id] ?? '',
                'correct_answer' => $word->russian,
                'is_phrase' => $word->part_of_speech === 'phrase',
                'is_correct' => $this->answerMatches($answers[$word->id] ?? '', $word->russian),
            ];
        }

        [$attempt, $earned] = $this->recordPracticeAttempt('translation-input', 'Ввод перевода', 'input', $rows, 16, $this->includePhrases($request) ? 2 : 0);
        $this->markPracticeSubmissionUsed($request, $attempt);

        return redirect()
            ->route('tests.attempt-result', $attempt)
            ->with('earned_achievement_ids', $earned->pluck('id')->all());
    }

    public function flashcards(Request $request)
    {
        $words = $this->wordsForLegacy($request)->take(15);

        return view('tests.flashcards', compact('words'));
    }

    private function wordsForLegacy(Request $request)
    {
        $includePhrases = $this->includePhrases($request);

        if ($request->string('source')->toString() === 'dictionary' && auth()->check()) {
            $words = auth()->user()->words()->inRandomOrder()->get();
            if (! $includePhrases) {
                $words = $words->where('part_of_speech', '!=', 'phrase')->values();
            }
            if ($words->count() > 0) {
                return $words;
            }
        }

        if ($request->filled('collection_id')) {
            $collection = \App\Models\Collection::with('words')->find($request->integer('collection_id'));
            if ($collection && $collection->words->count() > 0) {
                $words = $collection->words;
                if (! $includePhrases) {
                    $words = $words->where('part_of_speech', '!=', 'phrase');
                }

                if ($words->count() > 0) {
                    return $words->shuffle()->values();
                }
            }
        }

        $query = $this->visibleWordQuery()->inRandomOrder();
        if (! $includePhrases) {
            $query->where('part_of_speech', '!=', 'phrase');
        }

        return $query->get();
    }

    private function visibleWordQuery()
    {
        return Word::visibleTo(Auth::user());
    }

    private function rememberIncludePhrases(Request $request): bool
    {
        if ($request->has('include_phrases')) {
            session(['tests.include_phrases' => $request->boolean('include_phrases')]);
        }

        return (bool) session('tests.include_phrases', false);
    }

    private function includePhrases(Request $request): bool
    {
        if ($request->has('include_phrases')) {
            return $request->boolean('include_phrases');
        }

        return (bool) session('tests.include_phrases', false);
    }
}
