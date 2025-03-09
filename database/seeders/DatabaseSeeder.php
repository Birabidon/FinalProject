<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Location;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//        User::factory(10)->create();
        Location::query()->delete();
        Location::factory(10)->create();

//        Location::->create([
//            'title' => fake()->sentence(),
//            'lat' => fake()->randomFloat(15,55.8, 58.1),
//            'lng' => fake()->randomFloat(15,20.9, 28.3)
//        ]); without factory
    }
}
