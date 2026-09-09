@extends('layouts.app')
@section('title', 'Manajemen Maintenance')

@section('breadcrumb')
<li class="breadcrumb-item active">Maintenance</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Manajemen Maintenance</h4>
    <a href="{{ route('owner.maintenance.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Tambah Maintenance</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kendaraan</th>
                        <th>Tipe</th>
                        <th>Tanggal</th>
                        <th>Biaya</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($maintenances as $m)
                    <tr>
                        <td><strong>{{ $m->vehicle->license_plate }}</strong> <small class="text-muted">({{ $m->vehicle->brand }} {{ $m->vehicle->model }})</small></td>
                        <td><span class="badge bg-info">{{ ucfirst($m->type) }}</span></td>
                        <td>{{ $m->scheduled_date->format('d M Y') }}</td>
                        <td>Rp {{ number_format($m->estimated_cost ?? 0, 0, ',', '.') }}</td>
                        <td><span class="badge {{ $m->status_badge_class }}">{{ $m->status_label }}</span></td>
                        <td>
                            <a href="{{ route('owner.maintenance.show', $m) }}" class="btn btn-sm btn-outline-primary" title="Detail"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('owner.maintenance.edit', $m) }}" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fas fa-edit"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada record maintenance</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $maintenances->links() }}
    </div>
</div>
@endsection