<?php

use App\Models\News;

test('top page shows latest three news items', function () {
    News::create([
        'title' => '古いニュース',
        'content' => '<p>古い内容です。</p>',
    ]);

    $latestNews = collect([
        '最新ニュース1',
        '最新ニュース2',
        '最新ニュース3',
    ])->map(fn (string $title) => News::create([
        'title' => $title,
        'content' => '<p>最新の内容です。</p>',
    ]));

    $response = $this->get('/');

    $response->assertSuccessful();
    $latestNews->each(fn (News $news) => $response->assertSeeText($news->title));
    $response->assertDontSeeText('古いニュース');
});

test('news detail renders html content', function () {
    $news = News::create([
        'title' => 'HTML表示テスト',
        'content' => '<p><strong>重要</strong>なお知らせです。</p>',
    ]);

    $response = $this->get(route('news.show', $news));

    $response->assertSuccessful();
    $response->assertSeeText('HTML表示テスト');
    $response->assertSee('<strong>重要</strong>', false);
});
