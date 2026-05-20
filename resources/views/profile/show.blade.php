@extends('layouts.app')

@section('title', $user->name)

@section('content')
<div class="mx-auto max-w-2xl rounded-lg border border-slate-200 bg-white p-8 text-center shadow-sm">
    <div class="mx-auto grid h-24 w-24 place-items-center rounded bg-emerald-600 text-5xl text-white">{{ $user->avatar ?: '🙂' }}</div>
    <h1 class="mt-4 text-3xl font-black">{{ $user->name }}</h1>
    <p class="mt-1 text-slate-600">{{ $user->english_level }} · {{ $user->xp }} XP</p>
    <p class="mt-3 inline-block rounded bg-orange-50 px-3 py-2 text-sm font-bold text-orange-800">🔥 {{ $user->streak_days }} {{ trans_choice('день|дня|дней', $user->streak_days) }} подряд</p>

    @if($user->bio)
        <p class="mt-5 rounded bg-slate-50 p-4 text-left leading-7 text-slate-700">{{ $user->bio }}</p>
    @endif

    <div class="mt-6 grid grid-cols-3 gap-3">
        <div class="rounded bg-slate-50 p-4"><b>{{ $user->words_count }}</b><p class="text-sm text-slate-500">слов</p></div>
        <div class="rounded bg-slate-50 p-4"><b>{{ $user->attempts_count }}</b><p class="text-sm text-slate-500">тестов</p></div>
        <div class="rounded bg-slate-50 p-4"><b>{{ $user->achievements_count }} / {{ $achievementsTotal }}</b><p class="text-sm text-slate-500">достижений</p></div>
    </div>

    @auth
        @if(auth()->id() !== $user->id && !$isFriend)
            <form method="POST" action="{{ route('community.add-friend', $user) }}" class="mt-6">
                @csrf
                <button class="rounded bg-emerald-600 px-5 py-3 font-black text-white">Добавить в друзья</button>
            </form>
        @elseif($isFriend)
            <p class="mt-6 rounded bg-emerald-50 px-4 py-3 font-bold text-emerald-800">Вы друзья, можно общаться в чате.</p>
        @endif
    @endauth
</div>
@endsection
