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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->first_name .' '. $this->last_name,
            'paddle_id' => $this->paddle_id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'is_verified' => $this->is_verified === 1,
            'referral_code' => $this->referral_code,
            'referred_by' => $this->referred_by,

            'completed_achievements' => $this->userAchievements->where('completed', true)->count(),
            'progress_achievements' => $this->userAchievements->where('completed', false)->count(),
            'next_achievements' => 0,

            'bio' => $this->getSummary('bio')?->summary,
            'writing_goals' => $this->getSummary('writing_goals')?->summary,

            'avatar' => $this->avatar && !empty($this->avatar->name) ? $this->avatar->path.$this->avatar->name : null,
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
