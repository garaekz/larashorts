<?php

use App\Models\Url;
use App\Models\User;
use Spatie\Permission\Models\Role;

it('can list my shortened urls', function () {
    $user = User::factory()->create();
    Url::factory(3)->create([
        'urlable_id' => $user->id,
        'urlable_type' => User::class,
    ]);

    $this->actingAs($user)
        ->get('/dashboard/urls')
        ->assertOk()
        ->assertInertia(
            fn ($assert) => $assert
            ->component('Urls/Index')
            ->has('urls.data', 3)
        );
});

it('only lists my shortened urls', function () {
    $user = User::factory()->create();
    Url::factory(3)->create([
        'urlable_id' => $user->id,
        'urlable_type' => User::class,
    ]);
    Url::factory(3)->create([
        'urlable_id' => User::factory()->create()->id,
        'urlable_type' => User::class,
    ]);

    $this->actingAs($user)
        ->get('/dashboard/urls')
        ->assertOk()
        ->assertInertia(
            fn ($assert) => $assert
            ->component('Urls/Index')
            ->has('urls.data', 3)
        );
});

it('can create shortened url', function () {
    $this->actingAs(User::factory()->create())
        ->post('/dashboard/urls', [
            'url' => 'https://www.google.com',
        ])
        ->assertRedirect('/dashboard/urls')
        ->assertSessionHas('success', 'Url shortened successfully.');
});

it('can not create shortened url with invalid url', function () {
    $this->actingAs(User::factory()->create())
        ->post('/dashboard/urls', [
            'url' => 'invalid url',
        ])
        ->assertInvalid('url');
});

it('can not update shortened url with invalid url', function () {
    $user = User::factory()->create();
    $url = Url::factory()->create([
        'urlable_id' => $user->id,
        'urlable_type' => User::class,
    ]);

    $this->actingAs($user)
        ->put("/dashboard/urls/{$url->id}", [
            'url' => 'invalid url',
        ])
        ->assertInvalid('url');

    $this->assertDatabaseHas('urls', [
        'id' => $url->id,
        'original_url' => $url->original_url,
        'urlable_id' => $user->id,
        'urlable_type' => get_class($user),
    ]);
});

it('can not update someone else shortened url', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $url = Url::factory()->create([
        'urlable_id' => $otherUser->id,
        'urlable_type' => User::class,
    ]);

    $this->actingAs($user)
        ->put('/dashboard/urls/' . $url->id, [
            'url' => 'https://www.google.com',
        ])
        ->assertForbidden();

    $this->assertDatabaseHas('urls', [
        'id' => $url->id,
        'original_url' => $url->original_url,
        'urlable_id' => $otherUser->id,
        'urlable_type' => get_class($otherUser),
    ]);
});

it('can update own shortened url', function () {
    $user = User::factory()->create();
    $url = Url::factory()->create([
        'urlable_id' => $user->id,
        'urlable_type' => User::class,
    ]);
    $this->actingAs($user)
        ->put('/dashboard/urls/' . $url->id, [
            'url' => 'https://www.google.com',
        ])
    ->assertRedirect('/dashboard/urls');

    $this->assertDatabaseHas('urls', [
        'id' => $url->id,
        'original_url' => 'https://www.google.com',
        'urlable_id' => $user->id,
        'urlable_type' => User::class,
    ]);
});

it('can update someone else shortened url if admin', function () {
    $user = User::factory()->create();
    $url = Url::factory()->create([
        'urlable_id' => $user->id,
        'urlable_type' => User::class,
    ]);
    $user->assignRole(Role::create(['name' => 'admin']));
    $this->actingAs($user)
        ->put('/dashboard/urls/' . $url->id, [
            'url' => 'https://www.google.com',
        ])
        ->assertRedirect('/dashboard/urls');

    $this->assertDatabaseHas('urls', [
        'id' => $url->id,
        'original_url' => 'https://www.google.com',
        'urlable_id' => $user->id,
        'urlable_type' => User::class,
    ]);
});

it('can soft delete shortened url', function () {
    $user = User::factory()->create();
    $url = Url::factory()->create([
        'urlable_id' => $user->id,
        'urlable_type' => User::class,
    ]);

    $this->assertDatabaseHas('urls', [
        'id' => $url->id,
        'deleted_at' => null,
    ]);

    $this->actingAs($user)
    ->delete('/dashboard/urls/' . $url->id)
    ->assertRedirect('/dashboard/urls');

    $this->assertSoftDeleted($url);
});
