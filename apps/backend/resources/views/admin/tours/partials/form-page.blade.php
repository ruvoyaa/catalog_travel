@php($selectedCategories = collect(old('categories', $tour->categories->pluck('id')->all() ?? []))->map(fn ($id) => (int) $id)->all())
@php($images = old('images', $tour->images->map(fn ($image) => ['image_url' => $image->image_url, 'alt_text' => $image->alt_text])->all() ?: [['image_url' => '', 'alt_text' => '']]))
@php($datePrices = old('date_prices', $tour->datePrices->map(fn ($item) => ['start_date' => optional($item->start_date)->toDateString(), 'end_date' => optional($item->end_date)->toDateString(), 'price' => $item->price, 'currency' => $item->currency, 'label' => $item->label, 'is_active' => $item->is_active])->all() ?: [['start_date' => '', 'end_date' => '', 'price' => '', 'currency' => 'RUB', 'label' => '', 'is_active' => true]]))
@php($routeData = $tour->route)
@php($statusLabels = ['draft' => 'Черновик', 'published' => 'Опубликован'])
@php($controlClass = fn (string $key, string $base = 'w-full rounded-2xl border border-stone-300 px-4 py-3') => $errors->has($key) ? $base.' border-red-400 bg-red-50/60' : $base)
@php($simpleControlClass = fn (string $key, string $base = 'rounded-2xl border border-stone-300 px-4 py-3') => $errors->has($key) ? $base.' border-red-400 bg-red-50/60' : $base)

<div class="mb-6">
    <a class="text-sm text-stone-500 underline" href="{{ route('admin.tours.index') }}">← К списку туров</a>
    <h2 class="mt-3 text-3xl font-semibold">{{ $heading }}</h2>
</div>

