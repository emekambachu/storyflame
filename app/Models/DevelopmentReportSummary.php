<?php

namespace App\Models;

use App\Models\Summary\Summary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DevelopmentReportSummary extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'development_report_id',
        'summary_id',
        'order',
    ];

    /**
     * @return BelongsTo
     */
    public function developmentReport(): BelongsTo
    {
        return $this->belongsTo(DevelopmentReport::class);
    }

    /**
     * @return BelongsTo
     */
    public function summary(): BelongsTo
    {
        return $this->belongsTo(Summary::class);
    }
}
