<?php

namespace Tests\Feature;

use App\Models\ActivityOrganizer;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminOrganizerManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'organizer_test_memory',
            'database.connections.organizer_test_memory' => [
                'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => true,
            ],
            'session.driver' => 'array',
            'cache.default' => 'array',
        ]);

        $connection = DB::connection('organizer_test_memory');
        $this->assertSame('sqlite', $connection->getDriverName());
        $this->assertSame(':memory:', $connection->getDatabaseName());

        $schema = $connection->getSchemaBuilder();
        $schema->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone_number');
            $table->enum('role', ['Admin', 'Adventurer', 'Activity_Organizer']);
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        $schema->create('activity_organizers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users');
            $table->string('license_number');
            $table->enum('organizer_type', ['Adventure_group', 'Tourism_office'])->default('Tourism_office');
            $table->enum('approval_status', ['Pending', 'Approved', 'Rejected', 'Banned'])->default('Pending');
            $table->string('social_handle_url')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Route::get('/test-approved-organizer', fn () => response()->noContent())
            ->middleware(['web', 'auth', 'approved.organizer']);
        $this->withoutVite();
    }

    public function test_admin_routes_require_authentication_and_admin_role(): void
    {
        $organizer = $this->organizer('Pending');
        $index = route('admin.organizers.index');
        $update = route('admin.organizers.update', $organizer);
        $delete = route('admin.organizers.destroy', $organizer);

        $this->get($index)->assertRedirect(route('login'));
        foreach ([$this->user('Adventurer'), $organizer->user] as $user) {
            $this->actingAs($user)->get($index)->assertForbidden();
            $this->patch($update, ['approval_status' => 'Approved'])->assertForbidden();
            $this->delete($delete)->assertForbidden();
        }
        $this->assertSame('Pending', $organizer->fresh()->approval_status);
    }

    public function test_search_and_status_filters_are_preserved_across_pages(): void
    {
        $admin = $this->user('Admin');
        for ($i = 0; $i < 16; $i++) {
            $this->organizer('Pending', 'Matched account '.$i);
        }
        $this->organizer('Rejected', 'Matched rejected');
        $this->organizer('Pending', 'Different account');
        $filters = ['search' => 'Matched', 'status' => 'Pending'];

        $this->actingAs($admin)->get(route('admin.organizers.index', $filters))
            ->assertOk()->assertViewIs('admin.organizers')
            ->assertViewHas('organizers', fn ($organizers) => $organizers->total() === 16 && $organizers->count() === 15
                && str_contains($organizers->nextPageUrl(), 'status=Pending') && str_contains($organizers->nextPageUrl(), 'search=Matched')
                && $organizers->every(fn ($organizer) => $organizer->relationLoaded('user') && $organizer->relationLoaded('approver')))
            ->assertSee('Matched account')->assertDontSee('Matched rejected')->assertDontSee('Different account');
        $this->get(route('admin.organizers.index', $filters + ['page' => 2]))
            ->assertOk()->assertViewHas('organizers', fn ($organizers) => $organizers->count() === 1);
        $this->get(route('admin.organizers.index', ['search' => '%']))
            ->assertOk()->assertViewHas('organizers', fn ($organizers) => $organizers->isEmpty());
        $this->get(route('admin.organizers.index', ['status' => 'pending']))
            ->assertRedirect(route('admin.organizers.index'))->assertSessionHasErrors('status');
    }

    public function test_approval_and_rejection_update_only_the_selected_record_and_preserve_history(): void
    {
        $admin = $this->user('Admin');
        $organizer = $this->organizer('Pending');
        $other = $this->organizer('Pending');
        $this->freezeTime();
        $update = route('admin.organizers.update', $organizer);

        $this->actingAs($admin)->patch($update, [
            'approval_status' => 'Approved', 'approved_by' => $other->user_id,
            'user_id' => $other->user_id, 'license_number' => 'Tampered value',
        ])->assertRedirect(route('admin.organizers.index'))->assertSessionHas('status');

        $approved = $organizer->fresh();
        $this->assertSame('Approved', $approved->approval_status);
        $this->assertSame($admin->id, $approved->approved_by);
        $this->assertSame(now()->format('Y-m-d H:i:s'), $approved->approved_at->format('Y-m-d H:i:s'));
        $this->assertSame($organizer->user_id, $approved->user_id);
        $this->assertSame($organizer->license_number, $approved->license_number);
        $this->assertSame('Pending', $other->fresh()->approval_status);

        $this->actingAs($organizer->user)->get('/test-approved-organizer')->assertNoContent();

        $this->travel(1)->hours();
        $this->actingAs($this->user('Admin'))->patch($update, ['approval_status' => 'Approved'])->assertRedirect();
        $this->assertSame($approved->approved_by, $organizer->fresh()->approved_by);
        $this->assertEquals($approved->approved_at, $organizer->fresh()->approved_at);

        $this->patch($update, ['approval_status' => 'Rejected'])->assertRedirect()->assertSessionHas('status');
        $rejected = $organizer->fresh();
        $this->assertSame('Rejected', $rejected->approval_status);
        $this->assertSame($approved->approved_by, $rejected->approved_by);
        $this->assertEquals($approved->approved_at, $rejected->approved_at);
        $this->assertSame('Pending', $other->fresh()->approval_status);
        $this->actingAs($organizer->user()->firstOrFail())->get('/test-approved-organizer')
            ->assertRedirect(route('organizers.index'))->assertSessionHas('error');
    }

    public function test_invalid_and_banned_transitions_and_hard_deletion_are_denied(): void
    {
        $pending = $this->organizer('Pending');
        $banned = $this->organizer('Banned');
        $this->actingAs($this->user('Admin'));

        foreach (['approved', 'Pending', 'Banned', 'Unknown'] as $status) {
            $this->from(route('admin.organizers.index'))
                ->patch(route('admin.organizers.update', $pending), ['approval_status' => $status])
                ->assertRedirect(route('admin.organizers.index'))->assertSessionHasErrors('approval_status');
        }
        $this->patch(route('admin.organizers.update', $banned), ['approval_status' => 'Approved'])
            ->assertRedirect(route('admin.organizers.index'))->assertSessionHas('error');
        $this->delete(route('admin.organizers.destroy', $pending))->assertForbidden();
        $this->get(route('admin.organizers.index'))->assertOk()->assertDontSee('name="_method" value="DELETE"', false);
        $this->assertSame('Banned', $banned->fresh()->approval_status);
        $this->assertSame('Pending', $pending->fresh()->approval_status);
        $this->assertDatabaseCount('activity_organizers', 2);
    }

    public function test_profile_submission_uses_canonical_values_and_resubmits_rejected_profiles(): void
    {
        $user = $this->user('Activity_Organizer');
        $this->actingAs($user)->post(route('organizers.store'), ['organizer_type' => 'Adventure_group'])
            ->assertRedirect(route('organizers.index'));
        $organizer = $user->activityOrganizer()->sole();
        $this->assertSame('Pending', $organizer->approval_status);
        $this->assertSame('Adventure_group', $organizer->organizer_type);
        $this->assertSame('', $organizer->license_number);
        $organizer->update(['approval_status' => 'Rejected']);

        $this->put(route('organizers.update', $organizer), [
            'organizer_type' => 'Tourism_office', 'license_number' => 'JO-123', 'approval_status' => 'Approved',
        ])->assertRedirect(route('organizers.index'));
        $this->assertDatabaseHas('activity_organizers', [
            'id' => $organizer->id, 'organizer_type' => 'Tourism_office', 'approval_status' => 'Pending',
            'license_number' => 'JO-123',
        ]);
    }

    private function user(string $role, ?string $name = null): User
    {
        return User::factory()->create(array_filter(['role' => $role, 'phone_number' => '0790000000', 'name' => $name]));
    }

    private function organizer(string $status, ?string $name = null): ActivityOrganizer
    {
        return $this->user('Activity_Organizer', $name)->activityOrganizer()->create([
            'approval_status' => $status, 'license_number' => 'TEST-123',
        ]);
    }
}
