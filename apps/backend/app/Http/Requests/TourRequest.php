<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class TourRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (!$this->filled('slug') && $this->filled('title')) {
            $this->merge([
                'slug' => Str::slug((string) $this->input('title')),
            ]);
        }

        $datePrices = collect($this->input('date_prices', []))
            ->map(function (array $row): array {
                $row['is_active'] = Arr::has($row, 'is_active') && $row['is_active'];

                return $row;
            })
            ->all();

        $this->merge([
            'date_prices' => $datePrices,
        ]);
    }

    public function rules(): array
    {
        $tourId = $this->route('tour')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tours', 'slug')->ignore($tourId),
            ],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'full_description' => ['nullable', 'string'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:365'],
            'duration_label' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'primary_category_id' => ['nullable', 'exists:tour_categories,id'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', 'exists:tour_categories,id'],
            'images' => ['nullable', 'array'],
            'images.*.image_url' => ['nullable', 'url', 'max:2048'],
            'images.*.alt_text' => ['nullable', 'string', 'max:255'],
            'date_prices' => ['nullable', 'array'],
            'date_prices.*.start_date' => ['nullable', 'date'],
            'date_prices.*.end_date' => ['nullable', 'date'],
            'date_prices.*.price' => ['nullable', 'numeric', 'min:0'],
            'date_prices.*.currency' => ['nullable', 'string', 'max:10'],
            'date_prices.*.label' => ['nullable', 'string', 'max:255'],
            'date_prices.*.is_active' => ['nullable', 'boolean'],
            'route_title' => ['nullable', 'string', 'max:255'],
            'route_center_lat' => ['nullable', 'numeric', 'between:-90,90'],
            'route_center_lng' => ['nullable', 'numeric', 'between:-180,180'],
            'route_zoom' => ['nullable', 'integer', 'min:1', 'max:20'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $this->validateDatePrices($validator);
                $this->validateRouteFields($validator);
            },
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'название',
            'slug' => 'slug',
            'duration_days' => 'длительность',
            'duration_label' => 'подпись длительности',
            'short_description' => 'короткое описание',
            'full_description' => 'полное описание',
            'primary_category_id' => 'основная категория',
            'route_title' => 'название маршрута',
            'route_center_lat' => 'широта маршрута',
            'route_center_lng' => 'долгота маршрута',
            'route_zoom' => 'zoom маршрута',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Укажите название тура.',
            'slug.required' => 'Укажите slug тура.',
            'slug.unique' => 'Slug тура должен быть уникальным.',
            'duration_days.required' => 'Укажите длительность тура.',
            'duration_days.min' => 'Длительность тура должна быть не меньше 1 дня.',
            'status.required' => 'Укажите статус тура.',
            'status.in' => 'Статус тура должен быть draft или published.',
            'images.*.image_url.url' => 'Укажите корректный URL изображения.',
            'images.*.alt_text.max' => 'Alt text изображения не должен быть длиннее 255 символов.',
            'date_prices.*.start_date.date' => 'Дата начала должна быть корректной датой.',
            'date_prices.*.end_date.date' => 'Дата окончания должна быть корректной датой.',
            'date_prices.*.price.min' => 'Цена не может быть отрицательной.',
            'date_prices.*.currency.max' => 'Код валюты не должен быть длиннее 10 символов.',
            'route_center_lat.between' => 'Широта маршрута должна быть в диапазоне от -90 до 90.',
            'route_center_lng.between' => 'Долгота маршрута должна быть в диапазоне от -180 до 180.',
            'route_zoom.min' => 'Zoom маршрута должен быть не меньше 1.',
            'route_zoom.max' => 'Zoom маршрута должен быть не больше 20.',
        ];
    }

    private function validateDatePrices(Validator $validator): void
    {
        foreach (collect($this->input('date_prices', [])) as $index => $row) {
            if (!is_array($row)) {
                continue;
            }

            $hasAnyValue = collect([
                $row['start_date'] ?? null,
                $row['end_date'] ?? null,
                $row['price'] ?? null,
                $row['label'] ?? null,
            ])->contains(fn ($value) => filled($value));

            if (!$hasAnyValue) {
                continue;
            }

            if (!filled($row['start_date'] ?? null)) {
                $validator->errors()->add("date_prices.$index.start_date", 'Для заполненной строки даты и цены нужно указать дату начала.');
            }

            if (!filled($row['price'] ?? null)) {
                $validator->errors()->add("date_prices.$index.price", 'Для заполненной строки даты и цены нужно указать цену.');
            }

            if (filled($row['start_date'] ?? null) && filled($row['end_date'] ?? null)) {
                $start = strtotime((string) $row['start_date']);
                $end = strtotime((string) $row['end_date']);

                if ($start !== false && $end !== false && $end < $start) {
                    $validator->errors()->add("date_prices.$index.end_date", 'Дата окончания не может быть раньше даты начала.');
                }
            }
        }
    }

    private function validateRouteFields(Validator $validator): void
    {
        $lat = $this->input('route_center_lat');
        $lng = $this->input('route_center_lng');

        if (filled($lat) && !filled($lng)) {
            $validator->errors()->add('route_center_lng', 'Для маршрута нужно указать и широту, и долготу.');
        }

        if (!filled($lat) && filled($lng)) {
            $validator->errors()->add('route_center_lat', 'Для маршрута нужно указать и широту, и долготу.');
        }
    }
}
