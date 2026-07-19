<?php

use App\Models\User;

$confirmPasswordRoute = '/confirm-password';

test('confirm password screen can be rendered', function () use ($confirmPasswordRoute) {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get($confirmPasswordRoute);

    $response->assertStatus(200);
});

test('password can be confirmed', function () use ($confirmPasswordRoute) {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post($confirmPasswordRoute, [
        'password' => 'password',
    ]);

    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
});

test('password is not confirmed with invalid password', function () use ($confirmPasswordRoute) {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post($confirmPasswordRoute, [
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors();
});
