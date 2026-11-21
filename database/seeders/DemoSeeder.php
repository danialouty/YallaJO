<?php

namespace Database\Seeders;

use App\Models\ActivityOrganizer;
use App\Models\Adventure;
use App\Models\AdventureDay;
use App\Models\Adventure_schedules;
use App\Models\Adventurer;
use App\Models\Booking;
use App\Models\Guides;
use App\Models\Guides_availabilities;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@yallajo.com')->firstOrFail();

        $organizerUser = $this->upsertFactoryUser(
            'organizer',
            'demo.organizer@yallajo.com',
            'Jordan Trails Organizer',
            '0790000100'
        );

        $organizer = ActivityOrganizer::updateOrCreate(
            ['user_id' => $organizerUser->id],
            [
                'organizer_type' => 'Adventure_group',
                'license_number' => 'JO-DEMO-2026',
                'social_handle_url' => 'https://instagram.com/jordantrailsdemo',
                'approval_status' => 'Approved',
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ]
        );

        $guideData = Guides::factory()->make([
            'activity_organizer_id' => $organizer->id,
                'bio' => 'Experienced local guide for hiking and outdoor trips in Jordan.',
                'experience_years' => 8,
                'specialization' => ['Hiking', 'Camping', 'Wadi Rum'],
                'cetification' => 'Jordanian Hiking Guide Certificate',
                'status' => 'Available',
        ]);
        $guide = Guides::updateOrCreate(
            ['activity_organizer_id' => $organizer->id],
            $guideData->getAttributes()
        );

        Guides_availabilities::updateOrCreate(
            ['guide_id' => $guide->id],
            ['status' => 'Available']
        );

        $adventurers = [];
        foreach ([
            [1, 'Lina Tester', 'demo.adventurer1@yallajo.com', '0790000101', 'Beginner', ['Hiking', 'Camping'], 'Amman'],
            [2, 'Omar Tester', 'demo.adventurer2@yallajo.com', '0790000102', 'Intermediate', ['Hiking', 'Culture'], 'Irbid'],
        ] as [$index, $name, $email, $phone, $level, $interests, $location]) {
            $user = $this->upsertFactoryUser('adventurer', $email, $name, $phone);

            $adventurers[$index] = Adventurer::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'experience_level' => $level,
                    'interests' => $interests,
                    'location' => $location,
                ]
            );
        }

        $adventureFixtures = [
            [
                'title' => 'Demo: Wadi Rum Stargazing Camp',
                'description' => 'A two-day desert camp with guided hiking, Bedouin hospitality, and a night under the stars.',
                'difficulty_level' => 'Easy',
                'capacity' => 12,
                'category' => 'Camping',
                'location' => 'Wadi Rum, Aqaba Governorate',
                'duration_hours' => 30,
                'price' => 85,
                'requirements' => 'Bring a warm layer, comfortable shoes, and personal medication.',
                'starts_at' => Carbon::parse('2027-04-10 08:00:00'),
                'ends_at' => Carbon::parse('2027-04-11 14:00:00'),
                'days' => [
                    [1, 'Desert arrival and sunset hike', 'Meet the guide, explore the sandstone formations, and watch sunset from a viewpoint.'],
                    [2, 'Jeep tour and camp breakfast', 'Enjoy breakfast at camp, then take a short guided jeep tour before departure.'],
                ],
            ],
            [
                'title' => 'Demo: Dana Reserve Hiking Weekend',
                'description' => 'A guided two-day hike through Dana Biosphere Reserve with village and canyon viewpoints.',
                'difficulty_level' => 'Medium',
                'capacity' => 10,
                'category' => 'Hiking',
                'location' => 'Dana Biosphere Reserve, Tafilah',
                'duration_hours' => 16,
                'price' => 60,
                'requirements' => 'Bring hiking boots, water, sun protection, and a small day pack.',
                'starts_at' => Carbon::parse('2027-05-15 07:00:00'),
                'ends_at' => Carbon::parse('2027-05-16 17:00:00'),
                'days' => [
                    [1, 'Dana village to the canyon', 'Start in Dana village and hike with the guide to the canyon overlook.'],
                    [2, 'Morning trail and return', 'Follow a shorter morning trail, take a break at the reserve, and return to the village.'],
                ],
            ],
        ];

        $adventures = [];
        foreach ($adventureFixtures as $fixture) {
            $days = $fixture['days'];
            $startsAt = $fixture['starts_at'];
            $endsAt = $fixture['ends_at'];
            unset($fixture['days'], $fixture['starts_at'], $fixture['ends_at']);

            $adventureData = Adventure::factory()->make($fixture);
            $adventure = Adventure::updateOrCreate(
                [
                    'activity_organizer_id' => $organizer->id,
                    'title' => $fixture['title'],
                ],
                $adventureData->getAttributes()
            );
            $adventures[] = $adventure;

            $scheduleData = Adventure_schedules::factory()->make([
                'adventure_id' => $adventure->id,
                'guide_id' => $guide->id,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'capacity' => $adventure->capacity,
                'status' => 'Active',
            ]);
            Adventure_schedules::updateOrCreate(
                [
                    'adventure_id' => $adventure->id,
                    'starts_at' => $startsAt,
                ],
                $scheduleData->getAttributes()
            );

            foreach ($days as [$dayNumber, $title, $description]) {
                $dayData = AdventureDay::factory()->make([
                    'adventure_id' => $adventure->id,
                    'day_number' => $dayNumber,
                    'title' => $title,
                    'description' => $description,
                ]);
                AdventureDay::updateOrCreate(
                    ['adventure_id' => $adventure->id, 'day_number' => $dayNumber],
                    $dayData->getAttributes()
                );
            }
        }

        $reviewedBooking = null;
        foreach ($adventures as $adventureIndex => $adventure) {
            foreach ($adventurers as $adventurerIndex => $adventurer) {
                $isReviewedBooking = $adventureIndex === 0 && $adventurerIndex === 1;
                $status = $isReviewedBooking ? 'Accepted' : ($adventurerIndex === 1 ? 'Pending' : 'Accepted');
                $participants = $adventurerIndex === 1 ? 2 : 1;

                $bookingData = Booking::factory()->make([
                    'adventure_id' => $adventure->id,
                    'adventurer_id' => $adventurer->id,
                    'participants_count' => $participants,
                    'unit_price' => $adventure->price,
                    'commission_rate' => 0.10,
                    'total_amount' => $adventure->price * $participants,
                    'status' => $status,
                    'capacity' => $adventure->capacity,
                    'cancelled_at' => null,
                ]);
                $booking = Booking::updateOrCreate(
                    [
                        'adventure_id' => $adventure->id,
                        'adventurer_id' => $adventurer->id,
                    ],
                    $bookingData->getAttributes()
                );

                Payment::updateOrCreate(
                    ['booking_id' => $booking->id],
                    [
                        'total_amount' => $booking->total_amount,
                        'method' => $adventurerIndex === 0 ? 'Cash' : 'Visa',
                        'status' => $status === 'Accepted' ? 'Confirmed' : 'Pending',
                        'paid_at' => $status === 'Accepted' ? now()->subDays(2) : null,
                    ]
                );

                if ($isReviewedBooking) {
                    $reviewedBooking = $booking;
                }
            }
        }

        if ($reviewedBooking) {
            $reviewData = Review::factory()->make([
                'adventurer_id' => $reviewedBooking->adventurer_id,
                'adventure_id' => $reviewedBooking->adventure_id,
                'booking_id' => $reviewedBooking->id,
                'rating' => 5,
                'comment' => 'Beautiful scenery and a well-organized trip. The guide was helpful throughout.',
                'is_reported' => false,
                'report_status' => null,
                'report_reason' => null,
            ]);
            Review::updateOrCreate(
                ['booking_id' => $reviewedBooking->id],
                $reviewData->getAttributes()
            );
        }
    }

    private function upsertFactoryUser(string $role, string $email, string $name, string $phone): User
    {
        $factory = match ($role) {
            'organizer' => User::factory()->organizer(),
            'admin' => User::factory()->admin(),
            default => User::factory()->adventurer(),
        };

        $generatedUser = $factory->make([
            'email' => $email,
            'name' => $name,
            'phone_number' => $phone,
            'password' => 'password123',
        ]);

        return User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $generatedUser->name,
                'phone_number' => $generatedUser->phone_number,
                'role' => $generatedUser->role,
                'password' => $generatedUser->password,
            ]
        );
    }
}
