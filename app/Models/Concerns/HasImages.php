<?php

namespace App\Models\Concerns;

use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasImages
{
    public function images(string|null $group = null): MorphMany
    {
        $q = $this->morphMany(Image::class, 'imageable');
        if ($group) {
            $q->where('group', $group);
        }

        return $q;
    }
}
