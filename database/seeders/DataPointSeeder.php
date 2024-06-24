<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Category;
use App\Models\DataPoint;
use App\Models\DataPoint\DataPointAchievement;
use App\Models\DataPoint\DataPointCategory;
use App\Models\DataPoint\DataPointSummary;
use App\Models\Summary\Summary;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'slug' => 'story-primary-obstacle',
                'name' => 'Story - Primary Obstacle',
                'type' => 'DataPoint',
                'extraction_description' => 'Story (aka episode, novel) - Logline',
                'example' => null,
                'purpose' => 'Required',
                'development_order' => 12,
                'impact_score' => 1,
            ],
            [
                'achievement_id' => null,
                'slug' => 'story-key-characters',
                'name' => 'Story - Key Characters',
                'type' => 'DataPoint',
                'extraction_description' => 'Story (aka episode, novel) - Logline',
                'example' => null,
                'purpose' => 'Required',
                'development_order' => 19,
                'impact_score' => 1,
            ],
            [
                'achievement_id' => null,
                'slug' => 'story-key-relationships',
                'name' => 'Story - Key Relationships',
                'type' => 'DataPoint',
                'extraction_description' => 'Story (aka episode, novel) - Logline',
                'example' => null,
                'purpose' => 'Required',
                'development_order' => 20,
                'impact_score' => 1,
            ],
            [
                'achievement_id' => null,
                'slug' => 'story-impact-on-plot-and-theme',
                'name' => 'Story - Impact on Plot and Theme',
                'type' => 'DataPoint',
                'extraction_description' => 'Story (aka episode, novel) - Logline',
                'example' => null,
                'purpose' => 'Required',
                'development_order' => 22,
                'impact_score' => 1,
            ],
            [
                'achievement_id' => null,
                'slug' => 'plot-thematic-cohesion',
                'name' => 'Plot - Thematic Cohesion',
                'type' => 'DataPoint',
                'extraction_description' => 'Story (aka episode, novel) - Logline',
                'example' => null,
                'purpose' => 'Required',
                'development_order' => 41,
                'impact_score' => 1,
            ],
            [
                'achievement_id' => null,
                'slug' => 'plot-status-quo',
                'name' => 'Plot - Status Quo',
                'type' => 'DataPoint',
                'extraction_description' => 'Story (aka episode, novel) - Logline',
                'example' => null,
                'purpose' => 'Required',
                'development_order' => 44,
                'impact_score' => 1,
            ],
            [
                'achievement_id' => null,
                'slug' => 'story-exploring',
                'name' => 'Story - Exploring',
                'type' => 'DataPoint',
                'extraction_description' => 'Story (aka episode, novel) - Logline',
                'example' => null,
                'purpose' => 'Required',
                'development_order' => 83,
                'impact_score' => 1,
            ],
            [
                'achievement_id' => null,
                'slug' => 'story-premise',
                'name' => 'Story - Premise',
                'type' => 'DataPoint',
                'extraction_description' => 'Story (aka episode, novel) - Logline',
                'example' => null,
                'purpose' => 'Required',
                'development_order' => 86,
                'impact_score' => 1,
            ],
            [
                'achievement_id' => null,
                'slug' => 'story-setup',
                'name' => 'Story - Setup',
                'type' => 'DataPoint',
                'extraction_description' => 'Story (aka episode, novel) - Logline',
                'example' => null,
                'purpose' => 'Required',
                'development_order' => 88,
                'impact_score' => 1,
            ],
            [
                'achievement_id' => null,
                'slug' => 'story-inciting-incident',
                'name' => 'Story - Inciting Incident',
                'type' => 'DataPoint',
                'extraction_description' => 'Story (aka episode, novel) - Logline',
                'example' => null,
                'purpose' => 'Required',
                'development_order' => 89,
                'impact_score' => 1,
            ],
            [
                'achievement_id' => null,
                'slug' => 'story-unique-elements',
                'name' => 'Story - Unique Elements',
                'type' => 'DataPoint',
                'extraction_description' => 'Story (aka episode, novel) - Logline',
                'example' => null,
                'purpose' => 'Required',
                'development_order' => 101,
                'impact_score' => 1,
            ],
            [
                'achievement_id' => null,
                'slug' => 'achievement-impact-one-liner',
                'name' => 'Achievement - Impact One-Liner',
                'type' => 'DataPoint',
                'extraction_description' => 'Achievement - Impact One-Liner',
                'example' => null,
                'purpose' => '',
                'development_order' => 2,
                'impact_score' => 2,
            ],
            [
                'achievement_id' => null,
                'slug' => 'achievement-per-category-impact-summary',
                'name' => 'Achievement - [Per Category] Impact Summary',
                'type' => 'DataPoint',
                'extraction_description' => 'Achievement - [Per Category] Impact Summary',
                'example' => null,
                'purpose' => '',
                'development_order' => 3,
                'impact_score' => 3,
            ],
            [
                'achievement_id' => null,
                'slug' => 'arc-setup',
                'name' => 'Arc - Setup',
                'type' => 'DataPoint',
                'extraction_description' => 'Arc - Setup',
                'example' => null,
                'purpose' => '',
                'development_order' => 88,
                'impact_score' => 33,
            ],
            [
                'achievement_id' => null,
                'slug' => 'clarify-title',
                'name' => 'Clarify - Title',
                'type' => 'DataPoint',
                'extraction_description' => 'Clarify - Title',
                'example' => null,
                'purpose' => '',
                'development_order' => null,
                'impact_score' => 5,
            ],
            [
                'achievement_id' => null,
                'slug' => 'clarify-discrepancy',
                'name' => 'Clarify - Discrepancy',
                'type' => 'DataPoint',
                'extraction_description' => 'Clarify - Discrepancy',
                'example' => null,
                'purpose' => '',
                'development_order' => null,
                'impact_score' => 6,
            ],
            [
                'achievement_id' => null,
                'slug' => 'writer-origin-story',
                'name' => 'Writer - Origin Story',
                'type' => 'DataPoint',
                'extraction_description' => 'User - Bio',
                'example' => null,
                'purpose' => '',
                'development_order' => 130,
                'impact_score' => 7,
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


//        DataPoint::factory(10)->create()->each(function ($dataPoint) {
//
//            // Categories
//            DataPointCategory::create([
//                'data_point_id' => $dataPoint->id,
//                'category_id' => Category::inRandomOrder()->first()->id,
//            ]);
//
//            DataPointCategory::create([
//                'data_point_id' => $dataPoint->id,
//                'category_id' => Category::inRandomOrder()->first()->id,
//            ]);
//
//            // Achievements
//            DataPointAchievement::create([
//                'data_point_id' => $dataPoint->id,
//                'achievement_id' => Achievement::inRandomOrder()->first() ? Achievement::inRandomOrder()->first()->id : Achievement::factory()->create()->id,
//            ]);
//
//            DataPointAchievement::create([
//                'data_point_id' => $dataPoint->id,
//                'achievement_id' => Achievement::inRandomOrder()->first() ? Achievement::inRandomOrder()->first()->id : Achievement::factory()->create()->id,
//            ]);
//
//            // Summaries
//            DataPointSummary::create([
//                'data_point_id' => $dataPoint->id,
//                'summary_id' => Summary::inRandomOrder()->first() ? Summary::inRandomOrder()->first()->id : Summary::factory()->create()->id,
//            ]);
//
//            DataPointSummary::create([
//                'data_point_id' => $dataPoint->id,
//                'summary_id' => Summary::inRandomOrder()->first() ? Summary::inRandomOrder()->first()->id : Summary::factory()->create()->id,
//            ]);
//
//
//        });
    }
}
