<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $this->call([
            UserSeeder::class,
            AchievementSeeder::class,
            AchievementDataPointsSeeder::class,
            SummarySeeder::class,
            SummarySchemaSeeder::class,
            StoriesSeeder::class,
        ]);
    }
}
