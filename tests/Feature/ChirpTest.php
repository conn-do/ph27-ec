<?php

use App\Models\Chirp;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('chirps page requires authentication', function () {
    /** @var \Tests\TestCase $this */
    $this->get(route('chirps.index'))
        ->assertRedirect(route('login'));
});

test('authenticated users can view chirps', function () {
    /** @var \Tests\TestCase $this */
    /** @var User $user */
    $this->withoutVite();

    $user = User::factory()->create();
    $chirp = Chirp::factory()->for($user)->create([
        'message' => 'Tyson Chan',
    ]);

    $this->actingAs($user)
        ->get(route('chirps.index'))
        ->assertOk()
        ->assertInertia(
            fn(Assert $page) => $page
                ->component('chirps/index')
                ->has('chirps', 1)
                ->where('chirps.0.id', $chirp->id)
                ->where('chirps.0.message', 'Tyson Chan')
                ->where('chirps.0.user.name', $user->name),
        );
});

test('authenticated users can create chirps', function () {
    /** @var \Tests\TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('chirps.store'), [
            'message' => 'Tyson Chan',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('chirps.index'));

    $this->assertDatabaseHas('chirps', [
        'user_id' => $user->id,
        'message' => 'Tyson Chan',
    ]);
});

test('chirp message is required', function () {
    /** @var \Tests\TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('chirps.store'), [
            'message' => '',
        ])
        ->assertSessionHasErrors('message');
});
