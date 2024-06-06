<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AchievementsSeeder extends Seeder
{
    public function run(): void
    {
        Achievement::all()->each->forceDelete();

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
                'slug' => Str::slug($rowData['Achievement Title']),
                'element' => $rowData['Element'],
                'subtitle' => $rowData['Brief Subtitle focusing on benefit to the story'],
                'extraction_description' => $rowData['Extraction Description'],
                'purpose' => $rowData['Purpose'],
                'color' => $rowData['Color'],
                'icon' => 'temp.png',
            ];
        }

        // close the file
        fclose($file);

        // insert the data
        foreach ($data as $row) {
            Achievement::create($row);
        }

        // seed data points
        $this->call(AchievementDataPointsSeeder::class);
    }
}
