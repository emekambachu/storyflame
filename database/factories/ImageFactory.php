<?php

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ImageFactory extends Factory
{
    protected $model = Image::class;

    public function definition(): array
    {
        return [
            'path' => $this->faker->image(
                storage_path('app/public/images'),
                640,
                480,
            ),
            'group' => 'image',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
