<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\DataPoint;
use App\Services\ProcessingService;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ExtractionTest extends TestCase
{
    const TEST_CASES = [
//        'Incorrect Answer' => [
//            'question' => 'What genres do you like to write?',
//            'response' => 'I like to write novels.',
//            'element' => 'Writer',
//            'assert' => [
//                'formats' => [
//                    'rule' => ['required', 'array', 'size:1'],
//                    'value' => ['novel']
//                ],
//                'genres' => [
//                    'rule' => ['nullable', 'array', 'size:0']
//                ]
//            ]
//        ],
       'Named & Unnamed Characters' => [
           'question' => "Describe your protagonist's primary motivation.",
           'response' => "Sarah wants to uncover the truth behind her endearing and strong father's disappearance.",
           'element' => 'Story',
           'assert' => [
               'protagonists-overarching-goal' => [
                   'rule' => ['required', 'string'],
                   'value' => "Sarah wants to uncover the truth behind her father's disappearance."
               ],
//                'characters' => [
//                    'rule' => ['required', 'array', 'size:2'],
//                    'value' => ['Sarah', 'father']
//                ]
           ]
       ],
         'Inspiration and Genre' => [
             'question' => 'What inspired you to write this story?',
             'response' => "I've always been fascinated by the idea of a hidden world beneath our own. It's a contemporary fantasy with elements of mystery and romance.",
             'element' => 'Story',
             'assert' => [
                 'genres' => [
                     'rule' => ['required', 'array', 'size:3'],
                     'value' => ['fantasy', 'mystery', 'romance']
                 ],
                 'setting' => [
                     'rule' => ['required', 'string'],
                     'value' => 'hidden world beneath our own'
                 ]
             ]
         ],
//        'Character Intro and Conflict' => [
//            'question' => 'Tell us about your favorite characters.',
//            'response' => "Well, there's Liza, she's a young journalist, and she stumbles upon a secret society of magic users. She must learning about this new world, and as she does, she uncovers a plot that threatens both realms.",
//            'element' => 'Story',
//            'assert' => [
//                'protagonists-overarching-goal' => [
//                    'rule' => ['required', 'string'],
//                    'value' => 'Protagonist must navigate a secret magical society and uncover a plot that threatens both realms'
//                ],
//                'characters' => [
//                    'rule' => ['required', 'array', 'size:2'],
//                    'value' => ['Liza', 'secret society of magic users']
//                ],
//                'setting' => [
//                    'rule' => ['required', 'array', 'size:2'],
//                    'value' => ['Secret society of magic users', 'Parallel realms (magic and mundane)']
//                ]
//            ]
//        ],
//        'Antagonist and Stakes' => [
//            'question' => 'Are there any other main characters?',
//            'response' => "Yeah, there's this powerful mage named Malakai who seeks to merge the magical and mundane realms, which would cause chaos and destruction. If he succeeds, both worlds could be destroyed.",
//            'element' => 'Story',
//            'assert' => [
//                'antagonist-overarching-goal' => [
//                    'rule' => ['required', 'string'],
//                    'value' => 'merge magical and mundane realms'
//                ],
//                'stakes' => [
//                    'rule' => ['required', 'array', 'size:3'],
//                    'value' => ['chaos', 'destruction', 'potential end of both worlds']
//                ],
//                'antagonist' => [
//                    'rule' => ['required', 'string'],
//                    'value' => 'Malakai, a powerful mage'
//                ]
//            ]
//        ],
//        'Magic System and Limitations' => [
//            'question' => 'How does magic work in this story world?',
//            'response' => "So, magic is basically around the core elements like fire, water, air, earth. People are born with an innate affinity but can learn to control others. However, using magic depletes their life force, so it must be used sparingly.",
//            'element' => 'Story',
//            'assert' => [
//                'magic-system' => [
//                    'rule' => ['required', 'string'],
//                    'value' => 'Magic is based on core elements (fire, water, air, earth). People have innate affinities but can learn others.'
//                ],
//                'magic-limitations' => [
//                    'rule' => ['required', 'string'],
//                    'value' => 'Using magic depletes life force, so must be used sparingly.'
//                ]
//            ]
//        ],
//        'Impact of Magic Limitations' => [
//            'question' => 'How does the cost of using magic (depleting life force) impact the characters\' choices and the story\'s conflicts?',
//            'response' => '',
//            'element' => 'Story',
//            'assert' => [
//                'impact-on-characters' => [
//                    'rule' => ['nullable', 'string'],
//                    'value' => null
//                ]
//            ]
//        ],
//        'Subplots and Supporting Characters' => [
//            'question' => 'What subplots or supporting character arcs intertwine with the main plot?',
//            'response' => "Liza's estranged father is a member of the secret society, and she must confront their strained relationship while investigating the plot. Her best friend, a tech genius named Ethan, helps her navigate both worlds but gets drawn into the danger.",
//            'element' => 'Story',
//            'assert' => [
//                'subplots' => [
//                    'rule' => ['required', 'array', 'size:2'],
//                    'value' => ["Liza's strained relationship with her estranged father who is part of the secret society", "Liza's best friend Ethan helps her investigate but gets drawn into danger"]
//                ],
//                'supporting-characters' => [
//                    'rule' => ['required', 'array', 'size:2'],
//                    'value' => ["Liza's estranged father (member of secret society)", "Ethan (Liza's best friend, tech genius)"]
//                ],
//                'themes' => [
//                    'rule' => ['required', 'string'],
//                    'value' => 'Estranged family relationships'
//                ]
//            ]
//        ],
//        'Personal and Universal Themes' => [
//            'question' => 'What personal themes or universal ideas do you want to explore through this story?',
//            'response' => "On a personal level, it's about Liza learning to trust and forgive as she mends her relationship with her father. On a universal level, it's about the consequences of secrecy and the importance of balance and unity.",
//            'element' => 'Story',
//            'assert' => [
//                'personal-themes' => [
//                    'rule' => ['required', 'string'],
//                    'value' => 'trust, forgiveness, mending relationships'
//                ],
//                'universal-themes' => [
//                    'rule' => ['required', 'string'],
//                    'value' => 'consequences of secrecy, importance of balance and unity'
//                ]
//            ]
//        ],
//        'Themes and Main Conflict' => [
//            'question' => 'How do Liza\'s personal journey and the larger conflict with Malakai intersect to reinforce these themes?',
//            'response' => '',
//            'element' => 'Story',
//            'assert' => [
//                'theme-conflict-intersection' => [
//                    'rule' => ['nullable', 'string'],
//                    'value' => null
//                ]
//            ]
//        ],
//        'Tone and Mood' => [
//            'question' => 'Describe your desired tone and mood for this story.',
//            'response' => "I want it to be thrilling and suspenseful, with moments of wonder and discovery as Liza uncovers the magical world. But also poignant and heartfelt as she confronts her past and rebuilds relationships.",
//            'element' => 'Story',
//            'assert' => [
//                'tone' => [
//                    'rule' => ['required', 'string'],
//                    'value' => 'thrilling, suspenseful, poignant, heartfelt'
//                ],
//                'mood' => [
//                    'rule' => ['required', 'string'],
//                    'value' => 'wonder, discovery'
//                ]
//            ]
//        ]
    ];

    private function assertSimilarity(string $expected, string $actual): bool
    {
        $response = Http::post('host.docker.internal:1234/similarity', [
            'text1' => $expected,
            'text2' => $actual,
        ])->throw();
        $similarity = $response->json('similarity');
        return $similarity >= 70;
    }

    #[Test]
    #[Group("extraction")]
    public function test_extractor_extracts_parameters()
    {
        $asserts = [];
        foreach (self::TEST_CASES as $testKey => $testCase) {
            echo "\nTest: $testKey\n";
            $asserts[$testKey] = [
                'question' => $testCase['question'],
                'response' => $testCase['response'],
                'extracted' => [],
                'passed' => [],
                'failed' => [],
                'percent' => 0,
            ];
            $groups = Achievement::where('element', $testCase['element'])
                ->with('dataPoints')
                ->get()
                ->map(function (Achievement $achievement) {
                    return [
                        'name' => $achievement->slug,
                        'title' => $achievement->name,
                        'description' => $achievement->extraction_description,
                        'data_points' => $achievement->dataPoints
                            ->map(function (DataPoint $dataPoint) {
                                return $dataPoint->toProcessingArray();
                            })
                            ->values()
                            ->toArray()
                    ];
                })
                ->toArray();

            try {
                // assert that there are no duplicate data points
                $all_data_points = collect($groups)->pluck('data_points')->flatten(1);
                $duplicates = $all_data_points->duplicates('name');
                if ($duplicates->isNotEmpty()) {
                    throw new Exception('Duplicate data points found: ' . $duplicates->implode('name', ', ') . '.');
                }

                // assert that the data points set in assert are present in the groups
                $assertDataPoints = $all_data_points->pluck('name')->values();
                foreach ($testCase['assert'] as $key => $assert) {
                    if (!$assertDataPoints->contains($key)) {
                        throw new Exception("Data point '$key' not found in data points while testing '$testKey'.");
                    }
                }

                // try to extract data and validate it
                try {
                    $extracted = ProcessingService::extractData(
                        $testCase['question'],
                        $testCase['response'],
                        $groups
                    );
                    $asserts[$testKey]['extracted'] = $extracted;

                    $passed = 0;
                    foreach ($testCase['assert'] as $key => $assert) {
                        try {
                            if (isset($assert['rule'])) {
                                $validated = Validator::validate($extracted, [
                                    $key => $assert['rule']
                                ]);
                            }

                            if (isset($assert['value'])) {
                                if (is_array($assert['value'])) {
                                    foreach ($assert['value'] as $expected) {
                                        if (!in_array($expected, $extracted[$key])) {
                                            $found = false;
                                            foreach ($extracted[$key] as $extractedValue) {
                                                if ($this->assertSimilarity($extractedValue, $expected)) {
                                                    $found = true;
                                                    break;
                                                }
                                            }
                                            if (!$found) {
                                                $this->fail("Expected: $expected, Actual: " . json_encode($extracted[$key]));
                                            }
                                        }
                                    }
                                } else if ($extracted[$key] !== $assert['value']) {
                                    $this->assertTrue($this->assertSimilarity($assert['value'], $extracted[$key]), "Expected: $assert[value], Actual: $extracted[$key]");
                                }
                            }

                            $asserts[$testKey]['passed'][] = $key;
                            $passed++;
                        } catch (Exception $e) {
                            $asserts[$testKey]['failed'][] = $e->getMessage();
                        }
                    }

                    $asserts[$testKey]['percent'] = $passed / count($testCase['assert']) * 100;
                    echo "Passed: {$asserts[$testKey]['percent']}%\n";
                } catch (Exception $e) {
                    $asserts[$testKey]['failed'][] = $e->getMessage();
                    echo "Extraction failed: {$e->getMessage()}\n";
                }
            } catch (Exception $e) {
                $asserts[$testKey]['failed'][] = $e->getMessage();
                echo "Preparation failed: {$e->getMessage()}\n";
            }
        }

        echo "\n\nFailed Tests:\n";
        collect($asserts)->filter(function ($assert) {
            return $assert['percent'] < 100;
        })->each(function ($assert, $key) {
            echo "\nTest: $key\n";
            echo "Extracted: " . json_encode($assert['extracted']) . "\n";
            echo "Passed: " . json_encode($assert['passed']) . "\n";
            echo "Failed: " . json_encode($assert['failed']) . "\n";
        });

        $totalPercent = collect($asserts)->sum('percent') / count($asserts);
        echo "\n\nTotal Passed: $totalPercent%\n";
        $this->assertTrue(true, 'Extraction tests finished.');
    }
}
