<?php

namespace App\Models\Summary;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SummaryLink extends Pivot
{
    use HasUuids, HasFactory;

    protected $table = 'summary_links';

    protected $fillable = [
        'summary_id',
        'linked_summary_id',
    ];

    public function summary(): BelongsTo
    {
        return $this->belongsTo(Summary::class, 'summary_id', 'id');
    }

    public function linkedSummary(): BelongsTo
    {
        return $this->belongsTo(Summary::class, 'linked_summary_id', 'id');
    }
}
