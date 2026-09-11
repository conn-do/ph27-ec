<?php

use App\Models\User;

test('管理者フラグがないユーザーは管理画面にアクセスできない', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)->get('/admin')->assertForbidden();
});

test('管理者フラグがあるユーザーは管理画面にアクセスできる', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/admin')->assertOk();
});

test('canAccessPanelはis_adminカラムの値をそのまま返す', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $admin = User::factory()->admin()->create();

    expect($user->canAccessPanel(filament()->getDefaultPanel()))->toBeFalse();
    expect($admin->canAccessPanel(filament()->getDefaultPanel()))->toBeTrue();
});
