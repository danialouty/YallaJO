<?php

namespace Database\Factories;

use App\Models\Adventure;
use App\Models\Adventurer;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Review> */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'adventurer_id' => Adventurer::factory(),
            'adventure_id' => Adventure::factory(),
            'booking_id' => Booking::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->paragraph(),
            'is_reported' => false,
            'report_status' => null,
            'report_reason' => null,
        ];
    }

    public function reported(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_reported' => true,
            'report_status' => 'pending',
            'report_reason' => fake()->sentence(),
        ]);
    }
}
