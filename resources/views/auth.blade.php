@extends('layouts.app')

@section('title', $view === 'login' ? 'Вход' : 'Регистрация')

@section('content')
<div class="mx-auto max-w-md rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
    <h1 class="text-2xl font-black">{{ $view === 'login' ? 'Вход' : 'Регистрация' }}</h1>

    @if($errors->any())
        <div class="mt-4 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ $view === 'login' ? route('login') : route('register') }}" class="mt-5 space-y-4">
        @csrf
        @if($view === 'register')
            <input name="name" value="{{ old('name') }}" class="w-full rounded border border-slate-300 px-4 py-3" placeholder="Имя" required>
        @endif
        <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded border border-slate-300 px-4 py-3" placeholder="Email" required>
        <input type="password" name="password" class="w-full rounded border border-slate-300 px-4 py-3" placeholder="Пароль" required>
        @if($view === 'register')
            <input type="password" name="password_confirmation" class="w-full rounded border border-slate-300 px-4 py-3" placeholder="Повторите пароль" required>
        @endif
        <button class="w-full rounded bg-emerald-600 px-4 py-3 font-black text-white">{{ $view === 'login' ? 'Войти' : 'Создать профиль' }}</button>
    </form>

    <p class="mt-5 text-center text-sm text-slate-600">
        @if($view === 'login')
            Нет аккаунта? <a class="font-bold text-emerald-700" href="{{ route('register') }}">Зарегистрироваться</a>
        @else
            Уже есть аккаунт? <a class="font-bold text-emerald-700" href="{{ route('login') }}">Войти</a>
        @endif
    </p>
</div>
@endsection
