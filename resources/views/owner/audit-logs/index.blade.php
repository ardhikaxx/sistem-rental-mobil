@extends('layouts.app')
@section('title', 'Audit Log')

@section('breadcrumb')
<li class="breadcrumb-item active">Audit Log</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Audit Log</h4>
</div>

<div class="filter-bar">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Cari aktivitas..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <select name="type" class="form-select">
                <option value="">Semua Tipe</option>
                <option value="booking" {{ request('type')==='booking'?'selected':'' }}>Booking</option>
                <option value="payment" {{ request('type')==='payment'?'selected':'' }}>Pembayaran</option>
                <option value="vehicle" {{ request('type')==='vehicle'?'selected':'' }}>Kendaraan</option>
                <option value="customer" {{ request('type')==='customer'?'selected':'' }}>Pelanggan</option>
                <option value="expense" {{ request('type')==='expense'?'selected':'' }}>Pengeluaran</option>
                <option value="auth" {{ request('type')==='auth'?'selected':'' }}>Autentikasi</option>
                <option value="system" {{ request('type')==='system'?'selected':'' }}>Sistem</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
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
                        <th>Waktu</th>
                        <th>Pengguna</th>
                        <th>Tipe</th>
                        <th>Aktivitas</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td><small class="text-muted">{{ $log->created_at->format('d M Y H:i:s') }}</small></td>
                        <td><strong>{{ $log->user->name ?? 'System' }}</strong></td>
                        <td><span class="badge bg-secondary">{{ ucfirst($log->type ?? '-') }}</span></td>
                        <td>{{ $log->action }}</td>
                        <td>
                            @if($log->description)
                            <small>{{ Str::limit($log->description, 80) }}</small>
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada log</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $logs->links() }}
    </div>
</div>
@endsection