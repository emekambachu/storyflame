<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\DataPoint;
use App\Models\Story;
use App\Models\StoryElements\Character;
use App\Models\StoryElements\StorySequence;
use App\Models\StoryElements\StoryTheme;
use App\Models\Summary\Summary;
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
//        $user = User::factory()->createOne([
//            'name' => 'Mocked User',
//            'password' => bcrypt('password'),
//        ]);
        $user = User::find(3);

        $storyAchievements = Achievement::whereCategory('Story')
            ->get();
        $characterAchievements = Achievement::whereCategory('Character')
            ->get();

        Story::factory(1, [
            'user_id' => $user->id,
        ])
//            ->hasImages(1)
            ->hasCharacters(5)
            ->hasSequences(5)
            ->hasThemes(5)
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

                Summary::whereCategory(['Story', 'Story (aka episode, novel)'])->get()
                    ->each(function (Summary $summary) use ($story) {
                        $story->summaries()->create([
                            'summary_id' => $summary->id,
                            'summary' => 'Mocked ' . $summary->name,
                            'user_id' => $story->user_id,
                        ]);
                    });

                Summary::whereCategory('Character')->get()
                    ->each(function (Summary $summary) use ($story) {
                        $story->characters->each(function (Character $character) use ($summary) {
                            $character->summaries()->create([
                                'summary_id' => $summary->id,
                                'summary' => 'Mocked ' . $summary->name,
                                'user_id' => $character->story->user_id,
                            ]);
                        });
                    });

                Summary::whereCategory('Sequence')->get()
                    ->each(function (Summary $summary) use ($story) {
                        $story->sequences->each(function (StorySequence $sequence) use ($summary) {
                            $sequence->summaries()->create([
                                'summary_id' => $summary->id,
                                'summary' => 'Mocked ' . $summary->name,
                                'user_id' => $sequence->story->user_id,
                            ]);
                        });
                    });

                Summary::whereCategory('Theme')->get()
                    ->each(function (Summary $summary) use ($story) {
                        $story->themes->each(function (StoryTheme $theme) use ($summary) {
                            $theme->summaries()->create([
                                'summary_id' => $summary->id,
                                'summary' => 'Mocked ' . $summary->name,
                                'user_id' => $theme->story->user_id,
                            ]);
                        });
                    });
            });
    }
}
