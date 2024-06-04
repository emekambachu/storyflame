<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class OnboardingService extends ConversationService
{
    public function __construct(
        private readonly ChatService        $chatService,
        private readonly MediaService       $mediaService,
        private readonly AchievementService $achievementService,
    )
    {
        parent::__construct(
            $this->chatService,
            $this->achievementService,
        );
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
            'subtitle' => 'Nice to meet up, {extracted.name}!',
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
            'description' => 'Name of the user',
        ],
        'bio' => [
            'type' => 'text',
            'name' => 'Bio',
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

    public function getUserChat(User $user): ?Chat
    {
        return $user->chats()->where('type', 'onboarding')->first();
    }

    protected function createChat(User $user, array $data = []): Chat
    {
        return $user->chats()->create([
            'type' => 'onboarding',
        ]);
    }


    function finishConversation(array $data): void
    {
        $user = auth()->user();
        foreach ($data as $key => $value) {
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

        $this->generateBio($this->getChat());

        $user->save();
    }

    /**
     * @throws RequestException
     */
    private function generateBio(Chat $chat): void
    {
        $response = Http::post(config('app.processing_url') . '/onboarding/generate/details', [
            'history' => $this->getChatHistory($this->getChat()),
        ]);
        $response->throw();
        $extracted_data = $this->getExtractedData($chat);
        $extracted_data['bio'] = $response->json()['details'];
        $this->saveChatData($chat, $extracted_data);
    }

    /**
     * @throws RequestException
     */
    public function getSummary(): User
    {
        $user = auth()->user();
        $chat = $this->getChat();
        if (!$user->extra_attributes->get('bio')) {
            $this->generateBio($chat);
        }

        if (!$user->extra_attributes->onboarded ?? false) {
            $this->finishConversation($this->getExtractedData($chat));
            $this->achievementService->updateProgress($user);
        }
        return $user;
    }
}
