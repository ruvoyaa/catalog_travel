<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TourCategoryRequest;
use App\Models\TourCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TourCategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => TourCategory::withCount('tours')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.create', [
            'category' => new TourCategory(),
        ]);
    }

    public function store(TourCategoryRequest $request): RedirectResponse
    {
        TourCategory::create($request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Категория создана.');
    }

    public function edit(TourCategory $category): View
    {
        return view('admin.categories.edit', [
            'category' => $category,
        ]);
    }

    public function update(TourCategoryRequest $request, TourCategory $category): RedirectResponse
    {
        $category->update($request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Категория обновлена.');
    }

    public function destroy(TourCategory $category): RedirectResponse
    {
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Категория удалена.');
    }
}
