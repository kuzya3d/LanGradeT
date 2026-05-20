@extends('layouts.app')

@section('title', 'Чат')

@section('content')
<div class="grid gap-6 lg:grid-cols-[380px_1fr]">
    <aside class="space-y-6">
        <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <h1 class="text-2xl font-black">Новый диалог</h1>
            <p class="mt-1 text-sm text-slate-500">Писать можно только друзьям.</p>
            <form method="POST" action="{{ route('community.start') }}" class="mt-4 space-y-3">
                @csrf
                <select name="user_id" class="w-full rounded border border-slate-300 px-3 py-3" required>
                    <option value="">Выберите друга</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->avatar ?: '🙂' }} {{ $user->name }} · {{ $user->english_level }}</option>
                    @endforeach
                </select>
                <input name="subject" class="w-full rounded border border-slate-300 px-3 py-3" placeholder="Тема">
                <textarea name="body" rows="5" class="w-full rounded border border-slate-300 px-3 py-3" placeholder="Напишите сообщение" required></textarea>
                <button class="w-full rounded bg-emerald-600 px-4 py-3 font-bold text-white">Отправить</button>
            </form>
        </section>

        <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="text-xl font-black">Найти друзей</h2>
            <div class="mt-4 space-y-3">
                @forelse($allUsers as $user)
                    <div class="flex items-center justify-between gap-3 rounded bg-slate-50 p-3">
                        <a href="{{ route('users.show', $user) }}" class="font-bold">{{ $user->avatar ?: '🙂' }} {{ $user->name }}</a>
                        <form method="POST" action="{{ route('community.add-friend', $user) }}">
                            @csrf
                            <button class="rounded border border-slate-300 px-3 py-1 text-sm font-bold">Добавить</button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Пока некого добавить.</p>
                @endforelse
            </div>
        </section>
    </aside>

    <section class="space-y-4" x-data="{ open: @js(session('open_conversation')) }">
        <h2 class="text-2xl font-black">Мои диалоги</h2>
        @forelse($conversations as $conversation)
            <article class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm" data-conversation-id="{{ $conversation->id }}">
                <button type="button" @click="open === {{ $conversation->id }} ? open = null : open = {{ $conversation->id }}" class="flex w-full items-center justify-between gap-4 p-5 text-left">
                    <span>
                        <span class="block font-black">{{ $conversation->subject }}</span>
                        <span class="text-sm text-slate-500"><span data-message-count="{{ $conversation->id }}">{{ $conversation->messages->count() }}</span> сообщений</span>
                    </span>
                    <span class="text-xl font-black text-emerald-700" x-text="open === {{ $conversation->id }} ? '−' : '+'"></span>
                </button>
                <div x-show="open === {{ $conversation->id }}" x-transition class="border-t border-slate-100 p-5">
                    <div class="space-y-3" data-messages="{{ $conversation->id }}">
                        @foreach($conversation->messages as $message)
                            <div class="rounded p-3 {{ $message->user_id === Auth::id() ? 'bg-emerald-50 ring-1 ring-emerald-100' : 'bg-slate-50' }}">
                                <a href="{{ route('users.show', $message->user) }}" class="text-sm font-bold">{{ $message->user->avatar ?: '🙂' }} {{ $message->user->name }}</a>
                                <p class="mt-1 text-slate-700">{{ $message->body }}</p>
                            </div>
                        @endforeach
                    </div>
                    <form method="POST" action="{{ route('community.reply', $conversation) }}" class="community-reply-form mt-4 flex gap-2" data-conversation-id="{{ $conversation->id }}" @keydown.enter.prevent>
                        @csrf
                        <input name="body" class="min-w-0 flex-1 rounded border border-slate-300 px-3 py-2" placeholder="Ответить" required>
                        <button class="rounded bg-slate-900 px-4 py-2 font-bold text-white">OK</button>
                    </form>
                    <form method="POST" action="{{ route('community.destroy', $conversation) }}" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button class="text-sm font-bold text-red-600">Удалить диалог для обоих</button>
                    </form>
                </div>
            </article>
        @empty
            <div class="rounded-lg border border-dashed border-slate-300 p-8 text-center text-slate-500">Диалогов пока нет.</div>
        @endforelse
    </section>
</div>

<script>
    (() => {
        const updatesUrl = @js(route('community.updates'));
        const csrf = @js(csrf_token());
        let latestSnapshot = new Map();

        const escapeHtml = (value) => String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');

        const renderMessages = (conversation) => conversation.messages.map((message) => `
            <div class="rounded p-3 ${message.is_mine ? 'bg-emerald-50 ring-1 ring-emerald-100' : 'bg-slate-50'}" data-message-id="${message.id}">
                <a href="${escapeHtml(message.user.url)}" class="text-sm font-bold">${escapeHtml(message.user.avatar)} ${escapeHtml(message.user.name)}</a>
                <p class="mt-1 text-slate-700">${escapeHtml(message.body)}</p>
            </div>
        `).join('');

        const applyConversation = (conversation) => {
            const article = document.querySelector(`[data-conversation-id="${conversation.id}"]`);

            if (!article) {
                window.location.reload();
                return;
            }

            const previous = latestSnapshot.get(conversation.id);
            const current = `${conversation.messages_count}:${conversation.updated_at}`;

            if (previous === current) {
                return;
            }

            latestSnapshot.set(conversation.id, current);

            const count = document.querySelector(`[data-message-count="${conversation.id}"]`);
            const list = document.querySelector(`[data-messages="${conversation.id}"]`);

            if (count) {
                count.textContent = conversation.messages_count;
            }

            if (list) {
                const wasNearBottom = list.scrollHeight - list.scrollTop - list.clientHeight < 80;
                list.innerHTML = renderMessages(conversation);

                if (wasNearBottom) {
                    list.scrollTop = list.scrollHeight;
                }
            }
        };

        const poll = () => {
            fetch(updatesUrl, { headers: { 'Accept': 'application/json' } })
                .then((response) => response.ok ? response.json() : Promise.reject())
                .then((payload) => payload.conversations.forEach(applyConversation))
                .catch(() => {});
        };

        document.querySelectorAll('.community-reply-form').forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();

                const input = form.querySelector('input[name="body"]');
                const button = form.querySelector('button');
                const body = input.value.trim();

                if (!body) {
                    return;
                }

                button.disabled = true;

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({ body }),
                })
                    .then((response) => response.ok ? response.json() : Promise.reject())
                    .then((payload) => {
                        input.value = '';
                        applyConversation(payload.conversation);
                    })
                    .catch(() => form.submit())
                    .finally(() => {
                        button.disabled = false;
                        input.focus();
                    });
            });
        });

        poll();
        setInterval(poll, 3000);
    })();
</script>
@endsection
