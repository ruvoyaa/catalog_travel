@php($title = 'Категории')
@include('admin.layout', ['title' => $title, 'slot' => view('admin.categories.partials.index-content', compact('categories'))])
