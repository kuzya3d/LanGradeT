@extends('layouts.app')

@section('title', 'Результат начального теста')

@section('content')
<div class="mx-auto max-w-3xl rounded-lg border border-slate-200 bg-white p-8 text-center shadow-sm">
    <p class="text-sm font-bold uppercase tracking-wide text-emerald-700">Ваш уровень</p>
    <h1 class="mt-3 text-6xl font-black">{{ $level }}</h1>
    <p class="mt-4 text-slate-600">Правильных ответов: {{ $correct }} из {{ $total }} · итоговая оценка: {{ $score }}%</p>

    @if($timedOut ?? false)
        <p class="mt-3 rounded bg-red-50 px-4 py-3 text-sm font-bold text-red-700">
            Время вышло. Вступительный тест автоматически засчитан как минимальный уровень.
        </p>
    @endif

    @if($placementLevel !== $level)
        <p class="mt-3 rounded bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-800">
            По тесту: {{ $placementLevel }}. Итоговый уровень выше с учётом накопленного XP.
        </p>
    @endif

    <div class="mt-6 grid gap-3 sm:grid-cols-4">
        @foreach($sectionStats as $section => $stats)
            <div class="rounded bg-slate-50 p-3">
                <b>{{ (int) round(($stats['correct'] / max($stats['total'], 1)) * 100) }}%</b>
                <p class="text-xs text-slate-500">{{ ['vocabulary' => 'слова', 'phrases' => 'фразы', 'grammar' => 'грамматика', 'sentences' => 'предложения'][$section] ?? $section }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 rounded bg-slate-50 p-5 text-left leading-7 text-slate-700">
        {{ $recommendation }}
    </div>

    <div class="mt-7 flex justify-center gap-3">
        <a href="{{ route('lessons.index') }}" class="rounded bg-emerald-600 px-5 py-3 font-black text-white">К урокам</a>
        <a href="{{ route('tests.index') }}" class="rounded border border-slate-300 px-5 py-3 font-bold">К тестам</a>
    </div>
</div>
@endsection
