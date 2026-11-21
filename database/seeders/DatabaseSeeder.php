<?php

namespace Database\Seeders;

use App\Models\ActivityOrganizer;
use App\Models\Community;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Admin User
        $this->upsertFactoryUser('admin', 'admin@yallajo.com', 'YallaJO Admin', '0790000001');

        // 2. Seed Organizer User (with a pending profile to test Admin Approval)
        $pendingOrganizer = $this->upsertFactoryUser(
            'organizer',
            'organizer@yallajo.com',
            'Wadi Rum Adventures',
            '0790000002'
        );

        ActivityOrganizer::updateOrCreate(
            ['user_id' => $pendingOrganizer->id],
            [
                'organizer_type' => ActivityOrganizer::TOURISM_OFFICE,
                'license_number' => 'JO-MOTA-1045',
                'social_handle_url' => 'https://instagram.com/wadirumadventures',
                'approval_status' => ActivityOrganizer::PENDING,
            ]
        );

        // 3. Seed Fresh Organizer User (no profile yet, to test your Create Modal)
        $this->upsertFactoryUser('organizer', 'neworganizer@yallajo.com', 'Jordan Hike Club', '0790000003');

        // Additional approved organizer and related records for manual testing.
        $this->call(DemoSeeder::class);
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

        $communities = [
            [
                'name' => 'Wadi Mujib Trail Seekers',
                'slug' => 'wadi-mujib-trail-seekers',
                'description' => 'مجتمع لعشاق مسارات وادي الموجب، من أول الرحلات إلى المغامرين ذوي الخبرة.',
                'region' => 'وادي الموجب',
                'activity_style' => 'المشي والمسارات',
            ],
            [
                'name' => 'Jordan Weekend Campers',
                'slug' => 'jordan-weekend-campers',
                'description' => 'نخطط لرحلات تخييم ونشاطات نهاية الأسبوع حول مختلف مناطق الأردن.',
                'region' => 'الأردن',
                'activity_style' => 'التخييم',
            ],
            [
                'name' => 'Solo Female Hikers JO',
                'slug' => 'solo-female-hikers-jo',
                'description' => 'مجتمع آمن ومشجع للنساء لاستكشاف مسارات الأردن والمشاركة في الرحلات الجماعية.',
                'region' => 'عمان',
                'activity_style' => 'المشي النسائي',
            ],
        ];

        foreach ($communities as $community) {
            Community::updateOrCreate(
                ['slug' => $community['slug']],
                $community
            );
        }
    }
}
