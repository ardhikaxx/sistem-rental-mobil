@extends('layouts.app')
@section('title', 'Manajemen Booking')

@section('breadcrumb')
<li class="breadcrumb-item active">Booking</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Manajemen Booking</h4>
</div>

<div class="filter-bar">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Cari kode booking, nama pelanggan..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="pending_payment" {{ request('status')==='pending_payment'?'selected':'' }}>Menunggu Pembayaran</option>
                <option value="booked" {{ request('status')==='booked'?'selected':'' }}>Dibooking</option>
                <option value="in_progress" {{ request('status')==='in_progress'?'selected':'' }}>Berlangsung</option>
                <option value="completed" {{ request('status')==='completed'?'selected':'' }}>Selesai</option>
                <option value="cancelled" {{ request('status')==='cancelled'?'selected':'' }}>Dibatalkan</option>
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
                            <a href="{{ route('owner.bookings.show', $b) }}" class="btn btn-sm btn-outline-primary" title="Detail"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada booking</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $bookings->links() }}
    </div>
</div>
@endsection