@extends('layouts.app')
@section('title', 'Manajemen Pengeluaran')

@section('breadcrumb')
<li class="breadcrumb-item active">Pengeluaran</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Manajemen Pengeluaran</h4>
    <a href="{{ route('owner.expenses.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Tambah Pengeluaran</a>
</div>

<div class="filter-bar">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Cari deskripsi, kategori..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status')==='pending'?'selected':'' }}>Menunggu</option>
                <option value="approved" {{ request('status')==='approved'?'selected':'' }}>Disetujui</option>
                <option value="rejected" {{ request('status')==='rejected'?'selected':'' }}>Ditolak</option>
                <option value="paid" {{ request('status')==='paid'?'selected':'' }}>Dibayar</option>
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
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $e)
                    <tr>
                        <td>{{ $e->expense_date->format('d M Y') }}</td>
                        <td><span class="badge bg-secondary">{{ $e->category }}</span></td>
                        <td>{{ $e->description }}</td>
                        <td><strong>Rp {{ number_format($e->amount, 0, ',', '.') }}</strong></td>
                        <td><span class="badge {{ $e->status_badge_class }}">{{ $e->status_label }}</span></td>
                        <td>
                            <a href="{{ route('owner.expenses.show', $e) }}" class="btn btn-sm btn-outline-primary" title="Detail"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('owner.expenses.edit', $e) }}" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fas fa-edit"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada pengeluaran</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $expenses->links() }}
    </div>
</div>
@endsection