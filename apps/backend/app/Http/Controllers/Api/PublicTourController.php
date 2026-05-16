<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\TourCategory;
use App\Services\AI\TourEmbeddingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicTourController extends Controller
{
    public function index(Request $request, TourEmbeddingService $embeddingService): JsonResponse
    {
        $query = Tour::query()
            ->with(['primaryCategory', 'categories', 'images', 'datePrices', 'route', 'embedding'])
            ->where('status', 'published');

        if ($request->filled('category')) {
            $category = (string) $request->query('category');
            $query->where(function ($builder) use ($category): void {
                $builder
                    ->whereHas('categories', fn ($relation) => $relation->where('slug', $category))
                    ->orWhereHas('primaryCategory', fn ($relation) => $relation->where('slug', $category));
            });
        }

        if ($request->filled('duration')) {
            $query->where('duration_days', (int) $request->query('duration'));
        }

        $tours = $query->get();

        if ($request->filled('q')) {
            $search = trim((string) $request->query('q'));
            $queryEmbedding = $embeddingService->queryEmbedding($search);
            $normalizedSearch = str_replace('ё', 'е', mb_strtolower($search));
            $searchTerms = array_values(array_filter(preg_split('/\s+/u', $normalizedSearch) ?: []));

            $tours = $tours
                ->map(function (Tour $tour) use ($embeddingService, $searchTerms, $queryEmbedding) {
                    $haystack = str_replace('ё', 'е', mb_strtolower(implode(' ', array_filter([
                        $tour->title,
                        $tour->short_description,
                        $tour->full_description,
                    ]))));
                    $termBoost = collect($searchTerms)
                        ->sum(fn (string $term) => str_contains($haystack, $term) ? 0.45 : 0.0);
                    $tour->semantic_score = $embeddingService->similarity($queryEmbedding, $tour->embedding) + $termBoost;

                    return $tour;
                })
                ->filter(fn (Tour $tour) => $tour->semantic_score > 0.05)
                ->sortByDesc('semantic_score')
                ->values();
        } else {
            $tours = $tours->sortBy('title')->values();
        }

        $categories = TourCategory::query()
            ->orderBy('name')
            ->get()
            ->map(fn (TourCategory $category) => [
                'name' => $category->name,
                'slug' => $category->slug,
                'tourCount' => Tour::query()
                    ->where('status', 'published')
                    ->where(function ($builder) use ($category): void {
                        $builder
                            ->where('primary_category_id', $category->id)
                            ->orWhereHas('categories', fn ($relation) => $relation->where('tour_categories.id', $category->id));
                    })
                    ->count(),
            ]);

        return response()->json([
            'filters' => [
                'q' => (string) $request->query('q', ''),
                'category' => (string) $request->query('category', ''),
                'duration' => $request->query('duration'),
                'mode' => $request->filled('q') ? 'semantic' : 'catalog',
            ],
            'categories' => $categories,
            'tours' => $tours->map(fn (Tour $tour) => $this->catalogPayload($tour)),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $tour = Tour::query()
            ->with(['primaryCategory', 'categories', 'images', 'datePrices', 'route'])
            ->where('status', 'published')
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'tour' => $this->detailPayload($tour),
        ]);
    }

    private function catalogPayload(Tour $tour): array
    {
        $cover = $tour->images->first();
        $minPrice = $tour->datePrices
            ->where('is_active', true)
            ->min('price');

        return [
            'title' => $tour->title,
            'slug' => $tour->slug,
            'shortDescription' => $tour->short_description,
            'durationDays' => $tour->duration_days,
            'durationLabel' => $tour->duration_label ?: "{$tour->duration_days} дн.",
            'status' => $tour->status,
            'coverImageUrl' => $cover?->image_url,
            'coverImageAlt' => $cover?->alt_text,
            'minPrice' => $minPrice,
            'primaryCategory' => $tour->primaryCategory?->name,
            'categories' => $tour->categories->map(fn ($category) => [
                'name' => $category->name,
                'slug' => $category->slug,
            ])->values(),
        ];
    }

    private function detailPayload(Tour $tour): array
    {
        return [
            ...$this->catalogPayload($tour),
            'fullDescription' => $tour->full_description,
            'images' => $tour->images->map(fn ($image) => [
                'url' => $image->image_url,
                'alt' => $image->alt_text,
            ])->values(),
            'datePrices' => $tour->datePrices->map(fn ($datePrice) => [
                'startDate' => $datePrice->start_date?->toDateString(),
                'endDate' => $datePrice->end_date?->toDateString(),
                'price' => $datePrice->price,
                'currency' => $datePrice->currency,
                'label' => $datePrice->label,
                'isActive' => $datePrice->is_active,
            ])->values(),
            'route' => $tour->route ? [
                'title' => $tour->route->title,
                'centerLat' => $tour->route->center_lat,
                'centerLng' => $tour->route->center_lng,
                'zoom' => $tour->route->zoom,
                'staticMapUrl' => $this->yandexStaticMapUrl(
                    (float) $tour->route->center_lat,
                    (float) $tour->route->center_lng,
                    (int) $tour->route->zoom
                ),
            ] : null,
        ];
    }

    private function yandexStaticMapUrl(float $lat, float $lng, int $zoom): string
    {
        $coords = "{$lng},{$lat}";

        return "https://static-maps.yandex.ru/1.x/?lang=ru_RU&ll={$coords}&z={$zoom}&size=650,320&l=map&pt={$coords},pm2rdm";
    }
}
