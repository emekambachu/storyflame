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
            'description' => $this->subtitle ?? null,
            'extraction_description' => $this->extraction_description ?? null,
            'categories' => $this->categories ?? null,
            'example' => $this->example ?? null,
            'purpose' => $this->purpose ?? null,
            'color' => $this->color ?? null,
            'icon_path' => $this->icon_path ?? null,
            'publish_at' => $this->publish_at ?? null,
            'progress' => $this->when(isset($this->pivot), function () {
                return $this->pivot->progress ?? null;
            }),
            'completed_at' => $this->when(isset($this->pivot), function () {
                return $this->pivot->completed_at ?? null;
            }),
        ];
    }
}
