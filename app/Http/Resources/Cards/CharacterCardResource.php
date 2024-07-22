<?php

namespace App\Http\Resources\Cards;

use App\Models\StoryElements\Character;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Character */
class CharacterCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->dataPoint('character_character_name'),
            'description' => $this->summary('character_character_card_character_card_summary'),
            'role' => 'connect me',
            'type' => 'connect me',
            'episode' => 'connect me',
            'story' => $this->story->name,
        ];
    }
}
