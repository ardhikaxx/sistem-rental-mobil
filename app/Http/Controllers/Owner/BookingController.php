<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['customer', 'vehicle']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('booking_code', 'like', "%{$request->search}%")
                    ->orWhereHas('customer', function ($q2) use ($request) {
                        $q2->where('name', 'like', "%{$request->search}%");
                    });
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(15)->withQueryString();

        return view('owner.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['customer', 'vehicle', 'user', 'payments' => function ($q) {
            $q->latest();
        }, 'inspections' => function ($q) {
            $q->latest();
        }]);

        return view('owner.bookings.show', compact('booking'));
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
}
