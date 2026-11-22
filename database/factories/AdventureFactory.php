<?php

namespace Database\Factories;

use App\Models\ActivityOrganizer;
use App\Models\Adventure;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Adventure> */
class AdventureFactory extends Factory
{
    protected $model = Adventure::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'activity_organizer_id' => ActivityOrganizer::factory(),
            'difficulty_level' => fake()->randomElement(['Easy', 'Medium', 'Hard']),
            'capacity' => fake()->numberBetween(5, 20),
            'category' => fake()->randomElement(['Hiking', 'Diving', 'Camping', 'Cyciling', 'Zaitoon day out', 'kayaking', 'climbing', 'Sky Diving', 'Blimping', 'Toursim']),
            'location' => fake()->address(),
            'duration_hours' => fake()->randomFloat(2, 2, 48),
            'price' => fake()->randomFloat(2, 15, 300),
            'requirements' => fake()->sentence(),
            'status' => 'Active',
        ];
    }
}
