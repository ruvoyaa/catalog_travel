<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LlmGenerationArtifact extends Model
{
    protected $fillable = [
        'tour_id',
        'provider',
        'model',
        'status',
        'source_payload',
        'generated_payload',
        'generated_at',
        'applied_at',
    ];

    protected function casts(): array
    {
        return [
            'source_payload' => 'array',
            'generated_payload' => 'array',
            'generated_at' => 'datetime',
            'applied_at' => 'datetime',
        ];
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }
}
