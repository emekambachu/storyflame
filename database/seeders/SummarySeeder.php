<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Summary\Summary;
use App\Models\Summary\SummaryCategory;
use App\Services\Base\BaseService;
use Illuminate\Database\Seeder;

class SummarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $summaries = [
            [
                'name' => 'Logline',
                'slug' => 'logline',
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'location' => 'Story Detail Page',
                'purpose' => 'Provide a concise, compelling summary of the main story concept to guide development and pitch to others.',
                'creation_prompt' => 'Provide a concise, compelling summary of the main story concept to guide development and pitch to others',
                'example_summary' => 'Provide a concise, compelling summary of the main story concept to guide development and pitch to others',
                'published_at' => now(),
            ],
            [
                'name' => 'Impact One-Liner',
                'slug' => 'impact-one-liner',
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'location' => '-',
                'purpose' => 'Summarize the most important way this achievement helps develop the story, to motivate the writer.',
                'creation_prompt' => 'Summarize the most important way this achievement helps develop the story, to motivate the writer.',
                'example_summary' => 'Summarize the most important way this achievement helps develop the story, to motivate the writer.',
                'published_at' => now(),
            ],
            [
                'name' => '[Per Category] Impact Summary',
                'slug' => 'per-category-impact-summary',
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'location' => '-',
                'purpose' => 'Explain how this achievement contributes to the development of its specific category (character, plot, etc.), to help the writer understand its value.',
                'creation_prompt' => 'Explain how this achievement contributes to the development of its specific category (character, plot, etc.), to help the writer understand its value',
                'example_summary' => 'Explain how this achievement contributes to the development of its specific category (character, plot, etc.), to help the writer understand its value',
                'published_at' => now(),
            ],
            [
                'name' => 'Title',
                'slug' => 'title',
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'location' => '-',
                'purpose' => 'Highlight key subject and details for clarification, to ensure accuracy and consistency.',
                'creation_prompt' => 'Highlight key subject and details for clarification, to ensure accuracy and consistency.',
                'example_summary' => 'Highlight key subject and details for clarification, to ensure accuracy and consistency.',
                'published_at' => now(),
            ],
            [
                'name' => 'Discrepancy',
                'slug' => 'discrepancy',
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'location' => '-',
                'purpose' => 'Describe what needs to be resolved and why, to maintain story integrity.',
                'creation_prompt' => 'Highlight key subject and details for clarification, to ensure accuracy and consistency.',
                'example_summary' => 'Highlight key subject and details for clarification, to ensure accuracy and consistency.',
                'published_at' => now(),
            ],
            [
                'name' => 'Bio',
                'slug' => 'bio',
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'location' => 'User Profile',
                'purpose' => 'Showcase a writer\'s unique differentiators, interests, experience, and perspective, to facilitate professional opportunities and connections.',
                'creation_prompt' => 'Highlight key subject and details for clarification, to ensure accuracy and consistency.',
                'example_summary' => 'Highlight key subject and details for clarification, to ensure accuracy and consistency.',
                'published_at' => now(),
            ],
            [
                'name' => 'Writing Goals',
                'slug' => 'writing-goals',
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'location' => 'User Profile',
                'purpose' => 'Articulate the writer\'s aspirations and target audience, to guide project choice and development.',
                'creation_prompt' => 'Articulate the writer\'s aspirations and target audience, to guide project choice and development.',
                'example_summary' => 'Articulate the writer\'s aspirations and target audience, to guide project choice and development.',
                'published_at' => now(),
            ],
            [
                'name' => 'Character Card Summary',
                'slug' => 'character-card-summary',
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'location' => '-',
                'purpose' => 'Encapsulate the character\'s key traits and role, to provide a quick reference.',
                'creation_prompt' => 'Encapsulate the character\'s key traits and role, to provide a quick reference',
                'example_summary' => 'Encapsulate the character\'s key traits and role, to provide a quick reference',
                'published_at' => now(),
            ],
            [
                'name' => 'Plot Card Title',
                'slug' => 'plot-card-title',
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'location' => '-',
                'purpose' => 'Name the main event or turning point of the plot point, to orient the writer.',
                'creation_prompt' => 'Name the main event or turning point of the plot point, to orient the writer',
                'example_summary' => 'Name the main event or turning point of the plot point, to orient the writer',
                'published_at' => now(),
            ],
            [
                'name' => 'Plot Card Summary',
                'slug' => 'plot-card-summary',
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'location' => '-',
                'purpose' => 'Summarize the key event and its impact on the story, to trace the narrative arc.',
                'creation_prompt' => 'Name the primary location or time period of the setting, to establish the story world.',
                'example_summary' => 'Name the primary location or time period of the setting, to establish the story world.',
                'published_at' => now(),
            ],
            [
                'name' => 'Setting Card Title',
                'slug' => 'setting-card-title',
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'location' => '-',
                'purpose' => 'Name the primary location or time period of the setting, to establish the story world.',
                'creation_prompt' => 'Name the primary location or time period of the setting, to establish the story world.',
                'example_summary' => 'Name the primary location or time period of the setting, to establish the story world.',
                'published_at' => now(),
            ],
        ];

        foreach ($summaries as $summary) {
            $sum = Summary::create($summary);

            // Categories
            SummaryCategory::create([
                'summary_id' => $sum->id,
                'category_id' => Category::inRandomOrder()->first()->id,
            ]);

            SummaryCategory::create([
                'summary_id' => $sum->id,
                'category_id' => Category::inRandomOrder()->first()->id,
            ]);


        }

    }
}
