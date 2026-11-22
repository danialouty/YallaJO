<?php

use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk()
        ->assertSee('حذف الحساب من هذه الصفحة غير متاح')
        ->assertDontSee('confirm-user-deletion', false)
        ->assertDontSee('name="_method" value="delete"', false);
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('self service account deletion is forbidden even with a valid password', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete('/profile', [
            'password' => 'password',
        ]);

    $response->assertForbidden();

    $this->assertAuthenticatedAs($user);
    $this->assertModelExists($user);
});

test('self service account deletion without a password is also forbidden', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->deleteJson(route('profile.destroy'))
        ->assertForbidden()
        ->assertJsonPath('message', 'حذف الحساب من هذه الصفحة غير متاح حفاظًا على سجلات الحجوزات والمدفوعات.');

    $this->assertAuthenticatedAs($user);
    $this->assertModelExists($user);
});
