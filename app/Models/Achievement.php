<?php

namespace App\Models;

use App\Models\Achievement\AchievementCategory;
use App\Models\Admin\Admin;
use App\Models\Concerns\HasCategories;
use App\Models\DataPoint\DataPointAchievement;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Achievement extends Model
{
    use SoftDeletes, HasUuids, HasFactory, HasCategories;

    protected $fillable = [
        'slug',
        'name',
        'subtitle',
        'extraction_description',
        'example',
        'purpose',
        'color',
        'icon_path',
        'icon',
        'item_id',
        'admin_id',
        'publish_at',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function dataPoints(): BelongsToMany
    {
        return $this->belongsToMany(
            DataPoint::class,
            'data_point_achievements'
        )
            ->using(DataPointAchievement::class)
            ->withTimestamps();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            AchievementCategory::class,
        )
            ->using(AchievementCategory::class)
            ->withTimestamps();
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
