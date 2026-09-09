<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('owner can access payment index', function () {
    $owner = User::factory()->create(['role' => 'owner']);

    $response = $this->actingAs($owner)->get(route('owner.payments.index'));

    $response->assertStatus(200);
    $response->assertViewIs('owner.payments.index');
});

test('employee can access payment index', function () {
    $employee = User::factory()->create(['role' => 'employee']);

    $response = $this->actingAs($employee)->get(route('employee.payments.index'));

    $response->assertStatus(200);
    $response->assertViewIs('employee.payments.index');
});
