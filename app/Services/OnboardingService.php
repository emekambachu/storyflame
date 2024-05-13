<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Nette\NotImplementedException;

class OnboardingService
{
    public function __construct(
        private readonly ChatService          $chatService,
        private readonly TranscriptionService $transcriptionService,
        private readonly MediaService         $mediaService,
        private readonly AchievementService   $achievementService,
    )
    {
    }

    const QUESTION_GROUPS = [
        # get to know user
        'intro' => [
            'title' => 'Getting to know you',
            'subtitle' => 'Hello, welcome to {app_name}!',
            # pre-defined question
            'question' => 'What is your name?',
            # user needs to provide this data to proceed
            'data' => ['name'],
            # if user fails to provide this data, they will be asked to type it
            'fail' => 'prompt_repeat',
            'required' => true,
        ],
        # try to make user talk and extract as many as we can
        'big_picture' => [
            'title' => 'Getting to know you',
            'subtitle' => 'Nice to meet up, {name}!',
            # pre-defined question
            'question' => 'Tell us about yourself as a writer.',
            # user needs to provide any data to proceed
            'required' => true,
        ],
        # based on what we know about user so far
        # create a multiple choice question to get more specific data
        'focus' => [
            'title' => 'Know your focus',
            # generated question
            # user needs to choose one of the following
            'type' => 'multiple_choice',
            'data' => ['genre_focus'],
            'required' => true,
        ],
        'writing' => [
            'title' => 'Your writing process',
            # generated question
            # user needs to choose one of the following
            'type' => 'multiple_choice',
            'data' => ['writing_medium'],
            'required' => true,
        ],
        # ask user about his favorites
        'big_five' => [
            'title' => 'Your favorites',
            'data' => ['media', 'characters', 'audience', 'themes'],
            'required' => true,
        ],
        #tbd
    ];

    const EXTRACTION_DATA = [
        'name' => [
            'type' => 'text',
            'name' => 'Name',
            'question' => 'What is your name?',
            'description' => 'Name of the user',
        ],
        'bio' => [
            'type' => 'text',
            'name' => 'Bio',
            'question' => 'Tell us about yourself as a writer.',
            'description' => 'Some information about the user.',
        ],

        'level' => [
            'type' => 'text',
            'name' => 'Skill Level',
            'examples' => ['beginner', 'intermediate', 'advanced', 'professional'],
            'description' => 'Writing skill level of the user',
        ],
        'genre_focus' => [
            'type' => 'array',
            'name' => 'Genre Focus',
            'examples' => ['fantasy', 'sci-fi', 'horror', 'romance', 'mystery', 'thriller', 'comedy', 'drama'],
            'description' => 'Genre focus of the user',
        ],
        'writing_medium' => [
            'type' => 'array',
            'name' => 'Writing Medium',
            'examples' => ['TV', 'Film', 'Novelist', 'Short Story Writer', 'Poet', 'Playwright', 'Screenwriter', 'Copywriter'],
            'description' => 'Writing medium of the user, such as TV, Film, Novelist, etc.',
        ],

        'media' => [
            'type' => 'array',
            'name' => 'Media',
            'description' => 'Media that influences the user, such as movies, books, TV shows, music, etc.',
            'examples' => ['Star Wars', 'Harry Potter', 'Game of Thrones', 'The Great Gatsby', 'The Matrix', 'The Simpsons', 'The Godfather', 'The Beatles'],
        ],
        'characters' => [
            'type' => 'array',
            'name' => 'Characters',
            'description' => 'Real or fictional people and characters that influence the user. Should not include media titles.',
            'examples' => ['Luke Skywalker', 'Hermione Granger', 'Sherlock Holmes', 'Elizabeth Bennet', 'James Bond', 'Walter White', 'Indiana Jones', 'Buffy Summers'],
        ],
        'audience' => [
            'type' => 'array',
            'name' => 'Audience',
            'description' => 'Target audience of the user, such as children, young adults, adults, seniors, etc.',
            'examples' => ['children', 'young adults', 'adults', 'seniors', 'all ages']
        ],
        'themes' => [
            'type' => 'array',
            'name' => 'Themes',
            'examples' => ['Identity', 'Love', 'Loss', 'Coming of age', 'Social issues', 'Politics', 'History'],
            'description' => 'Themes or subjects that the user is interested in',
        ],

        'productivity' => [
            'type' => 'text',
            'name' => 'Productivity',
            'examples' => ['high-output', 'steady', 'sporadic', 'procrastinator'],
            'description' => 'Writing Productivity style of the user',
        ],
        'writing_process' => [
            'type' => 'text',
            'name' => 'Writing Process',
            'examples' => ['plotter', 'pantser', 'plantser'],
            'description' => 'Writing process of the user',
        ],
        'stage_preference' => [
            'type' => 'text',
            'name' => 'Writing Stage Preference',
            'examples' => ['starter', 'polisher', 'finisher'],
            'description' => 'Writing stage preference of the user',
        ],
        'revision_style' => [
            'type' => 'text',
            'name' => 'Revision Style',
            'examples' => ['iterative', 'holistic', 'collaborative', 'intuitive'],
            'description' => 'Revision style of the user',
        ],

        'collaboration_style' => [
            'type' => 'text',
            'name' => 'Collaboration Style',
            'examples' => ['solo', 'collaborator', 'ghostwriter', 'partner'],
            'description' => 'Collaboration style of the user',
        ],
        'adaptability' => [
            'type' => 'text',
            'name' => 'Adaptability',
            'examples' => ['versatile', 'specialized', 'open to feedback', 'resistant to change'],
            'description' => 'Adaptability of the user',
        ],

        'world_engagement' => [
            'type' => 'text',
            'name' => 'World Engagement',
            'examples' => ['builder', 'inhabitor', 'explorer', 'observer'],
            'description' => 'World engagement of the user',
        ],
        'narrative_focus' => [
            'type' => 'text',
            'name' => 'Narrative Focus',
            'examples' => ['plot-driven', 'character-centric', 'dialogue-oriented', 'theme-focused', 'atmosphere-builder'],
            'description' => 'Narrative focus of the user',
        ],

        'motivation' => [
            'type' => 'text',
            'name' => 'Motivation',
            'examples' => ['passion-driven', 'career-focused', 'therapeutic', 'hobby'],
            'description' => 'Motivation of the user',
        ],
        'education' => [
            'type' => 'text',
            'name' => 'Educational Background',
            'examples' => ['self-taught', 'creative writing degree', 'literature degree', 'MFA', 'PhD'],
            'description' => 'Educational background of the user',
        ],
        'stage' => [
            'type' => 'text',
            'name' => 'Career Stage',
            'examples' => ['aspiring', 'emerging', 'established', 'bestselling', 'professional'],
            'description' => 'Career stage of the user',
        ]
    ];

