<?php

namespace App\Models\DataPoint;

use App\Models\Achievement;
use App\Models\Category;
use App\Models\DataPoint;
use App\Models\Summary\Summary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataPointSummary extends Model
{
    use HasFactory;
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