<form method="POST" action="{{ $action }}" class="space-y-6" id="tour-form">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
        <h3 class="text-lg font-semibold">Основное</h3>
        <div class="mt-6 grid gap-6 md:grid-cols-2">
            <label class="block md:col-span-2">
                <span class="mb-2 block text-sm font-medium text-stone-700">Название</span>
                <input class="{{ $controlClass('title') }}" type="text" name="title" value="{{ old('title', $tour->title) }}" required>
                @error('title')
                    <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
                @enderror
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-medium text-stone-700">Slug</span>
                <input class="{{ $controlClass('slug') }}" type="text" name="slug" value="{{ old('slug', $tour->slug) }}">
                @error('slug')
                    <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
                @enderror
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-medium text-stone-700">Статус</span>
                <select class="{{ $controlClass('status') }}" name="status">
                    @foreach (['draft', 'published'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $tour->status) === $status)>{{ $statusLabels[$status] }}</option>
                    @endforeach
                </select>
                @error('status')
                    <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
                @enderror
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-medium text-stone-700">Длительность, дней</span>
                <input class="{{ $controlClass('duration_days') }}" type="number" min="1" name="duration_days" value="{{ old('duration_days', $tour->duration_days) }}" required>
                @error('duration_days')
                    <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
                @enderror
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-medium text-stone-700">Подпись длительности</span>
                <input class="{{ $controlClass('duration_label') }}" type="text" name="duration_label" value="{{ old('duration_label', $tour->duration_label) }}" placeholder="7 дней / 6 ночей">
                @error('duration_label')
                    <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
                @enderror
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-medium text-stone-700">Основная категория</span>
                <select class="{{ $controlClass('primary_category_id') }}" name="primary_category_id">
                    <option value="">Без основной категории</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((int) old('primary_category_id', $tour->primary_category_id) === $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('primary_category_id')
                    <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
                @enderror
            </label>
        </div>

        <div class="mt-6">
            <span class="mb-3 block text-sm font-medium text-stone-700">Категории тура</span>
            <div class="flex flex-wrap gap-3">
                @foreach ($categories as $category)
                    <label class="inline-flex items-center gap-2 rounded-full border border-stone-300 px-4 py-2 text-sm">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}" @checked(in_array($category->id, $selectedCategories, true))>
                        <span>{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('categories')
                <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
            @enderror
            @error('categories.*')
                <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-6 grid gap-6">
            <label class="block">
                <span class="mb-2 block text-sm font-medium text-stone-700">Короткое описание</span>
                <textarea class="{{ $controlClass('short_description', 'min-h-24 w-full rounded-2xl border border-stone-300 px-4 py-3') }}" name="short_description">{{ old('short_description', $tour->short_description) }}</textarea>
                @error('short_description')
                    <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
                @enderror
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-medium text-stone-700">Полное описание</span>
                <textarea class="{{ $controlClass('full_description', 'min-h-48 w-full rounded-2xl border border-stone-300 px-4 py-3') }}" name="full_description">{{ old('full_description', $tour->full_description) }}</textarea>
                @error('full_description')
                    <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
                @enderror
            </label>
        </div>
    </section>

    <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h3 class="text-lg font-semibold">Фотоальбом</h3>
            <button class="rounded-full border border-stone-300 px-4 py-2 text-sm" type="button" onclick="addRepeaterItem('images-repeater', 'images-template')">Добавить фото</button>
        </div>
        <div id="images-repeater" class="mt-6 space-y-4">
            @foreach ($images as $index => $image)
                <div class="grid gap-4 rounded-2xl border border-stone-200 p-4 xl:grid-cols-[2fr_1fr_auto] xl:items-start">
                    <div>
                        <input class="{{ $simpleControlClass("images.$index.image_url") }}" type="url" name="images[{{ $index }}][image_url]" value="{{ $image['image_url'] ?? '' }}" placeholder="https://...">
                        @foreach ($errors->get("images.$index.image_url") as $message)
                            <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
                        @endforeach
                    </div>
                    <div>
                        <input class="{{ $simpleControlClass("images.$index.alt_text") }}" type="text" name="images[{{ $index }}][alt_text]" value="{{ $image['alt_text'] ?? '' }}" placeholder="Описание изображения">
                        @foreach ($errors->get("images.$index.alt_text") as $message)
                            <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
                        @endforeach
                    </div>
                    <button class="rounded-full border border-red-300 px-4 py-2 text-sm text-red-700" type="button" onclick="this.closest('.grid').remove()">Удалить</button>
                </div>
            @endforeach
        </div>
        <template id="images-template">
            <div class="grid gap-4 rounded-2xl border border-stone-200 p-4 xl:grid-cols-[2fr_1fr_auto] xl:items-start">
                <input class="rounded-2xl border border-stone-300 px-4 py-3" type="url" name="images[__INDEX__][image_url]" placeholder="https://...">
                <input class="rounded-2xl border border-stone-300 px-4 py-3" type="text" name="images[__INDEX__][alt_text]" placeholder="Описание изображения">
                <button class="rounded-full border border-red-300 px-4 py-2 text-sm text-red-700" type="button" onclick="this.closest('.grid').remove()">Удалить</button>
            </div>
        </template>
    </section>

    <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h3 class="text-lg font-semibold">Даты и цены</h3>
            <button class="rounded-full border border-stone-300 px-4 py-2 text-sm" type="button" onclick="addRepeaterItem('dates-repeater', 'dates-template')">Добавить дату</button>
        </div>
        <div id="dates-repeater" class="mt-6 space-y-4">
            @foreach ($datePrices as $index => $row)
                <div class="rounded-2xl border border-stone-200 p-4">
                    <div class="grid gap-4 sm:grid-cols-2 2xl:grid-cols-5">
                        <div>
                            <input class="{{ $simpleControlClass("date_prices.$index.start_date") }}" type="date" name="date_prices[{{ $index }}][start_date]" value="{{ $row['start_date'] ?? '' }}">
                            @foreach ($errors->get("date_prices.$index.start_date") as $message)
                                <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
                            @endforeach
                        </div>
                        <div>
                            <input class="{{ $simpleControlClass("date_prices.$index.end_date") }}" type="date" name="date_prices[{{ $index }}][end_date]" value="{{ $row['end_date'] ?? '' }}">
                            @foreach ($errors->get("date_prices.$index.end_date") as $message)
                                <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
                            @endforeach
                        </div>
                        <div>
                            <input class="{{ $simpleControlClass("date_prices.$index.price") }}" type="number" step="0.01" min="0" name="date_prices[{{ $index }}][price]" value="{{ $row['price'] ?? '' }}" placeholder="Цена">
                            @foreach ($errors->get("date_prices.$index.price") as $message)
                                <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
                            @endforeach
                        </div>
                        <div>
                            <input class="{{ $simpleControlClass("date_prices.$index.currency") }}" type="text" name="date_prices[{{ $index }}][currency]" value="{{ $row['currency'] ?? 'RUB' }}" placeholder="RUB">
                            @foreach ($errors->get("date_prices.$index.currency") as $message)
                                <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
                            @endforeach
                        </div>
                        <div>
                            <input class="{{ $simpleControlClass("date_prices.$index.label") }}" type="text" name="date_prices[{{ $index }}][label]" value="{{ $row['label'] ?? '' }}" placeholder="Майские праздники">
                            @foreach ($errors->get("date_prices.$index.label") as $message)
                                <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <label class="inline-flex items-center gap-2 text-sm text-stone-700">
                            <input type="hidden" name="date_prices[{{ $index }}][is_active]" value="0">
                            <input type="checkbox" name="date_prices[{{ $index }}][is_active]" value="1" @checked(!empty($row['is_active']))>
                            <span>Активно</span>
                        </label>
                        <button class="rounded-full border border-red-300 px-4 py-2 text-sm text-red-700" type="button" onclick="this.closest('.rounded-2xl').remove()">Удалить</button>
                    </div>
                    @foreach ($errors->get("date_prices.$index.is_active") as $message)
                        <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
                    @endforeach
                </div>
            @endforeach
        </div>
        <template id="dates-template">
            <div class="rounded-2xl border border-stone-200 p-4">
                <div class="grid gap-4 sm:grid-cols-2 2xl:grid-cols-5">
                    <input class="rounded-2xl border border-stone-300 px-4 py-3" type="date" name="date_prices[__INDEX__][start_date]">
                    <input class="rounded-2xl border border-stone-300 px-4 py-3" type="date" name="date_prices[__INDEX__][end_date]">
                    <input class="rounded-2xl border border-stone-300 px-4 py-3" type="number" step="0.01" min="0" name="date_prices[__INDEX__][price]" placeholder="Цена">
                    <input class="rounded-2xl border border-stone-300 px-4 py-3" type="text" name="date_prices[__INDEX__][currency]" value="RUB" placeholder="RUB">
                    <input class="rounded-2xl border border-stone-300 px-4 py-3" type="text" name="date_prices[__INDEX__][label]" placeholder="Майские праздники">
                </div>
                <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <label class="inline-flex items-center gap-2 text-sm text-stone-700">
                        <input type="hidden" name="date_prices[__INDEX__][is_active]" value="0">
                        <input type="checkbox" name="date_prices[__INDEX__][is_active]" value="1" checked>
                        <span>Активно</span>
                    </label>
                    <button class="rounded-full border border-red-300 px-4 py-2 text-sm text-red-700" type="button" onclick="this.closest('.rounded-2xl').remove()">Удалить</button>
                </div>
            </div>
        </template>
    </section>

    <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
        <h3 class="text-lg font-semibold">Маршрут на карте</h3>
        <div class="mt-6 grid gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
                <input class="{{ $simpleControlClass('route_title', 'rounded-2xl border border-stone-300 px-4 py-3 md:col-span-2') }}" type="text" name="route_title" value="{{ old('route_title', $routeData?->title) }}" placeholder="Название маршрута">
                @error('route_title')
                    <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <input class="{{ $simpleControlClass('route_center_lat') }}" type="number" step="0.000001" name="route_center_lat" value="{{ old('route_center_lat', $routeData?->center_lat) }}" placeholder="Широта">
                @error('route_center_lat')
                    <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <input class="{{ $simpleControlClass('route_center_lng') }}" type="number" step="0.000001" name="route_center_lng" value="{{ old('route_center_lng', $routeData?->center_lng) }}" placeholder="Долгота">
                @error('route_center_lng')
                    <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
                @enderror
            </div>
            <div class="md:col-span-2">
                <input class="{{ $simpleControlClass('route_zoom', 'rounded-2xl border border-stone-300 px-4 py-3 md:col-span-2') }}" type="number" min="1" max="20" name="route_zoom" value="{{ old('route_zoom', $routeData?->zoom ?? 8) }}" placeholder="Масштаб карты">
                @error('route_zoom')
                    <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </section>

    <div class="flex justify-end">
        <button class="rounded-full bg-stone-900 px-6 py-3 text-sm font-medium text-white" type="submit">Сохранить тур</button>
    </div>
</form>

<script>
    function addRepeaterItem(containerId, templateId) {
        const container = document.getElementById(containerId);
        const template = document.getElementById(templateId).innerHTML;
        const index = container.children.length;
        container.insertAdjacentHTML('beforeend', template.replaceAll('__INDEX__', index));
    }
</script>
