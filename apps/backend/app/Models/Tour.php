<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tour extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'short_description',
        'full_description',
        'duration_days',
        'duration_label',
        'status',
        'primary_category_id',
    ];

    public function primaryCategory(): BelongsTo
    {
        return $this->belongsTo(TourCategory::class, 'primary_category_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(TourCategory::class, 'tour_category_links');
    }

    public function images(): HasMany
    {
        return $this->hasMany(TourImage::class)->orderBy('sort_order');
    }

    public function datePrices(): HasMany
    {
        return $this->hasMany(TourDatePrice::class)->orderBy('start_date');
    }

    public function route(): HasOne
    {
        return $this->hasOne(TourRoute::class);
    }

    public function embedding(): HasOne
    {
        return $this->hasOne(TourEmbedding::class);
    }

    public function generationArtifacts(): HasMany
    {
        return $this->hasMany(LlmGenerationArtifact::class)->latest();
    }
}
