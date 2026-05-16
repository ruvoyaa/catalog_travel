<?php

namespace Tests\Feature;

use App\Models\LlmGenerationArtifact;
use App\Models\Tour;
use App\Models\TourCategory;
use App\Services\AI\TourEmbeddingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StageThreeWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_semantic_search_returns_relevant_tours_first(): void
    {
        $category = TourCategory::create([
            'slug' => 'nature',
            'name' => 'Nature',
        ]);

        $baikal = Tour::create([
            'slug' => 'baikal-ice',
            'title' => 'Baikal Ice Journey',
            'short_description' => 'Лёд Байкала, прозрачные гроты и зимний маршрут.',
            'duration_days' => 5,
            'status' => 'published',
            'primary_category_id' => $category->id,
        ]);
        $baikal->categories()->attach([$category->id]);

        $city = Tour::create([
            'slug' => 'moscow-weekend',
            'title' => 'Moscow Weekend',
            'short_description' => 'Городской уикенд с музеями и гастрономией.',
            'duration_days' => 2,
            'status' => 'published',
        ]);

        $embeddingService = app(TourEmbeddingService::class);
        $embeddingService->rebuildForTour($baikal);
        $embeddingService->rebuildForTour($city);

        $response = $this->getJson('/api/tours?q=лед байкала');

        $response->assertOk();
        $response->assertJsonPath('filters.mode', 'semantic');
        $response->assertJsonPath('tours.0.slug', 'baikal-ice');
    }

    public function test_embedding_rebuild_command_creates_embeddings(): void
    {
        Tour::create([
            'slug' => 'kamchatka-volcano',
            'title' => 'Kamchatka Volcano',
            'short_description' => 'Вулканы и термальные долины.',
            'duration_days' => 8,
            'status' => 'published',
        ]);

        $this->artisan('tours:rebuild-embeddings')
            ->expectsOutput('Embeddings rebuilt: 1')
            ->assertExitCode(0);

        $this->assertDatabaseCount('tour_embeddings', 1);
    }

    public function test_admin_can_generate_and_apply_local_draft(): void
    {
        $this->signInAdmin();

        $tour = Tour::create([
            'slug' => 'altai-hike',
            'title' => 'Altai Hike',
            'duration_days' => 6,
            'status' => 'draft',
        ]);

        $this->post(route('admin.tours.generate-draft', $tour))
            ->assertRedirect(route('admin.tours.edit', $tour));

        $artifact = LlmGenerationArtifact::query()->firstOrFail();

        $this->assertSame($tour->id, $artifact->tour_id);
        $this->assertNotEmpty($artifact->generated_payload['short_description'] ?? null);
        $this->assertNotEmpty($artifact->generated_payload['full_description'] ?? null);

        $this->post(route('admin.tours.artifacts.apply', [$tour, $artifact]))
            ->assertRedirect(route('admin.tours.edit', $tour));

        $tour->refresh();
        $artifact->refresh();

        $this->assertSame($artifact->generated_payload['short_description'], $tour->short_description);
        $this->assertSame($artifact->generated_payload['full_description'], $tour->full_description);
        $this->assertSame('applied', $artifact->status);
        $this->assertNotNull($artifact->applied_at);
    }
}
