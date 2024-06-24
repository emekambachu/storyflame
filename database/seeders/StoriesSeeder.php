<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Character;
use App\Models\DataPoint;
use App\Models\Story;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserDataPoint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class StoriesSeeder extends Seeder
{
    private function seedUserAchievement(Model $target, User $user, Achievement $achievement): void
    {
        $userAchievement = UserAchievement::factory()->hasSummaries(2)->createOne([
            'user_id' => $user->id,
            'achievement_id' => $achievement->id,
            'target_type' => $target::class,
            'target_id' => $target->id,
        ]);

        $achievement->dataPoints->each(function (DataPoint $dataPoint) use ($userAchievement) {
            $userAchievement->userDataPoints()->save(
                UserDataPoint::factory()->createOne([
                    'user_id' => $userAchievement->user_id,
                    'target_id' => $userAchievement->target_id,
                    'target_type' => $userAchievement->target_type,
                    'data_point_id' => $dataPoint->id,
                    'user_achievement_id' => $userAchievement->id,
                    'data' => $dataPoint->type === 'array' ? ['foo', 'bar', 'baz'] : 'foo',
                ])
            );
        });

        $target->achievements()->save($userAchievement);
    }

    public function run(): void
    {
        $user = User::factory()->createOne([
            'name' => 'Mocked User',
            'email' => 'mock@example.com',
            'password' => bcrypt('password'),
        ]);

        $storyAchievements = Achievement::whereCategory('Story')
            ->get();
        $characterAchievements = Achievement::whereCategory('Character')
            ->get();

        Story::factory(1, [
            'user_id' => $user->id,
        ])
            ->hasImages(1)
            ->hasCharacters(5)
            ->create()
            ->each(function (Story $story) use ($characterAchievements, $storyAchievements) {
                $storyAchievements->each(function (Achievement $achievement) use ($story) {
                    $this->seedUserAchievement($story, $story->user, $achievement);
                });

                $story->characters->each(function (Character $character) use ($characterAchievements) {
                    $characterAchievements->each(function (Achievement $achievement) use ($character) {
                        $this->seedUserAchievement(
                            $character,
                            $character->story->user,
                            $achievement
                        );
                    });
                });
            });
    }
}
