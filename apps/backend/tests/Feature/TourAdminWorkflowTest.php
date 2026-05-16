<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\TourCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TourAdminWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_tour_with_related_entities(): void
    {
        $this->signInAdmin();

        $primaryCategory = TourCategory::create([
            'slug' => 'expeditions',
            'name' => 'Expeditions',
        ]);
        $secondaryCategory = TourCategory::create([
            'slug' => 'comfort',
            'name' => 'Comfort',
        ]);

        $response = $this->post('/admin/tours', [
            'title' => 'Altai Discovery',
            'slug' => '',
            'short_description' => 'Горный маршрут с озёрами и перевалами.',
            'full_description' => 'Подробная программа путешествия по Алтаю.',
            'duration_days' => 9,
            'duration_label' => '9 дней / 8 ночей',
            'status' => 'published',
            'primary_category_id' => $primaryCategory->id,
            'categories' => [$primaryCategory->id, $secondaryCategory->id],
            'images' => [
                [
                    'image_url' => 'https://example.com/altai-cover.jpg',
                    'alt_text' => 'Алтай на рассвете',
                ],
                [
                    'image_url' => '',
                    'alt_text' => 'Пустая строка должна быть отброшена',
                ],
            ],
            'date_prices' => [
                [
                    'start_date' => '2026-06-10',
                    'end_date' => '2026-06-18',
                    'price' => '149900',
                    'currency' => 'RUB',
                    'label' => 'Июньский выезд',
                    'is_active' => '1',
                ],
                [
                    'start_date' => '',
                    'end_date' => '',
                    'price' => '',
                    'currency' => 'RUB',
                    'label' => '',
                ],
            ],
            'route_title' => 'Курайская степь',
            'route_center_lat' => '50.120000',
            'route_center_lng' => '87.950000',
            'route_zoom' => '7',
        ]);

        $tour = Tour::query()->firstOrFail();

        $response->assertRedirect(route('admin.tours.edit', $tour));
        $this->assertSame('altai-discovery', $tour->slug);
        $this->assertSame('published', $tour->status);
        $this->assertSame($primaryCategory->id, $tour->primary_category_id);

        $this->assertDatabaseHas('tours', [
            'id' => $tour->id,
            'title' => 'Altai Discovery',
            'slug' => 'altai-discovery',
            'duration_days' => 9,
            'status' => 'published',
        ]);
        $this->assertDatabaseCount('tour_images', 1);
        $this->assertDatabaseHas('tour_images', [
            'tour_id' => $tour->id,
            'image_url' => 'https://example.com/altai-cover.jpg',
            'sort_order' => 0,
        ]);
        $this->assertDatabaseCount('tour_date_prices', 1);
        $this->assertDatabaseHas('tour_date_prices', [
            'tour_id' => $tour->id,
            'start_date' => '2026-06-10 00:00:00',
            'end_date' => '2026-06-18 00:00:00',
            'currency' => 'RUB',
            'label' => 'Июньский выезд',
            'is_active' => 1,
        ]);
        $this->assertDatabaseHas('tour_routes', [
            'tour_id' => $tour->id,
            'title' => 'Курайская степь',
            'center_lat' => '50.120000',
            'center_lng' => '87.950000',
            'zoom' => 7,
        ]);
        $this->assertEqualsCanonicalizing(
            [$primaryCategory->id, $secondaryCategory->id],
            $tour->categories()->pluck('tour_categories.id')->all()
        );
    }

    public function test_admin_update_replaces_related_entities_and_can_remove_route(): void
    {
        $this->signInAdmin();

        $oldCategory = TourCategory::create([
            'slug' => 'adventure',
            'name' => 'Adventure',
        ]);
        $newCategory = TourCategory::create([
            'slug' => 'family',
            'name' => 'Family',
        ]);

        $tour = Tour::create([
            'slug' => 'baikal-camp',
            'title' => 'Baikal Camp',
            'short_description' => 'Старое описание',
            'full_description' => 'Старая программа',
            'duration_days' => 6,
            'duration_label' => '6 дней',
            'status' => 'draft',
            'primary_category_id' => $oldCategory->id,
        ]);
        $tour->categories()->attach([$oldCategory->id]);
        $tour->images()->create([
            'image_url' => 'https://example.com/old-image.jpg',
            'alt_text' => 'Старое фото',
            'sort_order' => 0,
        ]);
        $tour->datePrices()->create([
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-06',
            'price' => 99000,
            'currency' => 'RUB',
            'label' => 'Старый выезд',
            'is_active' => true,
        ]);
        $tour->route()->create([
            'title' => 'Старая точка',
            'center_lat' => 52.000000,
            'center_lng' => 104.000000,
            'zoom' => 6,
            'route_points' => null,
        ]);

        $response = $this->put(route('admin.tours.update', $tour), [
            'title' => 'Baikal Family Camp',
            'slug' => 'baikal-family-camp',
            'short_description' => 'Новое описание',
            'full_description' => 'Новая программа',
            'duration_days' => 8,
            'duration_label' => '8 дней / 7 ночей',
            'status' => 'published',
            'primary_category_id' => $newCategory->id,
            'categories' => [$newCategory->id],
            'images' => [
                [
                    'image_url' => 'https://example.com/new-image.jpg',
                    'alt_text' => 'Новое фото',
                ],
            ],
            'date_prices' => [
                [
                    'start_date' => '2026-08-12',
                    'end_date' => null,
                    'price' => '125000',
                    'currency' => 'RUB',
                    'label' => 'Август',
                ],
            ],
            'route_title' => '',
            'route_center_lat' => '',
            'route_center_lng' => '',
            'route_zoom' => '8',
        ]);

        $response->assertRedirect(route('admin.tours.edit', $tour));

        $tour->refresh();

        $this->assertDatabaseHas('tours', [
            'id' => $tour->id,
            'title' => 'Baikal Family Camp',
            'slug' => 'baikal-family-camp',
            'duration_days' => 8,
            'status' => 'published',
            'primary_category_id' => $newCategory->id,
        ]);
        $this->assertDatabaseCount('tour_images', 1);
        $this->assertDatabaseHas('tour_images', [
            'tour_id' => $tour->id,
            'image_url' => 'https://example.com/new-image.jpg',
            'alt_text' => 'Новое фото',
        ]);
        $this->assertDatabaseMissing('tour_images', [
            'tour_id' => $tour->id,
            'image_url' => 'https://example.com/old-image.jpg',
        ]);
        $this->assertDatabaseCount('tour_date_prices', 1);
        $this->assertDatabaseHas('tour_date_prices', [
            'tour_id' => $tour->id,
            'start_date' => '2026-08-12 00:00:00',
            'price' => 125000,
            'label' => 'Август',
        ]);
        $this->assertDatabaseMissing('tour_routes', [
            'tour_id' => $tour->id,
        ]);
        $this->assertSame([$newCategory->id], $tour->categories()->pluck('tour_categories.id')->all());
    }

    public function test_primary_category_is_attached_even_without_manual_category_selection(): void
    {
        $this->signInAdmin();

        $primaryCategory = TourCategory::create([
            'slug' => 'weekend',
            'name' => 'Weekend',
        ]);

        $this->post('/admin/tours', [
            'title' => 'Weekend Escape',
            'slug' => 'weekend-escape',
            'duration_days' => 3,
            'status' => 'published',
            'primary_category_id' => $primaryCategory->id,
        ])->assertRedirect();

        $tour = Tour::query()->firstOrFail();

        $this->assertSame([$primaryCategory->id], $tour->categories()->pluck('tour_categories.id')->all());
    }

    public function test_tour_form_renders_inline_validation_errors_and_old_input(): void
    {
        $this->signInAdmin();

        $response = $this->from('/admin/tours/create')
            ->followingRedirects()
            ->post('/admin/tours', [
                'title' => '',
                'slug' => 'bad-tour',
                'duration_days' => 0,
                'status' => 'published',
                'short_description' => 'Черновик с ошибками',
                'images' => [
                    [
                        'image_url' => 'not-a-url',
                        'alt_text' => str_repeat('a', 300),
                    ],
                ],
                'date_prices' => [
                    [
                        'start_date' => 'not-a-date',
                        'price' => -50,
                        'currency' => str_repeat('R', 20),
                    ],
                ],
                'route_center_lat' => 120,
                'route_center_lng' => 300,
                'route_zoom' => 99,
            ]);

        $response->assertStatus(200);
        $response->assertSee('Есть ошибки в форме:');
        $response->assertSee('Укажите название тура.');
        $response->assertSee('Укажите корректный URL изображения.');
        $response->assertSee('Цена не может быть отрицательной.');
        $response->assertSee('Черновик с ошибками');
        $response->assertSee('bad-tour');
        $response->assertSee('not-a-url');
    }

    public function test_tour_form_validates_date_price_consistency_and_route_pair(): void
    {
        $this->signInAdmin();

        $response = $this->from('/admin/tours/create')
            ->followingRedirects()
            ->post('/admin/tours', [
                'title' => 'Validation Probe',
                'slug' => 'validation-probe',
                'duration_days' => 5,
                'status' => 'draft',
                'date_prices' => [
                    [
                        'start_date' => '2026-09-10',
                        'end_date' => '2026-09-08',
                        'price' => '',
                        'currency' => 'RUB',
                        'label' => 'Сломанная строка',
                    ],
                ],
                'route_center_lat' => '55.755800',
                'route_center_lng' => '',
            ]);

        $response->assertStatus(200);
        $response->assertSee('Для заполненной строки даты и цены нужно указать цену.');
        $response->assertSee('Дата окончания не может быть раньше даты начала.');
        $response->assertSee('Для маршрута нужно указать и широту, и долготу.');
    }
}
