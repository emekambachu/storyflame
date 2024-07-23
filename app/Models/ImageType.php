<?php

namespace App\Models;

use App\Models\Summary\Summary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class ImageType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'purpose',
        'creation_prompt',
        'example_prompt',
        'height',
        'width',
        'prompt_settings',
        'model_type', // like 'App\Models\Vendor'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($imageType) {
            $imageType->height = $this->setDimension($imageType->height);
            $imageType->width = $this->setDimension($imageType->width);
        });

        static::updating(function ($imageType) {
            $imageType->height = $this->setDimension($imageType->height);
            $imageType->width = $this->setDimension($imageType->width);
        });
    }

    private function setDimension($pixels)
    {
        return $pixels + (8 - ($pixels % 8));
    }

    public function model()
    {
        return app($this->model_type);
    }

    /**
     * @return HasMany
     */
    public function schemas(): HasMany
    {
        return $this->hasMany(ImageTypeSchema::class);
    }

    public function getDataPoints($onlyRequired = false)
    {
        $dataPoints = collect();

        // Get DataPoints directly associated with this ImageType
        $query = $this->schemas()->where('schemaable_type', DataPoint::class);
        if ($onlyRequired) {
            $query->where('is_required', true);
        }
        $query->with('schemaable')->get()->each(function ($schema) use (&$dataPoints) {
            if ($schema->schemaable) {
                $dataPoints->push($schema->schemaable);
            }
        });

        // Get DataPoints from associated Summaries
        $summaryQuery = $this->schemas()->where('schemaable_type', Summary::class);
        if ($onlyRequired) {
            $summaryQuery->where('is_required', true);
        }
        $summaryQuery->with(['schemaable.dataPoints' => function ($query) use ($onlyRequired) {
            if ($onlyRequired) {
                $query->wherePivot('is_required', true);
            }
        }])->get()->each(function ($schema) use (&$dataPoints) {
            if ($schema->schemaable) {
                $dataPoints = $dataPoints->merge($schema->schemaable->dataPoints);
            }
        });

        $uniqueDataPoints = $dataPoints->unique('id');

        Log::info(($onlyRequired ? 'Required' : 'All') . ' DataPoints', $uniqueDataPoints->toArray());

        return $uniqueDataPoints;
    }

    public function getMissingRequirements($model, $modelId)
    {
        $requiredDataPoints = $this->getDataPoints(true);
        $missingRequirements = [];

        foreach ($requiredDataPoints as $dataPoint) {
            $userDataPoint = UserDataPoint::where([
                'data_point_id' => $dataPoint->id,
                'target_type' => get_class($model),
                'target_id' => $modelId
            ])->first();

            if (!$userDataPoint || empty($userDataPoint->data)) {
                $missingRequirements[] = [
                    'id' => $dataPoint->id,
                    'name' => $dataPoint->name,
                    'type' => 'DataPoint'
                ];
            }
        }

        return $missingRequirements;
    }
}
