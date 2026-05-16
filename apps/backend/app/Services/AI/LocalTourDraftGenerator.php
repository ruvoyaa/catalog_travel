<?php

namespace App\Services\AI;

use App\Models\Tour;

class LocalTourDraftGenerator
{
    public function provider(): string
    {
        return 'local';
    }

    public function model(): string
    {
        return 'tour-draft-template-v1';
    }

    public function generate(Tour $tour): array
    {
        $tour->loadMissing(['primaryCategory', 'categories', 'route']);

        $categoryLine = $tour->categories->pluck('name')
            ->push($tour->primaryCategory?->name)
            ->filter()
            ->unique()
            ->implode(', ');

        $duration = $tour->duration_label ?: $tour->duration_days.' дн.';
        $routeTitle = $tour->route?->title ?: 'основные точки маршрута';

        return [
            'short_description' => trim("{$tour->title} — маршрут формата {$duration} с фокусом на {$routeTitle}."),
            'full_description' => trim(implode("\n\n", array_filter([
                "{$tour->title} — готовый черновик описания для каталога.",
                $categoryLine ? "Категории: {$categoryLine}." : null,
                "Продолжительность: {$duration}.",
                "Маршрут построен вокруг {$routeTitle} и рассчитан на публикацию после ручной редакторской доработки.",
                "Этот текст сгенерирован локальным MVP-пайплайном Stage 03 и предназначен как рабочий draft, а не финальная публикация.",
            ]))),
        ];
    }
}
