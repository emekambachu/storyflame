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
        'element',
        'subtitle',
        'extraction_description',
        'example',
        'purpose',
        'color',
        'icon'
    ];

    public function dataPoints(): HasMany
    {
        return $this->hasMany(DataPoint::class);
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
            'id' => $this->slug,
            'name' => $this->name,
            'extraction_description' => $this->extraction_description,
            'category' => $this->element,
            'data_points' => $this->dataPoints
                ->filter(fn($data_point) => !in_array($data_point->slug, $except_data_points))
                ->map
                ->toProcessingArray()
                ->values()
                ->toArray()
        ];
    }
}
