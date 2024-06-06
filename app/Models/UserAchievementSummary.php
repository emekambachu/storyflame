<?php

namespace App\Models;

use App\Observers\UserAchievementSummaryObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(UserAchievementSummaryObserver::class)]
class UserAchievementSummary extends Model
{
    use HasUuids, SoftDeletes, HasFactory;

    protected $fillable = [
        'user_achievement_id',
        'summary',
    ];

    public function userAchievement(): BelongsTo
    {
        return $this->belongsTo(UserAchievement::class);
    }
}
