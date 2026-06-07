<?php

use App\Models\User;
use Tests\TestCase;

test('guests are redirected to the login page from home', function () {
    /** @var TestCase $this */
    $response = $this->get(route('home'));

    $response->assertRedirect(route('login'));
});

test('authenticated users can visit home', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('home'));

    $response->assertOk();
});
