@extends('layouts.app')

@section('title', 'Рейтинг')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-black">Глобальный рейтинг</h1>
    <p class="mt-2 text-slate-600">Позиция строится по XP, активности в тестах, словарю и достижениям.</p>
</div>

<div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
    <table class="w-full min-w-[720px] text-left text-sm">
        <thead class="bg-slate-50 text-slate-500">
            <tr>
                <th class="px-4 py-3">#</th>
                <th class="px-4 py-3">Пользователь</th>
                <th class="px-4 py-3">Уровень</th>
                <th class="px-4 py-3">XP</th>
                <th class="px-4 py-3">Тесты</th>
                <th class="px-4 py-3">Слова</th>
                <th class="px-4 py-3">Достижения</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($leaders as $index => $user)
                <tr>
                    <td class="px-4 py-3 font-black">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 font-bold">
                        <a href="{{ route('users.show', $user) }}" class="text-emerald-700 hover:underline">{{ $user->avatar ?: '🙂' }} {{ $user->name }}</a>
                    </td>
                    <td class="px-4 py-3">{{ $user->english_level }}</td>
                    <td class="px-4 py-3">{{ $user->xp }}</td>
                    <td class="px-4 py-3">{{ $user->attempts_count }}</td>
                    <td class="px-4 py-3">{{ $user->words_count }}</td>
                    <td class="px-4 py-3">{{ $user->achievements_count }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-slate-500">Пока нет участников.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
