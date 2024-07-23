<?php

namespace App\Http\Resources\Admin\Summary;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminSummaryResource extends JsonResource
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
            'location' => $this->location,
            'purpose' => $this->purpose,
            'creation_prompt' => $this->creation_prompt,
            'example_summary' => $this->example_summary,

            'categories' => $this->categories && count($this->categories) > 0 ? $this->categories : [],
            'summaries' => $this->sumamries ?? [],
            'data_points' => $this->data_points ?? [],

            'linked_summaries' => $this->sumamries ? count($this->sumamries) : 0,
            'data_points_count' => $this->data_points ? count($this->data_points) : 0,

            'user' => $this->user ? $this->user->first_name.' '.$this->user->last_name : null,
            'publish_at' => $this->publish_at ?? null,
            'updated_at' => $this->updated_at->format('F d Y'),

//            'progress' => $this->when(isset($this->pivot), function () {
//                return $this->pivot->progress ?? null;
//            }),
//            'completed_at' => $this->when(isset($this->pivot), function () {
//                return $this->pivot->completed_at ?? null;
//            }),
        ];
    }
}
