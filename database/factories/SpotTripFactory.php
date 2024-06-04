<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Spot_trip>
 */
class SpotTripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'spot_id' => 1,
            'trip_id' => 1,
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
