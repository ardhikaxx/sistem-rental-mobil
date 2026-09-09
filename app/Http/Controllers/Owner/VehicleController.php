<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('license_plate', 'like', "%{$request->search}%")
                    ->orWhere('brand', 'like', "%{$request->search}%")
                    ->orWhere('model', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->transmission) {
            $query->where('transmission', $request->transmission);
        }

        $vehicles = $query->latest()->paginate(15)->withQueryString();

        return view('owner.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('owner.vehicles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'license_plate' => 'required|string|unique:vehicles,license_plate',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1990|max:'.(date('Y') + 1),
            'color' => 'required|string|max:50',
            'transmission' => 'required|in:manual,automatic',
            'fuel_type' => 'required|in:gasoline,diesel,electric,hybrid',
            'passenger_count' => 'required|integer|min:1|max:20',
            'chassis_number' => 'nullable|string|max:100',
            'engine_number' => 'nullable|string|max:100',
            'daily_rate' => 'required|numeric|min:0',
            'weekly_rate' => 'nullable|numeric|min:0',
            'monthly_rate' => 'nullable|numeric|min:0',
            'seasonal_rate' => 'nullable|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'purchase_date' => 'nullable|date',
            'tax_expiry_date' => 'nullable|date',
            'insurance_expiry_date' => 'nullable|date',
            'current_odometer' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('vehicles', 'public');
        }

        $validated['status'] = 'available';
        Vehicle::create($validated);

        return redirect()->route('owner.vehicles.index')->with('swal_success', 'Kendaraan berhasil ditambahkan.');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['bookings' => function ($q) {
            $q->latest()->take(10);
        }, 'maintenanceRecords' => function ($q) {
            $q->latest()->take(5);
        }]);

        return view('owner.vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        return view('owner.vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'license_plate' => 'required|string|unique:vehicles,license_plate,'.$vehicle->id,
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1990|max:'.(date('Y') + 1),
            'color' => 'required|string|max:50',
            'transmission' => 'required|in:manual,automatic',
            'fuel_type' => 'required|in:gasoline,diesel,electric,hybrid',
            'passenger_count' => 'required|integer|min:1|max:20',
            'chassis_number' => 'nullable|string|max:100',
            'engine_number' => 'nullable|string|max:100',
            'daily_rate' => 'required|numeric|min:0',
            'weekly_rate' => 'nullable|numeric|min:0',
            'monthly_rate' => 'nullable|numeric|min:0',
            'seasonal_rate' => 'nullable|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'purchase_date' => 'nullable|date',
            'tax_expiry_date' => 'nullable|date',
            'insurance_expiry_date' => 'nullable|date',
            'current_odometer' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            if ($vehicle->photo) {
                Storage::disk('public')->delete($vehicle->photo);
            }
            $validated['photo'] = $request->file('photo')->store('vehicles', 'public');
        }

        $vehicle->update($validated);

        return redirect()->route('owner.vehicles.show', $vehicle)->with('swal_success', 'Data kendaraan berhasil diperbarui.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('owner.vehicles.index')->with('swal_success', 'Kendaraan berhasil dihapus.');
    }

    public function updateStatus(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'status' => 'required|in:available,booked,rented,maintenance,unavailable',
        ]);

        $vehicle->update(['status' => $request->status]);

        return back()->with('swal_success', 'Status kendaraan berhasil diperbarui.');
    }
}
