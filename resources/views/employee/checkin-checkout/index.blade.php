@extends('layouts.app')
@section('title', 'Check-in / Check-out')

@section('breadcrumb')
<li class="breadcrumb-item active">Check-in / Check-out</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Check-in / Check-out</h4>
</div>

<div class="filter-bar">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Cari kode booking, nama pelanggan..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <select name="type" class="form-select">
                <option value="">Semua Tipe</option>
                <option value="checkin" {{ request('type')==='checkin'?'selected':'' }}>Check-in</option>
                <option value="checkout" {{ request('type')==='checkout'?'selected':'' }}>Check-out</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="booked" {{ request('status')==='booked'?'selected':'' }}>Dibooking</option>
                <option value="in_progress" {{ request('status')==='in_progress'?'selected':'' }}>Berlangsung</option>
                <option value="checked_out" {{ request('status')==='checked_out'?'selected':'' }}>Check-out</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-outline-primary w-100"><i class="fas fa-search me-1"></i>Filter</button>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>Pelanggan</th>
                        <th>Kendaraan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $b)
                    <tr>
                        <td><strong>{{ $b->booking_code }}</strong></td>
                        <td>{{ $b->customer->name }}</td>
                        <td>{{ $b->vehicle->brand }} {{ $b->vehicle->model }} <small class="text-muted">({{ $b->vehicle->license_plate }})</small></td>
                        <td>{{ $b->start_date->format('d M Y') }} - {{ $b->end_date->format('d M Y') }}</td>
                        <td><span class="badge {{ $b->status_badge_class }}">{{ $b->status_label }}</span></td>
                        <td>
                            @if($b->status === 'booked')
                            <a href="{{ route('employee.checkin-checkout.checkin', $b) }}" class="btn btn-sm btn-success" title="Check-in"><i class="fas fa-sign-in-alt me-1"></i>Check-in</a>
                            @elseif($b->status === 'in_progress')
                            <a href="{{ route('employee.checkin-checkout.checkout', $b) }}" class="btn btn-sm btn-warning" title="Check-out"><i class="fas fa-sign-out-alt me-1"></i>Check-out</a>
                            <a href="{{ route('employee.checkin-checkout.travel-doc', $b) }}" class="btn btn-sm btn-outline-info" title="Surat Jalan" target="_blank"><i class="fas fa-print"></i></a>
                            @elseif($b->status === 'checked_out')
                            <a href="{{ route('employee.checkin-checkout.travel-doc', $b) }}" class="btn btn-sm btn-outline-info" title="Surat Jalan" target="_blank"><i class="fas fa-print"></i></a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada booking untuk proses</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $bookings->links() }}
    </div>
</div>
@endsection
