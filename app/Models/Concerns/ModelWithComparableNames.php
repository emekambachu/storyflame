<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property string $id
 */
interface ModelWithComparableNames
{
    /**
     * Get the name of the model.
     * @return string
     */
    public function getComparableNameAttribute(): string;

    public function aliases(): MorphMany;
}
