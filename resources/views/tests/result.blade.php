@extends('layouts.app')

@section('title', 'Результат теста')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Результат первичного теста</h1>

    <p class="text-lg mb-4">Вы отметили <strong>{{ $count }}</strong> известных слов.</p>
    <p class="text-lg">Ваш предполагаемый уровень: <span class="font-semibold text-purple-600">{{ $level }}</span></p>

    <a href="{{ url('/') }}" class="mt-6 inline-block text-purple-600 hover:underline">Вернуться на главную</a>
@endsection
