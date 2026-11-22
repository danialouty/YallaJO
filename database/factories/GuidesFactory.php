<?php

namespace Database\Factories;

use App\Models\ActivityOrganizer;
use App\Models\Guides;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Guides> */
class GuidesFactory extends Factory
{
    protected $model = Guides::class;

    public function definition(): array
    {
        return [
            'activity_organizer_id' => ActivityOrganizer::factory(),
            'bio' => fake()->paragraph(),
            'experience_years' => fake()->numberBetween(1, 25),
            'specialization' => fake()->randomElements(['Hiking', 'Camping', 'Diving', 'Toursim'], 2),
            'cetification' => fake()->optional()->sentence(3),
            'status' => fake()->randomElement(['Available', 'Busy']),
        ];
    }
}
