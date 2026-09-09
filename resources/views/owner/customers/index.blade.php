@extends('layouts.app')
@section('title', 'Pelanggan')

@section('breadcrumb')
<li class="breadcrumb-item active">Pelanggan</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Manajemen Pelanggan</h4>
    <a href="{{ route('owner.customers.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Tambah Pelanggan</a>
</div>

<div class="filter-bar">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Cari nama, telepon, no. identitas..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="verification_status" class="form-select">
                <option value="">Semua Status</option>
                <option value="unverified" {{ request('verification_status')==='unverified'?'selected':'' }}>Belum Diverifikasi</option>
                <option value="verified" {{ request('verification_status')==='verified'?'selected':'' }}>Terverifikasi</option>
                <option value="problem" {{ request('verification_status')==='problem'?'selected':'' }}>Bermasalah</option>
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
                    <tr><th>Nama</th><th>Telepon</th><th>No. Identitas</th><th>Verifikasi</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($customers as $c)
                    <tr>
                        <td><strong>{{ $c->name }}</strong></td>
                        <td>{{ $c->phone }}</td>
                        <td>{{ $c->identity_number ?? '-' }}</td>
                        <td><span class="badge {{ $c->verification_badge_class }}">{{ $c->verification_status_label }}</span></td>
                        <td>
                            <a href="{{ route('owner.customers.show', $c) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('owner.customers.edit', $c) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pelanggan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $customers->links() }}
    </div>
</div>
@endsection
