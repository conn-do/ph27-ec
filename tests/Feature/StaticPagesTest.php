<?php

test('利用規約ページを表示できる', function () {
    $this->get('/terms')->assertOk()->assertSee('利用規約');
});

test('プライバシーポリシーページを表示できる', function () {
    $this->get('/privacy')->assertOk()->assertSee('プライバシーポリシー');
});

test('特定商取引法に基づく表記ページを表示できる', function () {
    $this->get('/legal')->assertOk()->assertSee('特定商取引法に基づく表記');
});

test('よくあるご質問ページを表示できる', function () {
    $this->get('/faq')->assertOk()->assertSee('よくあるご質問');
});
