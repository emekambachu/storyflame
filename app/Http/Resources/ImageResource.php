<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Image */
class ImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'imageable_type' => $this->imageable_type,
            'imageable_id' => $this->imageable_id,
            'group' => $this->group,
            'generation_service_name' => $this->generation_service_name,
            'generation_id' => $this->generation_id,
            'generation_settings' => $this->generation_settings
                ? json_decode($this->generation_settings, true)
                : null,
            'token_cost' => $this->token_cost,
            'files' => ImageFileResource::collection($this->files),
            'path' => $this->path,
        ];
    }
}
