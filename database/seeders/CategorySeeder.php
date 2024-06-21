<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
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
            Category::create($category);
        }
    }
}
