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
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('registration validation errors are shown in Japanese', function () {
    /** @var TestCase $this */
    $response = $this->from(route('register'))->post(route('register.store'), [
        'name' => '',
        'email' => 'not-an-email',
        'password' => 'secret',
        'password_confirmation' => 'different',
    ]);

    $response
        ->assertRedirect(route('register'))
        ->assertSessionHasErrors([
            'name' => 'お名前を入力してください。',
            'email' => 'メールアドレスには有効なメールアドレスを入力してください。',
            'password' => 'パスワードが確認用と一致しません。',
        ]);
});
