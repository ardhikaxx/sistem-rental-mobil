@extends('layouts.app')
@section('title', 'Detail Booking')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('employee.bookings.index') }}">Booking</a></li>
<li class="breadcrumb-item active">{{ $booking->booking_code }}</li>
@endsection

@section('content')
<div class="page-header">
    <h4>{{ $booking->booking_code }} <span class="badge {{ $booking->status_badge_class }} ms-2">{{ $booking->status_label }}</span></h4>
    <div class="d-flex gap-2">
        @if(in_array($booking->status, ['pending_payment', 'booked']))
        <form method="POST" action="{{ route('employee.bookings.cancel', $booking) }}" class="d-inline">
            @csrf @method('POST')
            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Batalkan booking ini?')"><i class="fas fa-times me-1"></i>Batalkan</button>
        </form>
        @endif
        @if($booking->status === 'pending_payment')
        <form method="POST" action="{{ route('employee.bookings.request-approval', $booking) }}" class="d-inline">
            @csrf @method('POST')
            <button type="submit" class="btn btn-outline-warning btn-sm"><i class="fas fa-paper-plane me-1"></i>Minta Approval</button>
        </form>
        @endif
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-info-circle me-2"></i>Informasi Booking</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="150">Kode Booking</th><td><strong>{{ $booking->booking_code }}</strong></td></tr>
                    <tr><th>Tanggal Mulai</th><td>{{ $booking->start_date->format('d M Y') }}</td></tr>
                    <tr><th>Tanggal Selesai</th><td>{{ $booking->end_date->format('d M Y') }}</td></tr>
                    <tr><th>Durasi</th><td>{{ $booking->duration }} hari</td></tr>
                    <tr><th>Biaya Sewa</th><td>Rp {{ number_format($booking->daily_rate * $booking->duration, 0, ',', '.') }}</td></tr>
                    <tr><th>Biaya Tambahan</th><td>Rp {{ number_format($booking->additional_fees ?? 0, 0, ',', '.') }}</td></tr>
                    <tr><th>Diskon</th><td class="text-danger">- Rp {{ number_format($booking->discount ?? 0, 0, ',', '.') }}</td></tr>
                    <tr><th>Total Biaya</th><td><strong>Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</strong></td></tr>
                    <tr><th>Catatan</th><td>{{ $booking->notes ?? '-' }}</td></tr>
                    <tr><th>Dibuat</th><td>{{ $booking->created_at->format('d M Y H:i') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-user me-2"></i>Informasi Pelanggan</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="150">Nama</th><td><a href="{{ route('employee.customers.show', $booking->customer) }}">{{ $booking->customer->name }}</a></td></tr>
                    <tr><th>Telepon</th><td>{{ $booking->customer->phone }}</td></tr>
                    <tr><th>Alamat</th><td>{{ $booking->customer->address ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header"><i class="fas fa-car me-2"></i>Informasi Kendaraan</div>
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-2">
                @if($booking->vehicle->photo)
                <img src="{{ asset('storage/' . $booking->vehicle->photo) }}" class="img-fluid rounded" alt="{{ $booking->vehicle->license_plate }}">
                @else
                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height:80px;"><i class="fas fa-car fa-2x text-muted"></i></div>
                @endif
            </div>
            <div class="col-md-5">
                <strong>{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</strong>
                <div class="text-muted">{{ $booking->vehicle->license_plate }} | {{ $booking->vehicle->year }} | {{ ucfirst($booking->vehicle->transmission) }}</div>
            </div>
            <div class="col-md-5 text-md-end">
                <span class="badge {{ $booking->vehicle->status_badge_class }}">{{ $booking->vehicle->status_label }}</span>
                <div class="text-muted mt-1">Rp {{ number_format($booking->vehicle->daily_rate, 0, ',', '.') }}/hari</div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header"><i class="fas fa-money-bill me-2"></i>Informasi Pembayaran</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 bg-light rounded text-center">
                    <div class="text-muted small">Total Biaya</div>
                    <div class="fw-bold fs-5">Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-light rounded text-center">
                    <div class="text-muted small">Sudah Dibayar</div>
                    <div class="fw-bold fs-5 text-success">Rp {{ number_format($booking->paid_amount ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-light rounded text-center">
                    <div class="text-muted small">Sisa Pembayaran</div>
                    <div class="fw-bold fs-5 text-danger">Rp {{ number_format($booking->remaining_amount ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header"><i class="fas fa-history me-2"></i>Status Timeline</div>
    <div class="card-body">
        <div class="position-relative ps-4">
            <div class="position-absolute top-0 start-0 bottom-0" style="width:2px;background:#dee2e6;"></div>
            @foreach($booking->statusHistory ?? [] as $history)
            <div class="position-relative mb-3">
                <div class="position-absolute rounded-circle" style="width:12px;height:12px;background:#0d6efd;left:-26px;top:4px;"></div>
                <div>
                    <strong>{{ $history->status_label }}</strong>
                    <div class="text-muted small">{{ $history->created_at->format('d M Y H:i') }}</div>
                    <div>{{ $history->notes ?? '-' }}</div>
                </div>
            </div>
            @endforeach
            @if(empty($booking->statusHistory))
            <p class="text-muted">Belum ada riwayat status</p>
            @endif
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header"><i class="fas fa-money-bill me-2"></i>Riwayat Pembayaran</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr><th>Tanggal</th><th>Metode</th><th>Jumlah</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($booking->payments as $p)
                    <tr>
                        <td>{{ $p->created_at ? $p->created_at->format('d M Y') : '-' }}</td>
                        <td>{{ $p->method_label }}</td>
                        <td>Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                        <td><span class="badge {{ $p->status_badge_class }}">{{ $p->status_label }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted">Belum ada pembayaran</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header"><i class="fas fa-clipboard-check me-2"></i>Riwayat Inspeksi</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr><th>Tanggal</th><th>Tipe</th><th>Kondisi</th><th>Catatan</th></tr>
                </thead>
                <tbody>
                    @forelse($booking->inspections as $i)
                    <tr>
                        <td>{{ $i->inspection_date->format('d M Y H:i') }}</td>
                        <td><span class="badge bg-info">{{ ucfirst($i->type) }}</span></td>
                        <td>{{ $i->condition_notes }}</td>
                        <td>{{ $i->notes ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted">Belum ada inspeksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
