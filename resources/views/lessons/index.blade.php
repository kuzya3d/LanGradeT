@extends('layouts.app')

@section('title', 'Уроки')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-black">Уроки и памятки</h1>
    <p class="mt-2 text-slate-600">Короткая теория для старта: фонетика, грамматика и первые диалоги.</p>
</div>

@foreach($lessons as $type => $items)
    <section class="mb-8">
        <h2 class="mb-3 text-xl font-black capitalize">{{ $type }}</h2>
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach($items as $lesson)
                <a href="{{ route('lessons.show', $lesson) }}" class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <span class="text-sm font-bold text-emerald-700">{{ $lesson->level }}</span>
                    <h3 class="mt-2 text-lg font-black">{{ $lesson->title }}</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $lesson->summary }}</p>
                </a>
            @endforeach
        </div>
    </section>
@endforeach
@endsection
