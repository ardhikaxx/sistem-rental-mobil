<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('owner can access owner dashboard', function () {
    $owner = User::where('role', 'owner')->first() ?? User::factory()->create(['role' => 'owner']);

    $response = $this->actingAs($owner)->get(route('owner.dashboard'));

    $response->assertStatus(200);
    $response->assertViewIs('owner.dashboard.index');
    $response->assertSee('Dashboard');
    $response->assertSee('Omzet Hari Ini');
});

test('employee can access employee dashboard', function () {
    $employee = User::where('role', 'employee')->first() ?? User::factory()->create(['role' => 'employee']);

    $response = $this->actingAs($employee)->get(route('employee.dashboard'));

    $response->assertStatus(200);
    $response->assertViewIs('employee.dashboard.index');
    $response->assertSee('Dashboard');
    $response->assertSee('Booking Hari Ini');
});
