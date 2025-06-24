@extends('layouts.app')

@section('title', 'Первичный тест')

@section('content')
<h1 class="text-2xl font-bold mb-6">Какие слова вы уже знаете?</h1>

<form method="POST" action="{{ route('initial-test.submit') }}">
    @csrf
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @foreach($words as $word)
            <label class="flex items-center space-x-2 bg-white shadow p-3 rounded hover:bg-purple-50 transition">
                <input type="checkbox" name="known_words[]" value="{{ $word }}" class="form-checkbox text-purple-600">
                <span class="text-gray-800">{{ $word }}</span>
            </label>
        @endforeach
    </div>

    <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700 transition">
        Продолжить
    </button>
</form>
@endsection
