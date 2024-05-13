<?php

namespace App\Observers;

use App\Models\Achievement;
use App\Notifications\AchievementUnlocked;

class AchievementObserver
{
    private function notifyIfAchievementUnlocked(Achievement $achievement): void
    {
        if ($achievement->progress === 100 && $achievement->completed_at === null) {
            $achievement->completed_at = now();
            $achievement->saveQuietly();
            $achievement->user->notify(new AchievementUnlocked($achievement));
        }
    }

    /**
     * Handle the Achievement "created" event.
     */
    public function created(Achievement $achievement): void
    {
        $this->notifyIfAchievementUnlocked($achievement);
    }

    /**
     * Handle the Achievement "updated" event.
     */
    public function updated(Achievement $achievement): void
    {
        $this->notifyIfAchievementUnlocked($achievement);
    }

    /**
     * Handle the Achievement "deleted" event.
     */
    public function deleted(Achievement $achievement): void
    {
        //
    }

    /**
     * Handle the Achievement "restored" event.
     */
    public function restored(Achievement $achievement): void
    {
        //
    }

    /**
     * Handle the Achievement "force deleted" event.
     */
    public function forceDeleted(Achievement $achievement): void
    {
        //
    }
}
