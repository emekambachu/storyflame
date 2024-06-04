<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\User;

class StoryCreatingService extends ConversationService
{
    const EXTRACTION_DATA = [
        'title' => [
            'type' => 'text',
            'name' => 'Title',
            'description' => 'The name of the story'
        ],
        'writers' => [
            'type' => 'array',
            'name' => 'Writers',
            'description' => 'The writers of the story'
        ],
        'logline' => [
            'type' => 'text',
            'name' => 'Logline',
            'description' => 'A brief summary of the story'
        ],
        'stage' => [
            'type' => 'text',
            'name' => 'Development Stage',
            'examples' => ['Ideation', 'Outline', 'Treatment', 'First Draft', 'Revision', 'Final Draft'],
            'description' => 'The current stage of the story'
        ],
        'format' => [
            'type' => 'text',
            'name' => 'Format',
            'examples' => ['TV Series', 'Feature Film', 'Short Film', 'Stage Play', 'Novel'],
            'description' => 'The format of the story'
        ],
        'genre' => [
            'type' => 'array',
            'name' => 'Genre',
            'examples' => ['Action', 'Comedy', 'Drama', 'Fantasy', 'Horror', 'Mystery', 'Romance', 'Sci-Fi', 'Thriller'],
            'description' => 'The genre of the story'
        ],
        'media' => [
            'type' => 'array',
            'name' => 'Influential Media',
            'description' => 'Movies, TV shows, books, or other media that inspired the story'
        ],
        'characters' => [
            'type' => 'array',
            'name' => 'Influential Characters',
            'description' => 'Characters from movies, TV shows, books, or other media that inspired the story'
        ],
        'audience' => [
            'type' => 'text',
            'name' => 'Target Audience',
            'description' => 'The intended audience for the story',
            'examples' => ['Children', 'Teenagers', 'Adults', 'Seniors']
        ],
        'rating' => [
            'type' => 'text',
            'name' => 'Projected Rating',
            'description' => 'The intended rating for the story',
            'examples' => ['G', 'PG', 'PG-13', 'R', 'NC-17']
        ],
        'dialogue_intensity' => [
            'type' => 'text',
            'name' => 'Dialogue Intensity',
            'description' => 'The level of intensity in the dialogue',
            'examples' => ['Sparse', 'Moderate', 'Heavy', 'Rapid-Fire', 'Poetic']
        ],
        'visual_style' => [
            'type' => 'text',
            'name' => 'Visual Style',
            'description' => 'The visual style of the story',
            'examples' => [
                'Realistic', 'Stylized', 'Animated', 'CGI-heavy', 'Practical Effects-driven'
            ]
        ],
        'plot_structure' => [
            'type' => 'text',
            'name' => 'Plot Structure',
            'description' => 'The structure of the story',
            'examples' => ['Three-Act Structure', 'Hero\'s Journey', 'Save the Cat', 'Non-Linear', 'Episodic']
        ],
        'character_diversity' => [
            'type' => 'text',
            'name' => 'Character Diversity',
            'description' => 'The diversity of characters in the story',
            'examples' => ['Inclusive', 'Homogeneous', 'Stereotypical', 'Tokenistic', 'Authentic']
        ],
        'protagonist_type' => [
            'type' => 'text',
            'name' => 'Protagonist Type',
            'description' => 'The type of protagonist in the story',
            'examples' => ['Hero', 'Anti-Hero', 'Villain', 'Everyman', 'Outsider']
        ],
        'ensemble_size' => [
            'type' => 'text',
            'name' => 'Ensemble Size',
            'description' => 'The size of the ensemble cast',
            'examples' => ['Solo', 'Duo', 'Trio', 'Small Group', 'Large Cast']
        ],
        'location' => [
            'type' => 'text',
            'name' => 'Primary Location',
            'description' => 'The primary location of the story',
            'examples' => ['Urban', 'Rural', 'Suburban', 'Fantasy World', 'Space']
        ],
        'setting' => [
            'type' => 'text',
            'name' => 'Setting',
            'description' => 'The setting of the story',
            'examples' => ['Urban', 'Rural', 'Suburban', 'International', 'Fictional World']
        ],
        'time_period' => [
            'type' => 'text',
            'name' => 'Time Period',
            'description' => 'The time period of the story',
            'examples' => ['Past', 'Present', 'Future', 'Alternate History', 'Time Travel']
        ],
        'themes' => [
            'type' => 'array',
            'name' => 'Themes',
            'description' => 'The themes of the story',
            'examples' => ['Love', 'Friendship', 'Betrayal', 'Redemption', 'Identity']
        ],
        'tone' => [
            'type' => 'text',
            'name' => 'Tone',
            'description' => 'The tone of the story',
            'examples' => ['Light-hearted', 'Dark', 'Satirical', 'Melancholic', 'Whimsical']
        ],
    ];

    const QUESTION_GROUPS = [
        'intro' => [
            'subtitle' => 'Let\'s dive deep into your story',
            'question' => 'What is the name of your story?',
            'data' => ['title'],
            'fail' => 'prompt_repeat',
            'required' => true
        ],
        'details' => [
            'subtitle' => '"{extraction.title}" sounds awesome!',
            'question' => 'Now would you tell us a bit about your story?',
            'data' => [
                'logline'
            ],
        ],
        'stage' => [
            'data' => ['stage'],
            'required' => true,
        ],
        'format' => [
            'data' => ['format'],
            'required' => true,
        ],
        'genre' => [
            'type' => 'multiple_choice',
            'data' => ['genre'],
            'required' => true,
        ],
        'media_and_characters' => [

        ],
    ];

    const TOPIC_GROUPS = [
        'story_foundation' => [
            'name' => 'Story Foundation',
            'description' => 'The core elements of your story',
            'data' => ['title', 'writers', 'logline', 'stage', 'format', 'genre'],
        ],
        'story_structure' => [
            'name' => 'Story Structure',
            'description' => 'The framework of your story',
            'data' => ['plot_structure', 'character_diversity', 'protagonist_type', 'ensemble_size']
        ],
        'story_inspiration' => [
            'name' => 'Story Inspiration',
            'description' => 'The sources of inspiration for your story',
            'data' => ['media', 'characters']
        ],
    ];

    public Chat $chat;

    protected function createChat(User $user, array $data = []): Chat
    {
    }

    protected function getUserChat(User $user): ?Chat
    {
        return $this->chat ?? null;
    }

    function finishConversation(array $data): void
    {
        // TODO: Implement saveData() method.
    }
}
