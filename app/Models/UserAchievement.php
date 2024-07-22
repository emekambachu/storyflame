<?php

namespace App\Models;

use App\Observers\UserAchievementObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(UserAchievementObserver::class)]
class UserAchievement extends Model
{
    use HasFactory;

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

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->whereNotNull('completed_at');
    }

    public function scopeInProgress(Builder $query): Builder
    {
        return $query->whereNull('completed_at')->where('progress', '>', 0);
    }

    public function scopeUpNext(Builder $query): Builder
    {
        return $query->whereNull('completed_at')->where('progress', 0);
    }
}
