<?php

namespace Tests\Feature;

use App\Engine\Context\StoryContext;
use App\Engine\ConversationEngine;
use App\Engine\ConversationEngineV2;
use App\Engine\Processing\MockProcessing;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StoryConversationEngineTest extends TestCase
{
    #[Test]
    #[Group("story_conversation")]
    public function testBasic()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $engine = ConversationEngine::make('stories')
            ->withMockResponses([
                'rateResponse' => [
                    'answer_rating' => true,
                    'topic_change' => false,
                    'is_skipped' => false,
                    'user_not_understand' => false,
                    'user_dont_know' => false,
                ],
                'extractData' => [
                    [
                        '_filters' => [
                            'answer' => 'answer 1 start'
                        ],
                        'response' => [
                            'foo' => 'bar'
                        ]
                    ]
                ],
                'generateNextQuestion' => [
                    'question' => 'new question',
                    'message' => 'new question',
                    'data_points' => [],
                ],
                //This is a story about a Galactic war between aliens and
                //humans. Main character Lucy finds out that she has superpowers
                //and gets rolled into human’s special forces to fight with aliens.
                //When on the task on planet Nibiru she talks to alien Jabu that tells
                //her that aliens doesn’t want to fight with humans, so she joins the rebels
                //to fight the corrupted government.
                'extractCategories' => [
                    [
                        'response' => [
                            [
                                'type' => 'Setting',
                                'elements' => [
                                    [
                                        'name' => 'Galactic war between aliens and humans'
                                    ],
                                    [
                                        'name' => 'Planet Nibiru'
                                    ]
                                ]
                            ],
                            [
                                'type' => 'Character',
                                'elements' => [
                                    [
                                        'name' => 'Lucy',
                                        'race' => 'Human',
                                        'superpowers' => true
                                    ],
                                    [
                                        'name' => 'Jabu',
                                        'race' => 'Human'
                                    ],
                                    [
                                        'name' => 'Aliens',
                                    ],
                                    [
                                        'name' => 'Humans',
                                    ],
                                    [
                                        'name' => 'Rebels',
                                    ],
                                    [
                                        'name' => 'Corrupted government',
                                    ]
                                ]
                            ],
                            [
                                'type' => 'Plot',
                                'elements' => [
                                    [
                                        'name' => 'Galactic war between aliens and humans'
                                    ],
                                    [
                                        'name' => 'Lucy finds out that she has superpowers'
                                    ],
                                    [
                                        'name' => 'Lucy gets rolled into human’s special forces to fight with aliens'
                                    ],
                                    [
                                        'name' => 'Lucy talks to alien Jabu that tells her that aliens dont want to fight with humans'
                                    ],
                                    [
                                        'name' => 'Lucy finds out that government is corrupted'
                                    ],
                                    [
                                        'name' => 'Lucy joins the rebels to fight the corrupted government'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
            ]);

        $engine->process('answer 1 start');

        $engine->process('answer 2');
    }

    #[Test]
    #[Group("test")]
    public function test()
    {
        $user = User::factory()->hasStories(1)->create();
        Auth::login($user);

        // able to create engine without context
        $engine = ConversationEngineV2::make();
        // able to create engine with context but without model
        $engine = ConversationEngineV2::make(new StoryContext());
        // able to create engine with context and model
        $engine = ConversationEngineV2::make(new StoryContext($user->stories->first()));


        $engine = ConversationEngineV2::make()
            ->setProcessing(
                MockProcessing::make()
                    ->addResponse('rateResponse', [
                        'answer_rating' => true,
                        'topic_change' => false,
                        'is_skipped' => false,
                        'user_not_understand' => false,
                        'user_dont_know' => false,
                    ])
                    ->addResponse('extractData', [
                        'elements' => [
                            'Writer' => [
                                [
                                    'name' => 'John Doe',
                                ]
                            ],
                            'Story' => [
                                [
                                    'setting' => 'Galactic war between aliens and humans',
                                    'elements' => [

                                    ]
                                ]
                            ],
                            'Character' => [
                                [
                                    'name' => 'Lucy',
                                ],
                                [
                                    'name' => 'Jabu',
                                ],
                                [
                                    'name' => 'Aliens',
                                ],
                                [
                                    'name' => 'Humans',
                                ],
                                [
                                    'name' => 'Rebels',
                                ],
                                [
                                    'name' => 'Corrupted government',
                                ]
                            ]
                        ]
                    ])
                    ->addResponse('generateNextQuestion', [
                        'question' => 'some question',
                        'title' => 'new question',
                        'data_points' => [
                            'name'
                        ],
                    ])
                    ->addResponse('extractData', [
                        'elements' => [
                            'Character' => [
                                [
                                    'name' => 'Lucy',
                                ],
                                [
                                    'name' => 'Jabu',
                                ],
                                [
                                    'name' => 'Aliens',
                                ],
                                [
                                    'name' => 'Humans',
                                ],
                                [
                                    'name' => 'Rebels',
                                ],
                                [
                                    'name' => 'Corrupted government',
                                ]
                            ]
                        ]
                    ])
            );

        $engine->process('answer 1 start');

        $engine->process('answer 2');
    }
}
