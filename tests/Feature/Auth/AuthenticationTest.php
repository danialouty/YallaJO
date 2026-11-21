<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users land on an accessible page after login', function (string $role, string $destination) {
    $user = User::factory()->create(['role' => $role]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route($destination, absolute: false));
    $this->get(route($destination))->assertOk();
})->with([
    'adventurer' => ['Adventurer', 'bookings.index'],
    'organizer' => ['Activity_Organizer', 'organizers.index'],
    'admin' => ['Admin', 'admin.dashboard'],
]);

test('login returns the user to the originally requested page', function () {
    $user = User::factory()->create(['role' => 'Admin']);
    $this->get(route('admin.users.index'))->assertRedirectToRoute('login');

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertRedirectToRoute('admin.users.index');
    $this->assertAuthenticatedAs($user);
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
