<?php

namespace App\Models\Concerns;

use App\Models\UserAchievement;

trait HasAchievements
{
    public function achievements()
    {
        return $this->morphMany(UserAchievement::class, 'target');
    }
}
