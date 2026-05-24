@extends('layouts.app')

@section('title', 'Тесты')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-black">Тренировки</h1>
    <p class="mt-2 text-slate-600">Короткие тесты дают XP, пополняют статистику и открывают достижения.</p>
</div>

@php
    $includePhrases = $includePhrases ?? false;
    $testQuery = ['include_phrases' => $includePhrases ? 1 : 0];
    $tests = [
        ['route' => 'tests.multiple-choice', 'title' => 'Быстрый выбор перевода', 'text' => 'Классика как в популярных языковых тренажерах: слово и 4 варианта перевода.'],
        ['route' => 'tests.gap-fill', 'title' => 'Пропущенное слово', 'text' => 'Впишите слово в контекст предложения. Хорошо тренирует лексику и грамматику.'],
        ['route' => 'tests.sentence-builder', 'title' => 'Собери предложение', 'text' => 'Порядок слов в английском без скучной теории.'],
        ['route' => 'tests.phonetics', 'title' => 'Фонетика', 'text' => 'Выберите слово по транскрипции и привыкните к чтению звуков.'],
        ['route' => 'tests.word-sprint', 'title' => 'Словарный спринт', 'text' => 'Быстро определяйте верные и неверные пары слово-перевод.'],
        ['route' => 'tests.compile-word', 'title' => 'Сборка слова', 'text' => 'Соберите английское слово из перемешанных букв и закрепите написание.'],
        ['route' => 'tests.translation', 'title' => 'Ввод перевода', 'text' => 'Введите перевод вручную: режим хорошо проверяет активное вспоминание слов.'],
        ['route' => 'tests.flashcards', 'title' => 'Флеш-карточки', 'text' => 'Учебный режим для спокойного повторения слов.'],
    ];
@endphp

<section class="mb-6 rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="font-black">Фразы в тренировках</h2>
            <p class="mt-1 text-sm text-slate-600">Можно убрать phrase из словарных тестов. С фразами за правильные ответы начисляется немного больше XP.</p>
        </div>
        <div class="flex rounded border border-slate-200 bg-slate-50 p-1">
            <a href="{{ route('tests.index', ['include_phrases' => 1]) }}" class="rounded px-4 py-2 text-sm font-bold {{ $includePhrases ? 'bg-emerald-600 text-white' : 'text-slate-700' }}">Включены</a>
            <a href="{{ route('tests.index', ['include_phrases' => 0]) }}" class="rounded px-4 py-2 text-sm font-bold {{ ! $includePhrases ? 'bg-emerald-600 text-white' : 'text-slate-700' }}">Без фраз</a>
        </div>
    </div>
</section>

<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
    @foreach($tests as $test)
        <a href="{{ route($test['route'], in_array($test['route'], ['tests.gap-fill', 'tests.sentence-builder'], true) ? [] : $testQuery) }}" class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-emerald-300 hover:shadow-md">
            <h2 class="text-lg font-black">{{ $test['title'] }}</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">{{ $test['text'] }}</p>
        </a>
    @endforeach
</div>
@endsection
