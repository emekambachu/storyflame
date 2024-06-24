<?php

namespace App\Models\Summary;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SummaryLink extends Pivot
{
    use HasUuids, HasFactory;

    protected $table = 'summary_links';

    protected $fillable = [
        'summary_id',
        'linked_summary_id',
    ];
}
