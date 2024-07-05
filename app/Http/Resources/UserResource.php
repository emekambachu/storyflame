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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name' => $this->name,
            'email' => $this->email,
            'completed_achievements' => $this->userAchievements->where('completed', true)->count(),
            'progress_achievements' => $this->userAchievements->where('completed', false)->count(),
            'next_achievements' => 0,

            'bio' => $this->getSummary('bio')?->summary,
            'writing_goals' => $this->getSummary('writing_goals')?->summary,

            'avatar' => !empty($this->avatar) ? config('app.url') . $this->avatar_path . $this->avatar : null,
            'password' => $this->password,

            'achievements' => AchievementResource::collection($this->achievements ?? []),
            'onboarded' => $this->extra_attributes['onboarded'] ?? false,
            'data' => $this->when($this->extra_attributes['onboarded'] ?? false, [
                'media' => MediaResource::collection($this->favoriteMovies),
                ...$this->extra_attributes ?? []
            ])
        ];
    }
}
