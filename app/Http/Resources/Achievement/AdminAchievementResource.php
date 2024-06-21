<?php

namespace App\Http\Resources\Achievement;

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
            'icon' => $this->icon ? '/images/achievements/' . $this->icon : null,
            'title' => $this->name,
            'item_id' => $this->item_id,
            'description' => $this->subtitle ?? null,
            'extraction_description' => $this->extraction_description ?? null,
            'categories' => $this->categories ?? null,
//            'data_points' => $this->dataPoints ?? null,
            'example' => $this->example ?? null,
            'purpose' => $this->purpose ?? null,
            'color' => $this->color ?? null,
            'icon_path' => $this->icon_path ?? null,
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
