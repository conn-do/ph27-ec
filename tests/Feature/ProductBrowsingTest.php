<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;

test('guests can browse active products including out of stock products', function () {
    $this->seed(CategorySeeder::class);
    $category = Category::where('slug', 'notebook')->firstOrFail();
    $product = Product::factory()->for($category)->create(['price' => 880, 'stock' => 0, 'image' => null]);
    Product::factory()->for($category)->create(['is_active' => false]);

    $this->get(route('shop'))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('shop/index')
        ->where('auth.user', null)
        ->has('categories', 5)
        ->where('categories.0.name', 'Writing')
        ->where('categories.4.name', 'Tools')
        ->where('filters', ['q' => '', 'category' => '', 'sort' => 'newest'])
        ->where('products.total', 1)
        ->has('products.data', 1)
        ->where('products.data.0.id', $product->id)
        ->where('products.data.0.category.slug', 'notebook')
        ->where('products.data.0.price', 880)
        ->where('products.data.0.stock', 0)
        ->where('products.data.0.image', null)
    );
});

test('search matches product names and descriptions without exposing inactive matches', function (string $keyword, int $count) {
    $category = Category::factory()->create();
    Product::factory()->for($category)->create(['name' => 'Grid Note A5', 'description' => '静かな時間の方眼ノート。0.5mmペンに。']);
    Product::factory()->for($category)->create(['name' => 'Desk Tray', 'description' => '木のトレイ']);
    Product::factory()->for($category)->create(['name' => 'Hidden Item', 'description' => 'Grid Note A5 方眼ノート 0.5mm', 'is_active' => false]);

    $this->get(route('shop', ['q' => $keyword]))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('shop/index')
        ->where('filters.q', trim($keyword))
        ->where('products.total', $count)
        ->has('products.data', $count)
    );
})->with([
    'name' => ['Grid', 1],
    'case insensitive' => ['grid', 1],
    'description' => ['方眼', 1],
    'trimmed' => ['  Grid  ', 1],
    'zero' => ['0', 1],
    'empty' => ['', 2],
    'no match' => ['Nothing Matches', 0],
    'SQL-like input is only a search value' => ["' OR 1=1 --", 0],
]);

test('each seeded category filters products by slug', function (string $slug) {
    $this->seed(CategorySeeder::class);
    $category = Category::where('slug', $slug)->firstOrFail();
    $other = Category::where('slug', '!=', $slug)->firstOrFail();
    $product = Product::factory()->for($category)->create();
    Product::factory()->for($category)->create(['is_active' => false]);
    Product::factory()->for($other)->create();

    $this->get(route('shop', ['category' => $slug]))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('filters.category', $slug)
        ->where('products.total', 1)
        ->where('products.data.0.id', $product->id)
        ->where('products.data.0.category.slug', $slug)
    );
})->with(['writing', 'notebook', 'desk', 'storage', 'tools']);

test('products can be sorted by date or price with deterministic ties', function (?string $sort, array $expected) {
    $category = Category::factory()->create();
    Product::factory()->for($category)->create(['slug' => 'old', 'price' => 900, 'created_at' => '2026-01-01 00:00:00']);
    Product::factory()->for($category)->create(['slug' => 'cheap', 'price' => 300, 'created_at' => '2026-01-03 00:00:00']);
    Product::factory()->for($category)->create(['slug' => 'expensive', 'price' => 1200, 'created_at' => '2026-01-02 00:00:00']);
    Product::factory()->for($category)->create(['slug' => 'tie', 'price' => 300, 'created_at' => '2026-01-03 00:00:00']);

    $this->get(route('shop', $sort ? ['sort' => $sort] : []))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('filters.sort', $sort ?? 'newest')
        ->where('products.data', fn ($products): bool => $products->pluck('slug')->all() === $expected)
    );
})->with([
    'default newest' => [null, ['tie', 'cheap', 'expensive', 'old']],
    'newest' => ['newest', ['tie', 'cheap', 'expensive', 'old']],
    'ascending price' => ['price_asc', ['tie', 'cheap', 'old', 'expensive']],
    'descending price' => ['price_desc', ['expensive', 'old', 'tie', 'cheap']],
]);

test('search category and price sort work together with grouped search conditions', function () {
    $notebook = Category::factory()->create(['slug' => 'notebook']);
    $desk = Category::factory()->create(['slug' => 'desk']);
    $cheap = Product::factory()->for($notebook)->create(['name' => 'Grid Book', 'description' => 'Daily notebook', 'price' => 880]);
    $expensive = Product::factory()->for($notebook)->create(['name' => 'Journal', 'description' => 'Grid pages', 'price' => 1540]);
    Product::factory()->for($notebook)->create(['name' => 'Blank Book', 'description' => 'Plain pages']);
    Product::factory()->for($notebook)->create(['name' => 'Hidden', 'description' => 'Grid pages', 'is_active' => false, 'price' => 1]);
    Product::factory()->for($desk)->create(['name' => 'Desk Tray', 'description' => 'Grid organizer', 'price' => 1]);

    $filters = ['q' => 'Grid', 'category' => 'notebook', 'sort' => 'price_asc'];

    $this->get(route('shop', $filters))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('filters', $filters)
        ->where('products.total', 2)
        ->where('products.data.0.id', $cheap->id)
        ->where('products.data.1.id', $expensive->id)
    );
});

