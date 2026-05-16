<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourRoute extends Model
{
    protected $fillable = [
        'tour_id',
        'title',
        'center_lat',
        'center_lng',
        'zoom',
        'route_points',
    ];

    protected function casts(): array
    {
        return [
            'center_lat' => 'decimal:6',
            'center_lng' => 'decimal:6',
            'zoom' => 'integer',
            'route_points' => 'array',
        ];
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }
}
