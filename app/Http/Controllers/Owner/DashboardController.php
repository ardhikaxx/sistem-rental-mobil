<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Vehicle;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfYear = Carbon::now()->startOfYear();

        $todayRevenue = Payment::where('status', 'confirmed')
            ->whereDate('created_at', $today)
            ->sum('amount');

        $monthRevenue = Payment::where('status', 'confirmed')
            ->where('created_at', '>=', $startOfMonth)
            ->sum('amount');

        $yearRevenue = Payment::where('status', 'confirmed')
            ->where('created_at', '>=', $startOfYear)
            ->sum('amount');

        $activeBookings = Booking::whereIn('status', ['booked', 'ready_pickup', 'rented'])->count();
        $availableVehicles = Vehicle::where('status', 'available')->where('is_active', true)->count();
        $rentedVehicles = Vehicle::where('status', 'rented')->count();
        $maintenanceVehicles = Vehicle::where('status', 'maintenance')->count();

        $totalReceivable = Booking::whereIn('status', ['booked', 'ready_pickup', 'rented'])
            ->where('remaining_amount', '>', 0)
            ->sum('remaining_amount');

        $totalExpenses = Expense::where('status', 'verified')
            ->where('expense_date', '>=', $startOfMonth)
            ->sum('amount');

        $recentBookings = Booking::with(['customer', 'vehicle'])
            ->latest()
            ->take(5)
            ->get();

        $monthlyRevenue = Payment::where('status', 'confirmed')
            ->where('created_at', '>=', Carbon::now()->subDays(30)->startOfDay())
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $vehicleStats = Vehicle::selectRaw('status, COUNT(*) as count')
            ->where('is_active', true)
            ->groupBy('status')
            ->get();

        $topVehicles = Vehicle::withCount(['bookings' => function ($q) {
            $q->where('status', '!=', 'cancelled');
        }])
            ->orderByDesc('bookings_count')
            ->take(5)
            ->get();

        return view('owner.dashboard.index', compact(
            'todayRevenue', 'monthRevenue', 'yearRevenue',
            'activeBookings', 'availableVehicles', 'rentedVehicles', 'maintenanceVehicles',
            'totalReceivable', 'totalExpenses',
            'recentBookings', 'monthlyRevenue', 'vehicleStats', 'topVehicles'
        ));
    }
}
