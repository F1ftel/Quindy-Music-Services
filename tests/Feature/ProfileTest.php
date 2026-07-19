<?php

use App\Models\User;

$testUserName = 'Test User';
$profileRoute = '/profile';

test('profile page is displayed', function () use ($profileRoute) {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get($profileRoute);

    $response->assertOk();
});

test('profile information can be updated', function () use ($testUserName, $profileRoute) {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch($profileRoute, [
            'name' => $testUserName,
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect($profileRoute);

    $user->refresh();

    $this->assertSame($testUserName, $user->name);
    $this->assertSame('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function () use ($testUserName, $profileRoute) {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch($profileRoute, [
            'name' => $testUserName,
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect($profileRoute);

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () use ($profileRoute) {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete($profileRoute, [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () use ($profileRoute) {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from($profileRoute)
        ->delete($profileRoute, [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect($profileRoute);

    $this->assertNotNull($user->fresh());
});
