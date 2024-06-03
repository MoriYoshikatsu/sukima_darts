<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Spot>
 */
class SpotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'spot_category_id' => 1,
            'name' => fake()-> secondaryAddress(),
            'latitude' => fake()-> randomFloat(2, 30, 40),
            'longitude' => fake()-> randomFloat(2, 130, 140),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
