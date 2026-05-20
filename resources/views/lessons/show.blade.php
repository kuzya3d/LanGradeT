@extends('layouts.app')

@section('title', $lesson->title)

@section('content')
@php
    $testLinks = [
        'phonetics-first-sounds' => route('tests.phonetics'),
        'present-simple' => route('tests.gap-fill'),
        'word-order' => route('tests.sentence-builder'),
        'questions-basic' => route('tests.sentence-builder'),
        'to-be' => route('tests.gap-fill'),
        'articles-a-an-the' => route('tests.gap-fill'),
        'speaking-about-yourself' => route('tests.sentence-builder'),
    ];
@endphp
<article class="mx-auto max-w-4xl rounded-lg border border-slate-200 bg-white p-8 shadow-sm">
    <p class="text-sm font-bold text-emerald-700">{{ $lesson->level }} · {{ $lesson->type }}</p>
    <h1 class="mt-2 text-4xl font-black">{{ $lesson->title }}</h1>
    <p class="mt-3 text-lg leading-8 text-slate-600">{{ $lesson->summary }}</p>

    <div class="prose-like mt-8 space-y-6 leading-8 text-slate-700">
        {!! str_contains($lesson->content, '<') ? $lesson->content : nl2br(e($lesson->content)) !!}
    </div>

    <div class="mt-8 flex flex-wrap gap-3">
        <a href="{{ route('lessons.index') }}" class="rounded border border-slate-300 px-4 py-2 font-bold">Все уроки</a>
        @auth
            <form method="POST" action="{{ route('lessons.favorite', $lesson) }}">
                @csrf
                <button class="rounded border border-slate-300 px-4 py-2 font-bold">В избранное</button>
            </form>
        @endauth
        @if(isset($testLinks[$lesson->slug]))
            <a href="{{ $testLinks[$lesson->slug] }}" class="rounded bg-emerald-600 px-4 py-2 font-bold text-white">Закрепить тестом</a>
        @endif
    </div>
</article>

<style>
    .prose-like h2 { margin-top: 1.8rem; font-size: 1.35rem; font-weight: 900; color: #0f172a; }
    .prose-like h3 { margin-top: 1.2rem; font-size: 1.05rem; font-weight: 900; color: #0f172a; }
    .prose-like ul { list-style: disc; padding-left: 1.5rem; }
    .prose-like table { width: 100%; border-collapse: collapse; overflow: hidden; border-radius: 8px; }
    .prose-like th, .prose-like td { border: 1px solid #e2e8f0; padding: .75rem; text-align: left; }
    .prose-like th { background: #f8fafc; color: #0f172a; }
    .prose-like .note { border-left: 4px solid #059669; background: #ecfdf5; padding: 1rem; border-radius: 6px; }
</style>
@endsection
