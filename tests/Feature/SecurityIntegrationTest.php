<?php

namespace Tests\Feature;

use App\Models\ActivityOrganizer;
use App\Models\Adventure;
use App\Models\Booking;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SecurityIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'security_integration_memory',
            'database.connections.security_integration_memory' => [
                'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => true,
            ],
            'session.driver' => 'array',
            'cache.default' => 'array',
        ]);

        $connection = DB::connection();
        $this->assertSame('sqlite', $connection->getDriverName());
        $this->assertSame(':memory:', $connection->getDatabaseName());
        foreach (glob(database_path('migrations/*.php')) as $migration) {
            (require $migration)->up();
        }
        $this->withoutVite();
    }

    public function test_booking_requires_active_adventure_and_checks_shared_capacity(): void
    {
        $adventure = $this->adventure($this->organizer(), ['status' => 'Suspended']);
        $guestPayload = ['adventure_id' => $adventure->id, 'participants_count' => 1];
        $adventurer = $this->user('Adventurer');
        $this->get(route('adventures.profile', $adventure))->assertOk()
            ->assertSee('الحجز غير متاح لهذه المغامرة حاليًا.')
            ->assertDontSee('id="bookingForm"', false);
        $this->actingAs($adventurer)->postJson(route('bookings.store'), $guestPayload)
            ->assertUnprocessable()->assertJsonValidationErrors('adventure_id');
        $this->assertDatabaseCount('bookings', 0);
        $this->assertDatabaseCount('payments', 0);
        $this->get(route('bookings.create'))->assertOk()->assertDontSee($adventure->title);

        $adventure->update(['status' => 'Active']);
        $this->get(route('adventures.profile', $adventure))->assertOk()->assertSee('id="bookingForm"', false);
        $this->get(route('bookings.create'))->assertOk()->assertSee($adventure->title);
        $this->booking($adventure, $this->user('Adventurer'), 'Accepted');
        $this->booking($adventure, $this->user('Adventurer'), 'Cancelled', 7);
        $this->postJson(route('bookings.store'), array_replace($guestPayload, ['participants_count' => 3]))
            ->assertUnprocessable()->assertJsonValidationErrors('participants_count');
        $this->post(route('bookings.store'), array_replace($guestPayload, ['participants_count' => 2, 'total_amount' => 1]))
            ->assertRedirectToRoute('bookings.index');
        $booking = Booking::where('adventurer_id', $adventurer->adventurer()->firstOrFail()->id)->firstOrFail();
        $this->assertSame('25.10', $booking->total_amount);
        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id, 'method' => 'Cash', 'status' => 'Pending', 'total_amount' => 25.10,
        ]);
        $this->postJson(route('bookings.store'), $guestPayload)
            ->assertUnprocessable()->assertJsonValidationErrors('adventure_id');
        $this->actingAs($this->user('Adventurer'))->postJson(route('bookings.store'), $guestPayload)
            ->assertUnprocessable()->assertJsonValidationErrors('participants_count');

        $adventure->update(['status' => 'Suspended']);
        $this->actingAs($adventurer)->get(route('bookings.show', $booking))->assertOk()->assertSee($adventure->title);
        $this->actingAs($this->user('Admin'))->get(route('admin.adventures.show', $adventure))->assertOk();
    }

    public function test_booking_writes_are_owner_scoped_and_preserve_payments_and_history(): void
    {
        $adventure = $this->adventure($this->organizer());
        $owner = $this->user('Adventurer');
        $booking = $this->booking($adventure, $owner);
        $this->actingAs($this->user('Adventurer'))->get(route('bookings.edit', $booking))->assertNotFound();
        $this->patchJson(route('bookings.update', $booking), [])->assertNotFound();
        $this->delete(route('bookings.destroy', $booking))->assertNotFound();
        $this->actingAs($this->user('Admin'))->patchJson(route('bookings.update', $booking), [
            'participants_count' => 2,
        ])->assertForbidden();

        $this->actingAs($owner)->get(route('bookings.edit', $booking))->assertOk()
            ->assertSee(route('bookings.update', $booking))->assertSee('value="PATCH"', false);
        $this->patchJson(route('bookings.update', $booking), ['participants_count' => 4])
            ->assertUnprocessable()->assertJsonValidationErrors('participants_count');
        $this->patch(route('bookings.update', $booking), ['participants_count' => 2])
            ->assertRedirectToRoute('bookings.index');
        $this->assertSame('25.10', $booking->fresh()->total_amount);
        $payment = $booking->payment()->create(['method' => 'Cash', 'status' => 'Pending', 'total_amount' => '25.10']);
        $this->patchJson(route('bookings.update', $booking), ['participants_count' => 1])->assertForbidden();
        $this->delete(route('bookings.destroy', $booking))->assertForbidden();
        $this->assertSame('25.10', $payment->fresh()->total_amount);
        $this->assertSame('Pending', $booking->fresh()->status);

        $otherAdventure = $this->adventure($this->organizer());
        $changeable = $this->booking($otherAdventure, $owner);
        $this->delete(route('bookings.destroy', $changeable))->assertRedirectToRoute('bookings.index');
        $this->assertSame('Cancelled', $changeable->fresh()->status);
        $this->assertNotNull($changeable->fresh()->cancelled_at);
    }

    public function test_guests_cannot_write_to_protected_routes(): void
    {
        foreach ([
            ['POST', 'bookings.store', []],
            ['POST', 'adventures.store', []],
            ['DELETE', 'adventures.destroy', [1]],
            ['POST', 'schedules.store', []],
            ['PUT', 'schedules.update', [1]],
            ['PUT', 'adventure-schedules.days.update', [1, 1]],
            ['POST', 'adventure-schedules.days.generate', [1]],
            ['POST', 'images.store', []],
            ['PUT', 'images.update', [1]],
            ['PUT', 'guides.update', [1]],
            ['POST', 'guide-availabilities.store', []],
            ['DELETE', 'guide-availabilities.destroy', [1]],
            ['POST', 'reviews.store', [1]],
        ] as [$method, $name, $parameters]) {
            $this->json($method, route($name, $parameters))->assertUnauthorized();
        }

        $this->get(route('adventures.index'))->assertRedirectToRoute('adventuresAll.index');
        $this->actingAs($this->user('Adventurer'))->postJson(route('adventures.store'))->assertForbidden();
    }

    public function test_account_deletion_preserves_history_for_customer_organizer_and_approving_admin(): void
    {
        $admin = $this->user('Admin');
        $organizer = $this->organizer();
        $organizer->update(['approved_by' => $admin->id, 'approved_at' => now()]);
        $adventure = $this->adventure($organizer);
        $customer = $this->user('Adventurer');
        $booking = $this->booking($adventure, $customer, 'Accepted');
        $payment = $booking->payment()->create([
            'method' => 'Cash', 'status' => 'Confirmed', 'total_amount' => $booking->total_amount,
        ]);
        $review = $booking->review()->create([
            'adventure_id' => $adventure->id, 'adventurer_id' => $booking->adventurer_id,
            'rating' => 5, 'comment' => 'Confirmed booking review',
        ]);

        $this->delete(route('profile.destroy'))->assertRedirectToRoute('login');
        foreach ([$customer, $organizer->user, $admin] as $user) {
            $this->actingAs($user)->delete(route('profile.destroy'), ['password' => 'password'])->assertForbidden();
            $this->assertAuthenticatedAs($user);
            $this->assertModelExists($user);
        }

        $this->assertDatabaseHas('activity_organizers', ['id' => $organizer->id, 'approved_by' => $admin->id]);
        $this->assertModelExists($adventure);
        $this->assertModelExists($customer->adventurer);
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'Accepted']);
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id, 'booking_id' => $booking->id, 'status' => 'Confirmed',
            'total_amount' => $booking->total_amount,
        ]);
        $this->assertModelExists($review);
    }

    public function test_new_booking_with_automatic_pending_payment_cannot_be_cancelled_by_owner_or_admin(): void
    {
        $owner = $this->user('Adventurer');
        $adventure = $this->adventure($this->organizer());
        $this->actingAs($owner)->post(route('bookings.store'), [
            'adventure_id' => $adventure->id, 'participants_count' => 1,
        ])->assertRedirectToRoute('bookings.index');
        $booking = $owner->adventurer()->firstOrFail()->bookings()->sole();
        $payment = $booking->payment()->sole();

        $this->get(route('bookings.show', $booking))->assertOk()
            ->assertSee('الإلغاء غير متاح لهذا الحجز لوجود دفعة مرتبطة به.')
            ->assertDontSee('name="_method" value="DELETE"', false);
        $this->delete(route('bookings.destroy', $booking))->assertForbidden();
        $this->actingAs($this->user('Admin'))->patch(route('admin.bookings.cancel', $booking))
            ->assertRedirectToRoute('admin.bookings.show', $booking)->assertSessionHas('error');

        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'Pending', 'cancelled_at' => null]);
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id, 'booking_id' => $booking->id, 'method' => 'Cash',
            'status' => 'Pending', 'total_amount' => '12.55', 'paid_at' => null,
        ]);
        $this->assertDatabaseCount('payments', 1);
    }

    public function test_organizer_cannot_write_another_organizers_records_or_transfer_ownership(): void
    {
        $owner = $this->organizer();
        $other = $this->organizer();
        $adventure = $this->adventure($owner);
        $foreignAdventure = $this->adventure($other);
        $guide = $this->guide($owner);
        $foreignGuide = $this->guide($other);
        $scheduleData = [
            'adventure_id' => $adventure->id, 'guide_id' => $guide->id, 'capacity' => 3,
            'starts_at' => '2027-01-01 10:00:00', 'ends_at' => '2027-01-01 15:00:00', 'status' => 'Active',
        ];
        $schedule = $adventure->schedules()->create($scheduleData);
        $dayData = ['day_number' => 1, 'title' => 'Itinerary'];
        $day = $adventure->days()->create($dayData);
        $image = $adventure->images()->create(['image_path' => 'existing.jpg']);
        $availability = $guide->availability()->create(['status' => 'Available']);

        $this->actingAs($other->user)->get(route('adventures.index'))->assertOk()->assertDontSee($adventure->title);
        $this->patchJson(route('adventures.update', $adventure), [])->assertForbidden();
        $this->delete(route('adventures.destroy', $adventure))->assertForbidden();
        $this->delete(route('adventure-schedules.destroy', $schedule))->assertForbidden();
        $this->putJson(route('schedules.update', $schedule), $scheduleData)->assertForbidden();
        $this->putJson(route('adventure-schedules.days.update', [$schedule, $day]), $dayData)->assertForbidden();
        $this->postJson(route('schedules.store'), ['phase' => 'days', 'schedule_id' => $schedule->id, 'days' => [$dayData]])
            ->assertForbidden();
        $this->postJson(route('adventure-schedules.days.generate', $schedule))->assertForbidden();
        $this->putJson(route('images.update', $image), [])->assertForbidden();
        $this->post(route('images.store'), [
            'adventure_id' => $adventure->id,
            'image_path' => UploadedFile::fake()->createWithContent('photo.png', base64_decode(
                'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+XfLYAAAAASUVORK5CYII='
            )),
        ])->assertForbidden();
        $this->patchJson(route('guide-availabilities.update', $availability), ['status' => 'Busy'])->assertNotFound();
        $this->delete(route('guide-availabilities.destroy', $availability))->assertNotFound();
        $this->putJson(route('guides.update', $guide), ['bio' => 'Changed'])->assertForbidden();

        $this->actingAs($owner->user)->postJson(route('schedules.store'), array_replace($scheduleData, [
            'guide_id' => $foreignGuide->id,
        ]))->assertForbidden();
        $this->postJson(route('guide-availabilities.store'), ['guide_id' => $foreignGuide->id, 'status' => 'Busy'])
            ->assertNotFound();
        $this->patch(route('guide-availabilities.update', $availability), ['status' => 'Busy'])
            ->assertRedirectToRoute('guide-availabilities.index');
        $this->putJson(route('schedules.update', $schedule), array_replace($scheduleData, [
            'adventure_id' => $foreignAdventure->id,
        ]))->assertForbidden();
        $foreignDay = $foreignAdventure->days()->create($dayData);
        $this->putJson(route('adventure-schedules.days.update', [$schedule, $foreignDay]), $dayData)->assertNotFound();
        $this->putJson(route('guides.update', $guide), ['activity_organizer_id' => $other->id])
            ->assertUnprocessable();
        $this->patch(route('adventures.update', $adventure), $adventure->only([
            'title', 'description', 'capacity', 'difficulty_level', 'category', 'location', 'duration_hours',
            'price', 'requirements', 'status',
        ]) + ['activity_organizer_id' => $other->id])->assertRedirectToRoute('adventures.index');
        $this->assertEquals($owner->id, $adventure->fresh()->activity_organizer_id);
        $this->put(route('adventure-schedules.days.update', [$schedule, $day]), [
            'day_number' => 1, 'title' => 'Updated itinerary',
        ])->assertRedirectToRoute('adventure-schedules.show', $schedule);
        $this->assertSame('Updated itinerary', $day->fresh()->title);

        $booking = $this->booking($adventure, $this->user('Adventurer'), 'Accepted');
        $this->deleteJson(route('adventures.destroy', $adventure))->assertUnprocessable();
        $this->assertDatabaseHas('bookings', ['id' => $booking->id]);
        $this->assertDatabaseHas('images', ['id' => $image->id]);
        $this->assertDatabaseHas('guides_availabilities', ['id' => $availability->id, 'status' => 'Busy']);
    }

    public function test_reviews_require_matching_accepted_booking_and_unique_booking_at_database_level(): void
    {
        $organizer = $this->organizer();
        $adventure = $this->adventure($organizer);
        $otherAdventure = $this->adventure($organizer);
        $owner = $this->user('Adventurer');
        $other = $this->user('Adventurer');
        $booking = $this->booking($adventure, $owner);
        $other->adventurer()->create();
        $payload = ['booking_id' => $booking->id, 'rating' => 4, 'comment' => 'A real review'];
        $this->actingAs($other)->post(route('reviews.store', $adventure), $payload)->assertNotFound();
        $this->actingAs($owner)->post(route('reviews.store', $otherAdventure), $payload)->assertNotFound();
        $this->post(route('reviews.store', $adventure), $payload)->assertForbidden();
        $this->assertDatabaseCount('reviews', 0);

        $booking->update(['status' => 'Accepted']);
        $this->post(route('reviews.store', $adventure), $payload + [
            'adventure_id' => $otherAdventure->id, 'adventurer_id' => $other->adventurer->id,
        ])->assertRedirectToRoute('reviews.index', $adventure)->assertSessionHas('success');
        $this->post(route('reviews.store', $adventure), $payload)
            ->assertRedirectToRoute('reviews.index', $adventure)->assertSessionHas('error');
        $this->assertDatabaseHas('reviews', [
            'booking_id' => $booking->id, 'adventure_id' => $adventure->id, 'adventurer_id' => $booking->adventurer_id,
        ]);
        $this->assertDatabaseCount('reviews', 1);

        $this->expectException(UniqueConstraintViolationException::class);
        Review::create($payload + ['adventure_id' => $adventure->id, 'adventurer_id' => $booking->adventurer_id]);
    }

    public function test_admin_sidebar_links_and_management_routes_resolve(): void
    {
        $this->actingAs($this->user('Admin'));
        foreach (['dashboard', 'organizers.index', 'users.index', 'adventures.index', 'bookings.index',
            'payments.index', 'reviews.index', 'reports.index'] as $route) {
            $this->get(route('admin.'.$route))->assertOk();
        }

        foreach (Route::getRoutes()->getRoutes() as $route) {
            if (str_starts_with($route->getName() ?? '', 'admin.')) {
                $this->assertContains('auth', $route->gatherMiddleware());
                $this->assertContains('admin', $route->gatherMiddleware());
            }
        }
    }

    public function test_organizer_schedule_forms_and_generated_days_work_together(): void
    {
        $organizer = $this->organizer();
        $adventure = $this->adventure($organizer);
        $guide = $this->guide($organizer);
        $data = [
            'adventure_id' => $adventure->id, 'guide_id' => $guide->id, 'capacity' => 3,
            'starts_at' => '2027-01-01 10:00:00', 'ends_at' => '2027-01-01 15:00:00',
        ];
        $this->actingAs($organizer->user)->get(route('organizer.dashboard'))->assertOk()
            ->assertSee(route('organizers.index'));
        $this->get(route('adventure-schedules.create'))->assertOk()->assertSee($adventure->title);
        $this->post(route('adventure-schedules.store'), $data)->assertRedirect();
        $schedule = $adventure->schedules()->sole();
        $this->assertSame('Active', $schedule->status);
        $this->get(route('adventure-schedules.create', ['phase' => 'days', 'schedule_id' => $schedule->id]))
            ->assertOk()->assertSee(route('adventure-schedules.days.generate', $schedule));

        Http::preventStrayRequests();
        Http::fake(['*/api/chat' => Http::response(['message' => ['content' => json_encode([
            'days' => [['title' => 'Suggested day', 'description' => 'Suggested itinerary']],
        ])]])]);
        $this->postJson(route('adventure-schedules.days.generate', $schedule))
            ->assertOk()->assertJsonPath('days.0.title', 'Suggested day');
        $this->assertDatabaseCount('adventure_days', 0);

        $this->post(route('adventure-schedules.store'), [
            'phase' => 'days', 'schedule_id' => $schedule->id, 'days' => [['title' => 'Saved itinerary']],
        ])->assertRedirectToRoute('adventure-schedules.show', $schedule);
        $day = $adventure->days()->sole();
        $this->get(route('adventure-schedules.edit', $schedule))->assertOk()->assertSee('Saved itinerary');
        $this->put(route('adventure-schedules.update', $schedule), $data + [
            'status' => 'Active', 'days' => [$day->id => ['title' => 'Edited itinerary']],
        ])->assertRedirectToRoute('adventure-schedules.index', ['adventure_id' => $adventure->id]);
        $this->get(route('adventures.profile', $adventure))->assertOk()->assertSee('Edited itinerary');
    }

    public function test_admin_fraud_report_suspension_blocks_new_bookings(): void
    {
        $adventure = $this->adventure($this->organizer());
        $owner = $this->user('Adventurer');
        $booking = $this->booking($adventure, $owner, 'Accepted');
        $review = $booking->review()->create([
            'adventure_id' => $adventure->id, 'adventurer_id' => $booking->adventurer_id,
            'rating' => 4, 'comment' => 'Reported review',
        ]);

        $this->actingAs($owner)->post(route('reviews.report', [$adventure, $review]), [
            'report_reason' => 'A report for the integrated admin queue',
        ])->assertRedirect();
        $this->patch(route('admin.reports.suspend', $review))->assertForbidden();
        $this->actingAs($this->user('Admin'))->get(route('admin.reports.index'))->assertOk()
            ->assertSee('A report for the integrated admin queue');
        $this->patch(route('admin.reports.suspend', $review))->assertRedirect();
        $this->assertSame('Suspended', $adventure->fresh()->status);
        $this->assertSame('resolved', $review->fresh()->report_status);
        $this->actingAs($this->user('Adventurer'))->postJson(route('bookings.store'), [
            'adventure_id' => $adventure->id, 'participants_count' => 1,
        ])->assertUnprocessable()->assertJsonValidationErrors('adventure_id');
    }

    public function test_review_index_rollback_preserves_foreign_keys_and_existing_rows(): void
    {
        $adventure = $this->adventure($this->organizer());
        $booking = $this->booking($adventure, $this->user('Adventurer'), 'Accepted');
        $review = $booking->review()->create([
            'adventure_id' => $adventure->id, 'adventurer_id' => $booking->adventurer_id,
            'rating' => 5, 'comment' => 'Review before rollback',
        ]);
        $foreignKeys = Schema::getForeignKeys('reviews');
        $migration = require database_path('migrations/2026_11_21_073352_add_unique_booking_to_reviews_table.php');

        $this->assertTrue(Schema::hasIndex('reviews', ['booking_id'], 'unique'));
        $migration->down();
        $this->assertFalse(Schema::hasIndex('reviews', ['booking_id'], 'unique'));
        $this->assertSame($foreignKeys, Schema::getForeignKeys('reviews'));
        $this->assertModelExists($review);

        $migration->up();
        $this->assertTrue(Schema::hasIndex('reviews', ['booking_id'], 'unique'));
        $this->assertModelExists($review);
    }

    private function user(string $role): User
    {
        return User::factory()->create(['role' => $role, 'phone_number' => '0790000000']);
    }

    private function organizer(): ActivityOrganizer
    {
        return $this->user('Activity_Organizer')->activityOrganizer()->create([
            'organizer_type' => ActivityOrganizer::ADVENTURE_GROUP, 'approval_status' => ActivityOrganizer::APPROVED,
            'license_number' => '',
        ]);
    }

    private function adventure(ActivityOrganizer $owner, array $overrides = []): Adventure
    {
        return $owner->adventures()->create($overrides + [
            'title' => 'Adventure '.fake()->unique()->word(), 'description' => 'A walk', 'location' => 'Amman',
            'requirements' => 'Shoes', 'duration_hours' => '1.50', 'capacity' => 3, 'price' => '12.55',
            'status' => 'Active', 'category' => 'Hiking', 'difficulty_level' => 'Easy',
        ]);
    }

    private function guide(ActivityOrganizer $owner): \App\Models\Guides
    {
        return $owner->guide()->create(['bio' => 'Guide', 'experience_years' => 2, 'specialization' => ['Hiking']]);
    }

    private function booking(Adventure $adventure, User $owner, string $status = 'Pending', int $seats = 1): Booking
    {
        return $owner->adventurer()->firstOrCreate()->bookings()->create([
            'adventure_id' => $adventure->id, 'participants_count' => $seats, 'unit_price' => $adventure->price,
            'total_amount' => (float) $adventure->price * $seats, 'commission_rate' => '0.10', 'status' => $status,
            'capacity' => $adventure->capacity,
        ]);
    }
}
