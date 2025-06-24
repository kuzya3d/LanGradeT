@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Привет, {{ $user->name }}!</h1>

    {{-- Личный словарь --}}
    <section class="mb-10">
        <h2 class="text-2xl font-semibold mb-4">Личный словарь</h2>
        @if($userWords->isEmpty())
            <p>Ваш словарь пока пуст. Добавляйте слова!</p>
        @else
            <ul class="list-disc pl-5 space-y-1">
                @foreach($userWords as $word)
                    <li>{{ $word->english }} — {{ $word->russian }}</li>
                @endforeach
            </ul>
        @endif
    </section>

    {{-- Статистика по тестам --}}
    <section class="mb-10" x-data="{ showStats: false }">
        <h2 class="text-2xl font-semibold mb-4">Ваша статистика по тестам</h2>

        @if($passedPercent > 0)
            <p>Средний результат: <strong>{{ $passedPercent }}%</strong></p>
            <button @click="showStats = !showStats" class="mt-2 text-purple-600 hover:underline text-sm">
                <span x-text="showStats ? 'Скрыть детали' : 'Показать детали'"></span>
            </button>

            <div x-show="showStats" class="mt-4">
                <ul class="space-y-2 text-gray-700">
                    <li>📘 Тест на перевод: <strong>{{ $translationResultsCount }}</strong> попыток, средний результат: <strong>{{ $translationAvgScore }}%</strong></li>
                    <li>🧩 Тест на сборку слова: <strong>{{ $compileResultsCount }}</strong> попыток, средний результат: <strong>{{ $compileAvgScore }}%</strong></li>
                </ul>
            </div>
        @else
            <p>Пока нет данных о прохождении тестов.</p>
        @endif
    </section>

    {{-- Памятка: таблица алфавита --}}
    <section class="mb-10">
        <h2 class="text-2xl font-semibold mb-4">Памятка: Алфавит</h2>
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse border border-gray-300 text-sm text-center">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1">Буква</th>
                        <th class="border px-2 py-1">Произношение</th>
                        <th class="border px-2 py-1">Транскрипция</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach([
                        ['A', 'эй', '[eɪ]'],
                        ['B', 'би', '[biː]'],
                        ['C', 'си', '[siː]'],
                        ['D', 'ди', '[diː]'],
                        ['E', 'и', '[iː]'],
                        ['F', 'эф', '[ɛf]'],
                        ['G', 'джи', '[dʒiː]'],
                        ['H', 'эйч', '[eɪtʃ]'],
                        ['I', 'ай', '[aɪ]'],
                        ['J', 'джей', '[dʒeɪ]'],
                        ['K', 'кей', '[keɪ]'],
                        ['L', 'эл', '[ɛl]'],
                        ['M', 'эм', '[ɛm]'],
                        ['N', 'эн', '[ɛn]'],
                        ['O', 'оу', '[oʊ]'],
                        ['P', 'пи', '[piː]'],
                        ['Q', 'кью', '[kjuː]'],
                        ['R', 'ар', '[ɑːr]'],
                        ['S', 'эс', '[ɛs]'],
                        ['T', 'ти', '[tiː]'],
                        ['U', 'ю', '[juː]'],
                        ['V', 'ви', '[viː]'],
                        ['W', 'дабл ю', '[ˈdʌbl juː]'],
                        ['X', 'экс', '[ɛks]'],
                        ['Y', 'уай', '[waɪ]'],
                        ['Z', 'зед/зи', '[zɛd] / [ziː]']
                    ] as $row)
                        <tr>
                            <td class="border px-2 py-1 font-bold">{{ $row[0] }}</td>
                            <td class="border px-2 py-1">{{ $row[1] }}</td>
                            <td class="border px-2 py-1">{{ $row[2] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    {{-- Кнопка выхода --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded">
            Выйти из профиля
        </button>
    </form>
</div>
@endsection
