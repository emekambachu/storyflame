<?php

namespace App\Http\Resources\Admin\LlmPrompt;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminLlmPromptVersionResource extends JsonResource
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
            'llm_prompt_id' => $this->llm_prompt_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'prompt_value' => $this->prompt_value,
            'updated_by' => $this->updated_by ? $this->updated_by->only(['id', 'name', 'first_name', 'last_name']) : null,
            'updated_at' => $this->updated_at,
        ];
    }
}
