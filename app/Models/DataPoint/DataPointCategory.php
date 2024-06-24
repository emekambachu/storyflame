<?php

namespace App\Models\DataPoint;

use App\Models\Category;
use App\Models\DataPoint;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DataPointCategory extends Pivot
{
    use HasFactory, HasUuids;

    protected $table = 'data_point_categories';

    protected $fillable = [
        'data_point_id',
        'category_id',
    ];

    public function dataPoint(): BelongsTo
    {
        return $this->belongsTo(DataPoint::class, 'data_point_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
