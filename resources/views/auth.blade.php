<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <title>LanGrade | Вход / Регистрация</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('TEST8.png') }}" type="image/png">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen relative">

    @if (session('success'))
        <div
            class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded shadow-lg z-50 transition-opacity duration-700"
            id="flash-message"
        >
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                const msg = document.getElementById('flash-message');
                if (msg) {
                    msg.style.opacity = '0';
                }
            }, 3000);
        </script>
    @endif

    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
        <div class="flex justify-around mb-6 border-b pb-2">
            <a href="{{ route('login') }}"
               class="{{ $view === 'login' ? 'font-bold text-purple-600 border-b-2 border-purple-600 pb-1' : 'text-gray-400' }}">
               Вход
            </a>
            <a href="{{ route('register') }}"
               class="{{ $view === 'register' ? 'font-bold text-purple-600 border-b-2 border-purple-600 pb-1' : 'text-gray-400' }}">
               Регистрация
            </a>
        </div>

        @if ($view === 'login')
            <form method="POST" action="{{ url('/login') }}" class="space-y-4" novalidate>
                @csrf
                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    value="{{ old('email') }}"
                    class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-purple-500"
                    required
                />
                <input
                    type="password"
                    name="password"
                    placeholder="Пароль"
                    class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-purple-500"
                    required
                />
                <button
                    type="submit"
                    class="w-full bg-purple-600 text-white py-3 rounded hover:bg-purple-700 transition"
                >
                    Войти
                </button>
            </form>
        @else
            <form method="POST" action="{{ url('/register') }}" class="space-y-4" novalidate>
                @csrf
                <input
                    type="text"
                    name="name"
                    placeholder="Имя"
                    value="{{ old('name') }}"
                    class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-purple-500"
                    required
                />
                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    value="{{ old('email') }}"
                    class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-purple-500"
                    required
                />
                <input
                    type="password"
                    name="password"
                    placeholder="Пароль"
                    class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-purple-500"
                    required
                />
                <input
                    type="password"
                    name="password_confirmation"
                    placeholder="Подтвердите пароль"
                    class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-purple-500"
                    required
                />
                <button
                    type="submit"
                    class="w-full bg-purple-600 text-white py-3 rounded hover:bg-purple-700 transition"
                >
                    Зарегистрироваться
                </button>
            </form>
        @endif

        @if ($errors->any())
            <div class="mt-4 text-red-600 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</body>
</html>
