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
                'organizer_type' => 'Tourism_office',
                'license_number' => 'JO-MOTA-1045',
                'social_handle_url' => 'https://instagram.com/wadirumadventures',
                'approval_status' => 'Pending',
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
    }
}
