<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PaymentAuthorizationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'payment_test_memory',
            'database.connections.payment_test_memory' => [
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

        // Build only the schema this suite needs on its private in-memory connection.
        foreach ([
            '0001_01_01_000000_create_users_table',
            '2026_11_18_120848_create_activity_organizers_table',
            '2026_11_18_120849_create_adventurers_table',
            '2026_11_18_120849_create_adventures_table',
            '2026_11_18_120850_create_bookings_table',
            '2026_11_18_120850_create_payments_table',
        ] as $migration) {
            (require database_path("migrations/{$migration}.php"))->up();
        }

        $this->withoutVite();
    }

    public function test_guests_are_redirected_from_payment_pages(): void
    {
        foreach ([
            route('payments.index'), route('payments.create'), route('payments.show', 1),
            route('payments.byBooking', 1), route('admin.payments.index'), route('admin.payments.show', 1),
        ] as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }

        $this->post(route('payments.store'))->assertRedirect(route('login'));
        $this->patch(route('payments.update', 1))->assertRedirect(route('login'));
        $this->delete(route('payments.destroy', 1))->assertRedirect(route('login'));
    }

    public function test_adventurers_can_only_read_their_own_payments_and_bookings(): void
    {
        $owner = $this->user('Adventurer');
        $ownBooking = $this->booking($owner);
        $ownPayment = $this->payment($ownBooking);
        $otherBooking = $this->booking($this->user('Adventurer'));
        $otherPayment = $this->payment($otherBooking);

        $this->actingAs($owner)->get(route('payments.index'))
            ->assertOk()->assertViewHas('payments', fn ($payments) => $payments->modelKeys() === [$ownPayment->id]);
        $this->get(route('payments.show', $ownPayment))->assertOk();
        $this->get(route('payments.show', $otherPayment))->assertNotFound();
        $this->get(route('payments.byBooking', $ownBooking))
            ->assertOk()->assertViewHas('payments', fn ($payments) => $payments->modelKeys() === [$ownPayment->id]);
        $this->get(route('payments.byBooking', $otherBooking))->assertNotFound();
        $this->get(route('payments.byBooking', $this->booking($this->user('Adventurer'))))->assertNotFound();
        $this->get(route('payments.byBooking', $this->booking($owner)))
            ->assertOk()->assertViewHas('payments', fn ($payments) => $payments->isEmpty());
        $this->get(route('payments.show', 999999))->assertNotFound();
    }

    public function test_admin_pages_are_restricted_and_render_payment_details(): void
    {
        $owner = $this->user('Adventurer');
        $payment = $this->payment($this->booking($owner));

        $this->actingAs($owner)->get(route('admin.payments.index'))->assertForbidden();
        $this->get(route('admin.payments.show', $payment))->assertForbidden();

        $this->actingAs($this->user('Activity_Organizer'))->get(route('payments.index'))->assertForbidden();
        $this->get(route('payments.show', $payment))->assertForbidden();
        $this->get(route('payments.byBooking', $payment->booking_id))->assertForbidden();
        $this->get(route('admin.payments.index'))->assertForbidden();

        $this->actingAs($this->user('Admin'))->get(route('admin.payments.index'))
            ->assertOk()->assertViewIs('admin.payments.index')->assertSee(route('admin.payments.show', $payment));
        $this->get(route('admin.payments.show', $payment))->assertOk()->assertViewIs('admin.payments.show')
            ->assertSee('Integration adventure')->assertSee('35.50')->assertSee(route('admin.payments.index'));
        $this->get(route('admin.payments.show', 999999))->assertNotFound();
    }

    public function test_manual_payment_writes_are_denied_even_for_admin(): void
    {
        $owner = $this->user('Adventurer');
        $booking = $this->booking($owner);
        $payment = $this->payment($booking);

        foreach ([$owner, $this->user('Adventurer'), $this->user('Admin')] as $user) {
            $this->actingAs($user)->post(route('payments.store'), ['booking_id' => $booking->id, 'method' => 'Visa'])->assertForbidden();
            $this->get(route('payments.edit', $payment))->assertForbidden();
            $this->patch(route('payments.update', $payment), ['status' => 'Confirmed'])->assertForbidden();
            $this->delete(route('payments.destroy', $payment))->assertForbidden();
        }

        $this->assertDatabaseCount('payments', 1);
        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'status' => 'Pending', 'total_amount' => 35.50, 'paid_at' => null]);
    }

    public function test_admin_search_filters_and_pagination_are_applied_together(): void
    {
        $booking = $this->booking($this->user('Adventurer'));
        $otherBooking = $this->booking($this->user('Adventurer'));
        for ($i = 0; $i < 16; $i++) {
            $this->payment($booking, 'Confirmed', 'Visa');
        }
        $this->payment($booking, 'Pending', 'Visa');
        $this->payment($booking, 'Confirmed', 'Cash');
        $this->payment($otherBooking, 'Confirmed', 'Visa');

        $filters = ['search' => $booking->id, 'status' => 'Confirmed', 'method' => 'Visa'];
        $this->actingAs($this->user('Admin'))->get(route('admin.payments.index', $filters))
            ->assertOk()->assertViewHas('payments', fn ($payments) => $payments->total() === 16
                && $payments->count() === 15 && str_contains($payments->nextPageUrl(), 'method=Visa'));
        $this->get(route('admin.payments.index', $filters + ['page' => 2]))
            ->assertOk()->assertViewHas('payments', fn ($payments) => $payments->count() === 1);
        $this->get(route('admin.payments.index', ['status' => 'Unknown']))
            ->assertRedirect(route('admin.payments.index'))->assertSessionHasErrors('status');
        $this->get(route('admin.payments.index', ['search' => 999999]))
            ->assertOk()->assertViewHas('payments', fn ($payments) => $payments->isEmpty());
    }

    public function test_booking_still_creates_a_pending_payment(): void
    {
        $adventureId = $this->booking($this->user('Adventurer'))->adventure_id;
        $customer = $this->user('Adventurer');
        $profile = $customer->adventurer()->create();

        $this->actingAs($customer)->post(route('bookings.store'), [
            'adventure_id' => $adventureId,
            'participants_count' => 2,
        ])->assertRedirect(route('bookings.index'))->assertSessionHas('success');

        $booking = $profile->bookings()->sole();
        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id, 'total_amount' => 71, 'method' => 'Cash', 'status' => 'Pending',
        ]);
        $this->get(route('payments.byBooking', $booking))->assertOk();
    }

    private function user(string $role): User
    {
        return User::factory()->create(['role' => $role, 'phone_number' => '0790000000']);
    }

    private function booking(User $owner): Booking
    {
        $adventurer = $owner->adventurer()->firstOrCreate();
        $organizer = $this->user('Activity_Organizer')->activityOrganizer()->create(['license_number' => 'TEST']);
        $adventure = $organizer->adventures()->create([
            'title' => 'Integration adventure', 'description' => 'Fixture', 'capacity' => 10,
            'location' => 'Ajloun', 'duration_hours' => 2, 'price' => 35.50, 'requirements' => 'Fixture',
        ]);

        return $adventurer->bookings()->create([
            'adventure_id' => $adventure->id, 'participants_count' => 1, 'unit_price' => 35.50,
            'commission_rate' => 0.10, 'total_amount' => 35.50, 'capacity' => 10,
        ]);
    }

    private function payment(Booking $booking, string $status = 'Pending', string $method = 'Cash'): Payment
    {
        return Payment::create([
            'booking_id' => $booking->id, 'total_amount' => 35.50, 'status' => $status, 'method' => $method,
        ]);
    }
}