    private function saveUserData(User $user): void
    {
        if (!$user->extra_attributes->data ?? false) {
            $user->extra_attributes->data = [];
        }
        foreach ($user->extra_attributes->onboarding as $key => $value) {
            switch ($key) {
                case 'name':
                    $user->name = $value;
                    break;
                case 'media':
                    foreach ($value as $media) {
                        $user->favoriteMovies()->syncWithoutDetaching(
                            $this->mediaService->getMovieByTitle($media)
                        );
                    }
                    break;
                default:
                    $data = $user->extra_attributes->data ?? [];
                    $data[$key] = $value;
                    $user->extra_attributes->data = $data;
            }
        }
        $user->extra_attributes['onboarded'] = true;

        $user->save();
    }

    public function getSummary(): User
    {
        $user = auth()->user();
        $response = Http::post(config('app.processing_url') . '/onboarding/generate/details', [
            'history' => $this->getChatHistory($this->getChat()),
        ]);
        $response->throw();
        $user->extra_attributes->set('onboarding.bio', $response->json()['details']);
        if (!$user->extra_attributes->onboarded ?? false) {
            $this->saveUserData($user);
            $this->achievementService->updateProgress($user);
        }
        return $user;
    }


    public function getQuestionCount(): int
    {
        return count(self::EXTRACTION_DATA);
    }

    public function getRequiredData(): array
    {
        return collect(array_map(fn($group) => $group['data'] ?? [], array_filter(self::QUESTION_GROUPS, fn($group) => $group['required'] ?? false)))
            ->flatten()
            ->unique()
            ->toArray();
    }

    public function getProgress(): int
    {
        $required_data = array_intersect(
            array_keys(auth()->user()->extra_attributes->onboarding ?? []),
            $this->getRequiredData()
        );
        return count($required_data) / count($this->getRequiredData()) * 100;
    }

    public function getChat(): Chat
    {
        $user = auth()->user();

        // if user has no onboarding chat, create one
        if (!$user->chats()->where('type', 'onboarding')->exists()) {
            $chat = $user->chats()->create([
                'type' => 'onboarding',
            ]);
        } else {
            $chat = $user->chats()->where('type', 'onboarding')->first();
        }

        // if chat has no messages, create an intro message
        if ($chat->chatMessages()->count() === 0) {
            $this->generateNextQuestion($chat);
        }

        return $chat;
    }

