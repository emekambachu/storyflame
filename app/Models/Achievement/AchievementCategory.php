<?php

namespace App\Models\Achievement;

use App\Models\Achievement;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AchievementCategory extends Pivot
{
    use HasFactory;

    protected $table = 'achievement_categories';

    protected $fillable = [
        'achievement_id',
        'category_id',
    ];

    public function achievement(): BelongsTo
    {
        return $this->belongsTo(Achievement::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
