<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Admin\Admin;
use App\Models\Category;
use App\Models\DataPoint;
use App\Models\DataPoint\DataPointAchievement;
use App\Models\DataPoint\DataPointCategory;
use App\Models\DataPoint\DataPointSummary;
use App\Models\Summary\Summary;
use App\Services\Base\BaseService;
use Illuminate\Database\Seeder;

class DataPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataPoints = [
            [
                'achievement_id' => null,
                'slug' => 'story-primary-obstacle'.BaseService::randomCharacters(5, '0123456789'),
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'name' => 'Story - Primary Obstacle',
                'type' => 'DataPoint',
                'extraction_description' => 'Story (aka episode, novel) - Logline',
                'example' => null,
                'purpose' => 'Required',
                'development_order' => BaseService::randomCharacters(2, '0123456789'),
                'impact_score' => 1,
            ],
            [
                'achievement_id' => null,
                'slug' => 'story-key-characters'.BaseService::randomCharacters(5, '0123456789'),
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'name' => 'Story - Key Characters',
                'type' => 'DataPoint',
                'extraction_description' => 'Story (aka episode, novel) - Logline',
                'example' => null,
                'purpose' => 'Required',
                'development_order' => BaseService::randomCharacters(2, '0123456789'),
                'impact_score' => 1,
            ],
            [
                'achievement_id' => null,
                'slug' => 'story-key-relationships'.BaseService::randomCharacters(5, '0123456789'),
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'name' => 'Story - Key Relationships',
                'type' => 'DataPoint',
                'extraction_description' => 'Story (aka episode, novel) - Logline',
                'example' => null,
                'purpose' => 'Required',
                'development_order' => BaseService::randomCharacters(2, '0123456789'),
                'impact_score' => 1,
            ],
            [
                'achievement_id' => null,
                'slug' => 'story-impact-on-plot-and-theme'.BaseService::randomCharacters(5, '0123456789'),
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'name' => 'Story - Impact on Plot and Theme',
                'type' => 'DataPoint',
                'extraction_description' => 'Story (aka episode, novel) - Logline',
                'example' => null,
                'purpose' => 'Required',
                'development_order' => BaseService::randomCharacters(2, '0123456789'),
                'impact_score' => 1,
                'admin_id' => Admin::first()->id,
            ],
            [
                'achievement_id' => null,
                'slug' => 'plot-thematic-cohesion'.BaseService::randomCharacters(5, '0123456789'),
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'name' => 'Plot - Thematic Cohesion',
                'type' => 'DataPoint',
                'extraction_description' => 'Story (aka episode, novel) - Logline',
                'example' => null,
                'purpose' => 'Required',
                'development_order' => BaseService::randomCharacters(2, '0123456789'),
                'impact_score' => 1,
                'admin_id' => Admin::first()->id,
            ],
            [
                'achievement_id' => null,
                'slug' => 'plot-status-quo'.BaseService::randomCharacters(5, '0123456789'),
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'name' => 'Plot - Status Quo',
                'type' => 'DataPoint',
                'extraction_description' => 'Story (aka episode, novel) - Logline',
                'example' => null,
                'purpose' => 'Required',
                'development_order' => BaseService::randomCharacters(2, '0123456789'),
                'impact_score' => 1,
                'admin_id' => Admin::first()->id,
            ],
            [
                'achievement_id' => null,
                'slug' => 'story-exploring'.BaseService::randomCharacters(5, '0123456789'),
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'name' => 'Story - Exploring',
                'type' => 'DataPoint',
                'extraction_description' => 'Story (aka episode, novel) - Logline',
                'example' => null,
                'purpose' => 'Required',
                'development_order' => BaseService::randomCharacters(2, '0123456789'),
                'impact_score' => 1,
                'admin_id' => Admin::first()->id,
            ],
            [
                'achievement_id' => null,
                'slug' => 'story-premise'.BaseService::randomCharacters(5, '0123456789'),
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'name' => 'Story - Premise',
                'type' => 'DataPoint',
                'extraction_description' => 'Story (aka episode, novel) - Logline',
                'example' => null,
                'purpose' => 'Required',
                'development_order' => BaseService::randomCharacters(2, '0123456789'),
                'impact_score' => 1,
                'admin_id' => Admin::first()->id,
            ],
            [
                'achievement_id' => null,
                'slug' => 'story-setup'.BaseService::randomCharacters(5, '0123456789'),
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'name' => 'Story - Setup',
                'type' => 'DataPoint',
                'extraction_description' => 'Story (aka episode, novel) - Logline',
                'example' => null,
                'purpose' => 'Required',
                'development_order' => BaseService::randomCharacters(2, '0123456789'),
                'impact_score' => 1,
                'admin_id' => Admin::first()->id,
            ],
            [
                'achievement_id' => null,
                'slug' => 'story-inciting-incident'.BaseService::randomCharacters(5, '0123456789'),
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'name' => 'Story - Inciting Incident',
                'type' => 'DataPoint',
                'extraction_description' => 'Story (aka episode, novel) - Logline',
                'example' => null,
                'purpose' => 'Required',
                'development_order' => BaseService::randomCharacters(2, '0123456789'),
                'impact_score' => 1,
                'admin_id' => Admin::first()->id,
            ],
            [
                'achievement_id' => null,
                'slug' => 'story-unique-elements'.BaseService::randomCharacters(5, '0123456789'),
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'name' => 'Story - Unique Elements',
                'type' => 'DataPoint',
                'extraction_description' => 'Story (aka episode, novel) - Logline',
                'example' => null,
                'purpose' => 'Required',
                'development_order' => BaseService::randomCharacters(2, '0123456789'),
                'impact_score' => 1,
                'admin_id' => Admin::first()->id,
            ],
            [
                'achievement_id' => null,
                'slug' => 'achievement-impact-one-liner'.BaseService::randomCharacters(5, '0123456789'),
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'name' => 'Achievement - Impact One-Liner',
                'type' => 'DataPoint',
                'extraction_description' => 'Achievement - Impact One-Liner',
                'example' => null,
                'purpose' => '',
                'development_order' => BaseService::randomCharacters(2, '0123456789'),
                'impact_score' => 2,
                'admin_id' => Admin::first()->id,
            ],
            [
                'achievement_id' => null,
                'slug' => 'achievement-per-category-impact-summary'.BaseService::randomCharacters(5, '0123456789'),
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'name' => 'Achievement - [Per Category] Impact Summary',
                'type' => 'DataPoint',
                'extraction_description' => 'Achievement - [Per Category] Impact Summary',
                'example' => null,
                'purpose' => '',
                'development_order' => BaseService::randomCharacters(2, '0123456789'),
                'impact_score' => 3,
                'admin_id' => Admin::first()->id,
            ],
            [
                'achievement_id' => null,
                'slug' => 'arc-setup'.BaseService::randomCharacters(5, '0123456789'),
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'name' => 'Arc - Setup',
                'type' => 'DataPoint',
                'extraction_description' => 'Arc - Setup',
                'example' => null,
                'purpose' => '',
                'development_order' => BaseService::randomCharacters(2, '0123456789'),
                'impact_score' => 33,
                'admin_id' => Admin::first()->id,
            ],
            [
                'achievement_id' => null,
                'slug' => 'clarify-title'.BaseService::randomCharacters(5, '0123456789'),
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'name' => 'Clarify - Title',
                'type' => 'DataPoint',
                'extraction_description' => 'Clarify - Title',
                'example' => null,
                'purpose' => '',
                'development_order' => BaseService::randomCharacters(2, '0123456789'),
                'impact_score' => 5,
                'admin_id' => Admin::first()->id,
            ],
            [
                'achievement_id' => null,
                'slug' => 'clarify-discrepancy'.BaseService::randomCharacters(5, '0123456789'),
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'name' => 'Clarify - Discrepancy',
                'type' => 'DataPoint',
                'extraction_description' => 'Clarify - Discrepancy',
                'example' => null,
                'purpose' => '',
                'development_order' => BaseService::randomCharacters(2, '0123456789'),
                'impact_score' => 6,
                'admin_id' => Admin::first()->id,
            ],
            [
                'achievement_id' => null,
                'slug' => 'writer-origin-story'.BaseService::randomCharacters(5, '0123456789'),
                'item_id' => BaseService::randomCharacters(5, '0123456789'),
                'name' => 'Writer - Origin Story',
                'type' => 'DataPoint',
                'extraction_description' => 'User - Bio',
                'example' => null,
                'purpose' => '',
                'development_order' => BaseService::randomCharacters(2, '0123456789'),
                'impact_score' => 7,
                'admin_id' => Admin::first()->id,
            ],
        ];

        foreach ($dataPoints as $dataPoint) {
            $created = DataPoint::factory()->create($dataPoint);
            // Categories
            DataPointCategory::create([
                'data_point_id' => $created->id,
                'category_id' => Category::inRandomOrder()->first()->id,
            ]);

            DataPointCategory::create([
                'data_point_id' => $created->id,
                'category_id' => Category::inRandomOrder()->first()->id,
            ]);

            // Achievements
            DataPointAchievement::create([
                'data_point_id' => $created->id,
                'achievement_id' => Achievement::inRandomOrder()->first() ? Achievement::inRandomOrder()->first()->id : Achievement::factory()->create()->id,
            ]);

            DataPointAchievement::create([
                'data_point_id' => $created->id,
                'achievement_id' => Achievement::inRandomOrder()->first() ? Achievement::inRandomOrder()->first()->id : Achievement::factory()->create()->id,
            ]);

            // Summaries
            DataPointSummary::create([
                'data_point_id' => $created->id,
                'summary_id' => Summary::inRandomOrder()->first() ? Summary::inRandomOrder()->first()->id : Summary::factory()->create()->id,
            ]);

            DataPointSummary::create([
                'data_point_id' => $created->id,
                'summary_id' => Summary::inRandomOrder()->first() ? Summary::inRandomOrder()->first()->id : Summary::factory()->create()->id,
            ]);
        }

    }
}
