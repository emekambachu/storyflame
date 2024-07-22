<?php

namespace App;

use App\Models\ImageType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Log;

trait HasImageTypes
{
    public function imageTypes()
    {
        Log::info('the static class is: ' . static::class);
        // Remember this only supposed to find the imageTypes associated with the Model and *not* a specific instance of the model_type.
        return ImageType::where('model_type', static::class)
            ->get();
    }

    /**
     * Scope a query to include specific image types.
     *
     * @param Builder $query
     * @param array $types
     * @return Builder
     */
    public function scopeWithImageTypes($query, array $types): Builder
    {
        return $query->whereHas('imageTypes', function ($query) use ($types) {
            $query->whereIn('type', $types);
        });
    }

    /**
     * Add an image type to the model.
     *
     * @param string $type
     * @return Model
     */
    public function addImageType(string $type)
    {
        return $this->imageTypes()->create(['type' => $type]);
    }

    /**
     * Remove an image type from the model.
     *
     * @param string $type
     * @return bool|null
     */
    public function removeImageType(string $type)
    {
        return $this->imageTypes()->where('type', $type)->delete();
    }
}
