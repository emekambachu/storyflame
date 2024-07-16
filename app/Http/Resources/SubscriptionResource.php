<?php

namespace App\Http\Resources;

use App\Models\SubscriptionItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'paddle_id' => $this->paddle_id,
            'status' => $this->status,
            'trial_ends_at' => $this->trial_ends_at,
            'ends_at' => $this->ends_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'items' => SubscriptionItemResource::collection($this->items),
        ];
    }
}
