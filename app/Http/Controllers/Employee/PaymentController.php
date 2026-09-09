<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['booking.customer', 'booking.vehicle', 'user']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('transaction_code', 'like', "%{$request->search}%")
                    ->orWhereHas('booking', fn ($q2) => $q2->where('booking_code', 'like', "%{$request->search}%"));
            });
        }

        $payments = $query->latest()->paginate(15)->withQueryString();

        return view('employee.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['booking.customer', 'booking.vehicle', 'user']);

        return view('employee.payments.show', compact('payment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:dp,installment,final_payment',
            'method' => 'required|in:cash,transfer,ewallet,card',
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'confirmed';
        $validated['transaction_code'] = 'TRX'.date('Ymd').strtoupper(Str::random(6));

        Payment::create($validated);

        $booking = Booking::find($validated['booking_id']);
        $booking->recalculatePaid();

        if ($validated['type'] === 'dp' && $booking->status === 'pending_payment') {
            $booking->update(['status' => 'booked']);
        }

        return back()->with('swal_success', 'Pembayaran berhasil dicatat.');
    }
}
