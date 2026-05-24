@extends('layouts.app')

@section('title', 'Профиль')

@section('content')
@php $avatars = ['🙂','😎','🤓','🧠','📚','⭐','🔥','🎯','🌿','💬']; @endphp
<div class="grid gap-6 lg:grid-cols-[320px_1fr]">
    <aside class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <div class="grid h-20 w-20 place-items-center rounded bg-emerald-600 text-4xl text-white">{{ $user->avatar ?: '🙂' }}</div>
        <h1 class="mt-4 text-2xl font-black">{{ $user->name }}</h1>
        <p class="text-slate-600">{{ $user->email }}</p>
        <p class="mt-3 rounded bg-orange-50 px-3 py-2 text-sm font-bold text-orange-800">🔥 {{ $user->streak_days }} {{ trans_choice('день|дня|дней', $user->streak_days) }} подряд</p>

        <form method="POST" action="{{ route('profile.avatar') }}" class="mt-5">
            @csrf
            <p class="mb-2 text-sm font-bold">Аватар</p>
            <div class="flex flex-wrap gap-2">
                @foreach($avatars as $avatar)
                    <button name="avatar" value="{{ $avatar }}" class="grid h-10 w-10 place-items-center rounded border text-xl {{ $user->avatar === $avatar ? 'border-emerald-600 bg-emerald-50' : 'border-slate-200' }}">{{ $avatar }}</button>
                @endforeach
            </div>
        </form>

        <form method="POST" action="{{ route('profile.bio') }}" class="mt-5">
            @csrf
            <label class="text-sm font-bold" for="bio">Био</label>
            <textarea id="bio" name="bio" maxlength="500" rows="4" class="mt-2 w-full rounded border border-slate-300 px-3 py-2 text-sm" placeholder="Пара слов о себе и целях в английском">{{ old('bio', $user->bio) }}</textarea>
            <button class="mt-2 w-full rounded bg-slate-900 px-4 py-2 text-sm font-bold text-white">Сохранить био</button>
        </form>

        <div class="mt-5 grid grid-cols-2 gap-3">
            <div class="rounded bg-slate-50 p-3"><b>{{ $user->english_level }}</b><p class="text-xs text-slate-500">уровень</p></div>
            <div class="rounded bg-slate-50 p-3"><b>{{ $user->xp }}</b><p class="text-xs text-slate-500">XP</p></div>
            <div class="rounded bg-slate-50 p-3"><b>{{ $userWords->count() }}</b><p class="text-xs text-slate-500">слов</p></div>
            <div class="rounded bg-slate-50 p-3"><b>{{ $passedPercent }}%</b><p class="text-xs text-slate-500">средний балл</p></div>
        </div>

        <div class="mt-6">
            <h2 class="mb-3 font-black">Друзья</h2>
            <div class="space-y-2">
                @forelse($user->friends as $friend)
                    <div class="flex items-center justify-between gap-2 rounded bg-slate-50 p-2">
                        <a href="{{ route('users.show', $friend) }}" class="text-sm font-bold">{{ $friend->avatar ?: '🙂' }} {{ $friend->name }}</a>
                        <form method="POST" action="{{ route('community.remove-friend', $friend) }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-xs font-bold text-red-600">Удалить</button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Пока нет друзей.</p>
                @endforelse
            </div>
        </div>
    </aside>

    <section class="space-y-6">
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-black">Статистика тестов</h2>
            <div class="mt-4 grid gap-3 md:grid-cols-3">
                <div class="rounded bg-slate-50 p-4"><b>{{ $totalAttempts }}</b><p class="text-sm text-slate-600">попыток всего</p></div>
                <div class="rounded bg-slate-50 p-4"><b>{{ $averageAttemptScore }}%</b><p class="text-sm text-slate-600">средний результат</p></div>
                <div class="rounded bg-slate-50 p-4"><b>{{ $bestAttemptScore }}%</b><p class="text-sm text-slate-600">лучший результат</p></div>
                <div class="rounded bg-slate-50 p-4"><b>{{ $accuracyPercent }}%</b><p class="text-sm text-slate-600">точность · {{ $totalCorrect }}/{{ $totalAnswered }}</p></div>
                <div class="rounded bg-slate-50 p-4"><b>{{ $totalXpDelta }}</b><p class="text-sm text-slate-600">XP за тесты</p></div>
                <div class="rounded bg-slate-50 p-4">
                    <b>{{ $bestMode['title'] ?? 'Нет данных' }}</b>
                    <p class="text-sm text-slate-600">
                        @if($bestMode)
                            лучший режим · {{ $bestMode['average'] }}%
                        @else
                            пройдите первый тест
                        @endif
                    </p>
                </div>
            </div>

            @if($testModeStats->isNotEmpty())
                <div class="mt-5 overflow-hidden rounded border border-slate-200">
                    <div class="grid grid-cols-[1.5fr_0.7fr_0.7fr_0.7fr] bg-slate-100 px-4 py-2 text-xs font-black uppercase text-slate-500 md:grid-cols-[1.8fr_0.7fr_0.7fr_0.7fr_0.9fr]">
                        <span>Режим</span>
                        <span>Попытки</span>
                        <span>Средний</span>
                        <span>Лучший</span>
                        <span class="hidden md:block">Последний</span>
                    </div>
                    @foreach($testModeStats as $modeStat)
                        <div class="grid grid-cols-[1.5fr_0.7fr_0.7fr_0.7fr] items-center border-t border-slate-200 px-4 py-3 text-sm md:grid-cols-[1.8fr_0.7fr_0.7fr_0.7fr_0.9fr]">
                            <b>{{ $modeStat['title'] }}</b>
                            <span>{{ $modeStat['count'] }}</span>
                            <span>{{ $modeStat['average'] }}%</span>
                            <span>{{ $modeStat['best'] }}%</span>
                            <span class="hidden text-slate-500 md:block">{{ $modeStat['last_at'] }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="mt-4 text-slate-500">Статистика появится после первой тренировки.</p>
            @endif
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-xl font-black">Достижения</h2>
                <span class="rounded bg-amber-100 px-3 py-1 text-sm font-black text-amber-900">{{ $achievements->count() }} из {{ $achievementsTotal }}</span>
            </div>
            <div class="mt-4 grid gap-3 md:grid-cols-2">
                @forelse($achievements as $achievement)
                    <div class="rounded border border-amber-200 bg-amber-50 p-4">
                        <b>{{ $achievement->display_icon }} {{ $achievement->title }}</b>
                        <p class="text-sm text-amber-800">{{ $achievement->description }}</p>
                    </div>
                @empty
                    <p class="text-slate-500">Достижения появятся после тестов и пополнения словаря.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-black">Последние тренировки</h2>
            <div class="mt-4 space-y-2">
                @forelse($attempts as $attempt)
                    <div class="flex flex-wrap items-center justify-between gap-3 rounded bg-slate-50 px-4 py-3">
                        <div>
                            <span class="font-bold">{{ $attempt->type->title ?? 'Тест' }}</span>
                            <p class="text-xs text-slate-500">{{ $attempt->correct_answers }}/{{ $attempt->total_questions }} · {{ $attempt->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                        <b>{{ $attempt->score }}%</b>
                    </div>
                @empty
                    <p class="text-slate-500">Пока нет новых тренировок.</p>
                @endforelse
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-black">Избранные уроки</h2>
                <div class="mt-4 space-y-2">
                    @forelse($user->favoriteLessons as $lesson)
                        <a href="{{ route('lessons.show', $lesson) }}" class="block rounded bg-slate-50 px-4 py-3 font-bold">{{ $lesson->title }}</a>
                    @empty
                        <p class="text-slate-500">Добавляйте полезные уроки в избранное.</p>
                    @endforelse
                </div>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-black">Избранные наборы</h2>
                <div class="mt-4 space-y-2">
                    @forelse($user->favoriteCollections as $collection)
                        <a href="{{ route('collections.show', $collection) }}" class="block rounded bg-slate-50 px-4 py-3 font-bold">{{ $collection->title }}</a>
                    @empty
                        <p class="text-slate-500">Сохраняйте подборки для быстрого повторения.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
