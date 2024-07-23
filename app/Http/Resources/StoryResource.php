<?php

namespace App\Http\Resources;

use App\Http\Resources\Cards\CharacterCardResource;
use App\Http\Resources\Cards\SequenceCardResource;
use App\Http\Resources\Cards\ThemeCardResource;
use App\Models\Achievement;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Story */
class StoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        Achievement::whereCategory('Story')
            ->whereNotIn('id', $this->achievements->pluck('achievement_id'))
            ->get()
            ->each(function ($achievement) {
                $this->achievements()->firstOrCreate([
                    'user_id' => $this->user_id,
                    'achievement_id' => $achievement->id,
                    'target_type' => Story::class,
                    'target_id' => $this->id,
                ], [
                    'progress' => 0,
                    'completed_at' => null,
                ]);
            });

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => ImageResource::make($this->images->first()),
            'type' => 'Story',
            'summaries' => [
                'logline' => $this->summary('story_header_story_logline'),
                'goals' => $this->summary('story_aka_episode_novel_story_goal_story_goal_summary'),
                'promise' => $this->summary('story_aka_episode_novel_story_goal_story_audience_promise'),
                'arc_setup' => $this->summary('story_arc_setup'),
                'arc_inciting_incident' => $this->summary('story_arc_inciting_incident'),
                'arc_trials_and_complications' => $this->summary('story_arc_trials_and_complications'),
                'arc_midpoint_twist' => $this->summary('story_arc_midpoint_twist'),
                'arc_crisis_point' => $this->summary('story_arc_crisis_point'),
                'arc_climax' => $this->summary('story_arc_climax'),
                'arc_resolution' => $this->summary('story_arc_resolution'),
                'arc_hook' => $this->summary('story_arc_hook'),
            ],
            'genres' => $this->genres ?? [],
            'format' => $this->format ?? [],
            'progress' => $this->progress ?? 0,
            'progress_description' => $this->progress_description ?? '0% completed',
            'elements' => [
                'characters' => [
                    'progress' => 0,
                    'count' => $this->characters->count(),
                    'elements' => CharacterCardResource::collection($this->characters)
                ],
                'sequences' => [
                    'progress' => 0,
                    'count' => $this->sequences->count(),
                    'elements' => SequenceCardResource::collection($this->sequences)
                ],
                'themes' => [
                    'progress' => 0,
                    'count' => $this->themes->count(),
                    'elements' => ThemeCardResource::collection($this->themes)
                ],
                'appeal' => [
                    'progress' => 0,
                    'count' => 0,
                    'elements' => []
                ],
            ],

            'achievements' => [
                'completed' => UserAchievementResource::collection($this->achievements()->completed()->limit(5)->get()),
                'in_progress' => UserAchievementResource::collection($this->achievements()->inProgress()->limit(5)->get()),
                'up_next' => UserAchievementResource::collection(
                    $this->achievements()
                        ->join('achievements', 'achievements.id', '=', 'user_achievements.achievement_id')
                        ->upNext()
                        ->orderBy('achievements.dev_order', 'asc')
                        ->orderBy('achievements.total_impact', 'desc')
                        ->limit(5)
                        ->get('user_achievements.*')
                ),
            ],

            'market_comps' => $this->market_comps ?? [],
            'characters' => $this->characters ?? [],
            'target_audience' => $this->target_audience ?? [],
            'impactful_scenes' => $this->impactful_scenes ?? [],
            'drafts' => $this->drafts ?? [],
            'discrepancies' => $this->discrepancies ?? [],
//            'achievements' => $this->achievements ?? [],
        ];
    }
}
