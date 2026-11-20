<?php

namespace Database\Seeders;

use App\Models\ActivityOrganizer;
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
                'organizer_type' => 'tourism_office',
                'license_number' => 'JO-MOTA-1045',
                'social_handle_url' => 'https://instagram.com/wadirumadventures',
                'approval_status' => 'pending',
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
    }
}
