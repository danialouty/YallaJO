<?php

namespace Database\Factories;

use App\Models\Adventurer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Adventurer> */
class AdventurerFactory extends Factory
{
    protected $model = Adventurer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->adventurer(),
            'experience_level' => fake()->randomElement(['Beginner', 'Intermediate', 'Advanced']),
            'interests' => fake()->randomElements(['Hiking', 'Camping', 'Diving', 'Toursim'], 2),
            'location' => fake()->city(),
        ];
    }
}
