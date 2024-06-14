<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\User;
use App\Models\UserAchievement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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

    private static function getAchievementProgress(Achievement $achievement, array $extracted): int
    {
        $requirements = $achievement->dataPoints->pluck('slug')->toArray();
        $totalRequirements = count($requirements);
        $metRequirements = $totalRequirements - count(array_diff($requirements, array_keys($extracted)));
        return $metRequirements / $totalRequirements * 100;
    }

    /**
     * @param User $user The user to update achievements for
     * @param array $extracted The extracted data from the user
     * @param Collection $achievements The achievements to check for progress
     * @param Model $target The target model for the achievements
     * @return void
     */
    public static function updateProgress(User $user, array $extracted, Collection $achievements, Model $target): void
    {
        foreach ($achievements as $achievement) {
            $ua = $user->userAchievements()
                ->where('achievement_id', $achievement->id)
                ->where('target_id', $target->id)
                ->where('target_type', get_class($target))
                ->first();

            if ($ua) {
                if ($ua->completed_at) {
                    continue;
                }
            }

            $progress = static::getAchievementProgress($achievement, $extracted);
            if ($progress > 0) {
                if ($ua) {
                    $ua->progress = $progress;
                    $ua->save();
                } else {
                    UserAchievement::create([
                        'achievement_id' => $achievement->id,
                        'user_id' => $user->id,
                        'target_id' => $target->id,
                        'target_type' => get_class($target),
                        'progress' => $progress,
                    ]);
                }
            }
        }
    }
}
