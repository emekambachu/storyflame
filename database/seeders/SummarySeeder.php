<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Summary\Summary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SummarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Summary::all()->each->forceDelete();

        // open csv file
        $file = fopen(database_path('csv/summaries.csv'), 'r');

        // skip the first row
        $columns = fgetcsv($file);

        $data = [];
        // read the rest of the file
        while ($row = fgetcsv($file)) {
            if (empty($row[0]) || empty($row[1])) {
                continue;
            }

            if ($row[1] === '-')
                continue;

            $rowData = [];
            // replace column index with column name
            foreach ($row as $key => $value) {
                $rowData[$columns[$key]] = $value;
            }

            $data[] = [
                'name' => $rowData['Name'],
                'slug' => Str::slug("{$rowData['Narrative Element']} {$rowData['Section / Component']} {$rowData['Name']}", '_'),
                'item_id' => $rowData['New Summary ID'],
                'location' => $rowData['Combined Name'],
                'length' => $rowData['Length'],
                'purpose' => $rowData['Purpose'],
                'creation_prompt' => $rowData['Creation Prompt'] ?? '-',
                'example_summary' => $rowData['Example'],
                'published_at' => now()->format('Y-m-d H:i:s'),

                'category' => $rowData['Narrative Element'],
            ];
        }

        // close the file
        fclose($file);

        foreach ($data as $row) {
            $category = Category::firstOrCreate(['name' => $row['category']], ['slug' => Str::slug($row['category'])]);
            unset($row['category']);

            $summary = Summary::create($row);
            $summary->categories()->attach($category);
        }
    }
}
