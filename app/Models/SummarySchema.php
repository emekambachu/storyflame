<?php

namespace App\Models;

use App\Models\Summary\Summary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SummarySchema extends Model
{
    use SoftDeletes, SoftDeletes;

    protected $fillable = [
        'summary_id',
        'schemaable_id',
        'schemaable_type',
        'is_required',
    ];

    public function summary(): BelongsTo
    {
        return $this->belongsTo(Summary::class);
    }

    public function schemaable(): MorphTo
    {
        return $this->morphTo();
    }

    public function userDataPoints()
    {
        return $this->hasMany(UserDataPoint::class, 'data_point_id', 'schemaable_id')
            ->where('schemaable_type', DataPoint::class);
    }

    public function userSummaries()
    {
        return $this->hasMany(UserSummary::class, 'summary_id', 'schemaable_id')
            ->where('schemaable_type', Summary::class);
    }
}
