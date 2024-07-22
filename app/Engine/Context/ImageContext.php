<?php

namespace App\Engine\Context;

use App\Models\DataPoint;
use App\Models\Image;
use App\Models\ImageType;
use App\Models\Summary\Summary;
use App\Models\UserDataPoint;
use App\Models\UserSummary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class ImageContext extends BaseContext
{
    protected ImageType $imageType;
    protected ?Model $model;
    protected $modelId;

    public function __construct(Image $image = null)
    {
        parent::__construct($image);
    }

    public function initialize(string $model, int $modelId, string $imageTypeSlug): void
    {
        $this->model = app($model);
        $this->modelId = $modelId;
        $this->imageType = ImageType::where('slug', $imageTypeSlug)->firstOrFail();

        if (!$this->getModel()) {
            $this->model = Image::create([
                'image_type_id' => $this->imageType->id,
                'imageable_type' => $model,
                'imageable_id' => $modelId,
            ]);
        }
    }

    /**
     * @return Image|null
     */
    public function getModel(): ?Image
    {
        return $this->model;
    }

    /**
     * @return string|null
     */
    public function getContextClass(): ?string
    {
        return Image::class;
    }

    /**
     * @return array[]
     */
    protected function getCurrentData(): array
    {
        return [
            'Image' => [
                'type' => $this->imageType->name,
                'for_model' => class_basename($this->model),
                'for_model_id' => $this->modelId,
                'existing_data_points' => $this->getExistingDataPoints(),
                'existing_summaries' => $this->getExistingSummaries(),
            ]
        ];
    }

    /**
     * @return ImageType
     */
    public function getImageType(): ImageType
    {
        return $this->imageType;
    }

    /**
     * @return string
     */
    protected function getContextName(): string
    {
        return "Image for {$this->model} #{$this->modelId}";
    }

    protected function getContextGoal(): string
    {
        return "Gather information to generate an image for {$this->imageType->name}";
    }

    public function getMissingRequirements(): array
    {
        return $this->imageType->getMissingRequirements($this->model, $this->modelId);
    }

    public function getDataPoints(bool $onlyRequired = false): Collection
    {
        return $this->imageType->getDataPoints($onlyRequired);
    }

    protected function getExistingDataPoints(): array
    {
        return UserDataPoint::where([
            'target_type' => get_class($this->model),
            'target_id' => $this->modelId,
        ])->whereIn('data_point_id', $this->getDataPoints()->pluck('id'))
            ->get()
            ->pluck('data', 'data_point.slug')
            ->toArray();
    }

    protected function getExistingSummaries(): array
    {
        return UserSummary::where([
            'target_type' => get_class($this->model),
            'target_id' => $this->modelId,
        ])->whereIn('summary_id', $this->imageType->schemas()
            ->where('schemaable_type', Summary::class)
            ->pluck('schemaable_id'))
            ->get()
            ->pluck('summary', 'summary.slug')
            ->toArray();
    }

    protected function onDataPointSaved(string $key, mixed $value): void
    {
        UserDataPoint::updateOrCreate(
            [
                'data_point_id' => DataPoint::where('slug', $key)->firstOrFail()->id,
                'target_type' => get_class($this->model),
                'target_id' => $this->modelId,
            ],
            ['data' => $value]
        );
    }
}
