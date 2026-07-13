<?php

use App\Models\Category;
use App\Models\News;

test('home page shows latest three news items with detail links', function () {
    /** @var \Tests\TestCase $this */
    Category::create([
        'name' => '文房具',
        'slug' => 'stationery',
    ]);

    $oldNews = News::create([
        'title' => '古いお知らせ',
        'content' => '<p>古い本文です。</p>',
    ]);

    $latestNews = collect(range(1, 3))->map(fn (int $number): News => News::create([
        'title' => "最新ニュース{$number}",
        'content' => "<p>最新ニュース{$number}の本文です。</p>",
    ]));

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertDontSee($oldNews->title);

    $latestNews->each(function (News $news) use ($response): void {
        $response->assertSee($news->title);
        $response->assertSee(route('news.show', $news, absolute: false));
    });
});

test('news detail page renders html content', function () {
    /** @var \Tests\TestCase $this */
    $news = News::create([
        'title' => 'HTML本文のお知らせ',
        'content' => '<p>本文に<strong>HTML</strong>を含みます。</p>',
    ]);

    $response = $this->get(route('news.show', $news));

    $response->assertOk();
    $response->assertSee($news->title);
    $response->assertSee('<strong>HTML</strong>', false);
});
