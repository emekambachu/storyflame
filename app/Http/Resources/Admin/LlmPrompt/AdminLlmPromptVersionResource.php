<?php

namespace App\Http\Resources\Admin\LlmPrompt;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminLlmPromptVersionResource extends JsonResource
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
            'llm_prompt_id' => $this->llm_prompt_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'prompt_value' => !empty($this->prompt_value) ? $this->filterStringOrJson($this->prompt_value) : null,
            //'updated_by' => $this->updated_by ? $this->updated_by->only(['id', 'name', 'first_name', 'last_name']) : null,
            'updated_at' => Carbon::parse($this->updated_at)->format('F d Y'),
        ];
    }
}
