<?php

namespace App\Observers;

use App\Models\UserAchievement;
use App\Notifications\AchievementUnlocked;

class UserAchievementObserver
{
    private function checkIfCompleted(UserAchievement $userAchievement): void
    {
        if ($userAchievement->progress >= 100 && is_null($userAchievement->completed_at)) {
            $userAchievement->completed_at = now();
            $userAchievement->save();
            $userAchievement->user->notify(new AchievementUnlocked($userAchievement->achievement));
            $userAchievement->summaries()->create([
                'summary' => $userAchievement->achievement->purpose,
            ]);
        }
    }

    public function created(UserAchievement $userAchievement): void
    {
        $this->checkIfCompleted($userAchievement);
    }

    public function updated(UserAchievement $userAchievement): void
    {
        $this->checkIfCompleted($userAchievement);
    }

    public function deleted(UserAchievement $userAchievement): void
    {
    }

    public function restored(UserAchievement $userAchievement): void
    {
    }
}
