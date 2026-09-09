<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\MaintenanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        return view('owner.calendar.index');
    }

    public function events(Request $request)
    {
        $start = $request->start ? Carbon::parse($request->start) : Carbon::now()->startOfMonth();
        $end = $request->end ? Carbon::parse($request->end) : Carbon::now()->endOfMonth();

        $bookings = Booking::with(['customer', 'vehicle'])
            ->whereIn('status', ['booked', 'ready_pickup', 'rented'])
            ->where(function ($q) use ($start, $end) {
                $q->where('start_date', '<=', $end)->where('end_date', '>=', $start);
            })
            ->get()
            ->map(function ($booking) {
                return [
                    'title' => $booking->vehicle->license_plate.' - '.$booking->customer->name,
                    'start' => $booking->start_date->format('Y-m-d\TH:i:s'),
                    'end' => $booking->end_date->format('Y-m-d\TH:i:s'),
                    'color' => match ($booking->status) {
                        'booked' => '#0dcaf0',
                        'ready_pickup' => '#0d6efd',
                        'rented' => '#ffc107',
                        default => '#6c757d',
                    },
                    'extendedProps' => [
                        'booking_code' => $booking->booking_code,
                        'customer' => $booking->customer->name,
                        'vehicle' => $booking->vehicle->brand.' '.$booking->vehicle->model,
                        'status' => $booking->status_label,
                    ],
                ];
            });

        $maintenance = MaintenanceRecord::with('vehicle')
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->where(function ($q) use ($start, $end) {
                $q->where('service_date', '<=', $end)
                    ->where(function ($q2) use ($start) {
                        $q2->whereNull('next_service_date')->orWhere('next_service_date', '>=', $start);
                    });
            })
            ->get()
            ->map(function ($record) {
                return [
                    'title' => 'Maintenance: '.$record->vehicle->license_plate.' - '.$record->service_type,
                    'start' => $record->service_date->format('Y-m-d'),
                    'end' => $record->next_service_date ? $record->next_service_date->format('Y-m-d') : $record->service_date->format('Y-m-d'),
                    'color' => '#dc3545',
                    'extendedProps' => [
                        'type' => 'maintenance',
                        'status' => $record->status_label,
                    ],
                ];
            });

        return response()->json(array_merge($bookings->toArray(), $maintenance->toArray()));
    }
}
