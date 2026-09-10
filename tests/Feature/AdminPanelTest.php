<?php

test('admin panel redirects guests to the login page', function () {
    /** @var \Tests\TestCase $this */
    $response = $this->get('/admin');

    $response->assertRedirect('/admin/login');
});

test('admin panel login page can be rendered', function () {
    /** @var \Tests\TestCase $this */
    $response = $this->get('/admin/login');

    $response->assertSuccessful()
        ->assertSee('ログイン');
});
