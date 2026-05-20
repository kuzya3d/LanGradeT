@extends('layouts.app')

@section('title', $collection->title)

@section('content')
<div class="mb-8 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="text-3xl font-black">{{ $collection->title }}</h1>
        <p class="mt-2 text-slate-600">{{ $collection->description }}</p>
    </div>
    <div class="flex flex-wrap gap-2">
        @auth
            <form method="POST" action="{{ route('collections.favorite', $collection) }}">
                @csrf
                <button class="rounded border border-slate-300 bg-white px-4 py-3 font-bold shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:text-emerald-700 hover:shadow-md">В избранное</button>
            </form>
        @endauth
        <a href="{{ route('tests.multiple-choice', ['collection_id' => $collection->id]) }}" class="rounded bg-emerald-600 px-4 py-3 font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-700 hover:shadow-md">Тренировать</a>
    </div>
</div>

<div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
    @foreach($collection->words as $word)
        <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-emerald-300 hover:shadow-md">
            <h2 class="text-xl font-black">{{ $word->english }}</h2>
            <p class="text-slate-600">{{ $word->transcription }} · {{ $word->part_of_speech }}</p>
            <p class="mt-2 font-bold">{{ $word->russian }}</p>
            @auth
                <form method="POST" action="{{ route('words.add', $word->id) }}" class="mt-4">
                    @csrf
                    <button class="rounded border border-slate-300 bg-white px-3 py-2 text-sm font-bold shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:text-emerald-700 hover:shadow-md">В личный словарь</button>
                </form>
            @endauth
        </article>
    @endforeach
</div>
@endsection
