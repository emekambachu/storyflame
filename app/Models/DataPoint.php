<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataPoint extends Model
{
    use SoftDeletes, HasUuids;

    protected $fillable = [
        'slug',
        'name',
        'category',
        'development_order',
        'impact_score',
        'extraction_description',
        'example',
        'purpose',
        'achievement_id',
    ];

    protected $casts = [
        'example' => 'json',
    ];

    public function achievement(): BelongsTo
    {
        return $this->belongsTo(Achievement::class);
    }

    /**
     * Return a formatted array for processing
     * @return array
     */
    public function toProcessingArray(): array
    {
        return [
            'name' => $this->slug,
            'title' => $this->name,
            'type' => $this->type ?? 'text',
            'description' => $this->extraction_description,
        ];
    }
}
