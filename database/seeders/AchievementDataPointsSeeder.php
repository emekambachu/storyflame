<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AchievementDataPointsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('data_points')->truncate();

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

            $data[] = [
                'slug' => Str::slug($rowData['Data Point']),
                'achievement' => $rowData['Achievement Title'],
                'category' => $rowData['Category'],
                'name' => $rowData['Data Point'],
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
            unset($row['achievement']);
            $achievement?->dataPoints()->create($row);
        }
    }
}
