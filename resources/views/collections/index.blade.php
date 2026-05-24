@extends('layouts.app')

@section('title', 'Подборки слов')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-black">Подборки слов</h1>
    <p class="mt-2 text-slate-600">Темы для набора начального словарного запаса.</p>
</div>

<div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
    <a href="{{ route('collections.show', 'all') }}" class="rounded-lg border-2 border-emerald-300 bg-emerald-50 p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
        <h2 class="text-xl font-black">Все слова</h2>
        <p class="mt-2 text-sm leading-6 text-emerald-900">Единый список всех слов из базы в одной коллекции.</p>
        <p class="mt-4 text-sm font-bold text-emerald-700">{{ $allWordsCount }} слов</p>
    </a>

    @foreach($collections as $collection)
        <a href="{{ route('collections.show', $collection) }}" class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
            <h2 class="text-xl font-black">{{ $collection->title }}</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">{{ $collection->description }}</p>
            <p class="mt-4 text-sm font-bold text-emerald-700">{{ $collection->words_count ?? $collection->words()->count() }} слов</p>
        </a>
    @endforeach
</div>
@endsection
