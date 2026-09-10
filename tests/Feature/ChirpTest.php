<?php

use App\Models\Chirp;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Vite;

test('guests cannot view or post chirps', function (string $method) {
    $this->{$method}('/chirps', ['message' => 'Guest post'])
        ->assertRedirect(route('login'));

    $this->assertDatabaseCount('chirps', 0);
})->with(['get', 'post']);

test('the chirp page loads its stylesheet without the inertia application', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('chirps.index'))
        ->assertOk()
        ->assertViewIs('chirps.index')
        ->assertSee(Vite::asset('resources/css/app.css'), false)
        ->assertDontSee(Vite::asset('resources/js/app.tsx'), false)
        ->assertDontSee('resources/js/app.js', false)
        ->assertDontSee('role="alert"', false);
});

test('chirp authors are fetched in one query regardless of the number of posts', function (int $count) {
    $users = User::factory()->count($count)->create();

    foreach ($users as $user) {
        $user->chirps()->create(['message' => 'Post by '.$user->name]);
    }

    $this->actingAs($users->first());
    DB::flushQueryLog();
    DB::enableQueryLog();

    try {
        $response = $this->get(route('chirps.index'));
        $queries = collect(DB::getQueryLog());
    } finally {
        DB::disableQueryLog();
        DB::flushQueryLog();
    }

    $response
        ->assertOk()
        ->assertViewHas('chirps', fn ($chirps) => $chirps->count() === $count
            && $chirps->every(fn (Chirp $chirp) => $chirp->relationLoaded('user')));

    expect($queries->filter(fn (array $query) => preg_match('/\bfrom\s+["`]?users\b/i', $query['query'])))->toHaveCount(1);

    foreach ($users as $user) {
        $response->assertSee($user->name);
    }
})->with([1, 4]);

test('authenticated users can post valid chirps as themselves', function (string $message) {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $this->actingAs($user)
        ->post(route('chirps.store'), [
            'message' => $message,
            'user_id' => $otherUser->id,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('chirps.index'));

    $this->assertDatabaseCount('chirps', 1);
    $this->assertDatabaseHas('chirps', [
        'user_id' => $user->id,
        'message' => $message,
    ]);

    $this->get(route('chirps.index'))->assertOk()->assertSee($message);
})->with([
    'normal message' => '授業の投稿テストです。',
    '255 characters' => str_repeat('あ', 255),
]);

test('invalid chirps display validation errors and restore escaped input', function (string|array $message, string $rule) {
    $expectedError = match ($rule) {
        'required' => __('validation.required', ['attribute' => 'message']),
        'string' => __('validation.string', ['attribute' => 'message']),
        default => __('validation.max.string', ['attribute' => 'message', 'max' => 255]),
    };
    $oldMessage = is_string($message) ? $message : '';

    $this->actingAs(User::factory()->create())
        ->from(route('chirps.index'))
        ->post(route('chirps.store'), ['message' => $message])
        ->assertRedirect(route('chirps.index'))
        ->assertSessionHasErrors(['message' => $expectedError]);

    $response = $this->withCookie(session()->getName(), session()->getId())
        ->get(route('chirps.index'))
        ->assertOk()
        ->assertSee('role="alert"', false)
        ->assertSee($expectedError)
        ->assertSee('>'.e($oldMessage).'</textarea>', false);

    if (str_contains($oldMessage, '<script>')) {
        $response->assertDontSee('<script>alert("x")</script>', false);
    }

    $this->assertDatabaseCount('chirps', 0);
})->with([
    'empty message' => ['', 'required'],
    '256 characters' => [str_repeat('あ', 256), 'max'],
    'multiline HTML input' => ["</textarea><script>alert(\"x\")</script>\n".str_repeat('あ', 256), 'max'],
    'non-string input' => [['unexpected'], 'string'],
]);

test('unimplemented chirp resource actions are not available', function (string $method, string $uri, string $name) {
    $this->actingAs(User::factory()->create())
        ->{$method}($uri, ['message' => 'Not supported'])
        ->assertNotFound();

    expect(Route::has('chirps.'.$name))->toBeFalse();
})->with([
    'create' => ['get', '/chirps/create', 'create'],
    'show' => ['get', '/chirps/1', 'show'],
    'edit' => ['get', '/chirps/1/edit', 'edit'],
    'update put' => ['put', '/chirps/1', 'update'],
    'update patch' => ['patch', '/chirps/1', 'update'],
    'destroy' => ['delete', '/chirps/1', 'destroy'],
]);
