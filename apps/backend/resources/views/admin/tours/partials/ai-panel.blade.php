<section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
    @php($artifactStatusLabels = ['generated' => 'Подготовлен', 'applied' => 'Применён'])
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-amber-700">Тексты тура</p>
            <h3 class="mt-3 text-2xl font-semibold">Черновики описания</h3>
            <p class="mt-3 text-sm text-stone-600">
                Здесь можно быстро подготовить черновой текст описания и при необходимости применить его к карточке тура.
            </p>
        </div>
        <form method="POST" action="{{ route('admin.tours.generate-draft', $tour) }}">
            @csrf
            <button class="rounded-full bg-stone-900 px-5 py-3 text-sm font-medium text-white" type="submit">
                Сформировать черновик
            </button>
        </form>
    </div>

    <div class="mt-6 grid gap-4 md:grid-cols-2">
        <div class="rounded-2xl bg-stone-50 p-4">
            <p class="text-xs uppercase tracking-[0.2em] text-stone-500">Индекс поиска</p>
            <p class="mt-2 text-sm text-stone-700">
                @if ($tour->embedding)
                    Обновлён {{ $tour->embedding->generated_at?->format('d.m.Y H:i') }}
                @else
                    Ещё не подготовлен.
                @endif
            </p>
        </div>
        <div class="rounded-2xl bg-stone-50 p-4">
            <p class="text-xs uppercase tracking-[0.2em] text-stone-500">Черновики</p>
            <p class="mt-2 text-sm text-stone-700">{{ $tour->generationArtifacts->count() }} шт.</p>
        </div>
    </div>

    <div class="mt-6 space-y-4">
        @forelse ($tour->generationArtifacts as $artifact)
            <article class="rounded-2xl border border-stone-200 p-5">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-stone-900">Черновик от {{ $artifact->generated_at?->format('d.m.Y H:i') }}</p>
                        <p class="mt-1 text-xs text-stone-500">
                            Статус: {{ $artifactStatusLabels[$artifact->status] ?? $artifact->status }}
                        </p>
                    </div>
                    <form method="POST" action="{{ route('admin.tours.artifacts.apply', [$tour, $artifact]) }}">
                        @csrf
                        <button class="rounded-full border border-stone-300 px-4 py-2 text-sm font-medium text-stone-700" type="submit">
                            Применить к туру
                        </button>
                    </form>
                </div>

                <div class="mt-4 grid gap-4 lg:grid-cols-2">
                    <div class="rounded-2xl bg-stone-50 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-stone-500">Краткое описание</p>
                        <p class="mt-2 whitespace-pre-line text-sm text-stone-700">{{ $artifact->generated_payload['short_description'] ?? '—' }}</p>
                    </div>
                    <div class="rounded-2xl bg-stone-50 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-stone-500">Полное описание</p>
                        <p class="mt-2 whitespace-pre-line text-sm text-stone-700">{{ $artifact->generated_payload['full_description'] ?? '—' }}</p>
                    </div>
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-stone-300 px-5 py-6 text-sm text-stone-500">
                Черновики ещё не генерировались.
            </div>
        @endforelse
    </div>
</section>
