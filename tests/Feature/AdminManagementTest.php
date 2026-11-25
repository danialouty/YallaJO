<?php

namespace Tests\Feature;

use App\Models\Adventure;
use App\Models\Booking;
use App\Models\Community;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    private const MODULES = ['users', 'adventures', 'bookings', 'reviews', 'communities'];

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'admin_management_test_memory',
            'database.connections.admin_management_test_memory' => [
                'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => true,
            ],
            'session.driver' => 'array',
            'cache.default' => 'array',
        ]);

        $connection = DB::connection();
        $this->assertSame('admin_management_test_memory', $connection->getName());
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
            '2026_11_21_150000_create_communities_table',
            '2026_11_21_153000_simplify_communities',
        ] as $migration) {
            (require database_path("migrations/{$migration}.php"))->up();
        }

        $this->withoutVite();
    }

    public function test_admin_routes_require_an_admin(): void
    {
        $community = Community::create([
            'name' => 'Admin test community',
            'slug' => 'admin-test-community',
            'description' => 'Test community description.',
            'region' => 'Amman',
            'activity_style' => 'Hiking',
        ]);

        foreach (self::MODULES as $module) {
            $this->get(route("admin.$module.index"))->assertRedirect(route('login'));
            $record = $module === 'communities' ? $community : 1;
            $this->get(route("admin.$module.show", $record))->assertRedirect(route('login'));
        }

        foreach (['Adventurer', 'Activity_Organizer'] as $role) {
            $this->actingAs($this->user($role));
            foreach (self::MODULES as $module) {
                $this->get(route("admin.$module.index"))->assertForbidden();
                $record = $module === 'communities' ? $community : 1;
                $this->get(route("admin.$module.show", $record))->assertForbidden();
            }
        }
    }

    public function test_empty_indexes_and_missing_records(): void
    {
        $this->actingAs($this->user('Admin'));

        foreach (self::MODULES as $module) {
            $missing = route("admin.$module.show", 999999);
            $parameters = $module === 'users' ? ['role' => 'Adventurer'] : [];

            $this->get(route("admin.$module.index", $parameters))->assertOk()
                ->assertViewHas('records', fn ($records) => $records->isEmpty());
            $this->get($missing)->assertNotFound();
        }
    }

    public function test_details_and_navigation_use_real_relationships_and_hide_credentials(): void
    {
        $admin = $this->user('Admin');
        $customer = $this->user('Adventurer', 'Trail customer');
        $customer->adventurer()->create(['experience_level' => 'Advanced', 'location' => 'Amman']);
        $organizerAccount = $this->user('Activity_Organizer', 'Trail organizer');
        $organizerAccount->activityOrganizer()->create(['license_number' => 'TEST-44', 'approval_status' => 'Approved']);
        $adventure = $this->adventure($organizerAccount, 'Ajloun trail');
        $booking = $this->booking($customer, $adventure);
        $emptyBooking = $this->booking($customer, $adventure);
        $review = $this->review($customer, $booking, '<script>test</script>');
        $payments = collect([25, 50])->map(fn ($amount) => Payment::create([
            'booking_id' => $booking->id, 'total_amount' => $amount, 'status' => 'Pending',
        ]));

        $this->actingAs($admin);
        foreach (['users' => $customer, 'adventures' => $adventure, 'bookings' => $booking, 'reviews' => $review] as $module => $record) {
            $index = route("admin.$module.index");
            $show = route("admin.$module.show", $record);
            $this->get($index)->assertOk()->assertSee($show);
            $response = $this->get($show)->assertOk()->assertSee($index)->assertSee('dir="rtl"', false);

            $document = new \DOMDocument();
            $document->loadHTML($response->getContent(), LIBXML_NOERROR | LIBXML_NOWARNING);
            $xpath = new \DOMXPath($document);
            $this->assertSame(2, $xpath->query('//a[@href="'.$index.'" and @aria-current="page"]')->length);
        }

        $this->get(route('admin.users.show', $customer))->assertSee('Amman')
            ->assertViewHas('user', fn ($user) => !array_key_exists('password', $user->getAttributes())
                && !array_key_exists('remember_token', $user->getAttributes()));
        $this->get(route('admin.users.show', $organizerAccount))->assertSee('Approved');
        $this->get(route('admin.users.show', $admin))->assertSee('غير موجود');
        $this->get(route('admin.adventures.show', $adventure))->assertSee('Trail organizer')->assertSee('35.50');
        foreach ($payments as $payment) {
            $this->get(route('admin.bookings.show', $booking))->assertSee(route('admin.payments.show', $payment));
        }
        $this->get(route('admin.bookings.show', $emptyBooking))->assertSee('لا توجد دفعات في هذه الصفحة.');
        $this->get(route('admin.reviews.show', $review))->assertSee('Trail customer')
            ->assertSee('<script>test</script>')->assertDontSee('<script>test</script>', false);
    }

    public function test_filters_and_search_are_applied_before_pagination(): void
    {
        $admin = $this->user('Admin', 'Matching admin');
        $organizer = $this->user('Activity_Organizer');
        $organizer->activityOrganizer()->create(['license_number' => 'TEST-44']);
        for ($i = 0; $i < 16; $i++) {
            $customer = $this->user('Adventurer', 'Matching customer '.$i);
            $customer->adventurer()->create();
            $adventure = $this->adventure($organizer, 'Matching trail '.$i);
            $booking = $this->booking($customer, $adventure);
            $this->review($customer, $booking, 'Great view');
        }
        $different = $this->adventure($organizer, 'Matching suspended trail', 'Suspended');
        $unmatchedBooking = $this->booking($customer, $different, 'Cancelled');
        $this->review($customer, $unmatchedBooking, 'Matching different rating', 2);

        $this->actingAs($admin);
        foreach ([
            'users' => ['search' => 'Matching', 'role' => 'Adventurer'],
            'adventures' => ['search' => 'Matching', 'status' => 'Active', 'difficulty_level' => 'Easy'],
            'bookings' => ['status' => 'Accepted'],
            'reviews' => ['search' => 'Matching trail', 'rating' => '4'],
        ] as $module => $filters) {
            $route = "admin.$module.index";
            $this->get(route($route, $filters))->assertOk()->assertViewHas('records', fn ($records) =>
                $records->total() === 16 && $records->count() === 15
                && str_contains($records->nextPageUrl(), http_build_query($filters, '', '&', PHP_QUERY_RFC3986)));
            $this->get(route($route, $filters + ['page' => 2]))->assertOk()
                ->assertViewHas('records', fn ($records) => $records->count() === 1);
        }

        $this->get(route('admin.bookings.index', ['search' => $booking->id, 'status' => 'Accepted']))->assertOk()
            ->assertViewHas('records', fn ($records) => $records->pluck('id')->all() === [$booking->id]);
        $this->get(route('admin.users.index', ['search' => $customer->email]))->assertOk()
            ->assertViewHas('records', fn ($records) => $records->pluck('id')->all() === [$customer->id]);
        $this->get(route('admin.reviews.index', ['search' => 'Great view', 'rating' => 4]))->assertOk()
            ->assertViewHas('records', fn ($records) => $records->total() === 16);
        $this->get(route('admin.reviews.index', ['rating' => 6]))
            ->assertRedirect(route('admin.reviews.index'))->assertSessionHasErrors('rating');
    }

    private function user(string $role, ?string $name = null): User
    {
        return User::factory()->create(array_filter(['role' => $role, 'name' => $name, 'phone_number' => '0790000000']));
    }

    private function adventure(User $organizer, string $title, string $status = 'Active'): Adventure
    {
        return $organizer->activityOrganizer->adventures()->create([
            'title' => $title, 'description' => 'Fixture', 'capacity' => 10, 'location' => 'Ajloun',
            'duration_hours' => 2, 'price' => 35.50, 'requirements' => 'Fixture', 'status' => $status,
        ]);
    }

    private function booking(User $customer, Adventure $adventure, string $status = 'Accepted'): Booking
    {
        return $customer->adventurer->bookings()->create([
            'adventure_id' => $adventure->id, 'participants_count' => 2, 'unit_price' => 35.50,
            'commission_rate' => 0.10, 'total_amount' => 71, 'capacity' => 10, 'status' => $status,
        ]);
    }

    private function review(User $customer, Booking $booking, string $comment, int $rating = 4): Review
    {
        return $customer->adventurer->reviews()->create([
            'adventure_id' => $booking->adventure_id, 'booking_id' => $booking->id, 'rating' => $rating, 'comment' => $comment,
        ]);
    }
}