test('pagination keeps all filters and applies them before paging', function () {
    $category = Category::factory()->create(['slug' => 'notebook']);
    Product::factory()->count(13)->for($category)->create(['name' => 'Grid Note', 'price' => 880]);
    Product::factory()->for($category)->create(['name' => 'Hidden Grid', 'is_active' => false]);
    $filters = ['q' => 'Grid', 'category' => 'notebook', 'sort' => 'price_desc'];

    $response = $this->get(route('shop', $filters))->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->where('products.total', 13)
        ->has('products.data', 12)
        ->where('products.current_page', 1)
        ->where('products.last_page', 2)
    );

    $nextUrl = $response->inertiaProps('products.next_page_url');
    parse_str(parse_url($nextUrl, PHP_URL_QUERY), $nextQuery);
    expect($nextQuery)->toMatchArray([...$filters, 'page' => '2']);

    $this->get($nextUrl)->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('filters', $filters)
        ->where('products.total', 13)
        ->has('products.data', 1)
        ->where('products.current_page', 2)
        ->where('products.next_page_url', null)
    );
});

test('out of range pages redirect to the first page while preserving filters', function (int $count) {
    $category = Category::factory()->create(['slug' => 'notebook']);
    Product::factory()->count($count)->for($category)->create(['name' => 'Grid Note']);
    $filters = ['q' => 'Grid', 'category' => 'notebook', 'sort' => 'price_asc'];

    $this->get(route('shop', [...$filters, 'page' => 99]))
        ->assertRedirect(route('shop', $filters));

    $this->get(route('shop', $filters))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('filters', $filters)
        ->where('products.current_page', 1)
        ->where('products.total', $count)
        ->has('products.data', $count)
    );
})->with([0, 3]);

test('blank filters behave as All and newest', function () {
    Product::factory()->count(2)->create();

    $this->get(route('shop', ['q' => ' ', 'category' => '', 'sort' => '']))->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('filters', ['q' => '', 'category' => '', 'sort' => 'newest'])
            ->where('products.total', 2)
        );
});

test('the empty catalog renders successfully', function () {
    $this->get(route('shop'))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('shop/index')
        ->has('products.data', 0)
        ->where('products.total', 0)
    );
});

test('invalid filter input redirects safely to the shop with Japanese errors', function (array $query, string $field, string $message) {
    $this->from(route('shop', $query))->get(route('shop', $query))
        ->assertRedirect(route('shop'))
        ->assertSessionHasErrors([$field => $message]);

    $this->withCookie(session()->getName(), session()->getId())->get(route('shop'))
        ->assertOk()->assertInertia(fn (Assert $page) => $page->where('errors.'.$field, $message));
})->with([
    'array search' => [['q' => ['Grid']], 'q', '検索キーワードは文字列で入力してください。'],
    'long search' => [['q' => str_repeat('あ', 101)], 'q', '検索キーワードは100文字以内で入力してください。'],
    'unknown category' => [['category' => 'missing'], 'category', '選択されたカテゴリは存在しません。'],
    'array category' => [['category' => ['notebook']], 'category', 'カテゴリを正しく選択してください。'],
    'invalid sort' => [['sort' => 'price;drop table products'], 'sort', '並び順を正しく選択してください。'],
    'array sort' => [['sort' => ['newest']], 'sort', '並び順を正しく選択してください。'],
    'zero page' => [['page' => '0'], 'page', 'ページ番号は1以上で指定してください。'],
    'array page' => [['page' => ['2']], 'page', 'ページ番号は整数で指定してください。'],
]);

test('product details are public and expose the requested information', function () {
    $category = Category::factory()->create(['name' => 'Notebook', 'slug' => 'notebook']);
    $product = Product::factory()->for($category)->create([
        'name' => 'Grid Note A5', 'slug' => 'grid-note-a5',
        'description' => '毎日の記録に使える方眼ノート。',
        'price' => 880, 'stock' => 0, 'image' => '/images/products/grid-note-a5.jpg',
    ]);

    $this->get(route('products.show', ['product' => $product->slug]))->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('products/show')
            ->where('auth.user', null)
            ->where('product.id', $product->id)
            ->where('product.slug', 'grid-note-a5')
            ->where('product.name', 'Grid Note A5')
            ->where('product.description', $product->description)
            ->where('product.price', 880)
            ->where('product.stock', 0)
            ->where('product.image', '/images/products/grid-note-a5.jpg')
            ->where('product.category.name', 'Notebook')
        );
});

test('inactive product details return 404 for guests and authenticated users', function (bool $authenticated) {
    if ($authenticated) {
        $this->actingAs(User::factory()->create());
    }

    $product = Product::factory()->create(['is_active' => false]);
    $this->get(route('products.show', ['product' => $product->slug]))->assertNotFound();
})->with([false, true]);

test('a nonexistent product slug returns 404', function () {
    $this->get(route('products.show', ['product' => 'does-not-exist']))->assertNotFound();
});

test('product detail routes bind by slug and not by numeric id', function () {
    $product = Product::factory()->create(['slug' => 'grid-note-a5']);
    $this->get(route('products.show', ['product' => $product->id]))->assertNotFound();
});

test('listing eager loads categories instead of querying once per product', function () {
    Product::factory()->count(10)->create();
    DB::enableQueryLog();

    $this->get(route('shop'))->assertOk();

    $categoryQueries = collect(DB::getQueryLog())
        ->filter(fn (array $query): bool => str_contains($query['query'], 'from "categories"'));
    DB::disableQueryLog();

    expect($categoryQueries)->toHaveCount(2);
});
