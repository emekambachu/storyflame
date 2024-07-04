<?php

namespace App\Http\Resources\Admin\LlmPrompt;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminLlmPromptResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'current_version' => $this->current_version ? $this->current_version : null,
            'current_prompt_version_id' => $this->current_prompt_version_id,
            'versions' => $this->versions && count($this->versions) > 0 ? $this->versions : null,
            'updated_by' => $this->updated_by ? $this->updated_by->only(['id', 'name', 'first_name', 'last_name']) : null,
            'updated_at' => $this->updated_at->format('F d Y'),
        ];
    }
}
