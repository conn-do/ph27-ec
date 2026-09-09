<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('storefront auth screens keep the original forms and csrf fields', function (string $route, string $heading) {
    $this->get(route($route))->assertOk()->assertSee($heading)
        ->assertSee('name="_token"', false)
        ->assertSee('カートを見る')
        ->assertDontSee('resources/js/app.tsx');
})->with([
    ['login', 'ログイン'],
    ['register', '会員登録'],
]);

test('failed login displays Japanese feedback and keeps only the email', function () {
    app()->setLocale('ja');
    $this->get(route('login'))->assertOk();
    $this->followingRedirects()->from(route('login'))->post(route('login.store'), [
        'email' => 'missing@example.com',
        'password' => 'private-password',
    ])->assertOk()
        ->assertSee('メールアドレスまたはパスワードが正しくありません。')
        ->assertSee('role="alert"', false)
        ->assertSee('missing@example.com')
        ->assertDontSee('private-password');
});

test('registration shows Japanese duplicate email short password and confirmation errors', function () {
    app()->setLocale('ja');
    User::factory()->create(['email' => 'existing@example.com']);

    $this->followingRedirects()->from(route('register'))->post(route('register.store'), [
        'name' => 'テスト会員',
        'email' => 'existing@example.com',
        'password' => 'short',
        'password_confirmation' => 'different',
    ])->assertOk()
        ->assertSee('この メールアドレス は既に使用されています。')
        ->assertSee('パスワードは8文字以上で入力してください。')
        ->assertSee('パスワード が一致しません。')
        ->assertSee('aria-invalid="true"', false)
        ->assertSee('existing@example.com')
        ->assertSee('value="テスト会員"', false)
        ->assertDontSee('value="short"', false);
    $this->assertDatabaseCount('users', 1);
});

test('empty registration uses Japanese required field errors', function () {
    app()->setLocale('ja');
    $this->followingRedirects()->from(route('register'))->post(route('register.store'), [])
        ->assertOk()
        ->assertSee('名前を入力してください。')
        ->assertSee('メールアドレスを入力してください。')
        ->assertSee('パスワードを入力してください。');
});

test('a registered customer can log out and log in again', function () {
    $this->post(route('register.store'), [
        'name' => 'New Customer',
        'email' => 'customer@example.com',
        'password' => 'secret-password',
        'password_confirmation' => 'secret-password',
    ])->assertRedirect();
    $this->assertAuthenticated();
    expect(Hash::check('secret-password', User::query()->sole()->password))->toBeTrue();

    $this->post(route('logout'))->assertRedirect();
    $this->assertGuest();
    $this->post(route('login.store'), [
        'email' => 'customer@example.com',
        'password' => 'secret-password',
    ])->assertRedirect();
    $this->assertAuthenticated();
});
