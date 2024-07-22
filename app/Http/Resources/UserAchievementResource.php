<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\UserAchievement */
class UserAchievementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->achievement->name,
            'description' => $this->achievement->subtitle,
            'icon' => ImageResource::make($this->achievement->images->first()),
            'time' => $this->achievement->estimated_seconds,
            'percent' => $this->progress,
            'color' => $this->achievement->color,
            'completed_at' => $this->completed_at,
        ];
    }
}
