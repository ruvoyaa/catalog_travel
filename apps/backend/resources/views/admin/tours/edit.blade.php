@php($title = 'Редактирование тура')
@include('admin.layout', ['title' => $title, 'slot' => '
    <div class="space-y-6">
        '.view('admin.tours.partials.ai-panel', [
            'tour' => $tour,
        ])->render().'
        '.view('admin.tours.partials.form-page', [
            'heading' => 'Редактирование тура',
            'tour' => $tour,
            'categories' => $categories,
            'action' => route('admin.tours.update', $tour),
            'method' => 'PUT',
        ])->render().'
    </div>
'])
