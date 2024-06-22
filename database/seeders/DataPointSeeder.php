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
        DataPoint::factory(10)->create()->each(function ($dataPoint) {

            // Categories
            DataPointCategory::create([
                'data_point_id' => $dataPoint->id,
                'category_id' => Category::inRandomOrder()->first()->id,
            ]);

            DataPointCategory::create([
                'data_point_id' => $dataPoint->id,
                'category_id' => Category::inRandomOrder()->first()->id,
            ]);

            // Achievements
            DataPointAchievement::create([
                'data_point_id' => $dataPoint->id,
                'achievement_id' => Achievement::inRandomOrder()->first() ? Achievement::inRandomOrder()->first()->id : Achievement::factory()->create()->id,
            ]);

            DataPointAchievement::create([
                'data_point_id' => $dataPoint->id,
                'achievement_id' => Achievement::inRandomOrder()->first() ? Achievement::inRandomOrder()->first()->id : Achievement::factory()->create()->id,
            ]);

            // Summaries
            DataPointSummary::create([
                'data_point_id' => $dataPoint->id,
                'summary_id' => Summary::inRandomOrder()->first() ? Summary::inRandomOrder()->first()->id : Summary::factory()->create()->id,
            ]);

            DataPointSummary::create([
                'data_point_id' => $dataPoint->id,
                'summary_id' => Summary::inRandomOrder()->first() ? Summary::inRandomOrder()->first()->id : Summary::factory()->create()->id,
            ]);


        });
    }
}
