@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10">
    <h1 class="text-3xl font-bold mb-4">{{ $collection->title }}</h1>
    <p class="text-gray-600 mb-8">{{ $collection->description }}</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach ($collection->words as $word)
            <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center text-center">
                <div class="w-28 h-28 mb-3 rounded bg-gray-100 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/' . $word->image) }}"
                         alt="{{ $word->english }}"
                         class="object-contain w-full h-full">
                </div>

                <h3 class="text-lg font-semibold mb-1">{{ $word->english }}</h3>
                <p class="text-gray-600 mb-3">{{ $word->russian }}</p>

                @auth
                    @if(in_array($word->id, $userWordIds))
                        <button disabled class="bg-gray-400 text-white text-sm px-3 py-1.5 rounded cursor-not-allowed">
                            Уже добавлено
                        </button>
                    @else
                        <form action="{{ route('words.add', $word->id) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit"
                                    class="w-full bg-purple-500 hover:bg-purple-600 text-white text-sm px-3 py-1.5 rounded transition">
                                Добавить в словарь
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
        @endforeach
    </div>
</div>
@endsection
