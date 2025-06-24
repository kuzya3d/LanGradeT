@extends('layouts.app')

@section('title', 'Тест на сборку слова')

@section('content')
<div class="max-w-xl mx-auto mt-12 p-6 bg-white rounded shadow text-center">

    <h1 class="text-2xl font-bold mb-6">Тест на сборку слова</h1>

    <div x-data="compileWordTest()" x-init="initWords()" x-cloak>
        <template x-if="currentWord">
            <div>
                <p class="text-lg mb-4 font-semibold" x-text="currentWord.russian"></p>

                <div class="mb-4 flex justify-center space-x-2">
                    <template x-for="(letter, index) in shuffledLetters" :key="index">
                        <span class="px-3 py-2 border rounded cursor-pointer select-none"
                              :class="selectedLetters.includes(letter) ? 'bg-gray-300' : 'bg-gray-100 hover:bg-gray-200'"
                              @click="selectLetter(letter)">
                            <span x-text="letter"></span>
                        </span>
                    </template>
                </div>

                <div class="mb-4 min-h-[40px] border rounded p-2">
                    <template x-for="(letter, index) in selectedLetters" :key="index">
                        <span class="inline-block px-3 py-1 m-1 bg-purple-200 rounded cursor-pointer"
                              @click="removeLetter(index)">
                            <span x-text="letter"></span>
                        </span>
                    </template>
                </div>

                <button class="px-6 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition"
                        @click="checkAnswer()">Проверить</button>

                <p x-show="feedback"
                   :class="{'text-green-600': correct, 'text-red-600': !correct}"
                   class="mt-4 font-semibold"
                   x-text="feedback"></p>
            </div>
        </template>

        <template x-if="!currentWord">
            <div>
                <h2 class="text-xl font-semibold mb-4">Тест завершен!</h2>
                <p>Вы правильно собрали <span x-text="score"></span> из <span x-text="words.length"></span> слов.</p>

                <form method="POST" action="{{ route('tests.submit-compile-word') }}">
                    @csrf
                    <input type="hidden" name="correct" :value="score">
                    <input type="hidden" name="total" :value="words.length">
                    <button type="submit" class="mt-6 px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                        Сохранить результат и вернуться к тестам
                    </button>
                </form>
            </div>
        </template>
    </div>

</div>

<script>
    const rawWords = @json($words);

    function compileWordTest() {
        return {
            words: [],
            currentIndex: 0,
            currentWord: null,
            shuffledLetters: [],
            selectedLetters: [],
            score: 0,
            feedback: '',
            correct: false,

            initWords() {
                this.words = rawWords;
                this.currentWord = this.words[0];
                this.shuffleLetters();
            },

            shuffleLetters() {
                if (!this.currentWord) return;
                let letters = this.currentWord.english.split('');
                for (let i = letters.length - 1; i > 0; i--) {
                    let j = Math.floor(Math.random() * (i + 1));
                    [letters[i], letters[j]] = [letters[j], letters[i]];
                }
                this.shuffledLetters = letters;
                this.selectedLetters = [];
                this.feedback = '';
                this.correct = false;
            },

            selectLetter(letter) {
                const index = this.shuffledLetters.indexOf(letter);
                if (index !== -1) {
                    this.selectedLetters.push(letter);
                    this.shuffledLetters.splice(index, 1);
                }
            },

            removeLetter(index) {
                const letter = this.selectedLetters[index];
                this.selectedLetters.splice(index, 1);
                this.shuffledLetters.push(letter);
            },

            checkAnswer() {
                let answer = this.selectedLetters.join('');
                if (answer.toLowerCase() === this.currentWord.english.toLowerCase()) {
                    this.feedback = 'Правильно!';
                    this.correct = true;
                    this.score++;
                    setTimeout(() => {
                        this.nextWord();
                    }, 1000);
                } else {
                    this.feedback = 'Неправильно. Попробуйте ещё раз.';
                    this.correct = false;
                }
            },

            nextWord() {
                this.currentIndex++;
                if (this.currentIndex < this.words.length) {
                    this.currentWord = this.words[this.currentIndex];
                    this.shuffleLetters();
                } else {
                    this.currentWord = null;
                }
            }
        }
    }
</script>
@endsection

