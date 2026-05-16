<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourEmbedding extends Model
{
    protected $fillable = [
        'tour_id',
        'provider',
        'model',
        'dimensions',
        'source_text',
        'embedding',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'embedding' => 'array',
            'generated_at' => 'datetime',
        ];
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }
}
