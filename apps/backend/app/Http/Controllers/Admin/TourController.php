<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TourRequest;
use App\Models\Tour;
use App\Models\TourCategory;
use App\Services\AI\TourEmbeddingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TourController extends Controller
{
    public function index(): View
    {
        return view('admin.tours.index', [
            'tours' => Tour::with(['primaryCategory', 'datePrices'])
                ->latest()
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.tours.create', [
            'tour' => new Tour([
                'status' => 'draft',
                'duration_days' => 7,
            ]),
            'categories' => TourCategory::orderBy('name')->get(),
        ]);
    }

    public function store(TourRequest $request, TourEmbeddingService $embeddingService): RedirectResponse
    {
        $tour = DB::transaction(function () use ($request, $embeddingService) {
            $tour = Tour::create($this->tourAttributes($request->validated()));
            $this->syncRelatedData($tour, $request->validated());
            $embeddingService->rebuildForTour($tour);

            return $tour;
        });

        return redirect()
            ->route('admin.tours.edit', $tour)
            ->with('status', 'Тур создан.');
    }

    public function edit(Tour $tour): View
    {
        $tour->load(['categories', 'images', 'datePrices', 'route', 'embedding', 'generationArtifacts']);

        return view('admin.tours.edit', [
            'tour' => $tour,
            'categories' => TourCategory::orderBy('name')->get(),
        ]);
    }

    public function update(TourRequest $request, Tour $tour, TourEmbeddingService $embeddingService): RedirectResponse
    {
        DB::transaction(function () use ($request, $tour, $embeddingService): void {
            $tour->update($this->tourAttributes($request->validated()));
            $this->syncRelatedData($tour, $request->validated());
            $embeddingService->rebuildForTour($tour);
        });

        return redirect()
            ->route('admin.tours.edit', $tour)
            ->with('status', 'Тур обновлён.');
    }

    public function destroy(Tour $tour): RedirectResponse
    {
        $tour->delete();

        return redirect()
            ->route('admin.tours.index')
            ->with('status', 'Тур удалён.');
    }

    private function tourAttributes(array $data): array
    {
        return [
            'title' => $data['title'],
            'slug' => $data['slug'],
            'short_description' => $data['short_description'] ?? null,
            'full_description' => $data['full_description'] ?? null,
            'duration_days' => $data['duration_days'],
            'duration_label' => $data['duration_label'] ?? null,
            'status' => $data['status'],
            'primary_category_id' => $data['primary_category_id'] ?? null,
        ];
    }

    private function syncRelatedData(Tour $tour, array $data): void
    {
        $categoryIds = collect($data['categories'] ?? []);

        if (filled($data['primary_category_id'] ?? null)) {
            $categoryIds->push((int) $data['primary_category_id']);
        }

        $tour->categories()->sync($categoryIds->filter()->unique()->values()->all());

        $tour->images()->delete();
        foreach (collect($data['images'] ?? [])->filter(fn (array $row) => filled($row['image_url'] ?? null))->values() as $index => $row) {
            $tour->images()->create([
                'image_url' => $row['image_url'],
                'alt_text' => $row['alt_text'] ?? null,
                'sort_order' => $index,
            ]);
        }

        $tour->datePrices()->delete();
        foreach (collect($data['date_prices'] ?? [])->filter(fn (array $row) => filled($row['start_date'] ?? null) && filled($row['price'] ?? null))->values() as $row) {
            $tour->datePrices()->create([
                'start_date' => $row['start_date'],
                'end_date' => $row['end_date'] ?? null,
                'price' => $row['price'],
                'currency' => $row['currency'] ?? 'RUB',
                'label' => $row['label'] ?? null,
                'is_active' => (bool) ($row['is_active'] ?? false),
            ]);
        }

        $hasRouteData = filled($data['route_center_lat'] ?? null) && filled($data['route_center_lng'] ?? null);
        if ($hasRouteData) {
            $tour->route()->updateOrCreate([], [
                'title' => $data['route_title'] ?? null,
                'center_lat' => $data['route_center_lat'],
                'center_lng' => $data['route_center_lng'],
                'zoom' => $data['route_zoom'] ?? 8,
                'route_points' => null,
            ]);
        } else {
            $tour->route()?->delete();
        }
    }
}
