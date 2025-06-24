@extends('layouts.app')

@section('title', 'Подборки слов')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Темы и подборки слов</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($collections as $collection)
            <a href="{{ route('collections.show', $collection) }}"
               class="block p-5 bg-white shadow rounded hover:shadow-lg transition duration-200">
                <h2 class="text-xl font-semibold text-purple-700 mb-1">{{ $collection->title }}</h2>
                <p class="text-sm text-gray-600">{{ $collection->description }}</p>
            </a>
        @empty
            <p>Нет доступных подборок.</p>
        @endforelse
    </div>
@endsection
