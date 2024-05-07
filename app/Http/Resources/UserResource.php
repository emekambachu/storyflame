<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'onboarded' => $this->extra_attributes['onboarded'] ?? false,
            'data' => $this->when($this->extra_attributes['onboarded'], [
                'media' => $this->favoriteMovies,
                ...$this->extra_attributes['data']
            ])
        ];
    }
}
