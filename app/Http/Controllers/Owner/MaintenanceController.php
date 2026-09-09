<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRecord;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = MaintenanceRecord::with('vehicle');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('service_type', 'like', "%{$request->search}%")
                    ->orWhere('workshop', 'like', "%{$request->search}%")
                    ->orWhereHas('vehicle', function ($q2) use ($request) {
                        $q2->where('license_plate', 'like', "%{$request->search}%");
                    });
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $records = $query->latest()->paginate(15)->withQueryString();

        return view('owner.maintenance.index', [
            'records' => $records,
            'maintenances' => $records,
        ]);
    }

    public function create()
    {
        $vehicles = Vehicle::where('is_active', true)->orderBy('license_plate')->get();

        return view('owner.maintenance.create', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'service_type' => 'nullable|string|max:100',
            'type' => 'nullable|string|max:100',
            'service_date' => 'nullable|date',
            'scheduled_date' => 'nullable|date',
            'odometer_at_service' => 'nullable|integer|min:0',
            'odometer_reading' => 'nullable|integer|min:0',
            'workshop' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'estimated_cost' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'nullable|in:scheduled,in_progress,completed,cancelled',
            'next_service_date' => 'nullable|date',
            'next_service_odometer' => 'nullable|integer|min:0',
        ]);

        $data = [
            'vehicle_id' => $validated['vehicle_id'],
            'service_type' => $validated['service_type'] ?? $validated['type'] ?? 'service',
            'service_date' => $validated['service_date'] ?? $validated['scheduled_date'] ?? date('Y-m-d'),
            'odometer_at_service' => $validated['odometer_at_service'] ?? $validated['odometer_reading'] ?? null,
            'workshop' => $validated['workshop'] ?? $validated['notes'] ?? null,
            'cost' => $validated['cost'] ?? $validated['actual_cost'] ?? $validated['estimated_cost'] ?? 0,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'] ?? 'scheduled',
            'next_service_date' => $validated['next_service_date'] ?? null,
            'next_service_odometer' => $validated['next_service_odometer'] ?? null,
        ];

        MaintenanceRecord::create($data);

        return redirect()->route('owner.maintenance.index')->with('swal_success', 'Jadwal maintenance berhasil dibuat.');
    }

    public function show(MaintenanceRecord $record)
    {
        $record->load('vehicle');

        return view('owner.maintenance.show', [
            'record' => $record,
            'maintenance' => $record,
        ]);
    }

    public function edit(MaintenanceRecord $record)
    {
        $vehicles = Vehicle::where('is_active', true)->orderBy('license_plate')->get();

        return view('owner.maintenance.edit', [
            'record' => $record,
            'maintenance' => $record,
            'vehicles' => $vehicles,
        ]);
    }

    public function update(Request $request, MaintenanceRecord $record)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'service_type' => 'nullable|string|max:100',
            'type' => 'nullable|string|max:100',
            'service_date' => 'nullable|date',
            'scheduled_date' => 'nullable|date',
            'odometer_at_service' => 'nullable|integer|min:0',
            'odometer_reading' => 'nullable|integer|min:0',
            'workshop' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'estimated_cost' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'nullable|in:scheduled,in_progress,completed,cancelled',
            'next_service_date' => 'nullable|date',
            'next_service_odometer' => 'nullable|integer|min:0',
        ]);

        $data = [
            'vehicle_id' => $validated['vehicle_id'],
            'service_type' => $validated['service_type'] ?? $validated['type'] ?? $record->service_type,
            'service_date' => $validated['service_date'] ?? $validated['scheduled_date'] ?? $record->service_date,
            'odometer_at_service' => $validated['odometer_at_service'] ?? $validated['odometer_reading'] ?? $record->odometer_at_service,
            'workshop' => $validated['workshop'] ?? $validated['notes'] ?? $record->workshop,
            'cost' => $validated['cost'] ?? $validated['actual_cost'] ?? $validated['estimated_cost'] ?? $record->cost,
            'description' => $validated['description'] ?? $record->description,
            'status' => $validated['status'] ?? $record->status,
            'next_service_date' => $validated['next_service_date'] ?? $record->next_service_date,
            'next_service_odometer' => $validated['next_service_odometer'] ?? $record->next_service_odometer,
        ];

        $record->update($data);

        return redirect()->route('owner.maintenance.show', $record)->with('swal_success', 'Data maintenance berhasil diperbarui.');
    }

    public function destroy(MaintenanceRecord $record)
    {
        $record->delete();

        return redirect()->route('owner.maintenance.index')->with('swal_success', 'Data maintenance berhasil dihapus.');
    }

    public function updateStatus(Request $request, MaintenanceRecord $record)
    {
        $request->validate([
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
        ]);

        $record->update(['status' => $request->status]);

        if ($request->status === 'completed') {
            $record->vehicle->update(['status' => 'available']);
        }

        return back()->with('swal_success', 'Status maintenance berhasil diperbarui.');
    }
}
