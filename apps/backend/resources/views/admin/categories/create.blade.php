@php($title = 'Новая категория')
@include('admin.layout', ['title' => $title, 'slot' => view('admin.categories.partials.form-page', [
    'heading' => 'Новая категория',
    'category' => $category,
    'action' => route('admin.categories.store'),
    'method' => 'POST',
])])