    public function addMessage(UploadedFile $audio = null, string $content = null, array $options = null): ChatMessage
    {
        if (!$content && !$audio && !$options) {
            throw new \InvalidArgumentException('Either content or audio or options must be provided');
        }

        $user = auth()->user();

        $chat = $this->getChat();

        // create message
        return $this->chatService->create($chat, $user, [
            'content' => $content ?? ($options ? implode(', ', $options) : null),
            'audio' => $audio,
        ], [
            'options' => $options,
            'extracted' => null,
        ]);
    }

    public function extractData(): array
    {
        $user = auth()->user();
        $user_data = $user->extra_attributes->onboarding ?? [];

        // get keys that are not in user data
        $available_properties = array_map(fn($data) => $data, array_diff_key(self::EXTRACTION_DATA, $user_data));

        $chat = $this->getChat();
        $question = $chat->chatMessages()->latest()->whereNull('user_id')->first();
        $answer = $chat->chatMessages()->latest()->whereNotNull('user_id')->first();

        // if the question was multiple choice and we have key for options
        // we don't need to extract data
        if ($question->type === 'multiple_choice' &&
            isset($question->extra_attributes['options_key']) &&
            isset($answer->extra_attributes['options'])
        ) {
            $extracted = [
                $question->extra_attributes['options_key'] => $answer->extra_attributes['options'],
            ];
        } else {
            $request = Http::post(config('app.processing_url') . '/onboarding/extract', [
                'question' => $question->content,
                'message' => $answer->content,
                'data' => $user_data,
                'available_properties' => $available_properties,
            ]);

            $request->throw();

            $replaceNull = fn($value) => $value === null || $value === '' || $value === 'null' || $value === 'N/A' ? null : $value;

            /**
             * Clean JSON, remove null values, empty string, 'null', 'N/A', empty arrays
             * @param array $json
             * @return array
             */
            function cleanJson(array $json): array
            {
                $cleaned = [];
                foreach ($json as $key => $value) {
                    if (is_array($value)) {
                        $arr = cleanJson($value);
                        if (!empty($arr)) {
                            $cleaned[$key] = $arr;
                        }
                    } else {
                        if (is_string($value)) {
                            $trim = trim($value);
                            if (in_array(strtolower($trim), ['null', 'n/a', '', '[]'])) {
                                continue;
                            }
                            $cleaned[$key] = $trim;
                        } else if ($value === null) {
                            continue;
                        } else {
                            // if it's not a string and null, it's a number or boolean
                            $cleaned[$key] = $value;
                        }
                    }
                }
                return $cleaned;
            }

            Log::info('response', [$request->json()]);

            # filter out null values
            $extracted = cleanJson($request->json());

            Log::info('cleaned', [$extracted]);
        }

        $answer->extra_attributes->extracted = $extracted;
        $answer->save();

        $user->extra_attributes->onboarding = array_merge($user_data, $extracted);
        $user->save();

        $this->achievementService->updateProgress($user);

        return $user->extra_attributes->onboarding;
    }

    private function replaceTemplate(string $text): string
    {
        $user = auth()->user();
        $replacements = [
            '{app_name}' => config('app.name'),
        ];
        foreach ($user->extra_attributes->onboarding ?? [] as $key => $value) {
            $replacements["{{$key}}"] = is_array($value) ? implode(', ', $value) : $value;
        }
        return str_replace(array_keys($replacements), array_values($replacements), $text);
    }

    private function createQuestion(Chat $chat, string $groupKey, string $subtitle, string $content, string $type = 'text', $options = null, $optionsKey = null): ChatMessage
    {
        $group = self::QUESTION_GROUPS[$groupKey];
        return $chat->chatMessages()->create([
            'type' => $type,
            'content' => $content,
            'user_id' => null,
            'extra_attributes' => array_filter(
                [
                    'group' => $groupKey,
                    'title' => isset($group['title']) ? $this->replaceTemplate($group['title']) : null,
                    'subtitle' => $this->replaceTemplate($subtitle),
                    'options' => $options,
                    'options_key' => $optionsKey,
                ],
                fn($value) => $value !== null
            ),
        ]);
    }

