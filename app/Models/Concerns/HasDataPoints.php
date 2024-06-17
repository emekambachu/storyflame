<?php

namespace App\Models\Concerns;

use App\Models\UserDataPoint;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasDataPoints
{
    public function dataPoints(): HasMany
    {
        return $this->hasMany(UserDataPoint::class);
    }

    public function dataPointsToArray(): array
    {
        return $this->dataPoints()->with('dataPoint')->get()->mapWithKeys(fn($item) => [
            $item->dataPoint->key => $item->data
        ])->toArray();
    }
}
