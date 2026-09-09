@extends('layouts.app')
@section('title', 'Dashboard Owner')

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
                <div class="stat-card-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="fas fa-money-bill-wave"></i></div>
                <div class="stat-card-info">
                    <div class="stat-card-value" title="Rp {{ number_format($todayRevenue, 0, ',', '.') }}">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
                    <div class="stat-card-label">Omzet Hari Ini</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon" style="background:#e3f2fd;color:#1565c0;"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-card-info">
                    <div class="stat-card-value" title="Rp {{ number_format($monthRevenue, 0, ',', '.') }}">Rp {{ number_format($monthRevenue, 0, ',', '.') }}</div>
                    <div class="stat-card-label">Omzet Bulan Ini</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon" style="background:#fff3e0;color:#e65100;"><i class="fas fa-file-invoice"></i></div>
                <div class="stat-card-info">
                    <div class="stat-card-value">{{ $activeBookings }}</div>
                    <div class="stat-card-label">Booking Aktif</div>
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
                <div class="stat-card-icon" style="background:#e8eaf6;color:#283593;"><i class="fas fa-car"></i></div>
                <div class="stat-card-info">
                    <div class="stat-card-value">{{ $rentedVehicles }}</div>
                    <div class="stat-card-label">Sedang Disewa</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon" style="background:#fce4ec;color:#c62828;"><i class="fas fa-tools"></i></div>
                <div class="stat-card-info">
                    <div class="stat-card-value">{{ $maintenanceVehicles }}</div>
                    <div class="stat-card-label">Maintenance</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon" style="background:#e0f2f1;color:#00695c;"><i class="fas fa-hand-holding-usd"></i></div>
                <div class="stat-card-info">
                    <div class="stat-card-value" title="Rp {{ number_format($totalReceivable, 0, ',', '.') }}">Rp {{ number_format($totalReceivable, 0, ',', '.') }}</div>
                    <div class="stat-card-label">Piutang</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon" style="background:#fbe9e7;color:#bf360c;"><i class="fas fa-receipt"></i></div>
                <div class="stat-card-info">
                    <div class="stat-card-value" title="Rp {{ number_format($totalExpenses, 0, ',', '.') }}">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</div>
                    <div class="stat-card-label">Pengeluaran Bulan Ini</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header">
                <i class="fas fa-chart-line me-2"></i>Pendapatan 30 Hari Terakhir
            </div>
            <div class="card-body">
                <div style="position: relative; height: 260px; width: 100%;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="fas fa-chart-pie me-2"></i>Status Armada
            </div>
            <div class="card-body">
                <div style="position: relative; height: 260px; width: 100%;">
                    <canvas id="vehicleChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header">
                <i class="fas fa-clock me-2"></i>Booking Terbaru
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Pelanggan</th>
                                <th>Kendaraan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBookings as $booking)
                            <tr>
                                <td><strong>{{ $booking->booking_code }}</strong></td>
                                <td>{{ $booking->customer->name }}</td>
                                <td>{{ $booking->vehicle->license_plate }}</td>
                                <td><span class="badge {{ $booking->status_badge_class }}">{{ $booking->status_label }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">Belum ada booking</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="fas fa-star me-2"></i>Kendaraan Terpopuler
            </div>
            <div class="card-body">
                @forelse($topVehicles as $v)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="me-2 text-truncate">
                        <strong>{{ $v->license_plate }}</strong>
                        <div class="text-muted small text-truncate">{{ $v->brand }} {{ $v->model }}</div>
                    </div>
                    <span class="badge bg-secondary flex-shrink-0">{{ $v->bookings_count }} booking</span>
                </div>
                @empty
                <div class="text-center text-muted py-4">Belum ada data</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
const revenueData = @json($monthlyRevenue);
const vehicleData = @json($vehicleStats);

new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: revenueData.map(d => new Date(d.date).toLocaleDateString('id-ID', {day:'numeric', month:'short'})),
        datasets: [{
            label: 'Pendapatan',
            data: revenueData.map(d => d.total),
            borderColor: '#1a2332',
            backgroundColor: 'rgba(26,35,50,0.05)',
            fill: true,
            tension: 0.3,
            pointRadius: 3,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') } },
            x: { grid: { display: false } }
        }
    }
});

const vehicleLabels = vehicleData.map(d => {
    const labels = {available:'Tersedia',booked:'Dibooking',rented:'Disewa',maintenance:'Maintenance',unavailable:'Tidak Tersedia'};
    return labels[d.status] || d.status;
});
const vehicleColors = vehicleData.map(d => {
    const colors = {available:'#198754',booked:'#0dcaf0',rented:'#ffc107',maintenance:'#fd7e14',unavailable:'#dc3545'};
    return colors[d.status] || '#6c757d';
});

new Chart(document.getElementById('vehicleChart'), {
    type: 'doughnut',
    data: {
        labels: vehicleLabels,
        datasets: [{ data: vehicleData.map(d => d.count), backgroundColor: vehicleColors }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { padding: 12 } } }
    }
});
</script>
@endpush
