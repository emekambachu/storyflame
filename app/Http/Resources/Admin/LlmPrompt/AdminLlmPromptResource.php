<?php

namespace App\Http\Resources\Admin\LlmPrompt;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminLlmPromptResource extends JsonResource
{
    // attempt to decode to json string, if not return as string
    private function filterStringOrJson($string) {
        $decoded = json_decode($string, true);
        return $decoded ?? $string;
    }

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
            'current_prompt_value' => !empty($this->current_version) ? $this->filterStringOrJson($this->current_version->prompt_value) : null,
            'current_version' => $this->current_version ?: null,
            'current_prompt_version_id' => $this->current_prompt_version_id,
            'versions' => $this->versions ? AdminLlmPromptVersionResource::collection($this->versions) : null,
            'updated_by' => $this->updated_by ? $this->updated_by->first_name.' '.$this->updated_by->last_name : null,
            'updated_at' => $this->updated_at->format('F d Y h:i A'),
        ];
    }
}