    private function getChatHistory(Chat $chat)
    {
        return $chat->chatMessages()->notSystem()->oldest()->get()->map(function ($message) {
            return [
                'agent' => $message->user_id ? 'human' : 'ai',
                'text' => $message->user_id === null
                    ? ('Message: ' . $message->extra_attributes->subtitle . ' Question: ' . $message->content)
                    : $message->content,
            ];
        })->toArray();
    }

    private function generateNextQuestion(Chat $chat): ChatMessage
    {
        $user_data = auth()->user()->extra_attributes->onboarding ?? [];

        if ($chat->chatMessages()->count() === 0) {
            # generate first question
            return $this->createQuestion($chat, 'intro', self::QUESTION_GROUPS['intro']['subtitle'], self::QUESTION_GROUPS['intro']['question']);
        }

        $lastQuestion = $chat->chatMessages()->latest()->whereNull('user_id')->first();

        # get last question group
        $lastGroup = self::QUESTION_GROUPS[$lastQuestion->extra_attributes['group']];


        $answeredAllQuestionsInGroup = !isset($lastGroup['data']) || empty(array_diff($lastGroup['data'], array_keys($user_data)));

        if (!$answeredAllQuestionsInGroup) {
            # if group has a fail condition
            if (isset($lastGroup['fail'])) {
                # ask user to type the data
                return $this->createQuestion(
                    $chat,
                    $lastQuestion->extra_attributes['group'],
                    $lastGroup['subtitle'] ?? $lastQuestion->extra_attributes['subtitle'],
                    $lastGroup['fail'],
                    'system'
                );
            }
        }

        # get next group
        $groupIndex = array_search($lastQuestion->extra_attributes['group'], array_keys(self::QUESTION_GROUPS));
        $nextGroupIndex = $groupIndex + ($answeredAllQuestionsInGroup ? 1 : 0);

        if (!isset(array_keys(self::QUESTION_GROUPS)[$nextGroupIndex])) {
            $this->saveUserData(auth()->user());
            return $this->createQuestion($chat, $lastQuestion->extra_attributes['group'], '', 'finish', 'system');
        }

        $nextGroupKey = array_keys(self::QUESTION_GROUPS)[$nextGroupIndex];
        $nextGroup = self::QUESTION_GROUPS[$nextGroupKey];

        # if there are no more groups
        if ($nextGroup === null) {
            throw new NotImplementedException('This should not happen');
        }

        # check if next group has a pre-defined question
        if (isset($nextGroup['question'])) {
            # check if that question wasn't already asked
            if (!$chat->chatMessages()->notSystem()->whereNull('user_id')->where('content', $nextGroup['question'])->exists()) {
                # ask the question
                return $this->createQuestion(
                    $chat,
                    $nextGroupKey,
                    $nextGroup['subtitle'],
                    $nextGroup['question'],
                );
            }
            # otherwise, generate a question based on the data
        }

        # get data for the question
        if (!isset($nextGroup['data'])) {
            throw new NotImplementedException('This should not happen, maybe :)');
        }

        $groupKeys = collect($nextGroup['data'])
            ->filter(fn($key) => !array_key_exists($key, $user_data));
        $group = $groupKeys
            ->map(fn($key) => self::EXTRACTION_DATA[$key])
            ->values();

        $request = Http::post(config('app.processing_url') . '/onboarding/question', [
            'type' => $nextGroup['type'] ?? 'text',
            'history' => $this->getChatHistory($chat),
            'data' => $group->map(fn($data) => [
                'name' => $data['name'],
                'description' => $data['description'],
                'examples' => $data['examples'] ?? null,
            ])->toArray(),
        ]);

        $request->throw();

        return $this->createQuestion(
            $chat,
            $nextGroupKey,
            $nextGroup['subtitle'] ?? $request->json()['message'],
            $request->json()['question'],
            $nextGroup['type'] ?? 'text',
            $request->json()['options'] ?? null,
            count($groupKeys) === 1 ? $groupKeys->first() : null
        );
    }

    public function getNextQuestion(): ChatMessage
    {
        $chat = $this->getChat();
        return $this->generateNextQuestion($chat);
    }

    public function getLastQuestion(): ChatMessage
    {
        $chat = $this->getChat();

        $questions = $chat->chatMessages()->whereNull('user_id')->where(fn($query) => $query
            ->notSystem()
            ->orWhere(
                fn($query) => $query->where('type', 'system')->where('content', 'finish')
            ))->latest()->get();
        if ($questions->count() === 0) {
            return $this->generateNextQuestion($chat);
        }
        return $questions->first();
    }
}
