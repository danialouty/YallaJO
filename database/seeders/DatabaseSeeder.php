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
        User::updateOrCreate(
            ['email' => 'admin@yallajo.com'],
            [
                'name' => 'YallaJO Admin',
                'password' => 'password123',
                'phone_number' => '0790000001',
                'role' => 'Admin',
            ]
        );

        // 2. Seed Organizer User (with a pending profile to test Admin Approval)
        $pendingOrganizer = User::updateOrCreate(
            ['email' => 'organizer@yallajo.com'],
            [
                'name' => 'Wadi Rum Adventures',
                'password' => 'password123',
                'phone_number' => '0790000002',
                'role' => 'Activity_Organizer',
            ]
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
        User::updateOrCreate(
            ['email' => 'neworganizer@yallajo.com'],
            [
                'name' => 'Jordan Hike Club',
                'password' => 'password123',
                'phone_number' => '0790000003',
                'role' => 'Activity_Organizer',
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
