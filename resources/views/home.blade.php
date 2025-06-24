@extends('layouts.app')

@section('title', 'Главная')

@section('content')

<!-- Блок теста на знание английского -->
<section class="mb-16 bg-white p-8 rounded shadow-md">
    <h2 class="text-2xl font-semibold mb-4">Пройдите тест на знание английского языка</h2>
    <a href="{{ url('/initial-test') }}" class="inline-block bg-purple-600 text-white px-6 py-3 rounded hover:bg-purple-700 transition">
        Начать тест
    </a>
</section>

<!-- Блок подборок слов -->
<section class="mb-16">
    <h2 class="text-2xl font-semibold mb-6">Подборки слов</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($collections as $collection)
            <a href="{{ url('/collections/'.$collection->id) }}"
               class="flex items-center bg-white p-4 rounded shadow hover:shadow-lg transition">
               
                <!-- Левая часть: текст -->
                <div class="flex-1 pr-4">
                    <h3 class="font-semibold text-lg mb-1">{{ $collection->title }}</h3>
                    <p class="text-gray-600 text-sm">{{ $collection->description }}</p>
                </div>
                
                <!-- Правая часть: картинка -->
                <div class="w-24 h-24 flex-shrink-0">
                    <img src="{{ asset('images/collections/' . $collection->image) }}"
                         alt="{{ $collection->title }}"
                         class="w-full h-full object-cover rounded">
                </div>
            </a>
        @endforeach
    </div>
</section>


<!-- Кратко о сайте и английском -->
<section class="bg-white p-8 rounded shadow-md">
    <h2 class="text-2xl font-semibold mb-4">О LanGrade и изучении английского</h2>
    <p class="text-gray-700 leading-relaxed">
        LanGrade — это удобный сайт-тренажер для изучения английского языка. Здесь вы можете тренировать свой словарный запас,
        проходить тесты и создавать свои подборки слов. Изучение английского становится проще и эффективнее!
    </p>
</section>

@endsection
