<div class="mb-6">
    <a class="text-sm text-stone-500 underline" href="{{ route('admin.categories.index') }}">← К списку категорий</a>
    <h2 class="mt-3 text-3xl font-semibold">{{ $heading }}</h2>
</div>

<form method="POST" action="{{ $action }}" class="space-y-6 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="grid gap-6 md:grid-cols-2">
        <label class="block">
            <span class="mb-2 block text-sm font-medium text-stone-700">Название</span>
            <input class="w-full rounded-2xl border border-stone-300 px-4 py-3" type="text" name="name" value="{{ old('name', $category->name) }}" required>
        </label>

        <label class="block">
            <span class="mb-2 block text-sm font-medium text-stone-700">Slug</span>
            <input class="w-full rounded-2xl border border-stone-300 px-4 py-3" type="text" name="slug" value="{{ old('slug', $category->slug) }}">
        </label>
    </div>

    <label class="block">
        <span class="mb-2 block text-sm font-medium text-stone-700">Описание</span>
        <textarea class="min-h-32 w-full rounded-2xl border border-stone-300 px-4 py-3" name="description">{{ old('description', $category->description) }}</textarea>
    </label>

    <div class="flex justify-end">
        <button class="rounded-full bg-stone-900 px-6 py-3 text-sm font-medium text-white" type="submit">Сохранить</button>
    </div>
</form>
