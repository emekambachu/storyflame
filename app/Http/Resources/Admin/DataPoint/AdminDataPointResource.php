<?php

namespace App\Http\Resources\Admin\DataPoint;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminDataPointResource extends JsonResource
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
            'slug' => $this->slug,
            'type' => $this->type,
            'development_order' => $this->development_order,
            'impact_score' => $this->impact_score,
            'extraction_description' => $this->extraction_description ?? null,
            'example' => $this->example ?? null,
            'purpose' => $this->purpose ?? null,

            'categories' => $this->categories && count($this->categories) > 0 ? $this->categories : [],
            'summaries' => $this->summaries && count($this->summaries) > 0 ? $this->summaries : [],
            'achievement' => $this->achievements && count($this->achievements) > 0 ? $this->achievements->first()->name : null,

            'user' => $this->user ? $this->user->first_name.' '.$this->user->last_name : null,
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
