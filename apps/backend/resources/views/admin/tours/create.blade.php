@php($title = 'Новый тур')
@include('admin.layout', ['title' => $title, 'slot' => view('admin.tours.partials.form-page', [
    'heading' => 'Новый тур',
    'tour' => $tour,
    'categories' => $categories,
    'action' => route('admin.tours.store'),
    'method' => 'POST',
])])
