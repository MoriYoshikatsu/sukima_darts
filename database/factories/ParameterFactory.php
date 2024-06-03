<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Parameter>
 */
class ParameterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'spot_category_id' => 1,
            'departure_latitude' => fake()-> randomFloat(2, 30, 40),
            'departure_longitude' => fake()-> randomFloat(2, 130, 140),
            'transportation' => fake()->word,
            'trip_time' => fake()->randomNumber(),
        ];
    }
}
