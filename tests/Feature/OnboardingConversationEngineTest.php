<?php

namespace Tests\Feature;

use App\Engine\Config\OnboardingEngineConfig;
use App\Engine\Context\BaseContext;
use App\Engine\ConversationEngineV2;
use App\Engine\Processing\MockProcessing;
use App\Models\Achievement;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OnboardingConversationEngineTest extends TestCase
{
    #[Test]
    #[Group("onboarding")]
    // sail artisan test --group onboarding
    public function test_fast_onboarding_scenario()
    {
        Notification::fake();

        $user = User::factory()->create([
            'name' => 'Definitely Not John Doe',
        ]);
        Auth::login($user);

        $achievement = Achievement::firstWhere('slug', 'writer_identity');
        $extractionData = $achievement->dataPoints->mapWithKeys(fn($dp) => [$dp->slug => $dp->type === 'array' ? json_decode($dp->example) : $dp->example])->toArray();

        $engine = ConversationEngineV2::make(BaseContext::make($user)->withConfig(new OnboardingEngineConfig()))
            ->setProcessing(
                MockProcessing::make()
                    ->addResponse('rateResponse', [
                        'answer_rating' => true,
                        'topic_change' => false,
                        'is_skipped' => false,
                        'user_not_understand' => false,
                        'user_dont_know' => false,
                    ])
                    ->addResponse('extractCategories', [
                        'categories' => [
                            'Writer' => [
                                [
                                    'name' => 'John Doe',
                                ]
                            ],
                            'Story' => [
                                [
                                    'name' => 'Test',
                                    'setting' => 'Galactic war between aliens and humans'
                                ]
                            ],
                        ]
                    ])
                    ->addResponse('extractData',
                        [
                            [
                                'data_points' => [
                                    [
                                        'data_point_id' => 'writer_writer_name',
                                        'data_point_value' => 'John Doe'
                                    ],
                                    [
                                        'data_point_id' => 'writer_origin_story',
                                        'data_point_value' => 'Galactic war between aliens and humans'
                                    ]
                                ]
                            ]
                        ])
                    ->addResponse('extractData', [
                        [
                            'data_points' => [
                                [
                                    'data_point_id' => 'writer_writer_name',
                                    'data_point_value' => 'John Doe'
                                ],
                                [
                                    'data_point_id' => 'writer_origin_story',
                                    'data_point_value' => 'Galactic war between aliens and humans'
                                ]
                            ]
                        ]
                    ])
            );

        // first question should be "What is your name?"
        $engine->process('John Doe');

        # only one achievement should have been created and had progress
        $this->assertCount(1, $user->userAchievements, 'User should have 1 achievement');
        $this->assertDatabaseHas('user_achievements', [
            'user_id' => $user->id,
            'achievement_id' => $achievement->id,
            'target_id' => $user->id,
            'target_type' => User::class,
            'completed_at' => null,
        ]);
        // assert that name is updated
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'John Doe',
        ]);

        // next question should be "Tell us about yourself as a writer"
        // if we provide all the data to one achievement it should finish the conversation
        $this->assertEquals(
            'finish',
            $engine->process('I am a writer at big publishing house, I write novels and short stories.')->content
        );


        $this->assertDatabaseHas('user_achievements', [
            'user_id' => $user->id,
            'achievement_id' => $achievement->id,
            'target_id' => $user->id,
            'target_type' => User::class,
            'progress' => 100,
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $extractionData['name']
        ]);

        Notification::assertSentTo($user, \App\Notifications\AchievementUnlocked::class);
        Notification::assertCount(1);
    }

    #[Test]
    #[Group("onboarding")]
    public function test_onboarding_scenario()
    {
        Notification::fake();

        $user = User::factory()->create([
            'name' => 'Definitely Not John Doe',
        ]);
        Auth::login($user);

        $achievement = Achievement::firstWhere('slug', 'writing_style');
        $extractionData = $achievement
            ->dataPoints
            ->mapWithKeys(
                fn($dp) => [
                    $dp->slug => $dp->type === 'array'
                        ? json_decode($dp->example ?? '[]')
                        : $dp->example ?? 'empty'
                ])
            ->toArray();

        $engine = ConversationEngineV2::make(BaseContext::make($user)->withConfig(new OnboardingEngineConfig()))
            ->setProcessing(
                MockProcessing::make()
                    ->addResponse('rateResponse', [
                        'answer_rating' => true,
                        'topic_change' => false,
                        'is_skipped' => false,
                        'user_not_understand' => false,
                        'user_dont_know' => false,
                    ], [
                        'answer' => 'test response 1'
                    ])
                    ->addResponse('rateResponse', [
                        'answer_rating' => false,
                        'topic_change' => false,
                        'is_skipped' => false,
                        'user_not_understand' => false,
                        'user_dont_know' => true,
                    ], [
                        'answer' => 'test response 2'
                    ])
                    ->addResponse('rateResponse', [
                        'answer_rating' => false,
                        'topic_change' => false,
                        'is_skipped' => false,
                        'user_not_understand' => true,
                        'user_dont_know' => false,
                    ], [
                        'answer' => 'test response 3'
                    ])
                    ->addResponse('rateResponse', [
                        'answer_rating' => true,
                        'topic_change' => false,
                        'is_skipped' => false,
                        'user_not_understand' => false,
                        'user_dont_know' => false,
                    ], [
                        'answer' => 'test response 4'
                    ])
                    ->addResponse('extractData', [
                        'elements' => [
                            'Writer' => [
                                [
                                    'name' => 'John Doe',
                                ]
                            ],
                        ]
                    ])
                    ->addResponse('extractData', [
                        'elements' => [
                            'Writer' => [
                                $extractionData
                            ]
                        ]
                    ], [
                        'answer' => 'test response 4'
                    ])
                    ->addResponse('generateNextQuestion', [
                        'question' => 'brainstorming question 2',
                        'title' => 'brainstorming message 2',
                        'data_points' => []
                    ], [
                        'type' => 'brainstorm',
                    ])
                    ->addResponse('generateNextQuestion', [
                        'question' => 'follow-up question 3',
                        'title' => 'follow-up message 3',
                        'data_points' => []
                    ], [
                        'type' => 'follow-up',
                    ])
                    ->addResponse('generateNextQuestion', [
                        'question' => 'end',
                        'title' => 'end',
                        'data_points' => []
                    ], [
                        'type' => 'basic',
                    ])
            );

        $engine->process('test response 1');

        $this->assertEquals(
            'brainstorming question 2',
            $engine->process('test response 2')->content
        );

        $this->assertEquals(
            'follow-up question 3',
            $engine->process('test response 3')->content
        );

        Notification::assertNothingSent();

        $this->assertEquals(
            'end',
            $engine->process('test response 4')->content
        );
    }
}
