@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">

    <h1 class="text-3xl font-bold mb-6">Мой словарь</h1>

    {{-- Форма добавления слова --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('dictionary.store') }}" class="mb-8 flex gap-4">
        @csrf
        <input type="text" name="english" placeholder="Слово на английском" 
               class="border p-2 rounded flex-grow" value="{{ old('english') }}">
        <input type="text" name="russian" placeholder="Перевод" 
               class="border p-2 rounded flex-grow" value="{{ old('russian') }}">
        <button type="submit" class="bg-purple-600 text-white px-4 rounded hover:bg-purple-700">Добавить</button>
    </form>

    @error('english')
        <p class="text-red-600 mb-2">{{ $message }}</p>
    @enderror
    @error('russian')
        <p class="text-red-600 mb-2">{{ $message }}</p>
    @enderror

    {{-- Список слов --}}
    @if($userWords->isEmpty())
        <p>Ваш словарь пока пуст.</p>
    @else
        <table class="w-full border-collapse border border-gray-300">
<thead>
    <tr class="bg-gray-100">
        <th class="border border-gray-300 p-2">Английское слово</th>
        <th class="border border-gray-300 p-2">Перевод</th>
        <th class="border border-gray-300 p-2">Удалить</th>
    </tr>
</thead>
<tbody>
    @foreach($userWords as $word)
        <tr>
            <td class="border border-gray-300 p-2">{{ $word->english }}</td>
            <td class="border border-gray-300 p-2">{{ $word->russian }}</td>
            <td class="border border-gray-300 p-2 text-center">
                <form method="POST" action="{{ route('dictionary.destroy', $word->id) }}"
                      onsubmit="return confirm('Удалить это слово?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:underline">Удалить</button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>

        </table>
    @endif

</div>
@endsection
