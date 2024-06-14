<?php

namespace App\Models;

use App\Observers\UserAchievementObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(UserAchievementObserver::class)]
class UserAchievement extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'user_id',
        'achievement_id',
        'target_type',
        'target_id',
        'progress',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function achievement(): BelongsTo
    {
        return $this->belongsTo(Achievement::class);
    }

    public function summaries(): HasMany
    {
        return $this->hasMany(UserAchievementSummary::class);
    }

    public function userDataPoints(): HasMany
    {
        return $this->hasMany(UserDataPoint::class);
    }
}
