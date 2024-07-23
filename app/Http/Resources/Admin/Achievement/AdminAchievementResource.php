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
            'icon' => $this->icon && !empty($this->icon->filename) ? config('app.url').$this->icon->path.$this->icon->filename : null,
            'title' => $this->name,
            'subtitle' => $this->subtitle ?? null,
            'extraction_description' => $this->extraction_description ?? null,
            'example' => $this->example ?? null,
            'purpose' => $this->purpose ?? null,
            'color' => $this->color ?? null,

            'categories' => $this->categories && count($this->categories) > 0 ? $this->categories : [],
            'data_points' => $this->dataPoints && count($this->dataPoints) > 0 ? $this->dataPoints : [],

            'dev_order' => $this->dev_order ?? null,
            'total_impact' => $this->total_impact ?? null,

            'user' => $this->user ? $this->user->first_name.' '.$this->user->last_name : null,
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
