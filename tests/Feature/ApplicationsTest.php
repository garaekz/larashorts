<?php

use App\Models\Application;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('can list my applications', function () {
    $user = User::factory()->create();
    $application = Application::factory()->create([
        'user_id' => $user->id,
    ]);
    $token = $application->createToken('test', ['api:read']);
    $token = explode('|', $token->plainTextToken, 2)[1];

    $this->actingAs($user)
        ->get(route('apikeys.index'))
        ->assertOk()
        ->assertInertia(
            fn ($assert) => $assert
            ->component('Dashboard/Applications/Index')
            ->has('applications', 1)
            ->where('applications.0.id', $application->id)
            ->where('applications.0.name', $application->name)
        );
});

it('can access application restricted routes', function () {
    $user = User::factory()->create();
    $application = Application::factory()->create([
        'user_id' => $user->id,
    ]);
    $token = $application->createToken('test', ['api:read']);
    $token = explode('|', $token->plainTextToken, 2)[1];

    Sanctum::actingAs($application, ['api:read']);
    $this->get(route('api.test'))
        ->assertOk()
        ->assertJson([
            'id' => $application->id,
            'name' => $application->name,
        ]);
});
