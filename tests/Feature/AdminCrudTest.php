<?php

namespace Tests\Feature;

use App\Models\Adventure;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'admin_crud_test_memory',
            'database.connections.admin_crud_test_memory' => [
                'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => true,
            ],
            'session.driver' => 'array', 'cache.default' => 'array',
        ]);
        $connection = DB::connection();
        $this->assertSame('sqlite', $connection->getDriverName());
        $this->assertSame(':memory:', $connection->getDatabaseName());

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

    public function test_create_and_write_routes_enforce_admin_middleware(): void
    {
        $create = ['admin.users.create', 'admin.adventures.create', 'admin.organizers.create'];
        foreach ($create as $route) {
            $this->get(route($route))->assertRedirect(route('login'));
        }
        foreach (['Adventurer', 'Activity_Organizer'] as $role) {
            $this->actingAs($this->user($role));
            foreach ($create as $route) {
                $this->get(route($route))->assertForbidden();
            }
            foreach (['users', 'adventures', 'organizers'] as $module) {
                $this->post(route("admin.$module.store"))->assertForbidden();
            }
            $this->patch(route('admin.bookings.cancel', 1))->assertForbidden();
            $this->delete(route('admin.reviews.destroy', 1))->assertForbidden();
        }
    }

    public function test_admin_user_forms_hash_passwords_and_maintain_role_profiles(): void
    {
        $admin = $this->user('Admin');
        $this->actingAs($admin)->get(route('admin.users.create'))->assertOk()->assertSee(route('admin.users.store'));
        $data = [
            'name' => 'Created account', 'email' => 'created@example.test', 'phone_number' => '0790000000',
            'role' => 'Adventurer', 'password' => 'Secret-local-test', 'password_confirmation' => 'Secret-local-test',
        ];
        $this->post(route('admin.users.store'), $data)->assertRedirect();
        $user = User::where('email', $data['email'])->sole();
        $this->assertTrue(Hash::check($data['password'], $user->password));
        $this->assertNotNull($user->adventurer);
        $this->assertNull($user->activityOrganizer);
        $this->post(route('admin.users.store'), $data)->assertSessionHasErrors('email');
        $this->post(route('admin.users.store'), array_replace($data, ['email' => 'bad@example.test', 'role' => 'Unknown']))
            ->assertSessionHasErrors('role');
        $this->post(route('admin.users.store'), array_replace($data, [
            'email' => 'organizer@example.test', 'role' => 'Activity_Organizer', 'organizer_type' => 'Adventure_group',
        ]))->assertRedirect();
        $organizer = User::where('email', 'organizer@example.test')->sole();
        $this->assertSame('Pending', $organizer->activityOrganizer->approval_status);
        $this->assertNull($organizer->adventurer);
        $this->get(route('admin.users.edit', $user))->assertOk()->assertSee('value="PATCH"', false)->assertDontSee($user->password);
        unset($data['role'], $data['password'], $data['password_confirmation']);
        $data['name'] = 'Updated account';
        $this->patch(route('admin.users.update', $user), $data)->assertRedirect(route('admin.users.show', $user));
        $this->assertSame('Updated account', $user->fresh()->name);
        $this->patch(route('admin.users.update', $admin), $data + ['role' => 'Adventurer'])->assertSessionHasErrors('role');
        $this->delete(route('admin.users.show', $admin))->assertMethodNotAllowed();
        $this->assertSame('Admin', $admin->fresh()->role);
    }

    public function test_adventure_creation_update_and_suspension_preserve_history(): void
    {
        $this->actingAs($this->user('Admin'));
        $organizer = $this->user('Activity_Organizer')->activityOrganizer()->create(['license_number' => 'TEST']);
        $this->get(route('admin.adventures.create'))->assertOk()->assertSee(route('admin.adventures.store'));
        $data = [
            'title' => 'Test adventure', 'description' => 'Trail', 'location' => 'Ajloun', 'requirements' => 'Shoes',
            'category' => 'Hiking', 'difficulty_level' => 'Easy', 'status' => 'Active', 'price' => '23.25',
            'capacity' => 10, 'duration_hours' => '2.50', 'activity_organizer_id' => $organizer->id,
        ];
        $this->post(route('admin.adventures.store'), array_replace($data, ['price' => '-1', 'difficulty_level' => 'Invalid']))
            ->assertSessionHasErrors(['price', 'difficulty_level']);
        $this->post(route('admin.adventures.store'), array_replace($data, ['activity_organizer_id' => 999999]))
            ->assertSessionHasErrors('activity_organizer_id');
        $this->post(route('admin.adventures.store'), $data)->assertRedirect();
        $adventure = Adventure::sole();
        $booking = $this->booking($adventure);
        $payment = Payment::create(['booking_id' => $booking->id, 'total_amount' => '23.25']);
        $this->get(route('admin.adventures.edit', $adventure))->assertOk()->assertSee('value="PATCH"', false);
        unset($data['activity_organizer_id']);
        $this->patch(route('admin.adventures.update', $adventure), $data + ['activity_organizer_id' => null])
            ->assertSessionHasErrors('activity_organizer_id');
        $this->patch(route('admin.adventures.update', $adventure), array_replace($data, ['capacity' => 1]))
            ->assertSessionHasErrors('capacity');
        $this->patch(route('admin.adventures.update', $adventure), array_replace($data, ['title' => 'Updated trail']))
            ->assertRedirect(route('admin.adventures.show', $adventure));
        $this->get(route('admin.adventures.show', $adventure))->assertSee(route('admin.adventures.suspend', $adventure));
        $this->patch(route('admin.adventures.suspend', $adventure))->assertRedirect();
        $this->assertSame('Suspended', $adventure->fresh()->status);
        $this->assertSame('Updated trail', $adventure->fresh()->title);
        $this->assertModelExists($booking);
        $this->assertModelExists($payment);
        $this->delete(route('admin.adventures.show', $adventure))->assertMethodNotAllowed();
    }

    public function test_only_payment_free_pending_bookings_can_be_updated_or_cancelled(): void
    {
        $this->actingAs($this->user('Admin'));
        $adventure = $this->adventure();
        $booking = $this->booking($adventure);
        $paid = $this->booking($adventure);
        $payment = Payment::create(['booking_id' => $paid->id, 'total_amount' => '46.50', 'status' => 'Confirmed']);
        $this->get(route('admin.bookings.index'))->assertOk()
            ->assertSee(route('admin.bookings.edit', $booking))->assertDontSee(route('admin.bookings.edit', $paid));
        $this->post(route('admin.bookings.index'))->assertMethodNotAllowed();
        $this->get(route('admin.bookings.edit', $booking))->assertOk();
        $this->patch(route('admin.bookings.update', $booking), ['participants_count' => 9])->assertSessionHasErrors('participants_count');
        $this->patch(route('admin.bookings.update', $booking), ['participants_count' => 3, 'total_amount' => '1.00'])->assertRedirect();
        $this->assertSame('69.75', $booking->fresh()->total_amount);
        $this->patch(route('admin.bookings.update', $paid), ['participants_count' => 3])->assertSessionHasErrors('participants_count');
        $this->patch(route('admin.bookings.cancel', $paid))->assertSessionHas('error');
        $this->patch(route('admin.bookings.cancel', $booking))->assertSessionHas('success');
        $this->assertSame('Cancelled', $booking->fresh()->status);
        $this->assertNotNull($booking->fresh()->cancelled_at);
        $this->assertSame('Pending', $paid->fresh()->status);
        $this->assertSame('Confirmed', $payment->fresh()->status);
        $this->assertDatabaseCount('payments', 1);
    }

    public function test_review_edits_and_confirmed_deletion_keep_the_booking_intact(): void
    {
        $this->actingAs($this->user('Admin'));
        $booking = $this->booking($this->adventure(), 'Accepted');
        $review = Review::create(['booking_id' => $booking->id, 'adventure_id' => $booking->adventure_id,
            'adventurer_id' => $booking->adventurer_id, 'rating' => 4, 'comment' => 'Original']);
        $payment = Payment::create(['booking_id' => $booking->id, 'total_amount' => '46.50']);
        $this->post(route('admin.reviews.index'), ['booking_id' => $booking->id])->assertMethodNotAllowed();
        $this->get(route('admin.reviews.edit', $review))->assertOk()->assertSee('value="PATCH"', false);
        $this->patch(route('admin.reviews.update', $review), ['rating' => 6, 'comment' => 'Invalid'])->assertSessionHasErrors('rating');
        $this->patch(route('admin.reviews.update', $review), ['rating' => 5, 'comment' => 'Changed', 'booking_id' => null])
            ->assertSessionHasErrors('booking_id');
        $this->patch(route('admin.reviews.update', $review), ['rating' => 5, 'comment' => 'Changed'])->assertRedirect();
        $this->assertSame(5, $review->fresh()->rating);
        $this->assertSame($booking->id, $review->fresh()->booking_id);
        $this->get(route('admin.reviews.show', $review))->assertSee('value="DELETE"', false);
        $this->delete(route('admin.reviews.destroy', $review))->assertSessionHasErrors('confirm_delete');
        $this->assertModelExists($review);
        $this->delete(route('admin.reviews.destroy', $review), ['confirm_delete' => '1'])->assertRedirect(route('admin.reviews.index'));
        $this->assertModelMissing($review);
        $this->assertModelExists($booking);
        $this->assertModelExists($payment);
    }

    public function test_organizer_profile_crud_preserves_approval_and_denies_deletion(): void
    {
        $admin = $this->user('Admin');
        $owner = $this->user('Activity_Organizer');
        $this->actingAs($admin)->get(route('admin.organizers.create'))->assertOk()->assertSee(route('admin.organizers.store'));
        $data = ['user_id' => $owner->id, 'organizer_type' => 'Tourism_office', 'license_number' => 'TEST'];
        $this->post(route('admin.organizers.store'), array_replace($data, ['user_id' => $admin->id]))->assertSessionHasErrors('user_id');
        $this->post(route('admin.organizers.store'), $data)->assertRedirect();
        $organizer = $owner->activityOrganizer()->sole();
        $this->post(route('admin.organizers.store'), $data)->assertSessionHasErrors('user_id');
        $this->patch(route('admin.organizers.update', $organizer), ['approval_status' => 'Approved'])->assertRedirect();
        $this->get(route('admin.organizers.show', $organizer))->assertOk()->assertSee($owner->name);
        $this->get(route('admin.organizers.edit', $organizer))->assertOk()->assertSee(route('admin.organizers.profile.update', $organizer));
        $update = ['organizer_type' => 'Adventure_group', 'license_number' => 'NEW-TEST'];
        $this->patch(route('admin.organizers.profile.update', $organizer), $update + ['approved_at' => null])
            ->assertSessionHasErrors('approved_at');
        $this->patch(route('admin.organizers.profile.update', $organizer), $update)->assertRedirect();
        $this->assertDatabaseHas('activity_organizers', [
            'id' => $organizer->id, 'license_number' => 'NEW-TEST', 'approval_status' => 'Approved', 'approved_by' => $admin->id,
        ]);
        $this->delete(route('admin.organizers.destroy', $organizer))->assertForbidden();
        $this->assertModelExists($organizer);
    }

    private function user(string $role): User
    {
        return User::factory()->create(['role' => $role, 'phone_number' => '0790000000']);
    }

    private function adventure(): Adventure
    {
        return $this->user('Activity_Organizer')->activityOrganizer()->create(['license_number' => 'TEST'])
            ->adventures()->create(['title' => 'Trail', 'description' => 'Trail', 'location' => 'Ajloun',
                'requirements' => 'Shoes', 'capacity' => 10, 'price' => '23.25', 'duration_hours' => 2]);
    }

    private function booking(Adventure $adventure, string $status = 'Pending'): Booking
    {
        return $this->user('Adventurer')->adventurer()->create()->bookings()->create([
            'adventure_id' => $adventure->id, 'status' => $status, 'participants_count' => 2,
            'unit_price' => '23.25', 'total_amount' => '46.50', 'commission_rate' => '0.10', 'capacity' => 10,
        ]);
    }
}
