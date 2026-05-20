<?php

namespace App\Console\Commands;

use App\Models\AiChatMessage;
use App\Services\GigaChatTutorService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AnswerAiTutorMessage extends Command
{
    protected $signature = 'ai:tutor-answer {message_id}';

    protected $description = 'Answer a pending AI mentor message in a separate process.';

    public function handle(GigaChatTutorService $gigaChat): int
    {
        $assistantMessage = AiChatMessage::findOrFail((int) $this->argument('message_id'));
        $session = $assistantMessage->session;

        if (! $session) {
            $this->error('AI chat session was not found.');

            return self::FAILURE;
        }

        Auth::loginUsingId($session->user_id);

        $userMessage = $session->messages()
            ->where('id', '<', $assistantMessage->id)
            ->where('role', 'user')
            ->latest('id')
            ->first();

        if (! $userMessage) {
            $assistantMessage->update([
                'content' => 'Не нашел вопрос для ответа. Попробуйте отправить сообщение ещё раз.',
                'meta' => ['engine' => 'local-fallback', 'error' => 'Missing user message'],
            ]);

            return self::FAILURE;
        }

        $history = $session->messages()
            ->where('id', '<', $assistantMessage->id)
            ->oldest()
            ->get();

        try {
            $answer = $gigaChat->answer($userMessage->content, $history);
            $meta = ['engine' => 'gigachat'];
        } catch (Throwable $exception) {
            $answer = 'GigaChat сейчас не ответил. Сайт не завис: запрос обработан в отдельном процессе. Попробуйте ещё раз позже или проверьте ключ/доступ к API.';
            $meta = [
                'engine' => 'local-fallback',
                'error' => $exception->getMessage(),
            ];
        }

        $assistantMessage->update([
            'content' => $answer,
            'meta' => $meta,
        ]);

        return self::SUCCESS;
    }
}
