<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Vehicle;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $todayBookings = Booking::whereDate('start_date', $today)
            ->where('status', '!=', 'cancelled')
            ->count();

        $todayPickups = Booking::whereDate('start_date', $today)
            ->whereIn('status', ['booked', 'ready_pickup'])
            ->count();

        $todayReturns = Booking::whereDate('end_date', $today)
            ->where('status', 'rented')
            ->count();

        $availableVehicles = Vehicle::where('status', 'available')
            ->where('is_active', true)
            ->count();

        $pendingPayments = Booking::whereIn('status', ['pending_payment', 'booked'])
            ->where('remaining_amount', '>', 0)
            ->sum('remaining_amount');

        $recentBookings = Booking::with(['customer', 'vehicle'])
            ->latest()
            ->take(5)
            ->get();

        return view('employee.dashboard.index', compact(
            'todayBookings', 'todayPickups', 'todayReturns',
            'availableVehicles', 'pendingPayments', 'recentBookings'
        ));
    }
}
