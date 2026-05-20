@extends('layouts.app')

@section('title', 'Результат')

@section('content')
@php $xpDelta = $attempt->payload['xp_delta'] ?? $attempt->xp_earned; @endphp
@php $practiceOnly = $practiceOnly ?? false; @endphp
<div class="mx-auto max-w-4xl">
    <section class="rounded-lg border border-slate-200 bg-white p-8 text-center shadow-sm">
        <p class="text-sm font-bold uppercase tracking-wide text-emerald-700">{{ $attempt->type->title ?? 'Тест' }}</p>
        <h1 class="mt-2 text-5xl font-black">{{ $attempt->score }}%</h1>
        <p class="mt-3 text-slate-600">
            Верно: {{ $attempt->correct_answers }} из {{ $attempt->total_questions }} ·
            @if($practiceOnly)
                XP не начислялись и не снимались
            @else
                {{ $xpDelta >= 0 ? 'получено' : 'снято' }} {{ abs($xpDelta) }} XP
            @endif
        </p>

        @if($practiceOnly)
            <p class="mx-auto mt-4 max-w-xl rounded bg-slate-50 px-4 py-3 text-sm font-bold text-slate-600">
                Это закрепление личного словаря, поэтому рейтинг и XP не меняются.
            </p>
        @endif

        @if($earned->count())
            <div class="mx-auto mt-6 max-w-xl rounded bg-amber-50 p-4 text-left">
                <h2 class="font-black text-amber-900">Новые достижения</h2>
                @foreach($earned as $achievement)
                    <p class="mt-1 text-amber-800">{{ $achievement->icon }} {{ $achievement->title }}: {{ $achievement->description }}</p>
                @endforeach
            </div>
        @endif
    </section>

    <section class="mt-6 rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-xl font-black">Разбор ответов</h2>
        <div class="mt-4 space-y-3">
            @foreach($attempt->answers as $answer)
                <article class="rounded-lg border p-4 {{ $answer->is_correct ? 'border-emerald-200 bg-emerald-50' : 'border-red-200 bg-red-50' }}">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-bold {{ $answer->is_correct ? 'text-emerald-800' : 'text-red-800' }}">
                                {{ $answer->is_correct ? 'Правильно' : 'Нужно повторить' }}
                            </p>
                            <h3 class="mt-1 font-black">{{ $answer->question }}</h3>
                        </div>
                        <span class="rounded bg-white px-3 py-1 text-sm font-bold">{{ $answer->is_correct ? '+1' : '0' }}</span>
                    </div>
                    <div class="mt-3 grid gap-2 text-sm md:grid-cols-2">
                        <p><b>Ваш ответ:</b> {{ $answer->user_answer ?: 'нет ответа' }}</p>
                        <p><b>Правильно:</b> {{ $answer->correct_answer ?: 'ответ не найден' }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <div class="mt-7 flex justify-center gap-3">
        <a href="{{ route('tests.index') }}" class="rounded border border-slate-300 px-5 py-3 font-bold">К тестам</a>
        <a href="{{ route('leaderboard.index') }}" class="rounded bg-slate-900 px-5 py-3 font-bold text-white">Рейтинг</a>
    </div>
</div>
@endsection
