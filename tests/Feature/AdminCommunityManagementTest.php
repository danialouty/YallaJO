<?php

namespace Tests\Feature;

use App\Models\Community;
use App\Models\CommunityMessage;
use App\Models\User;
use Tests\TestCase;

class AdminCommunityManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'admin_community_test_memory',
            'database.connections.admin_community_test_memory' => [
                'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => true,
            ],
            'session.driver' => 'array',
            'cache.default' => 'array',
        ]);

        foreach ([
            '0001_01_01_000000_create_users_table',
            '2026_11_21_150000_create_communities_table',
            '2026_11_21_153000_simplify_communities',
        ] as $migration) {
            (require database_path("migrations/{$migration}.php"))->up();
        }

        $this->withoutVite();
    }

    public function test_admin_can_create_view_and_update_community_without_changing_messages(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $this->actingAs($admin);

        $this->get(route('admin.communities.index'))->assertOk()
            ->assertSee('إدارة المجتمعات')->assertSee('إنشاء مجتمع')
            ->assertSee(route('admin.communities.create'));
        $this->get(route('admin.communities.create'))->assertOk();

        $this->post(route('admin.communities.store'), $this->communityData([
            'slug' => 'unsafe slug',
        ]))->assertSessionHasErrors('slug');
        $this->post(route('admin.communities.store'), $this->communityData())->assertRedirect();

        $community = Community::where('slug', 'north-trails')->sole();
        $this->assertDatabaseHas('communities', [
            'id' => $community->id,
            'name' => 'North Trails',
            'slug' => 'north-trails',
            'region' => 'North Jordan',
            'activity_style' => 'Hiking',
        ]);
        $this->get(route('admin.communities.show', $community))->assertOk()
            ->assertSee('North Trails')->assertSee('عدد الرسائل (دون عرض محتواها)');

        $member = User::factory()->create(['role' => 'Adventurer']);
        $message = CommunityMessage::create([
            'community_id' => $community->id,
            'user_id' => $member->id,
            'body' => 'Original message stays unchanged',
        ]);
        $this->get(route('admin.communities.show', $community))->assertOk()
            ->assertSee('Original message stays unchanged')
            ->assertSee($member->name)->assertSee($member->email)
            ->assertSee('رسائل المجتمع')->assertDontSee('name="body"', false);

        $updated = $this->communityData([
            'name' => 'Updated North Trails',
            'slug' => 'updated-north-trails',
            'region' => 'Ajloun',
        ]);
        $this->patch(route('admin.communities.update', $community), $updated)
            ->assertRedirect(route('admin.communities.show', ['community' => 'updated-north-trails']));

        $this->assertDatabaseHas('communities', [
            'id' => $community->id,
            'name' => 'Updated North Trails',
            'slug' => 'updated-north-trails',
            'region' => 'Ajloun',
        ]);
        $this->assertDatabaseHas('community_messages', [
            'id' => $message->id,
            'community_id' => $community->id,
            'body' => 'Original message stays unchanged',
        ]);
    }

    public function test_community_delete_is_blocked_when_messages_exist_and_allowed_when_none_exist(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $member = User::factory()->create(['role' => 'Adventurer']);
        $community = Community::create($this->communityData());
        $message = CommunityMessage::create([
            'community_id' => $community->id,
            'user_id' => $member->id,
            'body' => 'Keep this message',
        ]);

        $this->actingAs($admin)->delete(route('admin.communities.destroy', $community), ['confirm_delete' => '1'])
            ->assertRedirect(route('admin.communities.show', $community))->assertSessionHas('error');
        $this->assertModelExists($community);
        $this->assertModelExists($message);

        $emptyCommunity = Community::create($this->communityData([
            'name' => 'Empty Community',
            'slug' => 'empty-community',
        ]));
        $this->delete(route('admin.communities.destroy', $emptyCommunity), ['confirm_delete' => '1'])
            ->assertRedirect(route('admin.communities.index'));
        $this->assertModelMissing($emptyCommunity);
        $this->assertModelExists($message);
    }

    public function test_community_management_is_admin_only_and_create_validation_protects_database_values(): void
    {
        $community = Community::create($this->communityData());
        $this->get(route('admin.communities.index'))->assertRedirect(route('login'));
        $this->get(route('admin.communities.show', $community))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create(['role' => 'Adventurer']))
            ->get(route('admin.communities.index'))->assertForbidden();
        $this->post(route('admin.communities.store'), $this->communityData([
            'slug' => 'north-trails',
        ]))->assertForbidden();

        $this->actingAs(User::factory()->create(['role' => 'Admin']))
            ->post(route('admin.communities.store'), $this->communityData([
                'slug' => 'north-trails',
                'cover_image' => 'javascript:alert(1)',
            ]))->assertSessionHasErrors(['slug', 'cover_image']);
        $this->assertDatabaseCount('communities', 1);
    }

    private function communityData(array $overrides = []): array
    {
        return array_replace([
            'name' => 'North Trails',
            'slug' => 'north-trails',
            'description' => 'A community for outdoor adventures.',
            'region' => 'North Jordan',
            'activity_style' => 'Hiking',
            'cover_image' => 'https://example.test/cover.jpg',
        ], $overrides);
    }
}
