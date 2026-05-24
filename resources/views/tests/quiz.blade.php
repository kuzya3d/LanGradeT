@extends('layouts.app')

@section('title', $title)

@section('content')
@php $variant = $variant ?? request('variant', 'typing'); @endphp
<div class="mx-auto max-w-3xl" x-data="quizShell('{{ $mode }}', {{ $questions->count() }}, '{{ $variant }}')" x-init="initTimer()">
    <div class="mb-6">
        <h1 class="text-3xl font-black">{{ $title }}</h1>
        <p class="mt-2 text-slate-600">{{ $subtitle }}</p>

        @if($mode === 'sentence-builder')
            <div class="mt-4 flex flex-wrap gap-2">
                <button type="button" @click="activeVariant = 'typing'" class="rounded px-4 py-2 font-bold" :class="activeVariant === 'typing' ? 'bg-emerald-600 text-white' : 'border border-slate-300'">Печатать</button>
                <button type="button" @click="activeVariant = 'tiles'" class="rounded px-4 py-2 font-bold" :class="activeVariant === 'tiles' ? 'bg-emerald-600 text-white' : 'border border-slate-300'">Собирать плитками</button>
            </div>
        @endif

        @if($mode === 'multiple-choice')
            <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 font-bold text-amber-900">
                Таймер: <span x-text="timeLeft"></span> сек. Когда время закончится, сохранится текущий результат.
            </div>
        @endif

        <div class="mt-5 h-2 overflow-hidden rounded bg-slate-200">
            <div class="h-full rounded bg-emerald-600 transition-all duration-300" :style="`width: ${((step + 1) / total) * 100}%`"></div>
        </div>
    </div>

    @if($questions->isEmpty())
        <div class="rounded-lg border border-dashed border-slate-300 bg-white p-8 text-center text-slate-500">Для этого режима пока не хватает заданий.</div>
    @else
        <form x-ref="form" method="POST" action="{{ route('tests.modern-submit', $mode) }}" @keydown.enter.prevent>
            @csrf
            <input type="hidden" name="submission_token" value="{{ $submissionToken }}">
            <input type="hidden" name="variant" x-model="activeVariant">
            <input type="hidden" name="source" value="{{ $source ?? request('source') }}">
            <input type="hidden" name="include_phrases" value="{{ in_array($mode, ['gap-fill', 'sentence-builder'], true) ? 0 : (($includePhrases ?? (request()->has('include_phrases') ? request()->boolean('include_phrases') : session('tests.include_phrases', false))) ? 1 : 0) }}">
            @foreach($questions as $index => $question)
                <section data-question-index="{{ $index }}" x-show="step === {{ $index }}" x-transition class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm"
                    @if($mode === 'sentence-builder')
                        x-data="{ bank: @js($question['tiles'] ?? []), picked: [], answer: '' }"
                    @endif
                >
                    <p class="text-sm font-bold text-emerald-700">Вопрос {{ $index + 1 }} из {{ $questions->count() }}</p>
                    <h2 class="mt-3 text-2xl font-black">{{ $question['question'] }}</h2>
                    @if($question['hint'])
                        @if($mode === 'sentence-builder')
                            <p class="mt-2 text-slate-500" x-text="activeVariant === 'tiles' ? 'Соберите английский вариант из слов ниже.' : @js($question['hint'])"></p>
                        @else
                            <p class="mt-2 text-slate-500">{{ $question['hint'] }}</p>
                        @endif
                    @endif

                    <input type="hidden" name="correct[{{ $index }}]" value="{{ $question['correct'] }}">
                    <input type="hidden" name="questions[{{ $index }}]" value="{{ $question['question'] }}">
                    <input type="hidden" name="is_phrase[{{ $index }}]" value="{{ !empty($question['is_phrase']) ? 1 : 0 }}">

                    @if($mode === 'sentence-builder')
                        <input type="hidden" name="answers[{{ $index }}]" x-model="answer">
                        <input x-show="activeVariant === 'typing'" x-model="answer" class="mt-6 w-full rounded-lg border border-slate-300 px-4 py-4 text-lg outline-none focus:border-emerald-500" placeholder="Введите ответ">
                        <div x-show="activeVariant === 'tiles'" class="mt-6">
                            <div class="min-h-16 rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4">
                                <template x-for="(word, i) in picked" :key="`${word}-${i}`">
                                    <button type="button" @click="bank.push(word); picked.splice(i, 1); answer = picked.join(' ')" class="m-1 rounded bg-emerald-100 px-3 py-2 font-bold text-emerald-900" x-text="word"></button>
                                </template>
                            </div>
                            <div class="mt-5 flex flex-wrap gap-2">
                                <template x-for="(word, i) in bank" :key="`${word}-${i}`">
                                    <button type="button" @click="picked.push(word); bank.splice(i, 1); answer = picked.join(' ')" class="rounded border border-slate-300 bg-white px-3 py-2 font-bold hover:border-emerald-400" x-text="word"></button>
                                </template>
                            </div>
                        </div>
                    @elseif(count($question['options']))
                        <div class="mt-6 grid gap-3 sm:grid-cols-2">
                            @foreach($question['options'] as $value => $label)
                                @php $optionValue = is_string($value) ? $value : $label; @endphp
                                <label class="group flex cursor-pointer items-center gap-3 rounded-lg border border-slate-200 p-4 transition hover:-translate-y-0.5 hover:border-emerald-400 hover:bg-emerald-50">
                                    <input type="radio" name="answers[{{ $index }}]" value="{{ $optionValue }}">
                                    <span class="font-semibold">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <input class="mt-6 w-full rounded-lg border border-slate-300 px-4 py-4 text-lg outline-none focus:border-emerald-500" name="answers[{{ $index }}]" placeholder="Введите ответ">
                    @endif

                    <div class="mt-6 flex justify-between gap-3">
                        <button type="button" x-show="step > 0" @click="step--" class="rounded border border-slate-300 px-5 py-3 font-bold">Назад</button>
                        <span x-show="step === 0"></span>
                        <button type="button" x-show="step < total - 1" @click="step++" class="rounded bg-slate-900 px-5 py-3 font-bold text-white">Дальше</button>
                        <button type="submit" x-show="step === total - 1" class="rounded bg-emerald-600 px-5 py-3 font-black text-white">Завершить</button>
                    </div>
                </section>
            @endforeach
        </form>
    @endif
</div>
@endsection

<script>
    function quizShell(mode, total, variant = 'typing') {
        return {
            step: 0,
            total,
            timeLeft: 60,
            activeVariant: variant,
            initTimer() {
                if (mode !== 'multiple-choice' || total === 0) return;
                const timer = setInterval(() => {
                    this.timeLeft--;
                    if (this.timeLeft <= 0) {
                        clearInterval(timer);
                        this.submitTimed();
                    }
                }, 1000);
            },
            submitTimed() {
                const form = this.$refs.form;
                const current = form.querySelector(`[data-question-index="${this.step}"]`);
                const answeredCurrent = current && current.querySelector('input[type="radio"]:checked');
                const keepUntil = this.step + (answeredCurrent ? 1 : 0);
                form.querySelectorAll('[data-question-index]').forEach((section) => {
                    const index = Number(section.dataset.questionIndex);
                    if (index >= keepUntil) {
                        section.querySelectorAll('input').forEach((input) => input.disabled = true);
                    }
                });
                form.submit();
            }
        }
    }
</script>
