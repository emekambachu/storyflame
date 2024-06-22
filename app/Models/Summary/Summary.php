<?php

namespace App\Models\Summary;

use App\Models\Category;
use App\Models\DataPoint;
use App\Models\DataPoint\DataPointCategory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Summary extends Model
{
    use SoftDeletes, HasUuids, HasFactory;
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
            'data_point_summaries',
            'summary_id',
            'data_point_id'
        );
    }

    public function categories(): HasManyThrough
    {
        return $this->hasManyThrough(
            Category::class,
            SummaryCategory::class,
            'summary_id',
            'id',
            'id',
            'category_id'
        );
    }

    public function summaries(): HasMany
    {
        return $this->hasMany(__CLASS__, 'primary_summary_id', 'linked_summary_id');
    }
}
