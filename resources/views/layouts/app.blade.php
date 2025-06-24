<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LanGrade</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="icon" href="{{ asset('TEST8.png') }}" type="image/png">
</head>
<body class="min-h-screen flex flex-col bg-gray-50">

    <!-- Хедер -->
    <header x-data="{ open: false }" class="bg-white shadow-md">
        <div class="container mx-auto flex flex-wrap items-center justify-between px-4 py-4">

            <!-- Лого -->
            <a href="{{ url('/') }}" class="text-2xl font-bold text-purple-600">
                LanGrade
            </a>

            <!-- Бургер -->
            <button @click="open = !open" class="md:hidden text-purple-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Навигация -->
            <nav :class="{ 'block': open, 'hidden': !open }"
                 class="w-full md:flex md:items-center md:w-auto mt-4 md:mt-0 space-y-2 md:space-y-0 md:space-x-6 hidden text-gray-700 font-medium">
                <a href="{{ url('/dictionary') }}" class="block hover:text-purple-600">Личный словарь</a>
                <a href="{{ url('/tests') }}" class="block hover:text-purple-600">Тесты</a>
                <a href="{{ url('/collections') }}" class="block hover:text-purple-600">Подборки слов</a>
            </nav>

            <!-- Профиль -->
            <div class="w-full md:w-auto mt-4 md:mt-0 flex justify-end items-center space-x-4">
                @auth
                    <a href="{{ url('/profile') }}" class="text-gray-700 hover:text-purple-600 font-medium">
                        {{ Auth::user()->name }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-500 hover:underline">Выйти</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-purple-600 hover:underline font-medium">
                        Войти / Регистрация
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Основной контент -->
    <main class="flex-grow container mx-auto px-4 sm:px-6 py-6">
        @yield('content')
    </main>

    <!-- Футер -->
    <footer class="bg-white border-t mt-10 py-6 text-center text-gray-600 text-sm px-4">
        <p>Контакты: LanGradeContact@gmail.com</p>
        <p>Телефон: +7 908 244 88 41</p>
    </footer>

</body>
</html>
