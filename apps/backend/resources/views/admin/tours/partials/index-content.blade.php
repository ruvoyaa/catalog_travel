@php($statusLabels = ['draft' => 'Черновик', 'published' => 'Опубликован'])

<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="text-sm uppercase tracking-[0.25em] text-stone-500">Туры</p>
        <h2 class="text-3xl font-semibold">Каталог туров</h2>
    </div>
    <a class="rounded-full bg-stone-900 px-5 py-3 text-sm font-medium text-white" href="{{ route('admin.tours.create') }}">Новый тур</a>
</div>

<div class="mt-6 hidden overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-stone-200 md:block">
    <table class="min-w-full divide-y divide-stone-200">
        <thead class="bg-stone-50">
            <tr class="text-left text-xs uppercase tracking-[0.2em] text-stone-500">
                <th class="px-6 py-4">Тур</th>
                <th class="px-6 py-4">Категория</th>
                <th class="px-6 py-4">Длительность</th>
                <th class="px-6 py-4">Статус</th>
                <th class="px-6 py-4">Цены</th>
                <th class="px-6 py-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100 text-sm">
            @forelse ($tours as $tour)
                <tr>
                    <td class="px-6 py-4">
                        <p class="font-medium">{{ $tour->title }}</p>
                        <p class="text-xs text-stone-500">{{ $tour->slug }}</p>
                    </td>
                    <td class="px-6 py-4">{{ $tour->primaryCategory?->name ?? '—' }}</td>
                    <td class="px-6 py-4">{{ $tour->duration_label ?: $tour->duration_days . ' дн.' }}</td>
                    <td class="px-6 py-4">
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $tour->status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ $statusLabels[$tour->status] ?? $tour->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $tour->datePrices->count() }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-3">
                            <a class="text-stone-700 underline" href="{{ route('admin.tours.edit', $tour) }}">Изменить</a>
                            <form method="POST" action="{{ route('admin.tours.destroy', $tour) }}" onsubmit="return confirm('Удалить тур?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-700 underline" type="submit">Удалить</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="px-6 py-8 text-stone-500" colspan="6">Туров пока нет.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6 space-y-4 md:hidden">
    @forelse ($tours as $tour)
        <article class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-stone-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-lg font-semibold text-stone-900">{{ $tour->title }}</p>
                    <p class="mt-1 text-xs text-stone-500">{{ $tour->slug }}</p>
                </div>
                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $tour->status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                    {{ $statusLabels[$tour->status] ?? $tour->status }}
                </span>
            </div>
            <dl class="mt-4 grid gap-3 rounded-2xl bg-stone-50 p-4 text-sm text-stone-700">
                <div class="flex items-center justify-between gap-3">
                    <dt class="text-stone-500">Категория</dt>
                    <dd class="font-medium text-stone-900">{{ $tour->primaryCategory?->name ?? '—' }}</dd>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <dt class="text-stone-500">Длительность</dt>
                    <dd class="font-medium text-stone-900">{{ $tour->duration_label ?: $tour->duration_days . ' дн.' }}</dd>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <dt class="text-stone-500">Дат с ценами</dt>
                    <dd class="font-medium text-stone-900">{{ $tour->datePrices->count() }}</dd>
                </div>
            </dl>
            <div class="mt-4 flex items-center gap-4 text-sm">
                <a class="font-medium text-stone-700 underline" href="{{ route('admin.tours.edit', $tour) }}">Изменить</a>
                <form method="POST" action="{{ route('admin.tours.destroy', $tour) }}" onsubmit="return confirm('Удалить тур?');">
                    @csrf
                    @method('DELETE')
                    <button class="font-medium text-red-700 underline" type="submit">Удалить</button>
                </form>
            </div>
        </article>
    @empty
        <div class="rounded-3xl bg-white px-6 py-8 text-stone-500 shadow-sm ring-1 ring-stone-200">Туров пока нет.</div>
    @endforelse
</div>
