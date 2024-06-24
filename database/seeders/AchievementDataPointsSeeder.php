<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Category;
use App\Models\DataPoint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AchievementDataPointsSeeder extends Seeder
{
    public function run(): void
    {
        DataPoint::all()->each->forceDelete();

        // open csv file
        $file = fopen(database_path('csv/data_points.csv'), 'r');

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

            $example = $rowData['Extraction Example'];
            if (empty($example))
                $example = null;
//            else if (!str_starts_with($example, '['))
//                $example = '"' . $example . '"';

            $data[] = [
                'slug' => Str::slug($rowData['Data Point'], '_'),
                'achievement' => $rowData['Achievement Title'],
                'category' => $rowData['Category'],
                'name' => $rowData['Data Point'],
                'type' => $rowData['Extraction Type'],
                'example' => $example,
                'development_order' => $rowData['Development Order'],
                'extraction_description' => $rowData['Extraction Description'],
                'purpose' => $rowData['Definition/Purpose'],
                'impact_score' => $rowData['Impact Score'],
            ];
        }

        // close the file
        fclose($file);

        // insert the data
        foreach ($data as $row) {
            $achievement = Achievement::where('name', $row['achievement'])->first();
            $category = Category::firstOrCreate(
                ['name' => $row['category']],
                ['slug' => Str::slug($row['category'], '_')]
            );
            unset($row['category']);
            unset($row['achievement']);
            $dataPoint = $achievement?->dataPoints()->create($row);
            if ($dataPoint) {
                $dataPoint->categories()->attach($category->id);
            }
        }
    }
}
