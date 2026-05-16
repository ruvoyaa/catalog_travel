<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TourCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (!$this->filled('slug') && $this->filled('name')) {
            $this->merge([
                'slug' => Str::slug((string) $this->input('name')),
            ]);
        }
    }

    public function rules(): array
    {
        $categoryId = $this->route('category')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tour_categories', 'slug')->ignore($categoryId),
            ],
            'description' => ['nullable', 'string'],
        ];
    }
}
