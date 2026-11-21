<?php

namespace Database\Factories;

use App\Models\Adventure;
use App\Models\AdventureDay;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<AdventureDay> */
class AdventureDayFactory extends Factory
{
    protected $model = AdventureDay::class;

    public function definition(): array
    {
        return [
            'adventure_id' => Adventure::factory(),
            'day_number' => fake()->numberBetween(1, 7),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
        ];
    }
}
