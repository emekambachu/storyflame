<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Admin\Admin;
use App\Models\Category;
use App\Models\User;
use App\Services\Base\BaseService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        Achievement::all()->each->forceDelete();

        Admin::factory()->create();

        // open csv file
        $file = fopen(database_path('csv/achievements.csv'), 'r');

        // skip the first row
        $columns = fgetcsv($file);

        $data = [];
        // read the rest of the file
        while ($row = fgetcsv($file)) {
            if (empty($row[0])) {
                continue;
            }

            $rowData = [];
            // replace column index with column name
            foreach ($row as $key => $value) {
                $rowData[$columns[$key]] = $value;
            }

            $data[] = [
                'name' => $rowData['Achievement Title'],
                'item_id' => $rowData['Achievement ID'],
                'slug' => Str::slug($rowData['Achievement Title'], '_'),
                'example' => $rowData['Brief Subtitle focusing on benefit to the story'],
                'subtitle' => $rowData['Brief Subtitle focusing on benefit to the story'],
                'category' => $rowData['Element'],
                'extraction_description' => $rowData['Extraction Description'],
                'purpose' => $rowData['Purpose'],
                'color' => $rowData['Color'],
                'dev_order' => $rowData['Order'],
                'total_impact' => $rowData['Total Impact'],
                'icon' => $rowData['Final Icon Image'] . '.png',
                'icon_path' => '/uploads/achievements/icons/',
                'publish_at' => now()->format('Y-m-d H:i:s'),
                'admin_id' => Admin::first()->id,
                'user_id' => User::factory()->create()->id,
            ];
        }

        // close the file
        fclose($file);

        // insert the data
        foreach ($data as $row) {
            // check if image exists
            if (!file_exists(public_path('images/achievements/' . $row['icon']))) {
                throw new \RuntimeException('Image not found: ' . $row['icon']);
            }

            $category = Category::firstOrCreate(
                ['name' => $row['category']],
                ['slug' => Str::slug($row['category'], '_')]
            );
            unset($row['category']);
            $achievement = Achievement::create($row);
            $achievement->categories()->attach($category->id);

            // Add 2 categories to each achievement
//            AchievementCategory::create([
//                'achievement_id' => $achievement->id,
//                'category_id' => Category::inRandomOrder()->first()->id,
//            ]);
//            AchievementCategory::create([
//                'achievement_id' => $achievement->id,
//                'category_id' => Category::inRandomOrder()->first()->id,
//            ]);

//            AchievementCategory::factory(3)->create([
//                'achievement_id' => $achievement->id,
//                'category_id' => Category::inRandomOrder()->first()->id,
//            ]);
        }
    }
}
