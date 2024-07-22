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
            'customer_id' => $this->paddle_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'is_verified' => $this->is_verified === 1,
            'referred_by' => $this->referred_by,

            'completed_achievements' => $this->userAchievements->where('completed', true)->count(),
            'progress_achievements' => $this->userAchievements->where('completed', false)->count(),
            'achievements' => [
                'completed' => UserAchievementResource::collection($this->achievements()->completed()->get()),
                'in_progress' => UserAchievementResource::collection($this->achievements()->inProgress()->get()),
                'up_next' => [],
            ],
            'next_achievements' => 0,

            'bio' => $this->summary('user_user_bio_bio'),
            'writing_goals' => $this->summary('user_user_goals_writing_goals'),

            'avatar' => !empty($this->avatar) ? config('app.url') . $this->avatar_path . $this->avatar : null,

//            'achievements' => UserAchievementResource::collection($this->userAchievements ?? []),
            'onboarded' => $this->extra_attributes['onboarded'] ?? false,
            'data' => $this->when($this->extra_attributes['onboarded'] ?? false, [
                'media' => MediaResource::collection($this->favoriteMovies),
                ...$this->extra_attributes ?? []
            ])
        ];
    }
}
