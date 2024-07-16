<?php

namespace App\Models\Summary;

use App\Models\Category;
use App\Models\Concerns\HasCategories;
use App\Models\DataPoint;
use App\Models\DataPoint\DataPointSummary;
use App\Models\DevelopmentReport;
use App\Models\SummarySchema;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Summary extends Model
{
    use HasFactory, HasCategories;

    protected $fillable = [
        'name',
        'slug',
        'item_id',
        'location',
        'purpose',
        'creation_prompt',
        'example_summary',
        'published_at',
        'admin_id',
    ];

    public function dataPoints(): BelongsToMany
    {
        return $this->belongsToMany(
            DataPoint::class,
            DataPointSummary::class,
            'summary_id',
            'data_point_id',
        )
            ->using(DataPointSummary::class)
            ->withTimestamps();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            SummaryCategory::class,
            'summary_id',
            'category_id',
        )
            ->using(SummaryCategory::class)
            ->withTimestamps();
    }

    public function summaries(): BelongsToMany
    {
        return $this->belongsToMany(
            __CLASS__,
            SummaryLink::class,
            'summary_id',
            'linked_summary_id',
        )
            ->using(SummaryLink::class)
            ->withTimestamps();
    }

    public function schemas(): HasMany
    {
        return $this->hasMany(SummarySchema::class);
    }

    /**
     * @return BelongsToMany
     */
    public function developmentReports(): BelongsToMany
    {
        return $this->belongsToMany(DevelopmentReport::class, 'development_report_summaries')
            ->withPivot('order')
            ->orderBy('development_report_summaries.order')
            ->withTimestamps();
    }
}
