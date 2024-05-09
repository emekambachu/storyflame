<?php

namespace App\Http\Resources;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/** @mixin Media */
class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'poster' => Storage::url($this->images('poster')->first()?->path),
            'released' => $this->released,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'aliases_count' => $this->aliases_count,
            'images_count' => $this->images_count,
        ];
    }
}
