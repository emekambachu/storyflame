<?php

namespace App\Models;

use App\Models\Concerns\HasAchievements;
use App\Models\Concerns\HasAliases;
use App\Models\Concerns\HasDataPoints;
use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasRelatedChats;
use App\Models\Concerns\HasSchemalessAttributes;
use App\Models\Concerns\HasSummaries;
use App\Models\Concerns\ModelWithComparableNames;
use App\Models\Concerns\ModelWithId;
use App\Models\Concerns\ModelWIthRelatedChats;
use App\Models\StoryElements\Character;
use App\Observers\StoryObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(StoryObserver::class)]
class Story extends Model implements ModelWithComparableNames, ModelWIthRelatedChats, ModelWithId
{
    use SoftDeletes, HasFactory, HasRelatedChats, HasSchemalessAttributes, HasAchievements, HasImages, HasAliases, HasDataPoints, HasSummaries;

    protected $fillable = [
        'name',
        'user_id',
        'extra_attributes'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
    }

    public function sequences(): HasMany
    {
        return $this->hasMany(StoryElements\StorySequence::class);
    }

    public function themes(): HasMany
    {
        return $this->hasMany(StoryElements\StoryTheme::class);
    }

    public function plots(): HasMany
    {
        return $this->hasMany(StoryElements\StoryPlot::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(StoryElements\StorySetting::class);
    }

    public function getComparableNameAttribute(): string
    {
        return $this->name;
    }
}
