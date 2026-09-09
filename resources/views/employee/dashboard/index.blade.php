@extends('layouts.app')
@section('title', 'Dashboard Karyawan')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Dashboard</h4>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon" style="background:#e3f2fd;color:#1565c0;"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-card-info">
                    <div class="stat-card-value">{{ $todayBookings }}</div>
                    <div class="stat-card-label">Booking Hari Ini</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="fas fa-sign-in-alt"></i></div>
                <div class="stat-card-info">
                    <div class="stat-card-value">{{ $todayPickups }}</div>
                    <div class="stat-card-label">Pengambilan Hari Ini</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon" style="background:#fff3e0;color:#e65100;"><i class="fas fa-sign-out-alt"></i></div>
                <div class="stat-card-info">
                    <div class="stat-card-value">{{ $todayReturns }}</div>
                    <div class="stat-card-label">Pengembalian Hari Ini</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon" style="background:#f3e5f5;color:#7b1fa2;"><i class="fas fa-car-side"></i></div>
                <div class="stat-card-info">
                    <div class="stat-card-value">{{ $availableVehicles }}</div>
                    <div class="stat-card-label">Kendaraan Tersedia</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon" style="background:#fce4ec;color:#c62828;"><i class="fas fa-money-bill-wave"></i></div>
                <div class="stat-card-info">
                    <div class="stat-card-value" title="Rp {{ number_format($pendingPayments, 0, ',', '.') }}">Rp {{ number_format($pendingPayments, 0, ',', '.') }}</div>
                    <div class="stat-card-label">Pembayaran Tertunda</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-clock me-2"></i>Booking Terbaru
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th class="text-nowrap">Kode</th>
                                <th>Pelanggan</th>
                                <th>Kendaraan</th>
                                <th class="text-nowrap">Tanggal</th>
                                <th class="text-nowrap">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBookings as $booking)
                            <tr>
                                <td class="text-nowrap"><strong>{{ $booking->booking_code }}</strong></td>
                                <td>{{ $booking->customer->name }}</td>
                                <td>{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} <small class="text-muted">({{ $booking->vehicle->license_plate }})</small></td>
                                <td class="text-nowrap">{{ $booking->start_date->format('d M Y') }} - {{ $booking->end_date->format('d M Y') }}</td>
                                <td class="text-nowrap"><span class="badge {{ $booking->status_badge_class }}">{{ $booking->status_label }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada booking</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
