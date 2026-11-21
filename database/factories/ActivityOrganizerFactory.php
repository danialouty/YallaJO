<?php

namespace Database\Factories;

use App\Models\ActivityOrganizer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ActivityOrganizer> */
class ActivityOrganizerFactory extends Factory
{
    protected $model = ActivityOrganizer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->organizer(),
            'license_number' => fake()->unique()->bothify('JO-####-????'),
            'organizer_type' => fake()->randomElement(['Adventure_group', 'Tourism_office']),
            'approval_status' => 'Approved',
            'social_handle_url' => fake()->url(),
            'approved_by' => null,
            'approved_at' => now(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'approval_status' => 'Pending',
            'approved_by' => null,
            'approved_at' => null,
        ]);
    }
}
