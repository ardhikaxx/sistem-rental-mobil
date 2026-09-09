@extends('layouts.app')
@section('title', 'Manajemen Pembayaran')

@section('breadcrumb')
<li class="breadcrumb-item active">Kasir</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Kasir</h4>
</div>

<div class="filter-bar">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Cari kode booking, nama pelanggan..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <select name="type" class="form-select">
                <option value="">Semua Tipe</option>
                <option value="dp" {{ request('type')==='dp'?'selected':'' }}>DP</option>
                <option value="pelunasan" {{ request('type')==='pelunasan'?'selected':'' }}>Pelunasan</option>
                <option value="denda" {{ request('type')==='denda'?'selected':'' }}>Denda</option>
                <option value="tambahan" {{ request('type')==='tambahan'?'selected':'' }}>Tambahan</option>
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
                        <th>Tanggal</th>
                        <th>Booking</th>
                        <th>Pelanggan</th>
                        <th>Metode</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $p)
                    <tr>
                        <td>{{ $p->created_at ? $p->created_at->format('d M Y H:i') : '-' }}</td>
                        <td><a href="{{ route('employee.bookings.show', $p->booking) }}">{{ $p->booking->booking_code }}</a></td>
                        <td>{{ $p->booking->customer->name ?? '-' }}</td>
                        <td>{{ $p->method_label }}</td>
                        <td><strong>Rp {{ number_format($p->amount, 0, ',', '.') }}</strong></td>
                        <td><span class="badge {{ $p->status_badge_class }}">{{ $p->status_label }}</span></td>
                        <td>
                            <a href="{{ route('employee.payments.show', $p) }}" class="btn btn-sm btn-outline-primary" title="Detail"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pembayaran</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $payments->links() }}
    </div>
</div>
@endsection
