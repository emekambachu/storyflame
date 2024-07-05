<?php

namespace App\Models\Concerns;

use App\Models\UserDataPoint;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasDataPoints
{
    public function dataPoints(): MorphMany
    {
        return $this->morphMany(UserDataPoint::class, 'target');
    }

    public function dataPointsToArray(): array
    {
        return $this->dataPoints()->with('dataPoint')->get()->mapWithKeys(fn($item) => [
            $item->dataPoint->slug => $item->data
        ])->toArray();
    }
}
