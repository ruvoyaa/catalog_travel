<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\TourCategory;
use App\Models\TourDatePrice;
use App\Models\TourImage;
use App\Models\TourRoute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TourApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_api_returns_only_published_tours(): void
    {
        $category = TourCategory::create([
            'slug' => 'adventure',
            'name' => 'Adventure',
        ]);

        $publishedTour = Tour::create([
            'slug' => 'altai-escape',
            'title' => 'Altai Escape',
            'duration_days' => 7,
            'status' => 'published',
            'primary_category_id' => $category->id,
        ]);
        $publishedTour->categories()->attach([$category->id]);
        TourImage::create([
            'tour_id' => $publishedTour->id,
            'image_url' => 'https://example.com/altai.jpg',
            'sort_order' => 0,
        ]);
        TourDatePrice::create([
            'tour_id' => $publishedTour->id,
            'start_date' => '2026-06-01',
            'price' => 120000,
            'currency' => 'RUB',
            'is_active' => true,
        ]);
        TourRoute::create([
            'tour_id' => $publishedTour->id,
            'center_lat' => 50.008000,
            'center_lng' => 88.140000,
            'zoom' => 8,
        ]);

        Tour::create([
            'slug' => 'draft-tour',
            'title' => 'Draft Tour',
            'duration_days' => 4,
            'status' => 'draft',
        ]);

        $response = $this->getJson('/api/tours');

        $response->assertOk();
        $response->assertJsonCount(1, 'tours');
        $response->assertJsonPath('tours.0.slug', 'altai-escape');
    }

    public function test_public_api_filters_by_category_slug(): void
    {
        $adventure = TourCategory::create([
            'slug' => 'adventure',
            'name' => 'Adventure',
        ]);
        $family = TourCategory::create([
            'slug' => 'family',
            'name' => 'Family',
        ]);

        $tourOne = Tour::create([
            'slug' => 'baikal',
            'title' => 'Baikal',
            'duration_days' => 5,
            'status' => 'published',
        ]);
        $tourOne->categories()->attach([$adventure->id]);

        $tourTwo = Tour::create([
            'slug' => 'sochi',
            'title' => 'Sochi',
            'duration_days' => 5,
            'status' => 'published',
        ]);
        $tourTwo->categories()->attach([$family->id]);

        $response = $this->getJson('/api/tours?category=adventure');

        $response->assertOk();
        $response->assertJsonCount(1, 'tours');
        $response->assertJsonPath('tours.0.slug', 'baikal');
    }

    public function test_public_api_category_filter_and_counts_include_primary_category(): void
    {
        $weekend = TourCategory::create([
            'slug' => 'weekend',
            'name' => 'Weekend',
        ]);

        Tour::create([
            'slug' => 'tula-weekend',
            'title' => 'Tula Weekend',
            'duration_days' => 2,
            'status' => 'published',
            'primary_category_id' => $weekend->id,
        ]);

        $response = $this->getJson('/api/tours?category=weekend');

        $response->assertOk();
        $response->assertJsonCount(1, 'tours');
        $response->assertJsonPath('tours.0.slug', 'tula-weekend');
        $response->assertJsonPath('categories.0.slug', 'weekend');
        $response->assertJsonPath('categories.0.tourCount', 1);
    }
}
