<?php

namespace App\Models\Summary;

use App\Models\Category;
use App\Models\Concerns\HasCategories;
use App\Models\DataPoint;
use App\Models\DataPoint\DataPointSummary;
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
    ];

    public function dataPoints(): BelongsToMany
    {
        return $this->belongsToMany(
            DataPoint::class,
            DataPointSummary::class,
        )
            ->using(DataPointSummary::class)
            ->withTimestamps();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            SummaryCategory::class
        )
            ->using(SummaryCategory::class)
            ->withTimestamps();
    }

    public function summaries(): HasMany
    {
        return $this->hasMany(__CLASS__, 'primary_summary_id', 'linked_summary_id');
    }
}
