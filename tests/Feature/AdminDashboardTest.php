<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'admin_dashboard_test_memory',
            'database.connections.admin_dashboard_test_memory' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
            'session.driver' => 'array',
            'cache.default' => 'array',
        ]);

        $this->assertSame('sqlite', DB::connection()->getDriverName());
        $this->assertSame(':memory:', DB::connection()->getDatabaseName());

        // Build the dashboard schema only on this test's private connection.
        foreach ([
            '0001_01_01_000000_create_users_table',
            '2026_11_18_120848_create_activity_organizers_table',
            '2026_11_18_120849_create_adventurers_table',
            '2026_11_18_120849_create_adventures_table',
            '2026_11_18_120850_create_bookings_table',
            '2026_11_18_120850_create_payments_table',
            '2026_11_18_120851_create_reviews_table',
        ] as $migration) {
            (require database_path("migrations/{$migration}.php"))->up();
        }

        $this->withoutVite();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_non_admin_roles_cannot_access_the_overview(): void
    {
        foreach (['Adventurer', 'Activity_Organizer'] as $role) {
            $this->actingAs($this->user($role))->get(route('admin.dashboard'))->assertForbidden();
        }
    }

    public function test_admin_sees_zero_activity_and_working_navigation(): void
    {
        $admin = $this->user('Admin');
        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk()->assertViewIs('admin.dashboard')
            ->assertViewHas('metrics', fn (array $metrics) => count($metrics) === 14
                && $metrics['users'] === 1
                && array_sum($metrics) === 1)
            ->assertSee($admin->name)
            ->assertSee('0.00')
            ->assertSee(route('admin.organizers.index'))
            ->assertSee(route('admin.payments.index'))
            ->assertSee('dir="rtl"', false);

        $document = new \DOMDocument();
        $document->loadHTML($response->getContent(), LIBXML_NOERROR | LIBXML_NOWARNING);
        $xpath = new \DOMXPath($document);
        $this->assertGreaterThan(0, $xpath->query('//a[@aria-current="page" and @href="'.route('admin.dashboard').'"]')->length);

        $this->get(route('admin.organizers.index'))->assertOk();
        $this->get(route('admin.payments.index'))->assertOk();
    }

    public function test_metrics_count_database_records_and_sum_only_confirmed_payments(): void
    {
        $admin = $this->user('Admin');
        $adventurer = $this->user('Adventurer')->adventurer()->create();
        $organizer = $this->user('Activity_Organizer')->activityOrganizer()->create([
            'license_number' => 'TEST-PENDING', 'approval_status' => 'Pending',
        ]);
        $this->user('Activity_Organizer')->activityOrganizer()->create([
            'license_number' => 'TEST-APPROVED', 'approval_status' => 'Approved',
        ]);

        $adventureDetails = [
            'title' => 'Integration adventure', 'description' => 'Fixture', 'capacity' => 10,
            'location' => 'Ajloun', 'duration_hours' => 2, 'price' => 35.50, 'requirements' => 'Fixture',
        ];
        $adventure = $organizer->adventures()->create($adventureDetails + ['status' => 'Active']);
        $organizer->adventures()->create($adventureDetails + ['status' => 'Suspended']);

        $bookings = [];
        foreach (['Pending', 'Accepted', 'Cancelled'] as $status) {
            $bookings[$status] = $adventurer->bookings()->create([
                'adventure_id' => $adventure->id, 'participants_count' => 1, 'unit_price' => 35.50,
                'commission_rate' => 0.10, 'total_amount' => 35.50, 'capacity' => 10, 'status' => $status,
            ]);
        }

        foreach ([
            [$bookings['Accepted'], 'Confirmed', 35.50],
            [$bookings['Accepted'], 'Confirmed', 25.25],
            [$bookings['Pending'], 'Pending', 200],
            [$bookings['Cancelled'], 'Canceled', 400],
        ] as [$booking, $status, $amount]) {
            Payment::create(['booking_id' => $booking->id, 'total_amount' => $amount, 'status' => $status]);
        }

        $adventurer->reviews()->create([
            'adventure_id' => $adventure->id, 'booking_id' => $bookings['Accepted']->id, 'rating' => 4, 'comment' => 'Fixture',
        ]);

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk()
            ->assertViewHas('metrics', fn (array $metrics) => $metrics === [
                'users' => 4, 'adventurers' => 1, 'organizers' => 2, 'pendingOrganizers' => 1,
                'adventures' => 2, 'activeAdventures' => 1, 'bookings' => 3,
                'pendingBookings' => 1, 'acceptedBookings' => 1, 'cancelledBookings' => 1,
                'payments' => 4, 'confirmedPayments' => 2, 'confirmedPaymentAmount' => 60.75, 'reviews' => 1,
            ])->assertSee('60.75')->assertDontSee('660.75');
    }

    private function user(string $role): User
    {
        return User::factory()->create(['role' => $role, 'phone_number' => '0790000000']);
    }
}
