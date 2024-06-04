<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\User;

class AchievementService
{
    public const ACHIEVEMENTS = [
        'icebreaker' => [
            'title' => 'Ice Breaker',
            'description' => "Hooray, you’ve earned your very first badge!",
            'icon' => 'icebreaker.png',
            'user_data' => [
                'name',
                'genre_focus',
                'writing_medium',
            ]
        ],
        'recommendation_ready' => [
            'title' => 'Recommendation Ready',
            'description' => 'You’re ready to get recommendations!',
            'icon' => 'recommendation.png',
            'user_data' => [
                'media',
                'characters',
                'audience',
                'themes',
            ]
        ],
        'process' => [
            'title' => 'Process',
            'description' => 'You’ve got your process down!',
            'icon' => 'process.png',
            'user_data' => [
                'productivity',
                'writing_process',
                'stage_preference',
                'revision_style',
            ]
        ],
        'collaborator' => [
            'title' => 'Collaborator',
            'description' => 'You’re ready to collaborate!',
            'icon' => 'collaborator.png',
            'user_data' => [
                'collaboration_style',
                'adaptability',
            ]
        ],
        'sculptor' => [
            'title' => 'Sculptor',
            'description' => 'You’re ready to sculpt your story!',
            'icon' => 'profession.png',
            'user_data' => [
                'world_engagement',
                'narrative_focus',
            ]
        ],
        'growth_guru' => [
            'title' => 'Growth Guru',
            'description' => 'You’re ready to grow your story!',
            'icon' => 'growth.png',
            'user_data' => [
                'motivation',
                'education',
                'stage',
            ]
        ],
    ];

    private static function collectUserData(User $user): array
    {
        $userData = $user->attributesToArray();
        $userData = array_merge($userData, $user->extra_attributes['onboarding'] ?? [], $user->extra_attributes['data'] ?? []);
        $userData['media'] = $user->favoriteMovies;
        unset($userData['onboarding'], $userData['data']);
        return $userData;
    }

    private static function checkProgress(User $user, array $extracted): array
    {
        $progress = [];
        foreach (Achievement::all() as $achievement) {
            $requirements = $achievement->dataPoints->pluck('slug')->toArray();
            $totalRequirements = count($requirements);
            $metRequirements = $totalRequirements - count(array_diff($requirements, array_keys($extracted)));
            $progress[$achievement->id] = $metRequirements / $totalRequirements * 100;
        }
        return $progress;
    }

    public static function updateProgress(User $user, array $extracted): void
    {
        foreach (self::checkProgress($user, $extracted) as $achievement => $progress) {
            $user->achievements()->syncWithoutDetaching([
                $achievement => [
                    'progress' => $progress,
                ]
            ]);
        }
    }
}
