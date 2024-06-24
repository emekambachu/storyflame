<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Achievement\AchievementCategory;
use App\Models\Admin\Admin;
use App\Models\Category;
use App\Models\User;
use App\Services\Base\BaseService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
                'slug' => Str::slug($rowData['Achievement Title'], '_'),
                'item_id' => (int)BaseService::randomCharacters(5, '0123456789'),
                //'element' => $rowData['Element'],
                'subtitle' => $rowData['Brief Subtitle focusing on benefit to the story'],
                'extraction_description' => $rowData['Extraction Description'],
                'purpose' => $rowData['Purpose'],
                'color' => $rowData['Color'],
                'icon' => $rowData['Final Icon Image'] . '.png',
                'icon_path' => null,
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

            $achievement = Achievement::create($row);

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
