<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LanGrade')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <style>
        body { background: #f5f7fb; }
        .glass { background: rgba(255,255,255,.88); backdrop-filter: blur(16px); }
        button,
        a[class*="bg-"][class*="px-"],
        a[class*="border"][class*="px-"],
        a[class*="inline-block"][class*="rounded"] {
            transition-property: transform, box-shadow, border-color, background-color, color, opacity;
            transition-duration: 180ms;
            transition-timing-function: cubic-bezier(.4, 0, .2, 1);
        }
        button:not(:disabled):hover,
        a[class*="bg-"][class*="px-"]:hover,
        a[class*="border"][class*="px-"]:hover,
        a[class*="inline-block"][class*="rounded"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 18px -14px rgba(15, 23, 42, .45);
        }
        button:not(:disabled):active,
        a[class*="bg-"][class*="px-"]:active,
        a[class*="border"][class*="px-"]:active,
        a[class*="inline-block"][class*="rounded"]:active {
            transform: translateY(0);
            box-shadow: 0 3px 10px -10px rgba(15, 23, 42, .45);
        }
        button:focus-visible,
        a[class*="bg-"][class*="px-"]:focus-visible,
        a[class*="border"][class*="px-"]:focus-visible,
        a[class*="inline-block"][class*="rounded"]:focus-visible {
            outline: 3px solid rgba(16, 185, 129, .28);
            outline-offset: 2px;
        }
        button:disabled {
            transform: none;
            box-shadow: none;
            opacity: .72;
        }
    </style>
</head>
<body class="min-h-screen text-slate-900">
    <header x-data="{ open: false }" class="sticky top-0 z-40 border-b border-slate-200 glass">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3">
            <a href="{{ route('home') }}" class="flex items-center gap-3 font-black tracking-tight">
                <span class="grid h-10 w-10 place-items-center rounded bg-emerald-600 text-white">LG</span>
                <span class="text-xl">LanGrade</span>
            </a>

            <button @click="open = !open" class="rounded border border-slate-200 p-2 md:hidden" aria-label="Меню">
                <span class="block h-0.5 w-6 bg-slate-800"></span>
                <span class="mt-1.5 block h-0.5 w-6 bg-slate-800"></span>
                <span class="mt-1.5 block h-0.5 w-6 bg-slate-800"></span>
            </button>

            <nav :class="open ? 'block' : 'hidden'" class="absolute left-0 top-16 max-h-[calc(100vh-4rem)] w-full overflow-y-auto border-b border-slate-200 bg-white px-4 py-4 md:static md:block md:max-h-none md:w-auto md:overflow-visible md:border-0 md:bg-transparent md:p-0">
                <div class="flex flex-col gap-3 text-sm font-semibold md:flex-row md:items-center md:gap-5">
                    <a class="hover:text-emerald-700" href="{{ route('lessons.index') }}">Уроки</a>
                    <a class="hover:text-emerald-700" href="{{ route('tests.index') }}">Тесты</a>
                    <a class="hover:text-emerald-700" href="{{ route('collections.index') }}">Слова</a>
                    <a class="hover:text-emerald-700" href="{{ route('leaderboard.index') }}">Рейтинг</a>
                    @auth
                        <a class="hover:text-emerald-700" href="{{ route('dictionary.index') }}">Словарь</a>
                        <a class="hover:text-emerald-700" href="{{ route('community.index') }}">Чат</a>
                        <a class="hover:text-emerald-700" href="{{ route('ai.index') }}">AI-Наставник</a>
                        <a href="{{ route('profile') }}" class="rounded border border-slate-200 px-3 py-2 text-sm font-semibold md:hidden">{{ Auth::user()->name }} · {{ Auth::user()->xp }} XP</a>
                        <form method="POST" action="{{ route('logout') }}" class="md:hidden">
                            @csrf
                            <button class="w-full rounded bg-slate-900 px-3 py-2 text-left text-sm font-semibold text-white">Выйти</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="rounded bg-emerald-600 px-4 py-2 text-sm font-bold text-white md:hidden">Войти</a>
                    @endauth
                </div>
            </nav>

            <div class="hidden items-center gap-3 md:flex">
                @auth
                    <a href="{{ route('profile') }}" class="rounded border border-slate-200 px-3 py-2 text-sm font-semibold">{{ Auth::user()->name }} · {{ Auth::user()->xp }} XP</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="rounded bg-slate-900 px-3 py-2 text-sm font-semibold text-white">Выйти</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded bg-emerald-600 px-4 py-2 text-sm font-bold text-white">Войти</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8">
        @if(session('success'))
            <div class="mb-6 rounded border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-white py-6 text-center text-sm text-slate-500">
        <div class="mx-auto flex max-w-7xl flex-col items-center gap-1 px-4">
            <p>LanGrade · базовый английский от фонетики до первых диалогов</p>
            <p>
                Обратная связь:
                <a href="mailto:langradefeed@gmail.com" class="font-semibold text-emerald-700 hover:text-emerald-800">
                    langradefeedback@gmail.com
                </a>
            </p>
        </div>
    </footer>
</body>
</html>
