@extends('layouts.app')
@section('title', 'Manajemen Karyawan')

@section('breadcrumb')
<li class="breadcrumb-item active">Karyawan</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Manajemen Karyawan</h4>
    <a href="{{ route('owner.employees.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Tambah Karyawan</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Posisi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $e)
                    <tr>
                        <td><strong>{{ $e->name }}</strong></td>
                        <td>{{ $e->phone }}</td>
                        <td><span class="badge bg-secondary">{{ $e->position }}</span></td>
                        <td><span class="badge {{ $e->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $e->is_active ? 'Aktif' : 'Non-aktif' }}</span></td>
                        <td>
                            <a href="{{ route('owner.employees.show', $e) }}" class="btn btn-sm btn-outline-primary" title="Detail"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('owner.employees.edit', $e) }}" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fas fa-edit"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada karyawan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $employees->links() }}
    </div>
</div>
@endsection