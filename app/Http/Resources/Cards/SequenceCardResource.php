<?php

namespace App\Http\Resources\Cards;

use App\Models\StoryElements\StorySequence;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin StorySequence */
class SequenceCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->dataPoint('sequence_sequence_title'),
            'description' => $this->summary('sequence_header_sequence_card_sequence_action_based_title'),
            'role' => 'connect me',
            'type' => $this->summary('sequence_header_sequence_card_sequence_type'),
            'episode' => 'connect me',
            'story' => $this->story->name,
        ];
    }
}
