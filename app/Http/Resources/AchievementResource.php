<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Achievement */
class AchievementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->name,
            'description' => $this->subtitle,
            'icon' => ImageResource::make($this->images->first()),
            'time' => $this->estimated_seconds,
            'color' => $this->color,
            'percent' => 0,
            'completed_at' => null,
        ];
    }
}
