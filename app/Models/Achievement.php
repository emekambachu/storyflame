<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Achievement extends Model
{
    use SoftDeletes, HasUuids, HasFactory;

    protected $fillable = [
        'slug',
        'name',
        //'element',
        'subtitle',
        'extraction_description',
        'example',
        'purpose',
        'color',
        'icon_path',
        'icon',
        'item_id',
        'publish_at',
    ];

    public function dataPoints(): HasMany
    {
        return $this->hasMany(DataPoint::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'achievement_id', 'id');
    }

    public function totalImpactScore(): int
    {
        return $this->dataPoints->sum('impact_score');
    }

    /**
     * Return a formatted array for processing
     * @param array $except_data_points Data points slugs to exclude
     * @return array
     */
    public function toProcessingArray(array $except_data_points = []): array
    {
        return [
            'name' => $this->slug,
            'title' => $this->name,
            'description' => $this->extraction_description,
            'data_points' => $this->dataPoints
                ->filter(fn($data_point) => !in_array($data_point->slug, $except_data_points))
                ->map
                ->toProcessingArray()
                ->values()
                ->toArray()
        ];
    }
}
