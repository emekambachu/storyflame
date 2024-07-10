<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,
            'benefits' => $this->benefits,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
