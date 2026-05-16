<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TourCategory extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'description',
    ];

    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, 'tour_category_links');
    }

    public function primaryTours(): HasMany
    {
        return $this->hasMany(Tour::class, 'primary_category_id');
    }
}
