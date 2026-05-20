@extends('layouts.app')

@section('title', 'AI-Наставник')

@section('content')
@php use App\Support\AiMessageRenderer; @endphp
<style>
    .ai-message p { margin-top: .35rem; }
    .ai-message p:first-child { margin-top: 0; }
    .ai-message strong { font-weight: 800; }
    .ai-message a { color: #047857; font-weight: 700; text-decoration: underline; text-underline-offset: 3px; }
    .ai-message ul, .ai-message ol { margin-top: .5rem; padding-left: 1.25rem; }
    .ai-message ul { list-style: disc; }
    .ai-message ol { list-style: decimal; }
</style>
<div class="mx-auto max-w-4xl">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-3xl font-black">AI-Наставник</h1>
            <p class="mt-2 text-slate-600">Задавайте вопросы про английский: переводы, правила, примеры, произношение, упражнения и возможности LanGrade.</p>
        </div>

        @if($messages->isNotEmpty())
            <form method="POST" action="{{ route('ai.clear') }}">
                @csrf
                @method('DELETE')
                <button class="rounded border border-slate-300 px-4 py-2 text-sm font-bold text-slate-700 hover:border-red-300 hover:text-red-700">Очистить чат</button>
            </form>
        @endif
    </div>

    <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <div id="ai-messages" class="max-h-[520px] space-y-3 overflow-y-auto">
            @forelse($messages as $message)
                @if(($message->meta['engine'] ?? null) === 'pending')
                    <div class="px-2 py-1 text-sm italic text-slate-400" data-message-id="{{ $message->id }}" data-pending="true">Думаю...</div>
                @else
                    <div class="rounded p-4 {{ $message->role === 'user' ? 'bg-emerald-50' : 'bg-slate-50' }}" data-message-id="{{ $message->id }}">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm font-black">{{ $message->role === 'user' ? 'Вы' : 'AI-Наставник' }}</p>
                            @if(($message->meta['engine'] ?? null) === 'local-fallback')
                                <span class="rounded bg-amber-100 px-2 py-1 text-xs font-bold text-amber-800">локальный режим</span>
                            @endif
                        </div>
                        @if($message->role === 'assistant')
                            <div class="ai-message mt-1 leading-7">{!! AiMessageRenderer::html($message->content) !!}</div>
                        @else
                            <p class="mt-1 whitespace-pre-line leading-7">{{ $message->content }}</p>
                        @endif
                    </div>
                @endif
            @empty
                <div id="ai-empty" class="rounded bg-slate-50 p-4 text-slate-600">Спросите: "объясни Present Simple", "переведи reliable", "составь 5 предложений с travel", "проверь это предложение", "какой тест пройти после урока?"</div>
            @endforelse
        </div>

        <form id="ai-form" method="POST" action="{{ route('ai.ask') }}" class="mt-4 flex gap-2">
            @csrf
            <input id="ai-input" name="message" class="min-w-0 flex-1 rounded border border-slate-300 px-4 py-3" placeholder="Напишите вопрос..." autocomplete="off" required>
            <button id="ai-submit" class="rounded bg-emerald-600 px-5 py-3 font-black text-white">Отправить</button>
        </form>
    </section>
</div>

<script>
    (() => {
        const form = document.getElementById('ai-form');
        const input = document.getElementById('ai-input');
        const submit = document.getElementById('ai-submit');
        const messages = document.getElementById('ai-messages');
        const statusUrl = @js(url('/ai-tutor/messages'));
        const csrf = @js(csrf_token());

        const scrollToBottom = () => {
            messages.scrollTop = messages.scrollHeight;
        };

        const escapeHtml = (value) => value
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');

        const renderMarkdown = (message) => {
            if (message.role === 'assistant' && message.html) {
                return message.html;
            }

            return escapeHtml(message.content);
        };

        const bubble = (message) => {
            const isUser = message.role === 'user';
            const fallback = message.meta?.engine === 'local-fallback'
                ? '<span class="rounded bg-amber-100 px-2 py-1 text-xs font-bold text-amber-800">локальный режим</span>'
                : '';
            const contentClass = isUser ? 'whitespace-pre-line' : 'ai-message';

            return `
                <div class="rounded p-4 ${isUser ? 'bg-emerald-50' : 'bg-slate-50'}" data-message-id="${message.id}">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-sm font-black">${isUser ? 'Вы' : 'AI-Наставник'}</p>
                        ${fallback}
                    </div>
                    <div class="mt-1 ${contentClass} leading-7">${renderMarkdown(message)}</div>
                </div>
            `;
        };

        const thinking = (id) => `
            <div class="px-2 py-1 text-sm italic text-slate-400" data-message-id="${id}" data-pending="true">Думаю...</div>
        `;

        const poll = (id, attempt = 0) => {
            fetch(`${statusUrl}/${id}`, {
                headers: {
                    'Accept': 'application/json',
                },
            })
                .then((response) => response.ok ? response.json() : Promise.reject())
                .then((message) => {
                    const node = messages.querySelector(`[data-message-id="${id}"]`);

                    if (!node) {
                        return;
                    }

                    if (message.pending && attempt < 60) {
                        setTimeout(() => poll(id, attempt + 1), 1500);
                        return;
                    }

                    node.outerHTML = bubble(message);
                    scrollToBottom();
                })
                .catch(() => {
                    if (attempt < 10) {
                        setTimeout(() => poll(id, attempt + 1), 2000);
                    }
                });
        };

        form.addEventListener('submit', (event) => {
            event.preventDefault();

            const text = input.value.trim();

            if (!text) {
                return;
            }

            submit.disabled = true;
            submit.classList.add('opacity-60');

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify({ message: text }),
            })
                .then((response) => response.ok ? response.json() : Promise.reject())
                .then((payload) => {
                    document.getElementById('ai-empty')?.remove();
                    messages.insertAdjacentHTML('beforeend', bubble(payload.user));
                    messages.insertAdjacentHTML('beforeend', payload.assistant.pending ? thinking(payload.assistant.id) : bubble(payload.assistant));
                    input.value = '';
                    scrollToBottom();

                    if (payload.assistant.pending) {
                        poll(payload.assistant.id);
                    }
                })
                .catch(() => {
                    messages.insertAdjacentHTML('beforeend', '<div class="rounded bg-red-50 p-4 text-sm font-bold text-red-700">Не получилось отправить сообщение. Попробуйте ещё раз.</div>');
                    scrollToBottom();
                })
                .finally(() => {
                    submit.disabled = false;
                    submit.classList.remove('opacity-60');
                    input.focus();
                });
        });

        messages.querySelectorAll('[data-pending="true"]').forEach((node) => {
            poll(node.dataset.messageId);
        });

        scrollToBottom();
    })();
</script>
@endsection
