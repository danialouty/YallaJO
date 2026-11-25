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
        $show = route('admin.organizers.show', $organizer);

        $this->get($index)->assertRedirect(route('login'));
        $this->get($show)->assertRedirect(route('login'));
        foreach ([$this->user('Adventurer'), $organizer->user] as $user) {
            $this->actingAs($user)->get($index)->assertForbidden();
            $this->get($show)->assertForbidden();
        }
        $this->actingAs($this->user('Admin'));
        $this->get('/admin/organizers/create')->assertNotFound();
        $this->get('/admin/organizers/'.$organizer->id.'/edit')->assertNotFound();
        $this->post('/admin/organizers', ['user_id' => $organizer->user_id])->assertMethodNotAllowed();
        $this->patch('/admin/organizers/'.$organizer->id, ['approval_status' => 'Approved'])->assertMethodNotAllowed();
        $this->patch('/admin/organizers/'.$organizer->id.'/profile')->assertNotFound();
        $this->delete('/admin/organizers/'.$organizer->id)->assertMethodNotAllowed();
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

    public function test_admin_organizer_pages_only_display_records_and_never_mutate_them(): void
    {
        $admin = $this->user('Admin');
        $organizer = $this->organizer('Pending');
        $this->actingAs($admin)->get(route('admin.organizers.index'))
            ->assertOk()->assertSee('TEST-123')
            ->assertDontSee('name="current_password"', false)
            ->assertDontSee('تعديل الملف')->assertDontSee('name="approval_status"', false);
        $this->get(route('admin.organizers.show', $organizer))
            ->assertOk()->assertSee('بانتظار الموافقة')->assertDontSee('تعديل الملف');
        $this->assertSame('Pending', $organizer->fresh()->approval_status);
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
