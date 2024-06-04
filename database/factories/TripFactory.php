<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'parameter_id' => 1,
            'title' => fake()->word,
            'description' => fake()->realText(),
            'first_latitude' => fake()-> randomFloat(2, 30, 40),
            'first_longitude' => fake()-> randomFloat(2, 130, 140),
            'trip_date' => fake()-> date(),
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
