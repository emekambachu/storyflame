<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\DataPoint;
use App\Models\Summary\Summary;
use App\Models\SummarySchema;
use Illuminate\Database\Seeder;

class SummarySchemaSeeder extends Seeder
{
    public function run(): void
    {
        SummarySchema::all()->each->forceDelete();

        // open csv file
        $file = fopen(database_path('csv/summary_schema.csv'), 'r');

        // skip the first row
        $columns = fgetcsv($file);

        $data = [];
        // read the rest of the file
        while ($row = fgetcsv($file)) {
            if (empty($row[0]) || empty($row[1])) {
                continue;
            }

            $rowData = [];
            // replace column index with column name
            foreach ($row as $key => $value) {
                $rowData[$columns[$key]] = $value;
            }

            $data[] = [
                'summary_id' => $rowData['SummaryID'],
                'schemaable_type' => $rowData['Morphable Type'],
                'schemaable_id' => $rowData['Morphable ID'],
                'is_required' => $rowData['Required DataPoint'] === 'TRUE',
            ];
        }

        // close the file
        fclose($file);

        foreach ($data as $row) {
            if (empty($row['summary_id'])) {
                throw new \Exception('Summary ID is required');
            }
            if (empty($row['schemaable_type']) || empty($row['schemaable_id'])) {
                continue;
            }

            $summary = Summary::where('item_id', $row['summary_id'])->first();
            if (!$summary || !$summary->exists()) {
                dump('Summary not found for ID: ' . $row['summary_id']);
                continue;
            }

            if ($row['schemaable_type'] === 'DataPoint')
                $class = DataPoint::class;
            else if ($row['schemaable_type'] === 'Achievement')
                $class = Achievement::class;
            else throw new \Exception('Invalid schemaable type');

            if (is_numeric($row['schemaable_id'])) {
                // if schemaable_id is a number, it is an ID
                $schemaable = $class::firstWhere('item_id', $row['schemaable_id']);
                if (!$schemaable || !$schemaable->exists())
                    throw new \Exception('Schemaable not found for ID: ' . $row['schemaable_id']);
                $schemaable = collect([$schemaable]);
            } else if ($row['schemaable_id'] === '-') {
                // if schemaable_id is a dash, all schemaable of that type will be selected
                $schemaable = $class::all();
            } else {
                throw new \Exception('Invalid schemaable ID ' . $row['schemaable_id']);
            }

            if (empty($schemaable)) {
                throw new \Exception('Schemaable not found for ID: ' . $row['schemaable_id']);
            }

            $schemaable->each(function ($schemaable) use ($summary, $class, $row) {
                SummarySchema::create([
                    'summary_id' => $summary->id,
                    'schemaable_id' => $schemaable->id,
                    'schemaable_type' => $class,
                    'is_required' => $row['is_required'],
                ]);
            });
        }
    }
}
