<?php

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Filament\Facades\Filament;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    Storage::fake('public');
    $this->seed([CategorySeeder::class, ProductSeeder::class]);
});

test('guests must sign in to access their shelf or change favorites and profile', function () {
    $product = Product::firstOrFail();
    $this->get('/mypage')->assertRedirect(route('login'));
    $this->post('/favorites/'.$product->id)->assertRedirect(route('login'));
    $this->delete('/favorites/'.$product->id)->assertRedirect(route('login'));
    $this->patch('/mypage', [])->assertRedirect(route('login'));
});

test('favorites persist without duplicates and can be removed without affecting other users', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $product = Product::firstOrFail();
    $other->favoriteProducts()->attach($product);
    $this->actingAs($owner)->get('/mypage')->assertOk()->assertSee('まだ空っぽの、小さな文具棚。');
    $this->post('/favorites/'.$product->id, ['user_id' => $other->id])->assertRedirect();
    $this->post('/favorites/'.$product->id)->assertRedirect();
    expect($owner->favoriteProducts()->count())->toBe(1);
    $this->get('/mypage')->assertOk()->assertSee($product->name);
    $this->get('/products/'.$product->id)->assertOk()->assertSee('文具棚に保存済み');
    $this->delete('/favorites/'.$product->id)->assertRedirect();
    $this->delete('/favorites/'.$product->id)->assertRedirect();
    expect($owner->favoriteProducts()->count())->toBe(0);
    expect($other->favoriteProducts()->count())->toBe(1);
});

test('missing products are rejected and removing products clears favorite references', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->post('/favorites/999999')->assertNotFound();
    $product = Product::firstOrFail();
    $user->favoriteProducts()->attach($product);
    $product->delete();
    $this->assertDatabaseCount('favorites', 0);
    $this->get('/mypage')->assertOk();
});

test('profile changes update only the signed in user and reset email verification when needed', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $this->actingAs($user)->patch('/mypage', ['name' => '文具好き', 'email' => $user->email])->assertRedirect('/mypage');
    expect($user->fresh()->email_verified_at)->not->toBeNull();
    $this->patch('/mypage', ['name' => '新しい名前', 'email' => 'stationery@example.com', 'id' => $other->id])->assertRedirect('/mypage');
    expect($user->fresh()->name)->toBe('新しい名前');
    expect($user->fresh()->email_verified_at)->toBeNull();
    expect($other->fresh()->email)->toBe($other->email);
    $this->patch('/mypage', ['name' => '', 'email' => $other->email])->assertSessionHasErrors(['name', 'email']);
});

test('admin can create a product with a category and original image', function () {
    $this->actingAs(User::factory()->create(['email' => 'test@example.com']));
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    $category = Category::firstOrFail();
    Livewire::test(CreateProduct::class)
        ->fillForm([
            'name' => 'テスト用ペン',
            'category_id' => $category->id,
            'price' => 450,
            'stock' => 12,
            'description' => '書きやすいペンです。',
            'image' => UploadedFile::fake()->image('pen.png'),
        ])
        ->call('create')
        ->assertHasNoFormErrors();
    $this->assertDatabaseHas('products', ['name' => 'テスト用ペン', 'category_id' => $category->id, 'price' => 450]);
});

test('admin product form requires a category', function () {
    $this->actingAs(User::factory()->create(['email' => 'test@example.com']));
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    Livewire::test(CreateProduct::class)
        ->fillForm(['name' => '分類なし', 'price' => 100, 'stock' => 1, 'description' => 'テスト', 'image' => UploadedFile::fake()->image('pen.png')])
        ->call('create')
        ->assertHasFormErrors(['category_id' => 'required']);
});

test('missing legacy uploads fall back to the existing original product images', function () {
    $product = Product::where('name', 'すごいペン')->firstOrFail();
    $product->update(['image' => 'missing-upload.png']);
    expect($product->imageUrl())->toBe(asset('images/products/pen.png'));
    $this->get('/products/'.$product->id)->assertOk()->assertSee(asset('images/products/pen.png'));
});
