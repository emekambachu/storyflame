<?php

namespace App\Observers;

use App\Models\UserDataPoint;

class UserDataPointObserver
{
    public function created(UserDataPoint $userDataPoint): void
    {
        // set all other user data points with the same data point id to is_latest = false
        UserDataPoint::where('user_id', $userDataPoint->user_id)
            ->where('data_point_id', $userDataPoint->data_point_id)
            ->where('target_type', $userDataPoint->target_type)
            ->where('target_id', $userDataPoint->target_id)
            ->where('id', '!=', $userDataPoint->id)
            ->update(['is_latest' => false]);

        $userAchievement = $userDataPoint->userAchievement;
        if ($userAchievement->progress < 100) {
            $completed = $userAchievement
                ->userDataPoints
                ->where('is_latest', true)
                ->unique('data_point_id')
                ->count();
            $total = $userAchievement->achievement->dataPoints()->count();
            $userAchievement->progress = $completed / $total * 100;
            $userAchievement->save();
        }
    }

    public function updated(UserDataPoint $userDataPoint): void
    {
    }

    public function deleted(UserDataPoint $userDataPoint): void
    {
    }

    public function restored(UserDataPoint $userDataPoint): void
    {
    }
}
