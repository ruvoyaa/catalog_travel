<?php

namespace App\Services\AI;

use App\Models\Tour;
use App\Models\TourEmbedding;
use Illuminate\Support\Carbon;

class TourEmbeddingService
{
    public function __construct(
        private readonly LocalEmbeddingService $embeddingService,
    ) {
    }

    public function rebuildForTour(Tour $tour): TourEmbedding
    {
        $tour->loadMissing(['primaryCategory', 'categories', 'datePrices', 'route']);

        return TourEmbedding::updateOrCreate(
            ['tour_id' => $tour->id],
            [
                'provider' => $this->embeddingService->provider(),
                'model' => $this->embeddingService->model(),
                'dimensions' => $this->embeddingService->dimensions(),
                'source_text' => $this->buildSourceText($tour),
                'embedding' => $this->embeddingService->embed($this->buildSourceText($tour)),
                'generated_at' => Carbon::now(),
            ]
        );
    }

    public function buildSourceText(Tour $tour): string
    {
        $categoryNames = $tour->categories->pluck('name')
            ->push($tour->primaryCategory?->name)
            ->filter()
            ->unique()
            ->implode(' ');

        $dateLabels = $tour->datePrices->pluck('label')
            ->filter()
            ->implode(' ');

        return trim(implode("\n", array_filter([
            $tour->title,
            $tour->short_description,
            $tour->full_description,
            $tour->duration_label,
            $categoryNames,
            $tour->route?->title,
            $dateLabels,
        ])));
    }

    public function queryEmbedding(string $query): array
    {
        return $this->embeddingService->embed($query);
    }

    public function similarity(array $queryEmbedding, ?TourEmbedding $tourEmbedding): float
    {
        if (!$tourEmbedding) {
            return 0.0;
        }

        return $this->embeddingService->cosineSimilarity($queryEmbedding, $tourEmbedding->embedding ?? []);
    }
}
