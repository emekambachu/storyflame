<?php

namespace App\Services;

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
            'icon' => 'marketability.png',
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
            'icon' => 'process.png',
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
            'icon' => 'profession.png',
            'user_data' => [
                'motivation',
                'education',
                'stage',
            ]
        ],
    ];

    private function collectUserData(User $user): array
    {
        $userData = $user->attributesToArray();
        $userData = array_merge($userData, $user->extra_attributes['onboarding'] ?? [], $user->extra_attributes['data'] ?? []);
        $userData['media'] = $user->favoriteMovies;
        unset($userData['onboarding'], $userData['data']);
        return $userData;
    }

    private function checkProgress(User $user): array
    {
        $userData = $this->collectUserData($user);
        $progress = [];
        foreach (self::ACHIEVEMENTS as $achievement => $requirements) {
            $totalRequirements = count($requirements['user_data']);
            $metRequirements = $totalRequirements - count(array_diff($requirements['user_data'], array_keys($userData)));
            $progress[$achievement] = $metRequirements / $totalRequirements * 100;
        }
        return $progress;
    }

    public function updateProgress(User $user): void
    {
        foreach ($this->checkProgress($user) as $achievement => $progress) {
            if ($progress > 0) {
                $user->achievements()->updateOrCreate([
                    'name' => $achievement,
                ], [
                    'progress' => $progress,
                ]);
            }
        }
    }
}
