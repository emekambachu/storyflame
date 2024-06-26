<?php

namespace App\Http\Resources\Admin\Achievement;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminAchievementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,  // Try both id and uuid
            'name' => $this->name,
            'slug' => $this->slug,
            'icon' => !empty($this->icon) ? config('app.url').$this->icon_path.$this->icon : null,
            'title' => $this->name,
            'item_id' => $this->item_id,
            'subtitle' => $this->subtitle ?? null,
            'extraction_description' => $this->extraction_description ?? null,
            'example' => $this->example ?? null,
            'purpose' => $this->purpose ?? null,
            'color' => $this->color ?? null,

            'categories' => $this->categories && count($this->categories) > 0 ? $this->categories : [],
            'data_points' => $this->dataPoints && count($this->dataPoints) > 0 ? $this->dataPoints : [],

            'dev_order' => $this->dev_order ?? null,
            'total_impact' => $this->total_impact ?? null,

            'admin' => $this->admin ? $this->admin->first_name.' '.$this->admin->last_name : null,
            'publish_at' => $this->publish_at ?? null,
            'updated_at' => $this->updated_at->format('F d Y'),

            'progress' => $this->when(isset($this->pivot), function () {
                return $this->pivot->progress ?? null;
            }),
            'completed_at' => $this->when(isset($this->pivot), function () {
                return $this->pivot->completed_at ?? null;
            }),
        ];
    }
}
