<?php

namespace Database\Factories;

use App\Models\Adventure;
use App\Models\Adventure_schedules;
use App\Models\Guides;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Adventure_schedules> */
class AdventureSchedulesFactory extends Factory
{
    protected $model = Adventure_schedules::class;

    public function definition(): array
    {
        $startsAt = now()->addMonth()->setTime(8, 0);

        return [
            'adventure_id' => Adventure::factory(),
            'guide_id' => Guides::factory(),
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->copy()->addDay()->setTime(17, 0),
            'capacity' => 10,
            'status' => 'Active',
        ];
    }
}
