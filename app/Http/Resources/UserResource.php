<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'paddle_id' => $this->paddle_id,
            'name' => $this->name,
            'email' => $this->email,
            'achievements' => AchievementResource::collection($this->achievements),
            'completed_achievements' => $this->userAchievements->where('completed', true)->count(),
            'progress_achievements' => $this->userAchievements->where('completed', false)->count(),
            'next_achievements' => 0,

            'bio' => $this->getSummary('bio')?->summary,
            'writing_goals' => $this->getSummary('writing_goals')?->summary,

            'onboarded' => $this->extra_attributes['onboarded'] ?? false,
            'data' => $this->when($this->extra_attributes['onboarded'] ?? false, [
                'media' => MediaResource::collection($this->favoriteMovies),
                ...$this->extra_attributes ?? []
            ])
        ];
    }
}
