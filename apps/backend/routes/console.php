<?php

use App\Models\Tour;
use App\Services\AI\TourEmbeddingService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('tours:rebuild-embeddings', function (TourEmbeddingService $embeddingService) {
    $count = 0;

    Tour::query()->with(['primaryCategory', 'categories', 'datePrices', 'route'])->chunk(100, function ($tours) use ($embeddingService, &$count): void {
        foreach ($tours as $tour) {
            $embeddingService->rebuildForTour($tour);
            $count++;
        }
    });

    $this->info("Embeddings rebuilt: {$count}");
})->purpose('Rebuild local embeddings for every tour');
