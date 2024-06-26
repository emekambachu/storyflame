<?php

namespace App\Models;

use App\Models\Summary\Summary;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SummarySchema extends Model
{
    use SoftDeletes;

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

    public function schemaable()
    {
        return $this->morphTo();
    }
}
