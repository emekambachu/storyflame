<?php

namespace App\Models;

use App\Models\DataPoint\DataPointAchievement;
use App\Models\DataPoint\DataPointCategory;
use App\Models\DataPoint\DataPointSummary;
use App\Models\Summary\Summary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataPoint extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'development_order',
        'impact_score',
        'estimated_seconds',
        'type',
        'extraction_description',
        'example',
        'purpose',
        'admin_id',
    ];

    protected $casts = [
        'example' => 'json',
    ];

    public function achievement(): BelongsTo
    {
        return $this->belongsTo(Achievement::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            DataPointCategory::class
        )
            ->using(DataPointCategory::class)
            ->withTimestamps();
    }

    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(
            Achievement::class,
            DataPointAchievement::class
        )
            ->using(DataPointAchievement::class)
            ->withTimestamps();
    }

    public function summaries(): BelongsToMany
    {
        return $this->belongsToMany(
            Summary::class,
            DataPointSummary::class,
        )
            ->using(DataPointSummary::class)
            ->withTimestamps();
    }

    /**
     * Return a formatted array for processing
     * @return array
     */
    public function toProcessingArray(): array
    {
        return [
            'id' => $this->slug,
            'name' => $this->name,
            'extraction_description' => $this->extraction_description,
            'type' => $this->type ?? 'text',
        ];
    }
}
