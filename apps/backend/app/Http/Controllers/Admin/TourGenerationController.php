<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LlmGenerationArtifact;
use App\Models\Tour;
use App\Services\AI\LocalTourDraftGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;

class TourGenerationController extends Controller
{
    public function generate(Tour $tour, LocalTourDraftGenerator $generator): RedirectResponse
    {
        $payload = $generator->generate($tour);

        LlmGenerationArtifact::create([
            'tour_id' => $tour->id,
            'provider' => $generator->provider(),
            'model' => $generator->model(),
            'status' => 'generated',
            'source_payload' => [
                'title' => $tour->title,
                'duration_days' => $tour->duration_days,
                'primary_category' => $tour->primaryCategory?->name,
            ],
            'generated_payload' => $payload,
            'generated_at' => Carbon::now(),
        ]);

        return redirect()
            ->route('admin.tours.edit', $tour)
            ->with('status', 'Черновик описания сгенерирован.');
    }

    public function apply(Tour $tour, LlmGenerationArtifact $artifact): RedirectResponse
    {
        abort_unless($artifact->tour_id === $tour->id, 404);

        $tour->update([
            'short_description' => $artifact->generated_payload['short_description'] ?? $tour->short_description,
            'full_description' => $artifact->generated_payload['full_description'] ?? $tour->full_description,
        ]);

        $artifact->update([
            'status' => 'applied',
            'applied_at' => Carbon::now(),
        ]);

        return redirect()
            ->route('admin.tours.edit', $tour)
            ->with('status', 'Черновик применён к туру.');
    }
}
