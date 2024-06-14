<?php

namespace Tests\Feature;

use App\Models\Achievement;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AchievementsTest extends TestCase
{
    #[Test]
    #[Group("achievements")]
    public function test_achievements_to_processing_array_formated_correctly()
    {
        $formatted = Achievement::inRandomOrder()->get()->map->toProcessingArray()->values()->toArray();

        $this->assertIsArray($formatted);
        # assert format of the array
        foreach ($formatted as $achievement) {
            $this->assertArrayHasKey('name', $achievement);
            $this->assertArrayHasKey('title', $achievement);
            $this->assertArrayHasKey('description', $achievement);
            $this->assertArrayHasKey('data_points', $achievement);
            $this->assertIsArray($achievement['data_points']);
            foreach ($achievement['data_points'] as $data_point) {
                $this->assertArrayHasKey('name', $data_point);
                $this->assertArrayHasKey('title', $data_point);
                $this->assertArrayHasKey('type', $data_point);
                $this->assertArrayHasKey('description', $data_point);
            }
        }
    }

    #[Test]
    #[Group("achievements")]
    public function test_achievements_excludes_data_points()
    {
        $achievement = Achievement::inRandomOrder()->first();
        $data_point_to_exclude = $achievement->dataPoints()->inRandomOrder()->first();
        $formatted = $achievement->toProcessingArray([$data_point_to_exclude->slug]);

        $this->assertIsArray($formatted);
        $this->assertArrayHasKey('name', $formatted);
        $this->assertArrayHasKey('title', $formatted);
        $this->assertArrayHasKey('description', $formatted);
        $this->assertArrayHasKey('data_points', $formatted);
        $this->assertIsArray($formatted['data_points']);
        $this->assertCount($achievement->dataPoints->count() - 1, $formatted['data_points']);
    }
}
