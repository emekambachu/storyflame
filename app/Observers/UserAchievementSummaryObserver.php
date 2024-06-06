<?php

namespace App\Observers;

use App\Models\UserAchievementSummary;

class UserAchievementSummaryObserver
{
    public function created(UserAchievementSummary $userAchievementSummary): void
    {
        // when new summary created, mark other ones as not latest
        $userAchievementSummary->userAchievement->summaries()
            ->where('id', '!=', $userAchievementSummary->id)
            ->update(['is_latest' => false]);
    }

    public function updated(UserAchievementSummary $userAchievementSummary): void
    {
    }

    public function deleted(UserAchievementSummary $userAchievementSummary): void
    {
    }

    public function restored(UserAchievementSummary $userAchievementSummary): void
    {
    }
}
