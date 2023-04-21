<?php

use App\Models\User;

it('has urls page', function () {
    $response = $this->actingAs(User::factory()->create())->get('/urls');

    $response->assertStatus(200);
});
