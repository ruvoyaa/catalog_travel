<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="text-sm uppercase tracking-[0.25em] text-stone-500">Категории</p>
        <h2 class="text-3xl font-semibold">Типы туров</h2>
    </div>
    <a class="rounded-full bg-stone-900 px-5 py-3 text-sm font-medium text-white" href="{{ route('admin.categories.create') }}">Новая категория</a>
</div>

<div class="mt-6 hidden overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-stone-200 md:block">
    <table class="min-w-full divide-y divide-stone-200">
        <thead class="bg-stone-50">
            <tr class="text-left text-xs uppercase tracking-[0.2em] text-stone-500">
                <th class="px-6 py-4">Название</th>
                <th class="px-6 py-4">Slug</th>
                <th class="px-6 py-4">Туров</th>
                <th class="px-6 py-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100 text-sm">
            @forelse ($categories as $category)
                <tr>
                    <td class="px-6 py-4 font-medium">{{ $category->name }}</td>
                    <td class="px-6 py-4 text-stone-500">{{ $category->slug }}</td>
                    <td class="px-6 py-4">{{ $category->tours_count }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-3">
                            <a class="text-stone-700 underline" href="{{ route('admin.categories.edit', $category) }}">Изменить</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Удалить категорию?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-700 underline" type="submit">Удалить</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="px-6 py-8 text-stone-500" colspan="4">Категорий пока нет.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6 space-y-4 md:hidden">
    @forelse ($categories as $category)
        <article class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-stone-200">
            <p class="text-lg font-semibold text-stone-900">{{ $category->name }}</p>
            <p class="mt-1 text-xs text-stone-500">{{ $category->slug }}</p>
            <div class="mt-4 flex items-center justify-between rounded-2xl bg-stone-50 p-4 text-sm">
                <span class="text-stone-500">Туров в категории</span>
                <span class="font-semibold text-stone-900">{{ $category->tours_count }}</span>
            </div>
            <div class="mt-4 flex items-center gap-4 text-sm">
                <a class="font-medium text-stone-700 underline" href="{{ route('admin.categories.edit', $category) }}">Изменить</a>
                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Удалить категорию?');">
                    @csrf
                    @method('DELETE')
                    <button class="font-medium text-red-700 underline" type="submit">Удалить</button>
                </form>
            </div>
        </article>
    @empty
        <div class="rounded-3xl bg-white px-6 py-8 text-stone-500 shadow-sm ring-1 ring-stone-200">Категорий пока нет.</div>
    @endforelse
</div>
