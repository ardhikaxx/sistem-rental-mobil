@extends('layouts.app')
@section('title', 'Laporan Keuangan')

@section('breadcrumb')
<li class="breadcrumb-item active">Laporan Keuangan</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Laporan Keuangan</h4>
</div>

<div class="filter-bar">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date', now()->format('Y-m-d')) }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-outline-primary w-100"><i class="fas fa-search me-1"></i>Filter</button>
        </div>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="fas fa-money-bill-wave"></i></div>
                <div>
                    <div class="stat-card-value">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-card-label">Total Pendapatan</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon" style="background:#fce4ec;color:#c62828;"><i class="fas fa-receipt"></i></div>
                <div>
                    <div class="stat-card-value">Rp {{ number_format($totalExpenses ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-card-label">Total Pengeluaran</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon" style="background:#e3f2fd;color:#1565c0;"><i class="fas fa-chart-line"></i></div>
                <div>
                    <div class="stat-card-value">Rp {{ number_format($netProfit ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-card-label">Laba Bersih</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon" style="background:#fff3e0;color:#e65100;"><i class="fas fa-file-invoice"></i></div>
                <div>
                    <div class="stat-card-value">{{ $totalBookings ?? 0 }}</div>
                    <div class="stat-card-label">Total Booking</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon" style="background:#e8eaf6;color:#283593;"><i class="fas fa-briefcase"></i></div>
                <div>
                    <div class="stat-card-value">Rp {{ number_format($bookingRevenue ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-card-label">Pendapatan Sewa</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon" style="background:#e0f2f1;color:#00695c;"><i class="fas fa-hand-holding-usd"></i></div>
                <div>
                    <div class="stat-card-value">Rp {{ number_format($dpReceived ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-card-label">DP Diterima</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon" style="background:#fbe9e7;color:#bf360c;"><i class="fas fa-tools"></i></div>
                <div>
                    <div class="stat-card-value">Rp {{ number_format($maintenanceCost ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-card-label">Biaya Maintenance</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon" style="background:#f3e5f5;color:#7b1fa2;"><i class="fas fa-gas-pump"></i></div>
                <div>
                    <div class="stat-card-value">Rp {{ number_format($fuelCost ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-card-label">Biaya BBM</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-chart-bar me-2"></i>Pendapatan vs Pengeluaran per Bulan</div>
            <div class="card-body">
                <canvas id="reportChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-chart-pie me-2"></i>Breakdown Pengeluaran</div>
            <div class="card-body">
                <canvas id="expenseBreakdownChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-list me-2"></i>Top 5 Pendapatan per Kendaraan</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr><th>Kendaraan</th><th>Booking</th><th>Pendapatan</th></tr>
                        </thead>
                        <tbody>
                            @forelse($topRevenueVehicles ?? [] as $v)
                            <tr>
                                <td><strong>{{ $v->license_plate }}</strong> <small class="text-muted">({{ $v->brand }} {{ $v->model }})</small></td>
                                <td>{{ $v->bookings_count }}</td>
                                <td>Rp {{ number_format($v->revenue ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-list me-2"></i>Top 5 Pengeluaran Terbesar</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr><th>Kategori</th><th>Deskripsi</th><th>Jumlah</th></tr>
                        </thead>
                        <tbody>
                            @forelse($topExpenses ?? [] as $e)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $e->category }}</span></td>
                                <td>{{ $e->description }}</td>
                                <td>Rp {{ number_format($e->amount, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
const monthlyData = @json($monthlyData ?? []);

new Chart(document.getElementById('reportChart'), {
    type: 'bar',
    data: {
        labels: monthlyData.map(d => d.label),
        datasets: [
            {
                label: 'Pendapatan',
                data: monthlyData.map(d => d.revenue),
                backgroundColor: '#198754',
            },
            {
                label: 'Pengeluaran',
                data: monthlyData.map(d => d.expenses),
                backgroundColor: '#dc3545',
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: {
            y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') } },
            x: { grid: { display: false } }
        }
    }
});

const expenseData = @json($expenseBreakdown ?? []);
new Chart(document.getElementById('expenseBreakdownChart'), {
    type: 'doughnut',
    data: {
        labels: expenseData.map(d => d.category),
        datasets: [{ data: expenseData.map(d => d.total), backgroundColor: ['#198754','#0dcaf0','#ffc107','#fd7e14','#dc3545','#6f42c1','#20c997','#6c757d','#d63384'] }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { padding: 10 } } }
    }
});
</script>
@endpush