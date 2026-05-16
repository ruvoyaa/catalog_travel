@php($title = 'Редактирование категории')
@include('admin.layout', ['title' => $title, 'slot' => view('admin.categories.partials.form-page', [
    'heading' => 'Редактирование категории',
    'category' => $category,
    'action' => route('admin.categories.update', $category),
    'method' => 'PUT',
])])
