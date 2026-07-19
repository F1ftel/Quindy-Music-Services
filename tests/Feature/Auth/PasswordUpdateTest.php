<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$profileRoute = '/profile';

test('password can be updated', function () use ($profileRoute) {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from($profileRoute)
        ->put('/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect($profileRoute);

    $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
});

test('correct password must be provided to update password', function () use ($profileRoute) {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from($profileRoute)
        ->put('/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('updatePassword', 'current_password')
        ->assertRedirect($profileRoute);
});
