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
            'name' => $this->name,
            'icon' => '/images/achievements/' . $this->icon,
            'title' => $this->name,
            'description' => $this->subtitle,
            'progress' => $this->pivot->progress,
            'completed_at' => $this->pivot->completed_at,
        ];
    }
}
