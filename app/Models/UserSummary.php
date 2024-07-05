<?php

namespace App\Models;

use App\Models\Summary\Summary;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSummary extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'summary_id',
        'target_id',
        'target_type',
        'summary',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function summarySchema(): BelongsTo
    {
        return $this->belongsTo(Summary::class, 'summary_id');
    }

    public function target(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeWhereSummaryKey(Builder $query, string $key)
    {
        $query->whereHas('summarySchema', function ($query) use ($key) {
            $query->where('slug', $key);
        });
    }
}
