<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class OnboardingService
{
    public function __construct(
        private readonly ChatService          $chatService,
        private readonly TranscriptionService $transcriptionService,
    )
    {
    }

    const ACHIEVEMENTS = [
        'basic',
        'basic-bio',
        'icebreaker',
        'recommendation_ready',
        'process',
        'collaborator',
        'sculptor',
        'growth_guru',
    ];

    const EXTRACTION_DATA = [
        'name' => [
            'achievement' => 'basic',
            'name' => 'Name',
            'question' => 'What is your name?',
            'description' => 'Name of the user',
        ],
        'bio' => [
            'achievement' => 'basic-bio',
            'name' => 'Bio',
            'question' => 'Tell us about yourself as a writer.',
            'description' => 'Some information about the user.',
        ],

        'level' => [
            'achievement' => 'icebreaker',
            'name' => 'Skill Level',
            'examples' => ['beginner', 'intermediate', 'advanced', 'professional'],
            'description' => 'Skill level of the user',
        ],
        'genre_focus' => [
            'achievement' => 'icebreaker',
            'name' => 'Genre Focus',
            'examples' => ['fantasy', 'sci-fi', 'horror', 'romance', 'mystery', 'thriller', 'comedy', 'drama'],
            'description' => 'Genre focus of the user',
        ],
        'writing_medium' => [
            'achievement' => 'icebreaker',
            'name' => 'Writing Medium',
            'examples' => ['TV', 'Film', 'Novelist', 'Short Story Writer', 'Poet', 'Playwright', 'Screenwriter', 'Copywriter'],
            'description' => 'Writing medium of the user',
        ],

        'media' => [
            'achievement' => 'recommendation_ready',
            'name' => 'Influential Media',
            'description' => 'Media that influences the user',
        ],
        'characters' => [
            'achievement' => 'recommendation_ready',
            'name' => 'Influential Characters',
            'description' => 'Characters that influence the user',
        ],
        'audience' => [
            'achievement' => 'recommendation_ready',
            'name' => 'Target Audience',
            'description' => 'Target audience of the user',
        ],
        'themes' => [
            'achievement' => 'recommendation_ready',
            'name' => 'Themes',
            'examples' => ['Identity', 'Love', 'Loss', 'Coming of age', 'Social issues', 'Politics', 'History'],
            'description' => 'Themes or subjects that the user is interested in',
        ],

        'productivity' => [
            'achievement' => 'process',
            'name' => 'Productivity',
            'examples' => ['high-output', 'steady', 'sporadic', 'procrastinator'],
            'description' => 'Productivity style of the user',
        ],
        'writing_process' => [
            'achievement' => 'process',
            'name' => 'Writing Process',
            'examples' => ['plotter', 'pantser', 'plantser'],
            'description' => 'Writing process of the user',
        ],
        'stage_preference' => [
            'achievement' => 'process',
            'name' => 'Writing Stage Preference',
            'examples' => ['starter', 'polisher', 'finisher'],
            'description' => 'Writing stage preference of the user',
        ],
        'revision_style' => [
            'achievement' => 'process',
            'name' => 'Revision Style',
            'examples' => ['iterative', 'holistic', 'collaborative', 'intuitive'],
            'description' => 'Revision style of the user',
        ],

        'collaboration_style' => [
            'achievement' => 'collaborator',
            'name' => 'Collaboration Style',
            'examples' => ['solo', 'collaborator', 'ghostwriter', 'partner'],
            'description' => 'Collaboration style of the user',
        ],
        'adaptability' => [
            'achievement' => 'collaborator',
            'name' => 'Adaptability',
            'examples' => ['versatile', 'specialized', 'open to feedback', 'resistant to change'],
            'description' => 'Adaptability of the user',
        ],

        'world_engagement' => [
            'achievement' => 'sculptor',
            'name' => 'World Engagement',
            'examples' => ['builder', 'inhabitor', 'explorer', 'observer'],
            'description' => 'World engagement of the user',
        ],
        'narrative_focus' => [
            'achievement' => 'sculptor',
            'name' => 'Narrative Focus',
            'examples' => ['plot-driven', 'character-centric', 'dialogue-oriented', 'theme-focused', 'atmosphere-builder'],
            'description' => 'Narrative focus of the user',
        ],

        'motivation' => [
            'achievement' => 'growth_guru',
            'name' => 'Motivation',
            'examples' => ['passion-driven', 'career-focused', 'therapeutic', 'hobby'],
            'description' => 'Motivation of the user',
        ],
        'education' => [
            'achievement' => 'growth_guru',
            'name' => 'Educational Background',
            'examples' => ['self-taught', 'creative writing degree', 'literature degree', 'MFA', 'PhD'],
            'description' => 'Educational background of the user',
        ],
        'stage' => [
            'achievement' => 'growth_guru',
            'name' => 'Career Stage',
            'examples' => ['aspiring', 'emerging', 'established', 'bestselling', 'professional'],
            'description' => 'Career stage of the user',
        ]
    ];


    public function getQuestionCount(): int
    {
        return count(self::EXTRACTION_DATA);
    }

    public function getMessagesCache(): array
    {
        // if user registered, use database saved messages
        if ($user = auth()->user()) {
            // if user has no onboarding chat, create one
            if (!$user->chats()->where('type', 'onboarding')->exists()) {
                $chat = $user->chats()->create([
                    'type' => 'onboarding',
                ]);
            } else {
                $chat = $user->chats()->where('type', 'onboarding')->first();
            }

            // if chat has no messages, add first message
            /** @var Chat $chat */
            if ($chat->chatMessages->count() === 0) {
                $chat->chatMessages()->create([
                    'sender_id' => null,
                    'content' => self::ONBOARDING_QUESTIONS[0]['title'],
                ]);
            }

            // get messages
            return $chat->chatMessages->latest()->get()->map(function (ChatMessage $message) {
                return [
                    'agent' => $message->user_id ? 'USER' : 'ASSISTANT',
                    'content' => $message->content,
                ];
            })->toArray();
        } else {
            // if user not registered, use session messages
            $messages = session('onboarding.messages', []);

            // if no messages, add first message
            if (empty($messages)) {
                $messages[] = [
                    'agent' => 'ASSISTANT',
                    'content' => self::EXTRACTION_DATA['name']['question'],
                ];
                session()->put('onboarding.messages', $messages);
            }

            // reverse to show latest message first
            return array_reverse($messages);
        }
    }

    public function getLatestMessage(): array
    {
        return $this->getMessagesCache()[0];
    }

    public function addMessage(UploadedFile $audio = null, $content = null): void
    {
        if (!$content && !$audio) {
            throw new \InvalidArgumentException('Either content or audio must be provided');
        }

        // if user registered, save message to database
        if ($user = auth()->user()) {
            // if user has no onboarding chat, create one
            if (!$user->chats()->where('type', 'onboarding')->exists()) {
                $chat = $user->chats()->create([
                    'type' => 'onboarding',
                ]);
            } else {
                $chat = $user->chats()->where('type', 'onboarding')->first();
            }

            // create message
            $this->chatService->create($chat, $user, [
                'content' => $content,
                'audio' => $audio,
            ]);
        } else {
            // if user not registered, save message to session
            $messages = session('onboarding.messages', []);
            if ($audio) {
                $path = $audio->store('tmp');
                $transcription = $this->transcriptionService->transcribe($path);
                $messages[] = [
                    'agent' => 'USER',
                    'content' => $transcription,
                    'voice' => [
                        'content' => $transcription,
                        'path' => $path,
                    ]
                ];
            } else {
                $messages[] = [
                    'agent' => 'USER',
                    'content' => $content,
                ];
            }
            session()->put('onboarding.messages', $messages);
        }
    }

    public function extractData(array $messages, array $user_data): array
    {
        // get keys that are not in user data
        $available_properties = array_map(fn($data) => $data, array_diff_key(self::EXTRACTION_DATA, $user_data));

        $request = Http::post(config('app.processing_url') . '/onboarding/extract', [
            'question' => $messages[0]['text'],
            'message' => $messages[1]['text'],
            'data' => $user_data,
            'available_properties' => $available_properties,
        ]);

        $request->throw();

        # filter out null values
        return array_filter(array_map(
            fn($value) => $value === null || $value === '' || $value === 'null' || $value === 'N/A' ? null : $value,
            $request->json()
        ), fn($value) => $value !== null && $value !== '');
    }

    public function generateNextQuestion(array $messages, array $data): ?string
    {
        $last_question = last(
            array_filter($messages, fn($message) => $message['type'] === 'question')
        )['text'];

        # get unanswered questions
        $unanswered_questions = array_diff_key(
            self::EXTRACTION_DATA,
            array_filter($data, fn($value) => $value !== null)
        );

        if (empty($unanswered_questions)) {
            return null;
        }

        # group questions by achievement
        $grouped_questions = collect($unanswered_questions)->groupBy('achievement');


        # order groups by achievement order
        $ordered_groups = $grouped_questions->sortBy(fn($group, $achievement) => array_search($achievement, self::ACHIEVEMENTS));

        # get first group
        $group = $ordered_groups->first();

        if (isset($group[0]['question'])) {
            return $group[0]['question'];
        }

        $request = Http::post(config('app.processing_url') . '/onboarding/question', [
            'user' => $data,
            'previous_question' => $last_question ?? null,
            'data' => $group->map(fn($question) => [
                'name' => $question['name'],
                'description' => $question['description'],
                'examples' => $question['examples'] ?? null,
            ])->toArray(),
        ]);

        $request->throw();

        return $request->json();
    }
}
