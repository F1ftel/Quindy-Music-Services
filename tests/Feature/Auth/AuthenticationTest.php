<?php

use App\Models\User;

$loginRoute = '/login';

test('login screen can be rendered', function () use ($loginRoute) {
    $response = $this->get($loginRoute);

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () use ($loginRoute) {
    $user = User::factory()->create();

    $response = $this->post($loginRoute, [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users can not authenticate with invalid password', function () use ($loginRoute) {
    $user = User::factory()->create();

    $this->post($loginRoute, [
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
