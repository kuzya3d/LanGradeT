@extends('layouts.app')

@section('title', 'Сборка слова')

@section('content')
<div class="mx-auto max-w-3xl" x-data="compileWordTest()" x-init="init()">
    <div class="mb-6">
        <h1 class="text-3xl font-black">Сборка слова</h1>
        <p class="mt-2 text-slate-600">Соберите английское слово по переводу. За результат начисляется XP.</p>
        <div class="mt-5 h-2 overflow-hidden rounded bg-slate-200">
            <div class="h-full rounded bg-emerald-600 transition-all duration-300" :style="`width: ${((index + 1) / words.length) * 100}%`"></div>
        </div>
    </div>

    <template x-if="current">
        <section class="rounded-lg border border-slate-200 bg-white p-6 text-center shadow-sm">
            <p class="text-sm font-bold text-emerald-700" x-text="`Вопрос ${index + 1} из ${words.length}`"></p>
            <h2 class="mt-3 text-3xl font-black" x-text="current.russian"></h2>
            <p class="mt-1 text-slate-500" x-text="current.transcription"></p>

            <div class="mt-6 min-h-16 rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4">
                <template x-for="(letter, i) in selected" :key="`${letter}-${i}`">
                    <button type="button" @click="remove(i)" class="m-1 rounded bg-emerald-100 px-4 py-2 text-lg font-black text-emerald-900" x-text="letter"></button>
                </template>
            </div>

            <div class="mt-6 flex flex-wrap justify-center gap-2">
                <template x-for="(letter, i) in letters" :key="`${letter}-${i}`">
                    <button type="button" @click="pick(i)" class="rounded border border-slate-300 bg-white px-4 py-3 text-lg font-black transition hover:-translate-y-0.5 hover:border-emerald-400" x-text="letter"></button>
                </template>
            </div>

            <p x-show="feedback" class="mt-5 font-bold" :class="ok ? 'text-emerald-700' : 'text-red-700'" x-text="feedback"></p>
            <button type="button" @click="check()" class="mt-6 rounded bg-emerald-600 px-6 py-3 font-black text-white">Проверить</button>
        </section>
    </template>

    <form x-ref="form" method="POST" action="{{ route('tests.submit-compile-word') }}" class="hidden" @keydown.enter.prevent>
        @csrf
        <input type="hidden" name="submission_token" value="{{ $submissionToken }}">
        <template x-for="(row, i) in results" :key="i">
            <div>
                <input type="hidden" :name="`answers[${i}]`" :value="row.answer">
                <input type="hidden" :name="`correct_words[${i}]`" :value="row.correct">
                <input type="hidden" :name="`translations[${i}]`" :value="row.translation">
                <input type="hidden" :name="`is_phrase[${i}]`" :value="row.is_phrase ? 1 : 0">
            </div>
        </template>
        <input type="hidden" name="include_phrases" value="{{ request()->has('include_phrases') ? (request()->boolean('include_phrases') ? 1 : 0) : (session('tests.include_phrases', false) ? 1 : 0) }}">
    </form>
</div>

<script>
    function compileWordTest() {
        return {
            words: @json($words),
            index: 0,
            current: null,
            letters: [],
            selected: [],
            results: [],
            feedback: '',
            ok: false,
            init() {
                this.current = this.words[0] || null;
                this.shuffle();
            },
            shuffle() {
                const extras = 'abcdefghijklmnopqrstuvwxyz'.split('').sort(() => Math.random() - 0.5).slice(0, Math.min(4, Math.max(2, this.current.english.length - 2)));
                this.letters = this.current.english.split('').concat(extras).sort(() => Math.random() - 0.5);
                this.selected = [];
                this.feedback = '';
            },
            pick(i) {
                this.selected.push(this.letters[i]);
                this.letters.splice(i, 1);
            },
            remove(i) {
                this.letters.push(this.selected[i]);
                this.selected.splice(i, 1);
            },
            check() {
                const answer = this.selected.join('');
                this.ok = answer.toLowerCase() === this.current.english.toLowerCase();
                this.feedback = this.ok ? 'Правильно!' : `Верный ответ: ${this.current.english}`;
                this.results.push({ answer, correct: this.current.english, translation: this.current.russian, is_phrase: this.current.part_of_speech === 'phrase' });
                setTimeout(() => this.next(), 650);
            },
            next() {
                this.index++;
                if (this.index >= this.words.length) {
                    this.$nextTick(() => this.$refs.form.submit());
                    return;
                }
                this.current = this.words[this.index];
                this.shuffle();
            }
        }
    }
</script>
@endsection
