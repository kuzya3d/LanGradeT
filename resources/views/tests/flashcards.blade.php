@extends('layouts.app')

@section('title', 'Карточки слов')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded shadow mt-10 text-center">
    <h1 class="text-2xl font-bold mb-6">Карточки слов</h1>

    <div 
        x-data="flashcards({{ $words->isNotEmpty() ? $words->toJson() : '[]' }})" 
        class="relative"
    >
        <template x-if="!completed">
            <div>
                <div class="w-full h-64 mb-4 rounded bg-white flex items-center justify-center">
                    <img :src="`/images/${current.image}`" alt="" class="max-h-full max-w-full object-contain">
                </div>
                
                <h2 class="text-xl font-semibold mb-2" x-text="current.english"></h2>
                <p class="text-gray-700 text-lg mb-6" x-text="current.russian"></p>

                <div class="flex justify-between items-center">
                    <button @click="prevCard" class="text-sm text-purple-600 hover:underline" :disabled="index === 0">← Назад</button>
                    <button @click="nextCard" class="text-sm text-purple-600 hover:underline">Вперёд →</button>
                </div>
            </div>
        </template>

        <template x-if="completed">
            <div>
                <h2 class="text-xl font-semibold mb-4">Карточки закончились!</h2>
                <a href="{{ url('/tests') }}" class="text-purple-600 hover:underline">Вернуться к тестам</a>
            </div>
        </template>
    </div>
</div>

<script>
    function flashcards(words) {
        return {
            words: words,
            index: 0,
            completed: false,

            get current() {
                return this.words[this.index];
            },

            nextCard() {
                if (this.index < this.words.length - 1) {
                    this.index++;
                } else {
                    this.completed = true;
                }
            },

            prevCard() {
                if (this.index > 0) {
                    this.index--;
                }
            }
        }
    }
</script>
@endsection
