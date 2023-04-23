<?php

use App\Models\CodeGeneratorSetting;
use App\Models\Url;
use App\Models\User;
use Spatie\Permission\Models\Role;

it('can list my shortened urls', function () {
    $user = User::factory()->create();
    Url::factory()->count(3)->create([
        'user_id' => $user->id,
    ]);

    $this->actingAs($user)
        ->get('/urls')
        ->assertStatus(200)
        ->assertInertia(fn ($assert) => $assert
            ->component('Urls/Index')
            ->has('urls.data', 3)
        );
});

it('can create shortened url', function () {
    CodeGeneratorSetting::factory()->create();
    $this->actingAs(User::factory()->create())
    ->post('/urls', [
        'url' => 'https://www.google.com',
    ])
    ->assertStatus(201);
});

it('can not create shortened url with invalid url', function () {
    CodeGeneratorSetting::factory()->create();
    $this->actingAs(User::factory()->create())
    ->post('/urls', [
        'url' => 'invalid url',
    ])
    ->assertInvalid('url');
});

it('can not update shortened url with invalid url', function () {
    CodeGeneratorSetting::factory()->create();
    $user = User::factory()->create();
    $url = Url::factory()->create([
        'code' => 'test',
        'user_id' => $user->id,
    ]);
    $this->actingAs($user)
    ->put('/urls/' . $url->id, [
        'url' => 'invalid url',
    ])
    ->assertInvalid('url');
});

it('can not update someone else shortened url', function () {
    CodeGeneratorSetting::factory()->create();
    $user = User::factory()->create();
    $url = Url::factory()->create([
        'code' => 'test',
        'user_id' => User::factory()->create()->id,
    ]);
    $this->actingAs($user)
    ->put('/urls/' . $url->id, [
        'url' => 'https://www.google.com',
    ])
    ->assertStatus(403);
});

it('can update shortened url', function () {
    CodeGeneratorSetting::factory()->create();
    $user = User::factory()->create();
    $url = Url::factory()->create([
        'code' => 'test',
        'user_id' => $user->id,
    ]);
    $this->actingAs($user)
    ->put('/urls/' . $url->id, [
        'url' => 'https://www.google.com',
    ])
    ->assertRedirect('/urls');
});

it('can update someone else shortened url if admin', function () {
    CodeGeneratorSetting::factory()->create();
    $user = User::factory()->create();
    $url = Url::factory()->create([
        'code' => 'test',
        'user_id' => User::factory()->create()->id,
    ]);
    $user->assignRole(Role::create(['name' => 'admin']));
    $this->actingAs($user)
    ->put('/urls/' . $url->id, [
        'url' => 'https://www.google.com',
    ])
    ->assertRedirect('/urls');
});

it('can soft delete shortened url', function () {
    CodeGeneratorSetting::factory()->create();
    $user = User::factory()->create();
    $url = Url::factory()->create([
        'code' => 'test',
        'user_id' => $user->id,
    ]);

    $this->assertDatabaseHas('urls', [
        'id' => $url->id,
        'deleted_at' => null,
    ]);

    $this->actingAs($user)
    ->delete('/urls/' . $url->id)
    ->assertRedirect('/urls');

    $this->assertSoftDeleted($url);
});
