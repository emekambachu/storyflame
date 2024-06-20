<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Story', 'slug' => 'story'],
            ['name' => 'Character', 'slug' => 'character'],
            ['name' => 'Plot', 'slug' => 'plot'],
            ['name' => 'Sub Plot', 'slug' => 'sub-plot'],
            ['name' => 'Series', 'slug' => 'series'],
            ['name' => 'Season', 'slug' => 'season'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
