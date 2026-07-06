<?php

use App\Models\News;

test('home page shows latest three news and links to details', function () {
    $oldestNews = News::create([
        'title' => '古いお知らせ',
        'content' => '<p>古い内容です。</p>',
    ]);

    $latestNews = collect([
        News::create([
            'title' => '新しいお知らせ 1',
            'content' => '<p>内容1です。</p>',
        ]),
        News::create([
            'title' => '新しいお知らせ 2',
            'content' => '<p>内容2です。</p>',
        ]),
        News::create([
            'title' => '新しいお知らせ 3',
            'content' => '<p>内容3です。</p>',
        ]),
    ]);

    $response = $this->get('/');

    $response
        ->assertSuccessful()
        ->assertDontSee($oldestNews->title)
        ->assertSeeInOrder($latestNews->reverse()->pluck('title')->all());

    $latestNews->each(function (News $news) use ($response) {
        $response->assertSee('/news/'.$news->id);
    });
});

test('news detail page shows title and html content', function () {
    $news = News::create([
        'title' => '詳細ページのお知らせ',
        'content' => '<p><strong>HTML本文</strong>です。</p>',
    ]);

    $this->get('/news/'.$news->id)
        ->assertSuccessful()
        ->assertSee($news->title)
        ->assertSee($news->content, false);
});
