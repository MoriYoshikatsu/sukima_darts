<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(SpotCategorySeeder::class);
        $this->call(ParameterSeeder::class);
        $this->call(SpotSeeder::class);
        $this->call(TripSeeder::class);
        $this->call(SpotTripSeeder::class);
        $this->call(LikeSeeder::class);
        $this->call(ImageSeeder::class);
        $this->call(FollowSeeder::class);
    }
}
