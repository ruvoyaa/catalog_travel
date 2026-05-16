<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Catalog Travel Admin' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-stone-100 text-stone-900 antialiased">
        <div class="min-h-screen">
            <header class="border-b border-stone-200 bg-white/95 backdrop-blur">
                <div class="mx-auto flex max-w-6xl flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-amber-700">Каталог путешествий</p>
                        <h1 class="text-xl font-semibold text-stone-900">Управление турами</h1>
                    </div>
                    <nav class="flex flex-wrap items-center gap-2 text-sm font-medium">
                        <a class="rounded-full px-4 py-2 text-stone-700 transition hover:bg-stone-100" href="{{ route('admin.dashboard') }}">Обзор</a>
                        <a class="rounded-full px-4 py-2 text-stone-700 transition hover:bg-stone-100" href="{{ route('admin.categories.index') }}">Категории</a>
                        <a class="rounded-full px-4 py-2 text-stone-700 transition hover:bg-stone-100" href="{{ route('admin.tours.index') }}">Туры</a>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button class="rounded-full border border-stone-300 px-4 py-2 text-stone-700 transition hover:bg-stone-100" type="submit">Выйти</button>
                        </form>
                    </nav>
                </div>
            </header>

            <main class="mx-auto max-w-6xl px-4 py-6 sm:px-6 sm:py-8">
                @if (session('status'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <p class="font-semibold">Есть ошибки в форме:</p>
                        <ul class="mt-2 list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {!! $slot ?? '' !!}
                @yield('content')
            </main>
        </div>
    </body>
</html>
