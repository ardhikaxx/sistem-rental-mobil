<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Inspection;
use App\Models\InspectionPhoto;
use Illuminate\Http\Request;

class CheckinCheckoutController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['customer', 'vehicle']);

        if ($request->status === 'checkin') {
            $query->whereIn('status', ['booked', 'ready_pickup']);
        } elseif ($request->status === 'checkout') {
            $query->where('status', 'rented');
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('booking_code', 'like', "%{$request->search}%")
                    ->orWhereHas('customer', fn ($q2) => $q2->where('name', 'like', "%{$request->search}%"));
            });
        }

        $bookings = $query->latest()->paginate(15)->withQueryString();

        return view('employee.checkin-checkout.index', compact('bookings'));
    }

    public function showCheckin(Booking $booking)
    {
        if (! in_array($booking->status, ['booked', 'ready_pickup'])) {
            return redirect()->route('employee.checkin-checkout')->with('swal_error', 'Booking tidak dalam status yang sesuai untuk check-in.');
        }

        $booking->load(['customer', 'vehicle']);

        return view('employee.checkin-checkout.checkin', compact('booking'));
    }

    public function processCheckin(Request $request, Booking $booking)
    {
        if (! in_array($booking->status, ['booked', 'ready_pickup'])) {
            return back()->with('swal_error', 'Booking tidak dalam status yang sesuai.');
        }

        $validated = $request->validate([
            'odometer' => 'required|integer|min:0',
            'fuel_level' => 'required|in:empty,quarter,half,three_quarter,full',
            'exterior_condition' => 'nullable|string',
            'interior_condition' => 'nullable|string',
            'equipment_condition' => 'nullable|string',
            'previous_damage_notes' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'photo_categories.*' => 'nullable|string',
        ]);

        $inspection = Inspection::create([
            'booking_id' => $booking->id,
            'vehicle_id' => $booking->vehicle_id,
            'user_id' => auth()->id(),
            'type' => 'check_in',
            'inspection_date' => now(),
            'odometer' => $validated['odometer'],
            'fuel_level' => $validated['fuel_level'],
            'exterior_condition' => $validated['exterior_condition'] ?? null,
            'interior_condition' => $validated['interior_condition'] ?? null,
            'equipment_condition' => $validated['equipment_condition'] ?? null,
            'previous_damage_notes' => $validated['previous_damage_notes'] ?? null,
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $category = $validated['photo_categories'][$index] ?? 'other';
                InspectionPhoto::create([
                    'inspection_id' => $inspection->id,
                    'category' => $category,
                    'file_path' => $photo->store('inspections/'.$booking->booking_code, 'public'),
                ]);
            }
        }

        $booking->update([
            'status' => 'rented',
        ]);

        $booking->vehicle->update([
            'status' => 'rented',
            'current_odometer' => $validated['odometer'],
        ]);

        return redirect()->route('employee.bookings.show', $booking)->with('swal_success', 'Check-in berhasil. Kendaraan telah diserahkan.');
    }

    public function showCheckout(Booking $booking)
    {
        if ($booking->status !== 'rented') {
            return redirect()->route('employee.checkin-checkout')->with('swal_error', 'Booking tidak dalam status rental.');
        }

        $booking->load(['customer', 'vehicle', 'inspections' => function ($q) {
            $q->where('type', 'check_in')->latest();
        }]);

        return view('employee.checkin-checkout.checkout', compact('booking'));
    }

    public function processCheckout(Request $request, Booking $booking)
    {
        if ($booking->status !== 'rented') {
            return back()->with('swal_error', 'Booking tidak dalam status rental.');
        }

        $validated = $request->validate([
            'odometer' => 'required|integer|min:0',
            'fuel_level' => 'required|in:empty,quarter,half,three_quarter,full',
            'exterior_condition' => 'nullable|string',
            'interior_condition' => 'nullable|string',
            'new_damage_notes' => 'nullable|string',
            'officer_notes' => 'nullable|string',
            'late_fee' => 'nullable|numeric|min:0',
            'damage_fee' => 'nullable|numeric|min:0',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'photo_categories.*' => 'nullable|string',
        ]);

        $inspection = Inspection::create([
            'booking_id' => $booking->id,
            'vehicle_id' => $booking->vehicle_id,
            'user_id' => auth()->id(),
            'type' => 'check_out',
            'inspection_date' => now(),
            'odometer' => $validated['odometer'],
            'fuel_level' => $validated['fuel_level'],
            'exterior_condition' => $validated['exterior_condition'] ?? null,
            'interior_condition' => $validated['interior_condition'] ?? null,
            'new_damage_notes' => $validated['new_damage_notes'] ?? null,
            'officer_notes' => $validated['officer_notes'] ?? null,
            'late_fee' => $validated['late_fee'] ?? 0,
            'damage_fee' => $validated['damage_fee'] ?? 0,
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $category = $validated['photo_categories'][$index] ?? 'other';
                InspectionPhoto::create([
                    'inspection_id' => $inspection->id,
                    'category' => $category,
                    'file_path' => $photo->store('inspections/'.$booking->booking_code, 'public'),
                ]);
            }
        }

        $lateFee = $validated['late_fee'] ?? 0;
        $damageFee = $validated['damage_fee'] ?? 0;

        $booking->update([
            'actual_return_date' => now(),
            'late_fee' => $lateFee,
            'damage_fee' => $damageFee,
            'status' => 'completed',
        ]);

        $booking->calculateTotals();

        $vehicleStatus = $damageFee > 0 ? 'maintenance' : 'available';
        $booking->vehicle->update([
            'status' => $vehicleStatus,
            'current_odometer' => $validated['odometer'],
        ]);

        return redirect()->route('employee.bookings.show', $booking)->with('swal_success', 'Check-out berhasil. Kendaraan telah dikembalikan.');
    }

    public function travelDocument(Booking $booking)
    {
        $booking->load(['customer', 'vehicle', 'user', 'inspections' => function ($q) {
            $q->where('type', 'check_in')->with('photos')->latest();
        }]);

        $checkin = $booking->inspections->first();

        return view('employee.checkin-checkout.travel-doc', compact('booking', 'checkin'));
    }
}
