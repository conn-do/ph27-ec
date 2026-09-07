<?php

use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

test('profile page is displayed', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('profile.edit'));

    $response->assertOk();
});

test('profile information can be updated', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.edit'));

    $user->refresh();

    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');
    expect($user->email_verified_at)->toBeNull();
});

test('email verification status is unchanged when the email address is unchanged', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.edit'));

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('user can delete their account', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('profile.destroy'), [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('home'));

    $this->assertGuest();
    expect($user->fresh())->toBeNull();
});

test('correct password must be provided to delete account', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('profile.edit'))
        ->delete(route('profile.destroy'), [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrors('password')
        ->assertRedirect(route('profile.edit'));

    expect($user->fresh())->not->toBeNull();
});

test('deleting an account also deletes its demo orders without affecting other customers', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $product = Product::factory()->create();
    $order = $user->orders()->create(['total_price' => 500]);
    $order->details()->create(['product_id' => $product->id, 'quantity' => 1, 'unit_price' => 500, 'product_name' => $product->name]);
    $otherOrder = $other->orders()->create(['total_price' => 800]);

    $this->actingAs($user)->delete(route('profile.destroy'), ['password' => 'password'])
        ->assertSessionHasNoErrors()->assertRedirect(route('home'));
    $this->assertGuest();
    $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    $this->assertDatabaseMissing('order_details', ['order_id' => $order->id]);
    $this->assertDatabaseHas('orders', ['id' => $otherOrder->id]);
    expect($user->fresh())->toBeNull()->and($product->fresh())->not->toBeNull();
});
