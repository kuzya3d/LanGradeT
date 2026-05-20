<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\Sentence;
use App\Models\Word;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class GigaChatTutorService
{
    private const TOKEN_CACHE_KEY = 'gigachat_access_token';

    public function answer(string $message, Collection $history): string
    {
        $response = $this->sendChatRequest($message, $history, $this->accessToken());

        if ($response->status() === 401) {
            Cache::forget(self::TOKEN_CACHE_KEY);
            $response = $this->sendChatRequest($message, $history, $this->accessToken(true));
        }

        if (! $response->successful()) {
            throw new RuntimeException('GigaChat API error: '.$response->status().' '.$response->body());
        }

        $text = data_get($response->json(), 'choices.0.message.content');

        if (! is_string($text) || trim($text) === '') {
            throw new RuntimeException('GigaChat returned an empty response.');
        }

        return trim($text);
    }

    private function sendChatRequest(string $message, Collection $history, string $token)
    {
        return $this->request()
            ->withToken($token)
            ->post(rtrim(config('services.gigachat.api_url'), '/').'/api/v1/chat/completions', [
                'model' => config('services.gigachat.model', 'GigaChat'),
                'messages' => $this->messages($message, $history),
                'temperature' => 0.45,
                'top_p' => 0.9,
                'max_tokens' => 1200,
            ]);
    }

    private function accessToken(bool $forceRefresh = false): string
    {
        if (! $forceRefresh && Cache::has(self::TOKEN_CACHE_KEY)) {
            return Cache::get(self::TOKEN_CACHE_KEY);
        }

        $authorizationKey = config('services.gigachat.authorization_key');

        if (! $authorizationKey) {
            throw new RuntimeException('GigaChat authorization key is not configured.');
        }

        $response = $this->request()
            ->asForm()
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Basic '.$authorizationKey,
                'RqUID' => (string) Str::uuid(),
            ])
            ->post(rtrim(config('services.gigachat.oauth_url'), '/').'/api/v2/oauth', [
                'scope' => config('services.gigachat.scope', 'GIGACHAT_API_PERS'),
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('GigaChat OAuth error: '.$response->status().' '.$response->body());
        }

        $token = data_get($response->json(), 'access_token');

        if (! is_string($token) || trim($token) === '') {
            throw new RuntimeException('GigaChat OAuth returned an empty token.');
        }

        Cache::put(self::TOKEN_CACHE_KEY, $token, $this->tokenTtl($response->json()));

        return $token;
    }

    private function tokenTtl(array $payload): int
    {
        $expiresAt = (int) data_get($payload, 'expires_at', 0);

        if ($expiresAt > 1000000000000) {
            $expiresAt = (int) floor($expiresAt / 1000);
        }

        if ($expiresAt > time()) {
            return max(60, $expiresAt - time() - 60);
        }

        return 25 * 60;
    }

    private function request()
    {
        $request = Http::timeout((int) config('services.gigachat.timeout', 12))
            ->connectTimeout((int) config('services.gigachat.connect_timeout', 5))
            ->acceptJson()
            ->asJson();

        if (config('services.gigachat.verify_ssl') === false) {
            $request = $request->withOptions(['verify' => false]);
        }

        if (config('services.gigachat.proxy')) {
            $request = $request->withOptions(['proxy' => config('services.gigachat.proxy')]);
        }

        return $request;
    }

    private function messages(string $message, Collection $history): array
    {
        $messages = [
            [
                'role' => 'system',
                'content' => $this->systemInstruction($message),
            ],
        ];

        foreach ($history->take(-12) as $historyMessage) {
            $messages[] = [
                'role' => $historyMessage->role === 'assistant' ? 'assistant' : 'user',
                'content' => $historyMessage->content,
            ];
        }

        return $messages;
    }

    private function systemInstruction(string $message): string
    {
        return implode("\n\n", array_filter([
            'Ты AI-Наставник LanGrade, дружелюбный помощник русскоязычного сайта для изучения английского.',
            'Всегда отвечай на русском, если пользователь явно не попросил другой язык. Английский используй только там, где он нужен по смыслу: для слов, фраз, примеров предложений, упражнений и исправленных вариантов.',
            'Не переводи весь текст пользователя на английский автоматически. Переводи только тот фрагмент, слово, фразу или предложение, которые пользователь прямо попросил перевести. Если запрос неоднозначный, кратко уточни или переведи самый очевидный фрагмент.',
            'Формат ответа выбирай свободно под задачу. Если спрашивают перевод, дай перевод и, при пользе, 1-3 естественных варианта. Если просят предложения, составь предложения на английском и добавь русский перевод только если это помогает. Если спрашивают грамматику, объясни правило простыми словами и дай примеры. Если просят проверить текст, исправь ошибки и коротко объясни главное.',
            'Не навязывай один шаблон вида "слово - перевод - транскрипция". Транскрипцию, часть речи и подробный разбор добавляй только когда это действительно уместно или пользователь попросил.',
            'Помогай с переводами, грамматикой, правилами, исключениями, произношением, примерами предложений, разбором ошибок, подбором тренировок и навигацией по сайту.',
            'Маршруты сайта: /dictionary личный словарь, /collections подборки слов, /collections/all все слова, /tests тренировки, /lessons уроки, /profile профиль, /leaderboard рейтинг, /ai-tutor этот чат.',
            'Не выдумывай данные аккаунта пользователя. Если нужен точный результат теста, профиль или личная статистика, предложи открыть соответствующий раздел сайта.',
            $this->siteContext($message),
        ]));
    }

    private function siteContext(string $message): string
    {
        $tokens = collect(preg_split('/\s+/u', preg_replace('/[^\pL\pN\s]/u', ' ', Str::lower($message))))
            ->filter(fn ($token) => mb_strlen($token) > 2)
            ->take(8);

        $words = $tokens->isEmpty() ? '' : Word::visibleTo(auth()->user())
            ->where(function ($wordQuery) use ($tokens) {
                foreach ($tokens as $token) {
                    $wordQuery
                        ->orWhere('english', 'like', "%{$token}%")
                        ->orWhere('russian', 'like', "%{$token}%");
                }
            })
            ->limit(5)
            ->get()
            ->map(fn ($word) => "{$word->english} - {$word->russian}".($word->transcription ? " {$word->transcription}" : ''))
            ->implode('; ');

        $lessons = $tokens->isEmpty() ? '' : Lesson::query()
            ->where(function ($lessonQuery) use ($tokens) {
                foreach ($tokens as $token) {
                    $lessonQuery
                        ->orWhere('title', 'like', "%{$token}%")
                        ->orWhere('summary', 'like', "%{$token}%");
                }
            })
            ->limit(3)
            ->get()
            ->map(fn ($lesson) => "{$lesson->title} ({$lesson->level})")
            ->implode('; ');

        $examples = Sentence::inRandomOrder()
            ->limit(3)
            ->get()
            ->map(fn ($sentence) => "{$sentence->english} - {$sentence->russian}")
            ->implode('; ');

        return implode("\n", array_filter([
            'Контекст из базы сайта:',
            $words ? "Слова: {$words}" : null,
            $lessons ? "Уроки: {$lessons}" : null,
            $examples ? "Примеры предложений: {$examples}" : null,
        ]));
    }
}
