@extends('layouts.app')

@section('title', 'LanGrade')

@section('content')
<section class="grid gap-8 lg:grid-cols-[1.1fr_.9fr] lg:items-center">
    <div>
        <p class="mb-3 text-sm font-bold uppercase tracking-wide text-emerald-700">English A0-A2</p>
        <h1 class="max-w-3xl text-4xl font-black leading-tight sm:text-7xl">Учите английский через слова, тесты и живую практику.</h1>
        <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-600">LanGrade соединяет личный словарь, фонетику, грамматику, интерактивные тренировки, достижения, внутренний чат и AI-наставника для ежедневного обучения.</p>
        <div class="mt-7 flex flex-wrap gap-3">
            <a href="{{ route('initial-test.show') }}" class="rounded bg-emerald-600 px-5 py-3 font-bold text-white">Пройти начальный тест</a>
            <a href="{{ route('tests.index') }}" class="rounded border border-slate-300 px-5 py-3 font-bold">Открыть тренировки</a>
        </div>
    </div>
    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <div class="grid gap-3 sm:grid-cols-2">
            <div class="rounded bg-slate-50 p-4"><b>Более 10</b><p class="text-sm text-slate-600">режимов практики</p></div>
            <div class="rounded bg-slate-50 p-4"><b>Несколько тысяч</b><p class="text-sm text-slate-600">слов и форм для изучения</p></div>
            <div class="rounded bg-slate-50 p-4"><b>XP</b><p class="text-sm text-slate-600">рейтинг и достижения</p></div>
            <div class="rounded bg-slate-50 p-4"><b>AI-Наставник</b><p class="text-sm text-slate-600">учебный помощник на базе GigaChat</p></div>
        </div>
    </div>
</section>

<section class="mt-12">
    <div class="mb-5 flex items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black">Подборки слов</h2>
            <p class="text-slate-600">Каждый набор содержит тематические слова и примеры для тренировок.</p>
        </div>
        <a href="{{ route('collections.index') }}" class="text-sm font-bold text-emerald-700">Все подборки</a>
    </div>
    <div class="grid gap-4 md:grid-cols-3">
        @foreach($collections as $collection)
            <a href="{{ route('collections.show', $collection) }}" class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <h3 class="font-bold">{{ $collection->title }}</h3>
                <p class="mt-1 text-sm text-slate-600">{{ $collection->description }}</p>
                <p class="mt-3 text-xs font-bold text-emerald-700">{{ $collection->words_count ?? $collection->words()->count() }} слов</p>
            </a>
        @endforeach
    </div>
</section>
@endsection
