@extends('layouts.app')

@section('title', 'Личный словарь')

@section('content')
<div class="grid gap-6 lg:grid-cols-[360px_1fr]">
    <aside class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <h1 class="text-2xl font-black">Добавить слово</h1>
        <form method="POST" action="{{ route('dictionary.store') }}" class="mt-4 space-y-3">
            @csrf
            <input name="english" class="w-full rounded border border-slate-300 px-3 py-3" placeholder="English word" required>
            <input name="russian" class="w-full rounded border border-slate-300 px-3 py-3" placeholder="Перевод" required>
            <button class="w-full rounded bg-emerald-600 px-4 py-3 font-bold text-white">Добавить</button>
        </form>
    </aside>

    <section>
        <div class="mb-4 flex items-end justify-between">
            <div>
                <h2 class="text-2xl font-black">Мои слова</h2>
                <p class="text-slate-600">Всего: {{ $userWords->count() }}</p>
            </div>
            <a href="{{ route('tests.multiple-choice', ['source' => 'dictionary']) }}" class="rounded border border-slate-300 px-4 py-2 font-bold">Повторить слова</a>
        </div>
        <div class="grid gap-3 md:grid-cols-2">
            @forelse($userWords as $word)
                <article class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-black">{{ $word->english }} <span class="text-sm font-medium text-slate-500">{{ $word->transcription }}</span></h3>
                            <p class="text-slate-700">{{ $word->russian }}</p>
                            @if($word->part_of_speech)
                                <p class="mt-2 text-sm text-slate-500">{{ $word->part_of_speech }}</p>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('dictionary.destroy', $word) }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-sm font-bold text-red-600">Удалить</button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="rounded-lg border border-dashed border-slate-300 p-8 text-center text-slate-500">Словарь пока пуст.</div>
            @endforelse
        </div>
    </section>
</div>
@endsection
