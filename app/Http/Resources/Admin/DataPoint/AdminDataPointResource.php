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
            'item_id' => $this->item_id,
            'type' => $this->type,
            'development_order' => $this->development_order,
            'impact_score' => $this->impact_score,
            'extraction_description' => $this->extraction_description ?? null,
            'example' => $this->example ?? null,
            'purpose' => $this->purpose ?? null,

            'categories' => $this->categories ?? null,
            'summaries' => $this->sumamries ?? null,
            'achievement' => $this->achievements ? $this->achievements->first() : null,

            'admin' => $this->admin ? $this->admin->first_name.' '.$this->admin->last_name : null,
            'publish_at' => $this->publish_at ?? null,
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
