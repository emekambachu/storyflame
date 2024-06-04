<?php

namespace App\Models;

use App\Observers\AchievementObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Achievement extends Model
{
    use SoftDeletes, HasUuids, HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'element',
        'subtitle',
        'extraction_description',
        'purpose',
        'color',
        'icon'
    ];

    public function dataPoints(): HasMany
    {
        return $this->hasMany(DataPoint::class);
    }

    public function totalImpactScore(): int
    {
        return $this->dataPoints->sum('impact_score');
    }
}
