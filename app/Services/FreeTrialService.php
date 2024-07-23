<?php

namespace App\Services;

use App\Models\User;
use App\Models\FreeTrialInteraction;
use Carbon\Carbon;

class FreeTrialService
{
    public function initializeFreeTrial(User $user)
    {
        FreeTrialInteraction::create([
            'user_id' => $user->id,
        ]);
    }

    public function canInteract(User $user)
    {
        $trialInteraction = $user->freeTrialInteraction;

        if (!$trialInteraction) {
            $this->initializeFreeTrial($user);
            $trialInteraction = $user->freeTrialInteraction;
        }

        $totalLimit = config('free_trial.total_interactions');
        $dailyLimit = config('free_trial.daily_interactions');

        if ($trialInteraction->total_interactions_used >= $totalLimit) {
            return false;
        }

        if ($trialInteraction->last_interaction_date != Carbon::today()) {
            $trialInteraction->update([
                'daily_interactions_used' => 0,
                'last_interaction_date' => Carbon::today(),
            ]);
        }

        return $trialInteraction->daily_interactions_used < $dailyLimit;
    }

    public function trackInteraction(User $user)
    {
        if ($this->canInteract($user)) {
            $trialInteraction = $user->freeTrialInteraction;
            $trialInteraction->increment('total_interactions_used');
            $trialInteraction->increment('daily_interactions_used');
            return true;
        }
        return false;
    }

    public function getRemainingInteractions(User $user)
    {
        $trialInteraction = $user->freeTrialInteraction;
        $totalLimit = config('free_trial.total_interactions');
        return $totalLimit - $trialInteraction->total_interactions_used;
    }

    public function getRemainingInteractionsForToday(User $user)
    {
        $trialInteraction = $user->freeTrialInteraction;
        $dailyLimit = config('free_trial.daily_interactions');
        return $dailyLimit - $trialInteraction->daily_interactions_used;
    }

    public function trackImageGeneration(User $user)
    {
        $trialInteraction = $user->freeTrialInteraction;
        $imageLimit = config('free_trial.max_images');

        if ($trialInteraction->images_generated < $imageLimit) {
            $trialInteraction->increment('images_generated');
            return true;
        }
        return false;
    }
}
