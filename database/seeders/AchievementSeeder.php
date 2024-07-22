<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Admin\Admin;
use App\Models\Category;
use App\Models\Image;
use App\Models\User;
use App\Services\Base\BaseService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
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
                'user_id' => User::where('email', 'mitch@hiddenplanetproductions.com')->first()->id,
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


            try {
                $category = Category::firstOrCreate(
                    ['name' => $row['category']],
                    ['slug' => Str::slug($row['category'], '_')]
                );

                $path = $row['icon_path'];
                $icon = $row['icon'];

                unset($row['category'], $row['icon_path'], $row['icon']);

                $achievement = Achievement::create($row);
                $achievement->categories()->attach($category->id);

                $achievement->icon()->create([
                    'path' => $path,
                    'name' => $icon,
                    'group' => 'default',
                    'imageable_id' => $achievement->id,
                    'imageable_type' => Achievement::class,
                    'token_cost' => 0,
                ]);

            } catch (\Exception $e) {
                Log::error('Error creating icon: ' . $e->getMessage());
                dd('Error creating icon: ' . $e->getMessage());
            }

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
