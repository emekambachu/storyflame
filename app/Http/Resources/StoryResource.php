<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Story */
class StoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => ImageResource::make($this->images->first()),
            'type' => 'Story',
            'logline' => $this->getSummary('logline')?->summary,
            'goals' => $this->getSummary('goal_summary')?->summary,
            'promise' => $this->getSummary('promise')?->summary,
            'genres' => $this->genres ?? [],
            'format' => $this->format ?? [],
            'progress'=> $this->progress ?? 0,
            'progress_description' => $this->progress_description ?? '0% completed',
            'items_progress' => [
                [
                    'name' => 'Characters',
                    'progress' => 0,
                    'total' => $this->characters->count(),
                ],
                [
                    'name' => 'Sequences',
                    'progress' => 0,
                    'total' => 0,
                ],
                [
                    'name' => 'Themes',
                    'progress' => 0,
                    'total' => 0,
                ],
                [
                    'name' => 'Appeal',
                    'progress' => 0,
                    'total' => 0,
                ],
            ],

            'market_comps' => $this->market_comps ?? [],
            'characters' => $this->characters ?? [],
            'target_audience' => $this->target_audience ?? [],
            'impactful_scenes' => $this->impactful_scenes ?? [],
            'drafts' => $this->drafts ?? [],
            'discrepancies' => $this->discrepancies ?? [],
            'achievements' => $this->achievements ?? [],
        ];
    }
}
