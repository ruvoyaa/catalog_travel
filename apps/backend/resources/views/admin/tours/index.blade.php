@php($title = 'Туры')
@include('admin.layout', ['title' => $title, 'slot' => view('admin.tours.partials.index-content', compact('tours'))])
