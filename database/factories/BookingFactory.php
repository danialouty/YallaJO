<?php

namespace Database\Factories;

use App\Models\Adventure;
use App\Models\Adventurer;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Booking> */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $unitPrice = fake()->randomFloat(2, 15, 300);
        $participants = fake()->numberBetween(1, 4);

        return [
            'adventure_id' => Adventure::factory(),
            'adventurer_id' => Adventurer::factory(),
            'participants_count' => $participants,
            'unit_price' => $unitPrice,
            'commission_rate' => 0.10,
            'total_amount' => $unitPrice * $participants,
            'status' => fake()->randomElement(['Accepted', 'Pending', 'Cancelled']),
            'capacity' => 10,
            'cancelled_at' => null,
        ];
    }
}
