<?php

namespace App\Models;

use App\Models\Concerns\HasAchievements;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Character extends Model
{
    use SoftDeletes, HasUuids, HasFactory, HasAchievements;

    protected $fillable = [
        'story_id',
        'name',
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }
}
