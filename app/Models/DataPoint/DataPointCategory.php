<?php

namespace App\Models\DataPoint;

use App\Models\Achievement;
use App\Models\Category;
use App\Models\DataPoint;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataPointCategory extends Model
{
    use HasFactory;
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
