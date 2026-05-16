<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-stone-200">
        <p class="text-sm text-stone-500">Всего туров</p>
        <p class="mt-3 text-3xl font-semibold text-stone-900">{{ $tourCount }}</p>
    </div>
    <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-stone-200">
        <p class="text-sm text-stone-500">Опубликовано</p>
        <p class="mt-3 text-3xl font-semibold text-stone-900">{{ $publishedTourCount }}</p>
    </div>
    <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-stone-200">
        <p class="text-sm text-stone-500">Категорий</p>
        <p class="mt-3 text-3xl font-semibold text-stone-900">{{ $categoryCount }}</p>
    </div>
    <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-stone-200">
        <p class="text-sm text-stone-500">Поисковых записей</p>
        <p class="mt-3 text-3xl font-semibold text-stone-900">{{ $embeddingCount }}</p>
    </div>
</div>

<div class="mt-6 grid gap-4 xl:grid-cols-[1.2fr_0.8fr]">
    <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-amber-700">Обзор</p>
        <h2 class="mt-3 text-2xl font-semibold">Главные действия перед публикацией</h2>
        <div class="mt-5 grid gap-3 md:grid-cols-2">
            <div class="rounded-2xl bg-stone-50 p-4">
                <p class="text-sm font-semibold text-stone-900">1. Наполнить тур</p>
                <p class="mt-2 text-sm leading-6 text-stone-600">Название, описание, длительность, категория и статус.</p>
            </div>
            <div class="rounded-2xl bg-stone-50 p-4">
                <p class="text-sm font-semibold text-stone-900">2. Проверить программу</p>
                <p class="mt-2 text-sm leading-6 text-stone-600">Фото, маршрут на карте, даты и стоимость поездки.</p>
            </div>
            <div class="rounded-2xl bg-stone-50 p-4">
                <p class="text-sm font-semibold text-stone-900">3. Подготовить текст</p>
                <p class="mt-2 text-sm leading-6 text-stone-600">При необходимости сформировать и применить черновик описания.</p>
            </div>
            <div class="rounded-2xl bg-stone-50 p-4">
                <p class="text-sm font-semibold text-stone-900">4. Опубликовать</p>
                <p class="mt-2 text-sm leading-6 text-stone-600">После сохранения в статусе «Опубликован» тур появится в каталоге.</p>
            </div>
        </div>
    </section>

    <section class="rounded-3xl bg-gradient-to-br from-amber-500 via-orange-500 to-rose-500 p-6 text-white shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/70">Каталог</p>
        <h2 class="mt-3 text-2xl font-semibold">Готово к презентации</h2>
        <p class="mt-3 text-sm leading-6 text-white/85">
            В каталоге уже работают карточки туров, поиск, фильтры, маршруты, даты и цены. Здесь удобно контролировать публикацию и быстро обновлять содержимое.
        </p>
    </section>
</div>
