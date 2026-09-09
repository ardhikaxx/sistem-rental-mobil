<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Approval;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['customer', 'vehicle']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('booking_code', 'like', "%{$request->search}%")
                    ->orWhereHas('customer', fn ($q2) => $q2->where('name', 'like', "%{$request->search}%"));
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(15)->withQueryString();

        return view('employee.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $vehicles = Vehicle::where('status', 'available')->where('is_active', true)->orderBy('license_plate')->get();

        return view('employee.bookings.create', compact('customers', 'vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'additional_fees' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'dp_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $vehicle = Vehicle::find($validated['vehicle_id']);

        if (! $vehicle->isAvailableForDates($validated['start_date'], $validated['end_date'])) {
            return back()->withInput()->with('swal_error', 'Kendaraan tidak tersedia pada tanggal yang dipilih.');
        }

        $start = new \DateTime($validated['start_date']);
        $end = new \DateTime($validated['end_date']);
        $rentalDays = max(1, $start->diff($end)->days);

        $booking = Booking::create([
            'booking_code' => 'BK'.date('Ymd').strtoupper(Str::random(6)),
            'customer_id' => $validated['customer_id'],
            'vehicle_id' => $validated['vehicle_id'],
            'user_id' => auth()->id(),
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'rental_days' => $rentalDays,
            'daily_rate_snapshot' => $vehicle->daily_rate,
            'rental_subtotal' => $vehicle->daily_rate * $rentalDays,
            'additional_fees' => $validated['additional_fees'] ?? 0,
            'discount' => $validated['discount'] ?? 0,
            'total_amount' => ($vehicle->daily_rate * $rentalDays) + ($validated['additional_fees'] ?? 0) - ($validated['discount'] ?? 0),
            'dp_amount' => $validated['dp_amount'],
            'remaining_amount' => max(0, ($vehicle->daily_rate * $rentalDays) + ($validated['additional_fees'] ?? 0) - ($validated['discount'] ?? 0) - ($validated['dp_amount'] ?? 0)),
            'status' => 'pending_payment',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('employee.bookings.show', $booking)->with('swal_success', 'Booking berhasil dibuat.');
    }

    public function show(Booking $booking)
    {
        $booking->load(['customer', 'vehicle', 'user', 'payments' => function ($q) {
            $q->latest();
        }, 'inspections' => function ($q) {
            $q->latest();
        }]);

        return view('employee.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $vehicles = Vehicle::where('is_active', true)->orderBy('license_plate')->get();

        return view('employee.bookings.edit', compact('booking', 'customers', 'vehicles'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'additional_fees' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $vehicle = Vehicle::find($validated['vehicle_id']);

        if (! $vehicle->isAvailableForDates($validated['start_date'], $validated['end_date'], $booking->id)) {
            return back()->with('swal_error', 'Kendaraan tidak tersedia pada tanggal yang dipilih.');
        }

        $start = new \DateTime($validated['start_date']);
        $end = new \DateTime($validated['end_date']);
        $rentalDays = max(1, $start->diff($end)->days);

        $booking->update([
            'customer_id' => $validated['customer_id'],
            'vehicle_id' => $validated['vehicle_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'rental_days' => $rentalDays,
            'daily_rate_snapshot' => $vehicle->daily_rate,
            'rental_subtotal' => $vehicle->daily_rate * $rentalDays,
            'additional_fees' => $validated['additional_fees'] ?? 0,
            'discount' => $validated['discount'] ?? 0,
            'total_amount' => ($vehicle->daily_rate * $rentalDays) + ($validated['additional_fees'] ?? 0) - ($validated['discount'] ?? 0),
            'remaining_amount' => max(0, ($vehicle->daily_rate * $rentalDays) + ($validated['additional_fees'] ?? 0) - ($validated['discount'] ?? 0) - $booking->dp_amount - $booking->paid_amount),
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('employee.bookings.show', $booking)->with('swal_success', 'Booking berhasil diperbarui.');
    }

    public function cancel(Booking $booking)
    {
        if (! in_array($booking->status, ['pending_payment', 'booked'])) {
            return back()->with('swal_error', 'Booking tidak dapat dibatalkan pada status saat ini.');
        }

        $booking->update(['status' => 'cancelled']);
        $booking->vehicle->update(['status' => 'available']);

        return back()->with('swal_success', 'Booking berhasil dibatalkan.');
    }

    public function requestApproval(Booking $booking, Request $request)
    {
        $request->validate([
            'type' => 'required|in:discount,cancellation',
            'reason' => 'required|string',
            'amount' => 'nullable|numeric|min:0',
        ]);

        Approval::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'reference_type' => Booking::class,
            'reference_id' => $booking->id,
            'amount' => $request->amount,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return back()->with('swal_success', 'Permintaan approval berhasil diajukan.');
    }
}
