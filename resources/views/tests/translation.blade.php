@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-4">Переведите слова</h1>

    <form action="{{ route('tests.translation') }}" method="POST">
        @csrf
        @foreach($words as $word)
            <div class="mb-4">
                <label class="block font-semibold mb-1">{{ $word->english }}</label>
                <input type="text" name="answers[{{ $word->id }}]" class="w-full border px-3 py-2 rounded" placeholder="Ваш перевод">
            </div>
        @endforeach

        <button type="submit" class="mt-4 px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
            Завершить тест
        </button>
    </form>
</div>
@endsection
