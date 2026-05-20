<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Sentence;
use App\Models\Word;
use App\Support\RecordsPractice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PracticeController extends Controller
{
    use RecordsPractice;

    public function multipleChoice(Request $request)
    {
        $includePhrases = $this->includePhrases($request);
        $questions = $this->wordPool($request)->take(12)->map(function (Word $word) use ($includePhrases) {
            return [
                'id' => $word->id,
                'question' => $word->english,
                'hint' => $word->transcription,
                'correct' => $word->russian,
                'is_phrase' => $word->part_of_speech === 'phrase',
                'options' => $this->visibleWordQuery()->where('id', '!=', $word->id)
                    ->when(! $includePhrases, fn ($query) => $query->where('part_of_speech', '!=', 'phrase'))
                    ->inRandomOrder()
                    ->take(5)
                    ->pluck('russian')
                    ->push($word->russian)
                    ->shuffle()
                    ->values(),
            ];
        });

        return view('tests.quiz', [
            'title' => 'Быстрый выбор перевода',
            'subtitle' => $request->string('source')->toString() === 'dictionary'
                ? 'Закрепление личного словаря без начисления и снятия XP.'
                : 'Выберите правильный русский перевод английского слова.',
            'mode' => 'multiple-choice',
            'questions' => $questions,
            'source' => $request->string('source')->toString(),
            'includePhrases' => $includePhrases,
        ]);
    }

    public function gapFill(Request $request)
    {
        $questions = $this->sentencePool($request)->take(12)->map(function (Sentence $sentence) {
            $gap = $this->randomGap((string) $sentence->english);

            return [
                'id' => $sentence->id,
                'question' => $gap['question'],
                'hint' => collect([$sentence->russian, $sentence->format])->filter()->implode(' - '),
                'correct' => $gap['answer'],
                'options' => [],
            ];
        })->filter(fn ($question) => $question['correct'] !== '')->values();

        return view('tests.quiz', [
            'title' => 'Пропущенное слово',
            'subtitle' => 'Впишите недостающее английское слово в предложении.',
            'mode' => 'gap-fill',
            'questions' => $questions,
        ]);
    }

    public function sentenceBuilder(Request $request)
    {
        $variant = $request->input('variant', 'typing');
        $questions = $this->sentencePool($request)->take(10)->map(function (Sentence $sentence) {
            $words = $this->sentenceTiles((string) $sentence->english);

            return [
                'id' => $sentence->id,
                'question' => $sentence->russian,
                'hint' => $words->implode(' / '),
                'format' => $sentence->format,
                'correct' => $sentence->english,
                'tiles' => $words,
                'options' => [],
            ];
        });

        return view('tests.quiz', [
            'title' => 'Собери предложение',
            'subtitle' => 'Выберите режим: печатать полностью или собирать предложение из плиток.',
            'mode' => 'sentence-builder',
            'variant' => $variant,
            'questions' => $questions,
        ]);
    }

    public function phonetics(Request $request)
    {
        $includePhrases = $this->includePhrases($request);
        $questions = $this->wordPool($request)->whereNotNull('transcription')->take(12)->map(function (Word $word) use ($includePhrases) {
            return [
                'id' => $word->id,
                'question' => $word->transcription,
                'hint' => 'Выберите слово по транскрипции',
                'correct' => $word->english,
                'is_phrase' => $word->part_of_speech === 'phrase',
                'options' => $this->visibleWordQuery()->where('id', '!=', $word->id)
                    ->whereNotNull('transcription')
                    ->when(! $includePhrases, fn ($query) => $query->where('part_of_speech', '!=', 'phrase'))
                    ->inRandomOrder()
                    ->take(5)
                    ->pluck('english')
                    ->push($word->english)
                    ->shuffle()
                    ->values(),
            ];
        });

        return view('tests.quiz', [
            'title' => 'Фонетика',
            'subtitle' => 'Узнайте слово по транскрипции. Это помогает связать написание и произношение.',
            'mode' => 'phonetics',
            'questions' => $questions,
            'includePhrases' => $includePhrases,
        ]);
    }

    public function wordSprint(Request $request)
    {
        $includePhrases = $this->includePhrases($request);
        $questions = $this->wordPool($request)->take(15)->map(function (Word $word) use ($includePhrases) {
            $isTrue = (bool) random_int(0, 1);
            $shownTranslation = $isTrue ? $word->russian : $this->visibleWordQuery()->where('id', '!=', $word->id)
                ->when(! $includePhrases, fn ($query) => $query->where('part_of_speech', '!=', 'phrase'))
                ->inRandomOrder()
                ->value('russian');

            return [
                'id' => $word->id,
                'question' => "{$word->english} - {$shownTranslation}",
                'hint' => 'Это верная пара?',
                'correct' => $isTrue ? 'yes' : 'no',
                'is_phrase' => $word->part_of_speech === 'phrase',
                'options' => collect(['yes' => 'Верно', 'no' => 'Неверно']),
            ];
        });

        return view('tests.quiz', [
            'title' => 'Словарный спринт',
            'subtitle' => 'Быстро решайте, совпадает ли слово с переводом.',
            'mode' => 'word-sprint',
            'questions' => $questions,
            'includePhrases' => $includePhrases,
        ]);
    }

    public function submit(Request $request, string $mode)
    {
        $answers = $request->input('answers', []);
        $correct = $request->input('correct', []);
        $questions = $request->input('questions', []);
        $variant = $request->input('variant', 'typing');
        $source = $request->string('source')->toString();
        $practiceOnly = $mode === 'multiple-choice' && $source === 'dictionary';
        $titles = [
            'multiple-choice' => 'Быстрый выбор перевода',
            'gap-fill' => 'Пропущенное слово',
            'sentence-builder' => 'Собери предложение',
            'phonetics' => 'Фонетика',
            'word-sprint' => 'Словарный спринт',
        ];

        $rows = [];
        foreach ($correct as $key => $expected) {
            $given = $answers[$key] ?? '';
            $rows[] = [
                'question' => $questions[$key] ?? null,
                'user_answer' => $given,
                'correct_answer' => $expected,
                'is_phrase' => (bool) $request->input("is_phrase.{$key}", false),
                'is_correct' => $this->answerMatches($given, $expected),
            ];
        }

        [$attempt, $earned] = $this->recordPracticeAttempt(
            $mode,
            $titles[$mode] ?? ucfirst(str_replace('-', ' ', $mode)),
            in_array($mode, ['gap-fill', 'sentence-builder'], true) ? 'input' : 'choice',
            $rows,
            $practiceOnly ? 0 : ($mode === 'sentence-builder' ? ($variant === 'typing' ? 24 : 16) : 12),
            in_array($mode, ['gap-fill', 'sentence-builder'], true) ? 0 : ($this->includePhrases($request) ? 2 : 0),
        );

        return view('tests.modern_result', compact('attempt', 'earned', 'practiceOnly'));
    }

    private function randomGap(string $english): array
    {
        $candidates = $this->gapCandidates($english);

        if ($candidates->isEmpty()) {
            return ['question' => $english, 'answer' => ''];
        }

        $answer = $candidates->random();

        return [
            'question' => preg_replace('/\b'.preg_quote($answer, '/').'\b/i', '___', $english, 1) ?? $english,
            'answer' => $answer,
        ];
    }

    private function gapCandidates(string $english)
    {
        $skip = [
            'a', 'an', 'the', 'i', 'you', 'he', 'she', 'it', 'we', 'they',
            'my', 'your', 'his', 'her', 'our', 'their', 'this', 'that',
            'to', 'in', 'on', 'at', 'for', 'of', 'with', 'about', 'and',
        ];

        preg_match_all('/\b[a-z][a-z\']*\b/i', $english, $matches);

        return collect($matches[0] ?? [])
            ->map(fn (string $word) => trim($word, "'"))
            ->filter(fn (string $word) => mb_strlen($word) > 2)
            ->reject(fn (string $word) => in_array(Str::lower($word), $skip, true))
            ->values();
    }

    private function sentenceTiles(string $english)
    {
        preg_match_all('/\b[\pL\pN\']+\b/u', $english, $matches);

        return collect($matches[0] ?? [])->filter()->shuffle()->values();
    }

    private function wordPool(Request $request)
    {
        $includePhrases = $this->includePhrases($request);

        if ($request->string('source')->toString() === 'dictionary' && Auth::check()) {
            $words = Auth::user()->words()->inRandomOrder()->get();
            if (! $includePhrases) {
                $words = $words->where('part_of_speech', '!=', 'phrase')->values();
            }
            if ($words->count() > 0) {
                return $words;
            }
        }

        if ($request->filled('collection_id')) {
            $collection = Collection::with('words')->find($request->integer('collection_id'));
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

    private function includePhrases(Request $request): bool
    {
        if ($request->has('include_phrases')) {
            return $request->boolean('include_phrases');
        }

        return (bool) session('tests.include_phrases', false);
    }

    private function sentencePool(Request $request)
    {
        if ($request->filled('collection_id')) {
            $collection = Collection::with('words')->find($request->integer('collection_id'));
            $words = $collection?->words->pluck('english')->all() ?? [];
            if ($words === []) {
                return Sentence::inRandomOrder()->get();
            }

            $sentences = Sentence::where(function ($query) use ($words) {
                foreach ($words as $word) {
                    $query->orWhere('english', 'like', "%{$word}%");
                }
            })->inRandomOrder()->get();

            if ($sentences->count() > 0) {
                return $sentences;
            }
        }

        return Sentence::inRandomOrder()->get();
    }
}
