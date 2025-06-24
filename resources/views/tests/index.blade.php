@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-12">
    <h1 class="text-3xl font-bold mb-6 text-center">Выбор теста</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <a href="{{ route('tests.compile-word') }}" class="p-6 bg-white shadow rounded-lg hover:shadow-xl transition">
            <h2 class="text-xl font-semibold mb-2">Тест на сборку слова</h2>
            <p class="text-gray-600">Соберите слово на английском из букв под русским переводом.</p>
        </a>

        <a href="{{ route('tests.translation') }}" class="p-6 bg-white shadow rounded-lg hover:shadow-xl transition">
            <h2 class="text-xl font-semibold mb-2">Тест на перевод</h2>
            <p class="text-gray-600">Проверьте себя: сможете ли вы перевести эти слова правильно?</p>
        </a>
    </div>

    {{-- Новая кнопка на 2 колонки --}}
    <div class="grid grid-cols-1 md:grid-cols-2">
        <a href="{{ route('tests.flashcards') }}" class="md:col-span-2 p-6 bg-white shadow rounded-lg hover:shadow-xl transition block">
            <h2 class="text-xl font-semibold mb-2">Карточки слов (учебный режим)</h2>
            <p class="text-gray-600">Учите слова по карточкам с изображениями — просто листайте и запоминайте!</p>
        </a>
    </div>
</div>
@endsection
