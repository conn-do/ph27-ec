<?php

test('the storefront homepage uses the site layout', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('site-header', false)
        ->assertSee('すごい文房具サイト', false)
        ->assertSee('商品一覧');
});

test('the login page renders the storefront form', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('ログイン')
        ->assertSee('auth-panel', false);
});
