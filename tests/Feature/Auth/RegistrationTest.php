<?php

use Laravel\Fortify\Features;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    $this->skipUnlessFortifyHas(Features::registration());
});

test('registration screen can be rendered', function () {
    /** @var TestCase $this */
    $response = $this->get(route('register'));

    $response->assertOk();
});

test('new users can register', function () {
    /** @var TestCase $this */
    $response = $this->post(route('register.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('home', absolute: false));
});
