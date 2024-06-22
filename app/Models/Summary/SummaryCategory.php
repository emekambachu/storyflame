<?php

namespace App\Models\Summary;

use App\Models\Category;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SummaryCategory extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'summary_id',
        'category_id',
    ];

    public function summary(): BelongsTo
    {
        return $this->belongsTo(Summary::class, 'summary_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
