@extends('layouts.app')

@section('title', 'Ввод перевода')

@section('content')
<div class="mx-auto max-w-3xl" x-data="{ step: 0, total: {{ $words->count() }} }">
    <div class="mb-6">
        <h1 class="text-3xl font-black">Ввод перевода</h1>
        <p class="mt-2 text-slate-600">Напишите русский перевод слова. После завершения получите XP и разбор.</p>
        <div class="mt-5 h-2 overflow-hidden rounded bg-slate-200">
            <div class="h-full rounded bg-emerald-600 transition-all duration-300" :style="`width: ${((step + 1) / total) * 100}%`"></div>
        </div>
    </div>

    <form action="{{ route('tests.translation.submit') }}" method="POST" @keydown.enter.prevent>
        @csrf
        <input type="hidden" name="submission_token" value="{{ $submissionToken }}">
        <input type="hidden" name="include_phrases" value="{{ request()->has('include_phrases') ? (request()->boolean('include_phrases') ? 1 : 0) : (session('tests.include_phrases', false) ? 1 : 0) }}">
        @foreach($words as $index => $word)
            <section x-show="step === {{ $index }}" x-transition class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-bold text-emerald-700">Вопрос {{ $index + 1 }} из {{ $words->count() }}</p>
                <h2 class="mt-3 text-3xl font-black">{{ $word->english }}</h2>
                <p class="mt-1 text-slate-500">{{ $word->transcription }} · {{ $word->part_of_speech }}</p>
                <input name="answers[{{ $word->id }}]" class="mt-6 w-full rounded-lg border border-slate-300 px-4 py-4 text-lg outline-none focus:border-emerald-500" placeholder="Ваш перевод">
                <div class="mt-6 flex justify-between">
                    <button type="button" x-show="step > 0" @click="step--" class="rounded border border-slate-300 px-5 py-3 font-bold">Назад</button>
                    <span x-show="step === 0"></span>
                    <button type="button" x-show="step < total - 1" @click="step++" class="rounded bg-slate-900 px-5 py-3 font-bold text-white">Дальше</button>
                    <button type="submit" x-show="step === total - 1" class="rounded bg-emerald-600 px-5 py-3 font-black text-white">Завершить</button>
                </div>
            </section>
        @endforeach
    </form>
</div>
@endsection
