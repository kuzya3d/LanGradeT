@extends('layouts.app')

@section('title', 'Результат перевода')

@section('content')
<div class="max-w-2xl mx-auto mt-12 bg-white p-6 rounded shadow text-center">
    <h1 class="text-2xl font-bold mb-6">Результаты теста</h1>

    <p class="text-lg mb-4">Вы правильно перевели <strong>{{ $score }}</strong> из <strong>{{ $total }}</strong> слов.</p>

    <div class="text-left mt-6 space-y-4">
        @foreach($words as $word)
            @php
                $userAnswer = trim($answers[$word->id] ?? '');
                $isCorrect = mb_strtolower($userAnswer) === mb_strtolower($word->russian);
            @endphp

            <div class="p-4 rounded border {{ $isCorrect ? 'border-green-400 bg-green-50' : 'border-red-400 bg-red-50' }}">
                <p><strong>Английское слово:</strong> {{ $word->english }}</p>
                <p><strong>Ваш перевод:</strong> {{ $userAnswer }}</p>
                @if(!$isCorrect)
                    <p><strong>Правильный перевод:</strong> {{ $word->russian }}</p>
                @endif
            </div>
        @endforeach
    </div>

    <a href="{{ url('/tests') }}" class="inline-block mt-8 text-purple-600 hover:underline">Вернуться к тестам</a>
</div>
@endsection
