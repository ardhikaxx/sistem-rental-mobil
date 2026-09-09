<?php

use App\Models\MaintenanceRecord;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('owner can access maintenance index', function () {
    $owner = User::factory()->create(['role' => 'owner']);
    $vehicle = Vehicle::create([
        'license_plate' => 'B 1234 ABC',
        'brand' => 'Toyota',
        'model' => 'Avanza',
        'year' => 2022,
        'color' => 'Hitam',
        'transmission' => 'automatic',
        'fuel_type' => 'gasoline',
        'capacity' => 7,
        'daily_rate' => 350000,
        'current_odometer' => 15000,
        'status' => 'available',
    ]);

    MaintenanceRecord::create([
        'vehicle_id' => $vehicle->id,
        'service_type' => 'Servis Berkala',
        'service_date' => '2026-03-01',
        'cost' => 500000,
        'status' => 'scheduled',
    ]);

    $response = $this->actingAs($owner)->get(route('owner.maintenance.index'));

    $response->assertStatus(200);
    $response->assertViewIs('owner.maintenance.index');
    $response->assertSee('Manajemen Maintenance');
    $response->assertSee('B 1234 ABC');
});

test('owner can access maintenance create page', function () {
    $owner = User::factory()->create(['role' => 'owner']);

    $response = $this->actingAs($owner)->get(route('owner.maintenance.create'));

    $response->assertStatus(200);
    $response->assertViewIs('owner.maintenance.create');
});

test('owner can view maintenance detail', function () {
    $owner = User::factory()->create(['role' => 'owner']);
    $vehicle = Vehicle::create([
        'license_plate' => 'B 5678 DEF',
        'brand' => 'Honda',
        'model' => 'Brio',
        'year' => 2021,
        'color' => 'Putih',
        'transmission' => 'manual',
        'fuel_type' => 'gasoline',
        'capacity' => 5,
        'daily_rate' => 250000,
        'current_odometer' => 25000,
        'status' => 'available',
    ]);

    $maintenance = MaintenanceRecord::create([
        'vehicle_id' => $vehicle->id,
        'service_type' => 'Ganti Oli',
        'service_date' => '2026-03-05',
        'cost' => 300000,
        'status' => 'scheduled',
    ]);

    $response = $this->actingAs($owner)->get(route('owner.maintenance.show', $maintenance));

    $response->assertStatus(200);
    $response->assertViewIs('owner.maintenance.show');
    $response->assertSee('Detail Maintenance');
    $response->assertSee('B 5678 DEF');
});
