@extends('layouts.app')
@section('title', 'Detail Karyawan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('owner.employees.index') }}">Karyawan</a></li>
<li class="breadcrumb-item active">{{ $employee->name }}</li>
@endsection

@section('content')
<div class="page-header">
    <h4>{{ $employee->name }} <span class="badge {{ $employee->is_active ? 'bg-success' : 'bg-secondary' }} ms-2">{{ $employee->is_active ? 'Aktif' : 'Non-aktif' }}</span></h4>
    <div class="d-flex gap-2">
        <a href="{{ route('owner.employees.edit', $employee) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit me-1"></i>Edit</a>
        <form method="POST" action="{{ route('owner.employees.destroy', $employee) }}" class="d-inline">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus karyawan ini?')"><i class="fas fa-trash me-1"></i>Hapus</button>
        </form>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-user me-2"></i>Data Diri</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="150">Nama</th><td>{{ $employee->name }}</td></tr>
                    <tr><th>Telepon</th><td>{{ $employee->phone }}</td></tr>
                    <tr><th>Email</th><td>{{ $employee->email ?? '-' }}</td></tr>
                    <tr><th>Posisi</th><td><span class="badge bg-secondary">{{ $employee->position }}</span></td></tr>
                    <tr><th>Alamat</th><td>{{ $employee->address ?? '-' }}</td></tr>
                    <tr><th>No. Identitas</th><td>{{ $employee->identity_number ?? '-' }}</td></tr>
                    <tr><th>Gaji Pokok</th><td>{{ $employee->salary ? 'Rp ' . number_format($employee->salary, 0, ',', '.') : '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-clipboard-list me-2"></i>Riwayat Tugas</div>
            <div class="card-body">
                @forelse($employee->bookings->take(10) as $b)
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        <a href="{{ route('owner.bookings.show', $b) }}">{{ $b->booking_code }}</a>
                        <small class="text-muted">- {{ $b->customer->name }}</small>
                    </div>
                    <span class="badge {{ $b->status_badge_class }}">{{ $b->status_label }}</span>
                </div>
                @empty
                <p class="text-muted">Belum ada riwayat tugas</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection