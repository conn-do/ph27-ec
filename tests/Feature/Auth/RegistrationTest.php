<?php

use Laravel\Fortify\Features;

beforeEach(function () {
    /** @var \Tests\TestCase $this */
    $this->skipUnlessFortifyHas(Features::registration());
});

test('registration screen can be rendered', function () {
    /** @var \Tests\TestCase $this */
    $response = $this->get(route('register'));

    $response->assertOk()
        ->assertSee('会員登録')
        ->assertSee('パスワード（確認）')
        ->assertSee('アカウントを作成');
});

test('new users can register', function () {
    /** @var \Tests\TestCase $this */
    $response = $this->post(route('register.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
