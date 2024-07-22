<?php

namespace App\Http\Resources\Cards;

use App\Models\StoryElements\Character;
use App\Models\StoryElements\StoryTheme;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin StoryTheme */
class ThemeCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->summary('theme_theme_card_theme_card_title'),
            'description' => $this->summary('theme_theme_card_theme_card_summary'),
            'role' => 'connect me',
            'type' => 'connect me',
            'episode' => 'connect me',
            'story' => $this->story->name,
        ];
    }
}
