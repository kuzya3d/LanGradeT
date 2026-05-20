<?php

namespace App\Http\Controllers;

use App\Models\AiChatMessage;
use App\Models\AiChatSession;
use App\Models\Lesson;
use App\Models\Sentence;
use App\Models\Word;
use App\Services\AchievementService;
use App\Services\BackgroundCommandRunner;
use App\Support\AiMessageRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AiTutorController extends Controller
{
    public function index()
    {
        $session = AiChatSession::firstOrCreate(['user_id' => Auth::id()], ['title' => 'AI-Наставник']);
        $messages = $session->messages()->latest('id')->take(30)->get()->reverse();

        return view('ai.index', compact('session', 'messages'));
    }

    public function ask(Request $request, BackgroundCommandRunner $runner)
    {
        $data = $request->validate(['message' => ['required', 'string', 'max:2000']]);
        $session = AiChatSession::firstOrCreate(['user_id' => Auth::id()], ['title' => 'AI-Наставник']);

        $userMessage = AiChatMessage::create([
            'ai_chat_session_id' => $session->id,
            'role' => 'user',
            'content' => $data['message'],
        ]);
        app(AchievementService::class)->sync(Auth::user());

        $assistantMessage = AiChatMessage::create([
            'ai_chat_session_id' => $session->id,
            'role' => 'assistant',
            'content' => 'Думаю...',
            'meta' => ['engine' => 'pending'],
        ]);

        if (! $runner->run('ai:tutor-answer '.$assistantMessage->id)) {
            $assistantMessage->update([
                'content' => $this->localAnswer($data['message']),
                'meta' => ['engine' => 'local-fallback', 'error' => 'Background process failed to start'],
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'user' => $this->messagePayload($userMessage),
                'assistant' => $this->messagePayload($assistantMessage->refresh()),
            ]);
        }

        return redirect()->route('ai.index');
    }

    public function message(AiChatMessage $message)
    {
        abort_unless($message->session && $message->session->user_id === Auth::id(), 404);

        return response()->json($this->messagePayload($message->refresh()));
    }

    public function clear()
    {
        $session = AiChatSession::firstOrCreate(['user_id' => Auth::id()], ['title' => 'AI-Наставник']);
        $session->messages()->delete();

        return redirect()->route('ai.index')->with('success', 'Чат с наставником очищен.');
    }

    private function messagePayload(AiChatMessage $message): array
    {
        return [
            'id' => $message->id,
            'role' => $message->role,
            'content' => $message->content,
            'html' => $message->role === 'assistant' ? AiMessageRenderer::toHtml($message->content) : null,
            'meta' => $message->meta ?? [],
            'pending' => ($message->meta['engine'] ?? null) === 'pending',
        ];
    }

    private function localAnswer(string $message): string
    {
        $query = $this->normalize($message);
        $word = $this->findWord($query);

        if ($word) {
            return implode("\n", array_filter([
                "Нашел в базе: **{$word->english}** - {$word->russian}.",
                $word->example_en ? "Пример: {$word->example_en}" : null,
                $word->example_ru ? "Перевод примера: {$word->example_ru}" : null,
                "Можно спросить: \"составь предложения с {$word->english}\" или \"как использовать {$word->english}\".",
            ]));
        }

        if (Str::contains($query, ['пример', 'examples', 'sentence', 'предложени'])) {
            $sentences = Sentence::inRandomOrder()->take(5)->get();

            return "Вот несколько предложений для разбора:\n".$sentences->map(fn ($s) => "- {$s->english} - {$s->russian}")->implode("\n");
        }

        $lesson = $this->findLesson($query);

        if ($lesson) {
            return $this->lessonAnswer($lesson, $query);
        }

        if (Str::contains($query, ['тест', 'практик', 'закреп', 'ошиб'])) {
            return "Дерево практики:\n1. Слова и перевод: начни с «Быстрый выбор перевода».\n2. Контекст: затем «Пропущенное слово».\n3. Порядок слов: «Собери предложение».\n4. Произношение: «Фонетика».\nЕсли результат ниже 55%, сайт снимет немного XP, поэтому лучше повторить слова перед тестом.";
        }

        return "Я могу искать слова по русскому и английскому, объяснять уроки и подбирать тесты. Спроси, например: «переведи reliable», «что такое Present Simple», «дай примеры предложений», «какой тест пройти после артиклей».";
    }

    private function findWord(string $query): ?Word
    {
        $tokens = collect(preg_split('/\s+/u', $query))->filter(fn ($token) => mb_strlen($token) > 2)->values();
        $words = Word::visibleTo(Auth::user())->get();

        $best = null;
        $bestScore = 999;

        foreach ($words as $word) {
            foreach ([$word->english, $word->russian] as $candidate) {
                $candidate = $this->normalize((string) $candidate);

                foreach ($tokens as $token) {
                    if (str_contains($candidate, $token) || str_contains($token, $candidate)) {
                        return $word;
                    }

                    $distance = levenshtein($token, $candidate);

                    if ($distance < $bestScore) {
                        $bestScore = $distance;
                        $best = $word;
                    }
                }
            }
        }

        return $bestScore <= 2 ? $best : null;
    }

    private function findLesson(string $query): ?Lesson
    {
        $lessons = Lesson::all();

        foreach ($lessons as $lesson) {
            $haystack = $this->normalize($lesson->title.' '.$lesson->summary.' '.strip_tags($lesson->content));

            foreach (preg_split('/\s+/u', $query) as $token) {
                if (mb_strlen($token) > 4 && str_contains($haystack, $token)) {
                    return $lesson;
                }
            }
        }

        return null;
    }

    private function lessonAnswer(Lesson $lesson, string $query): string
    {
        $depth = Str::contains($query, ['подроб', 'глуб', 'исключ', 'таблиц']) ? 'deep' : 'short';
        $plain = trim(preg_replace('/\s+/', ' ', strip_tags($lesson->content)));
        $excerpt = Str::limit($plain, $depth === 'deep' ? 850 : 420);

        return "Тема: {$lesson->title} ({$lesson->level}).\n{$lesson->summary}\n\n{$excerpt}\n\nДальше можно спросить: «дай примеры», «какие ошибки», «какой тест пройти».";
    }

    private function normalize(string $value): string
    {
        $value = Str::lower($value);

        return trim(preg_replace('/[^\pL\pN\s]/u', ' ', $value));
    }
}
