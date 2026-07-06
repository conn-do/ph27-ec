<?php

use App\Models\News;

test('top page displays latest three news links', function () {
    $oldestNews = News::create([
        'title' => '古いお知らせ',
        'content' => '<p>古いお知らせです。</p>',
    ]);

    $firstNews = News::create([
        'title' => '新商品入荷のお知らせ',
        'content' => '<p>新商品が入荷しました。</p>',
    ]);

    $secondNews = News::create([
        'title' => 'セール開催のお知らせ',
        'content' => '<p>セールを開催します。</p>',
    ]);

    $thirdNews = News::create([
        'title' => 'サイトメンテナンスのお知らせ',
        'content' => '<p>メンテナンスを実施します。</p>',
    ]);

    $this->get('/')
        ->assertSuccessful()
        ->assertSee('最新情報')
        ->assertSee($thirdNews->title)
        ->assertSee('/news/'.$thirdNews->id)
        ->assertSee($secondNews->title)
        ->assertSee('/news/'.$secondNews->id)
        ->assertSee($firstNews->title)
        ->assertSee('/news/'.$firstNews->id)
        ->assertDontSee($oldestNews->title);
});

test('news detail page displays title and html content', function () {
    $news = News::create([
        'title' => '新商品入荷のお知らせ',
        'content' => '<p>便利な文房具の新商品が入荷しました。</p>',
    ]);

    $this->get('/news/'.$news->id)
        ->assertSuccessful()
        ->assertSee($news->title)
        ->assertSee($news->content, false);
});
