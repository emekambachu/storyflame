<?php

namespace App\Models;

use App\Observers\AchievementObserver;
use App\Services\AchievementService;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([AchievementObserver::class])]
class Achievement extends Model
{
    use SoftDeletes, HasUuids, HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'progress'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getIconAttribute(): string
    {
        return '/images/achievements/' . AchievementService::ACHIEVEMENTS[$this->name]['icon'];
    }

    public function getTitleAttribute(): string
    {
        return AchievementService::ACHIEVEMENTS[$this->name]['title'];
    }

    public function getDescriptionAttribute(): string
    {
        return AchievementService::ACHIEVEMENTS[$this->name]['description'];
    }
}
