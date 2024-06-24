<?php

namespace App\Models\DataPoint;

use App\Models\DataPoint;
use App\Models\Summary\Summary;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DataPointSummary extends Pivot
{
    use HasFactory, HasUuids;

    protected $table = 'data_point_summaries';

    protected $fillable = [
        'data_point_id',
        'summary_id',
    ];

    public function data_point(): BelongsTo
    {
        return $this->belongsTo(DataPoint::class, 'data_point_id', 'id');
    }

    public function summary(): BelongsTo
    {
        return $this->belongsTo(Summary::class, 'summary_id', 'id');
    }
}
