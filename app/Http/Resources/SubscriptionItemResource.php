<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionItemResource extends JsonResource
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
            'product_id' => $this->product_id,
            'price_id' => $this->price_id,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'product' => new ProductResource($this->whenLoaded('product')),
            'price' => new ProductPriceResource($this->whenLoaded('price')),
        ];
    }
}
