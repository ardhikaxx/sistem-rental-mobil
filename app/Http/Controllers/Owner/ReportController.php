<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Expense;
use App\Models\MaintenanceRecord;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        $revenue = Payment::where('status', 'confirmed')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->sum('amount');

        $expenses = Expense::where('status', 'verified')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');

        $maintenanceCosts = MaintenanceRecord::where('status', 'completed')
            ->whereBetween('service_date', [$startDate, $endDate])
            ->sum('cost');

        $bookingsCount = Booking::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->count();

        $netProfit = $revenue - $expenses - $maintenanceCosts;

        $revenueByType = Payment::where('status', 'confirmed')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->selectRaw('type, SUM(amount) as total')
            ->groupBy('type')
            ->get();

        $expenseByCategory = Expense::where('status', 'verified')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        $dailyRevenue = Payment::where('status', 'confirmed')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('owner.reports.index', compact(
            'startDate', 'endDate', 'revenue', 'expenses', 'maintenanceCosts',
            'bookingsCount', 'netProfit', 'revenueByType', 'expenseByCategory', 'dailyRevenue'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        $revenue = Payment::where('status', 'confirmed')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])->sum('amount');

        $expenses = Expense::where('status', 'verified')
            ->whereBetween('expense_date', [$startDate, $endDate])->sum('amount');

        $maintenanceCosts = MaintenanceRecord::where('status', 'completed')
            ->whereBetween('service_date', [$startDate, $endDate])->sum('cost');

        $data = [
            'Periode' => $startDate->format('d M Y').' - '.$endDate->format('d M Y'),
            'Total Pendapatan' => 'Rp '.number_format($revenue, 0, ',', '.'),
            'Total Pengeluaran' => 'Rp '.number_format($expenses, 0, ',', '.'),
            'Biaya Maintenance' => 'Rp '.number_format($maintenanceCosts, 0, ',', '.'),
            'Laba/Rugi' => 'Rp '.number_format($revenue - $expenses - $maintenanceCosts, 0, ',', '.'),
        ];

        $csv = implode("\n", array_map(fn ($k, $v) => "{$k};{$v}", array_keys($data), array_values($data)));
        $filename = "laporan_{$startDate->format('Y-m-d')}_{$endDate->format('Y-m-d')}.csv";

        return response()->make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
