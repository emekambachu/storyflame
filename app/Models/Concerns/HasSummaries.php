<?php

namespace App\Models\Concerns;

use App\Models\UserSummary;
use Illuminate\Database\Eloquent\Builder;

trait HasSummaries
{
    public function summaries()
    {
        return $this->morphMany(UserSummary::class, 'target');
    }

    public function getSummary(string $key): ?UserSummary
    {
        return $this->summaries()->whereSummaryKey($key)->first() ?? null;
    }

    public function summary(string $key): ?string
    {
        return $this->getSummary($key)?->summary;
    }
}
