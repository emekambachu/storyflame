<?php

namespace App\Models;

use App\Models\Concerns\HasAchievements;
use App\Models\Concerns\HasAliases;
use App\Models\Concerns\HasDataPoints;
use App\Models\Concerns\HasRelatedChats;
use App\Models\Concerns\ModelWithComparableNames;
use App\Models\Concerns\ModelWithId;
use App\Models\Concerns\ModelWIthRelatedChats;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Character extends Model implements ModelWithComparableNames, ModelWIthRelatedChats, ModelWithId
{
    use SoftDeletes, HasFactory, HasAchievements, HasAliases, HasRelatedChats, HasDataPoints;

    protected $fillable = [
        'story_id',
        'name',
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    public function getComparableNameAttribute(): string
    {
        return $this->name;
    }
}
