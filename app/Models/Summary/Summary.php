<?php
namespace App\Models\Summary;

use App\Models\Category;
use App\Models\Concerns\HasCategories;
use App\Models\DataPoint;
use App\Models\DataPoint\DataPointSummary;
use App\Models\DevelopmentReport;
use App\Models\SummarySchema;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Summary extends Model
{
    use HasFactory, HasCategories;

    protected $fillable = [
        'name',
        'slug',
        'location',
        'purpose',
        'creation_prompt',
        'example_summary',
        'published_at',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function dataPoints(): MorphToMany
    {
        return $this->morphedByMany(
            DataPoint::class,
            'schemaable',
            'summary_schemas',
            'summary_id',
            'schemaable_id'
        )->withTimestamps();
    }

    public function linkedSummaries(): MorphToMany
    {
        return $this->morphedByMany(
            __CLASS__,
            'schemaable',
            'summary_schemas',
            'summary_id',
            'schemaable_id'
        )->withTimestamps();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            SummaryCategory::class,
            'summary_id',
            'category_id'
        )->withTimestamps();
    }

    public function schemas(): HasMany
    {
        return $this->hasMany(SummarySchema::class);
    }

    public function developmentReports(): BelongsToMany
    {
        return $this->belongsToMany(DevelopmentReport::class, 'development_report_summaries')
            ->withPivot('order')
            ->orderBy('development_report_summaries.order')
            ->withTimestamps();
    }
}
