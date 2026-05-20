@extends('layouts.app')

@section('title', 'Все слова')

@section('content')
<div class="mb-8 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="text-3xl font-black">Все слова</h1>
        <p class="mt-2 text-slate-600">Полный список слов и фраз. Здесь все, что поможет пополнить свой словарный запас.</p>
    </div>
    <a href="{{ route('tests.multiple-choice') }}" class="rounded bg-emerald-600 px-4 py-3 font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-700 hover:shadow-md">Тренировать</a>
</div>

<div class="grid gap-3 md:grid-cols-2 lg:grid-cols-4">
    @foreach($words as $word)
        <article class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:border-emerald-300 hover:shadow-md">
            <h2 class="font-black">{{ $word->english }}</h2>
            <p class="text-sm text-slate-500">{{ $word->transcription }} · {{ $word->part_of_speech }}</p>
            <p class="mt-2 font-bold">{{ $word->russian }}</p>
            @auth
                <form method="POST" action="{{ route('words.add', $word->id) }}" class="mt-3">
                    @csrf
                    <button class="rounded border border-slate-300 bg-white px-3 py-2 text-sm font-bold text-emerald-700 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md">В словарь</button>
                </form>
            @endauth
        </article>
    @endforeach
</div>

<div class="mt-6">
    {{ $words->links() }}
</div>
@endsection
