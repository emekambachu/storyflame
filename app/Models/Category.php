<?php

namespace App\Models;

use App\Models\Achievement\AchievementCategory;
use App\Models\DataPoint\DataPointCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(AchievementCategory::class, 'category_id', 'id');
    }

    public function dataPoints(): BelongsToMany
    {
        return $this->belongsToMany(DataPointCategory::class, 'category_id', 'id');
    }


}
