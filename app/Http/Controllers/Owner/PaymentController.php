<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['booking.customer', 'booking.vehicle', 'user']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('transaction_code', 'like', "%{$request->search}%")
                    ->orWhereHas('booking', function ($q2) use ($request) {
                        $q2->where('booking_code', 'like', "%{$request->search}%");
                    });
            });
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(15)->withQueryString();

        return view('owner.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['booking.customer', 'booking.vehicle', 'user']);

        return view('owner.payments.show', compact('payment'));
    }

    public function confirm(Payment $payment)
    {
        $payment->update(['status' => 'confirmed']);
        $payment->booking->recalculatePaid();

        return back()->with('swal_success', 'Pembayaran berhasil dikonfirmasi.');
    }
}
