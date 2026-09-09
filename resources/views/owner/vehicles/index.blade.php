@extends('layouts.app')
@section('title', 'Manajemen Armada')

@section('breadcrumb')
<li class="breadcrumb-item active">Armada</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Manajemen Armada</h4>
    <a href="{{ route('owner.vehicles.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Tambah Kendaraan</a>
</div>

<div class="filter-bar">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Cari plat, merk, model..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="available" {{ request('status')==='available'?'selected':'' }}>Tersedia</option>
                <option value="booked" {{ request('status')==='booked'?'selected':'' }}>Dibooking</option>
                <option value="rented" {{ request('status')==='rented'?'selected':'' }}>Disewa</option>
                <option value="maintenance" {{ request('status')==='maintenance'?'selected':'' }}>Maintenance</option>
                <option value="unavailable" {{ request('status')==='unavailable'?'selected':'' }}>Tidak Tersedia</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="transmission" class="form-select">
                <option value="">Semua Transmisi</option>
                <option value="manual" {{ request('transmission')==='manual'?'selected':'' }}>Manual</option>
                <option value="automatic" {{ request('transmission')==='automatic'?'selected':'' }}>Automatic</option>
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
                        <th>Plat</th>
                        <th>Kendaraan</th>
                        <th>Tahun</th>
                        <th>Transmisi</th>
                        <th>Tarif/Hari</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicles as $v)
                    <tr>
                        <td><strong>{{ $v->license_plate }}</strong></td>
                        <td>{{ $v->brand }} {{ $v->model }}</td>
                        <td>{{ $v->year }}</td>
                        <td>{{ ucfirst($v->transmission) }}</td>
                        <td>Rp {{ number_format($v->daily_rate, 0, ',', '.') }}</td>
                        <td><span class="badge {{ $v->status_badge_class }}">{{ $v->status_label }}</span></td>
                        <td>
                            <a href="{{ route('owner.vehicles.show', $v) }}" class="btn btn-sm btn-outline-primary" title="Detail"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('owner.vehicles.edit', $v) }}" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fas fa-edit"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada kendaraan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $vehicles->links() }}
    </div>
</div>
@endsection
