@php($title = 'Dashboard')
@include('admin.layout', ['title' => $title, 'slot' => view('admin.partials.dashboard-content', compact('tourCount', 'publishedTourCount', 'categoryCount', 'embeddingCount'))])
