<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Вход в админку' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[radial-gradient(circle_at_top,_#fed7aa,_#fafaf9_45%,_#e7e5e4)] text-stone-900 antialiased">
        <main class="mx-auto flex min-h-screen max-w-6xl items-center px-4 py-10 sm:px-6">
            <div class="grid w-full gap-6 lg:grid-cols-[1.05fr_0.95fr]">
                <section class="rounded-[2rem] border border-stone-200 bg-white/90 p-7 shadow-sm backdrop-blur sm:p-9">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-700">Каталог путешествий</p>
                    <h1 class="mt-4 text-3xl font-semibold tracking-tight text-stone-900 sm:text-4xl">Вход в админку</h1>
                    <p class="mt-4 max-w-xl text-sm leading-6 text-stone-600 sm:text-base">
                        Защищённый вход для управления турами, категориями, маршрутами, ценами и содержимым каталога.
                    </p>
                    <div class="mt-6 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-[1.5rem] bg-stone-50 p-4">
                            <p class="text-[11px] uppercase tracking-[0.25em] text-stone-500">Доступ</p>
                            <p class="mt-2 text-sm font-semibold text-stone-900">Только по паролю</p>
                        </div>
                        <div class="rounded-[1.5rem] bg-stone-50 p-4">
                            <p class="text-[11px] uppercase tracking-[0.25em] text-stone-500">Сессия</p>
                            <p class="mt-2 text-sm font-semibold text-stone-900">Локальная авторизация</p>
                        </div>
                    </div>
                </section>

                <section class="rounded-[2rem] border border-stone-200 bg-white p-7 shadow-sm sm:p-9">
                    @if (session('status'))
                        <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-5">
                        @csrf
                        <label class="block">
                            <span class="mb-2 block text-sm font-medium text-stone-700">Логин</span>
                            <input class="w-full rounded-2xl border border-stone-300 px-4 py-3" type="text" name="login" value="{{ old('login') }}" autocomplete="username" required>
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-medium text-stone-700">Пароль</span>
                            <input class="w-full rounded-2xl border border-stone-300 px-4 py-3" type="password" name="password" autocomplete="current-password" required>
                        </label>

                        <button class="inline-flex w-full justify-center rounded-full bg-stone-900 px-6 py-3 text-sm font-medium text-white transition hover:bg-stone-800" type="submit">
                            Войти
                        </button>
                    </form>
                </section>
            </div>
        </main>
    </body>
</html>
