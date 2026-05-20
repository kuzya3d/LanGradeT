@extends('layouts.app')

@section('title', 'Флеш-карточки')

@section('content')
@php
    $cards = $words->values();
@endphp

<div class="mx-auto max-w-2xl">
    <div class="mb-6">
        <h1 class="text-3xl font-black">Флеш-карточки</h1>
        <p class="mt-2 text-slate-600">Учебный режим без XP для спокойного повторения слов.</p>
    </div>

    @if($cards->isEmpty())
        <section class="rounded-lg border border-dashed border-slate-300 bg-white p-8 text-center text-slate-500">
            Пока нет слов для карточек. Запустите сидеры или добавьте слова в базу.
        </section>
    @else
        <div x-data="{ index: 0, completed: false, cards: @js($cards) }" @keydown.enter.prevent>
            <template x-if="!completed">
                <section class="rounded-lg border border-slate-200 bg-white p-6 text-center shadow-sm">
                    <div class="mx-auto grid h-48 w-48 place-items-center rounded-full bg-gradient-to-br from-emerald-100 to-sky-100 text-7xl font-black text-emerald-800">
                        <span x-text="cards[index].english.charAt(0).toUpperCase()"></span>
                    </div>
                    <p class="mt-6 text-sm font-bold text-emerald-700" x-text="`${index + 1} / ${cards.length}`"></p>
                    <h2 class="mt-2 text-4xl font-black" x-text="cards[index].english"></h2>
                    <p class="mt-1 text-slate-500" x-text="cards[index].transcription || ''"></p>
                    <p class="mt-4 text-2xl font-bold" x-text="cards[index].russian"></p>
                    <p class="mt-4 rounded bg-slate-50 p-4 text-slate-600" x-text="cards[index].part_of_speech || ''"></p>
                    <div class="mt-6 flex justify-between">
                        <button type="button" @click="if (index > 0) index--" class="rounded border border-slate-300 px-5 py-3 font-bold" :disabled="index === 0">Назад</button>
                        <button type="button" @click="index < cards.length - 1 ? index++ : completed = true" class="rounded bg-emerald-600 px-5 py-3 font-black text-white" x-text="index === cards.length - 1 ? 'Завершить' : 'Дальше'"></button>
                    </div>
                </section>
            </template>

            <template x-if="completed">
                <section class="rounded-lg border border-slate-200 bg-white p-8 text-center shadow-sm">
                    <h2 class="text-2xl font-black">Карточки закончились</h2>
                    <a href="{{ route('tests.index') }}" class="mt-5 inline-block rounded bg-slate-900 px-5 py-3 font-bold text-white">К тестам</a>
                </section>
            </template>
        </div>
    @endif
</div>
@endsection
