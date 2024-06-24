<?php

namespace App\Models;

use App\Observers\UserDataPointObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(UserDataPointObserver::class)]
class UserDataPoint extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'data_point_id',
        'target_type',
        'target_id',
        'user_achievement_id',
        'data',
        'summary',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dataPoint(): BelongsTo
    {
        return $this->belongsTo(DataPoint::class);
    }

    public function target(): MorphTo
    {
        return $this->morphTo();
    }

    public function chatMessages(): BelongsToMany
    {
        return $this->belongsToMany(ChatMessage::class, 'user_data_point_chat_messages');
    }

    public function userAchievement(): BelongsTo
    {
        return $this->belongsTo(UserAchievement::class);
    }
}
