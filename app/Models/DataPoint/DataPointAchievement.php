<?php

namespace App\Models\DataPoint;

use App\Models\Achievement;
use App\Models\DataPoint;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataPointAchievement extends Model
{
    use SoftDeletes, HasUuids, HasFactory;
    protected $fillable = [
        'data_point_id',
        'achievement_id',
    ];

    public function dataPoint(): BelongsTo
    {
        return $this->belongsTo(DataPoint::class, 'data_point_id', 'id');
    }

    public function achievement(): BelongsTo
    {
        return $this->belongsTo(Achievement::class, 'achievement_id', 'id');
    }

}
