<?php

namespace App\Models;

use App\Models\DataPoint\DataPointAchievement;
use App\Models\DataPoint\DataPointCategory;
use App\Models\DataPoint\DataPointSummary;
use App\Models\Summary\Summary;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataPoint extends Model
{
    use SoftDeletes, HasUuids;

    protected $fillable = [
        'slug',
        'name',
        'type',
        //'category',
        'development_order',
        'impact_score',
        'extraction_description',
        'example',
        'purpose',
        //'achievement_id',
    ];

    protected $casts = [
        'example' => 'json',
    ];

    public function achievement(): BelongsTo
    {
        return $this->belongsTo(Achievement::class);
    }

    public function categories(): HasManyThrough
    {
        return $this->hasManyThrough(
            Category::class,
            DataPointCategory::class,
            'data_point_id',
            'id',
            'id',
            'category_id'
        );
    }

    public function achievements(): HasManyThrough
    {
        return $this->hasManyThrough(
            Achievement::class,
            DataPointAchievement::class,
            'data_point_id',
            'id',
            'id',
            'achievement_id'
        );
    }

    public function summaries(): HasManyThrough
    {
        return $this->hasManyThrough(
            Summary::class,
            DataPointSummary::class,
            'data_point_id',
            'id',
            'id',
            'summary_id'
        );
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
