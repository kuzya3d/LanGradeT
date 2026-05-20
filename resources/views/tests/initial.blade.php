@extends('layouts.app')

@section('title', 'Начальный тест')

@section('content')
<div class="mx-auto max-w-5xl">
    <div class="mb-8">
        <p class="text-sm font-bold uppercase tracking-wide text-emerald-700">Placement test</p>
        <h1 class="mt-2 text-4xl font-black">Определим стартовый уровень</h1>
        <p class="mt-3 max-w-2xl text-slate-600">Тест проверяет не только отдельные слова, но и словосочетания, грамматику и понимание предложений. Уровень выше базового даётся только при хорошем общем результате и уверенных ответах в ключевых секциях.</p>
    </div>

    <div class="sticky top-20 z-20 mb-6 rounded-lg border border-emerald-200 bg-white/95 p-4 shadow-sm backdrop-blur">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-bold text-emerald-700">Время на тест</p>
                <p class="text-xs text-slate-500">Если время закончится, тест автоматически засчитается как минимальный уровень.</p>
            </div>
            <div id="placement-timer" class="rounded bg-slate-900 px-4 py-2 text-lg font-black tabular-nums text-white">05:00</div>
        </div>
    </div>

    <form id="placement-form" method="POST" action="{{ route('initial-test.submit') }}" class="space-y-8">
        @csrf
        <input id="placement-timed-out" type="hidden" name="timed_out" value="0">
        @php $i = 0; @endphp

        @foreach($sections as $section)
            <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5">
                    <h2 class="text-2xl font-black">{{ $section['title'] }}</h2>
                    <p class="mt-1 text-slate-500">{{ $section['hint'] }}</p>
                </div>

                <div class="space-y-5">
                    @foreach($section['items'] as $item)
                        <div class="rounded bg-slate-50 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <p class="font-black">{{ $item['question'] }}</p>
                                <span class="rounded bg-white px-2 py-1 text-xs font-bold text-slate-500">{{ $item['level'] }}</span>
                            </div>
                            <div class="mt-3 grid gap-2 md:grid-cols-2">
                                @foreach($item['options'] as $option)
                                    <label class="cursor-pointer rounded border border-slate-200 bg-white p-3 transition hover:border-emerald-400">
                                        <input type="radio" name="answers[{{ $i }}]" value="{{ $option }}" class="accent-emerald-600" required>
                                        <span class="ml-2">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @php $i++; @endphp
                    @endforeach
                </div>
            </section>
        @endforeach

        <button class="w-full rounded bg-emerald-600 px-6 py-4 text-lg font-black text-white">Показать уровень</button>
    </form>
</div>

<script>
    (() => {
        const form = document.getElementById('placement-form');
        const timedOut = document.getElementById('placement-timed-out');
        const timer = document.getElementById('placement-timer');
        const deadline = Date.now() + 5 * 60 * 1000;

        const tick = () => {
            const left = Math.max(0, deadline - Date.now());
            const minutes = String(Math.floor(left / 60000)).padStart(2, '0');
            const seconds = String(Math.floor((left % 60000) / 1000)).padStart(2, '0');

            timer.textContent = `${minutes}:${seconds}`;

            if (left <= 30000) {
                timer.classList.remove('bg-slate-900');
                timer.classList.add('bg-red-600');
            }

            if (left <= 0) {
                timedOut.value = '1';
                form.submit();
                return;
            }

            window.setTimeout(tick, 250);
        };

        tick();
    })();
</script>
@endsection
