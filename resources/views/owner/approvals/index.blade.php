@extends('layouts.app')
@section('title', 'Manajemen Persetujuan')

@section('breadcrumb')
<li class="breadcrumb-item active">Persetujuan</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Manajemen Persetujuan</h4>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tipe</th>
                        <th>Deskripsi</th>
                        <th>Diajukan Oleh</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($approvals as $a)
                    <tr>
                        <td><span class="badge bg-info">{{ ucfirst($a->type) }}</span></td>
                        <td>{{ $a->description }}</td>
                        <td>{{ $a->employee->name ?? $a->user->name ?? '-' }}</td>
                        <td>{{ $a->created_at->format('d M Y') }}</td>
                        <td><span class="badge {{ $a->status_badge_class }}">{{ $a->status_label }}</span></td>
                        <td>
                            <a href="{{ route('owner.approvals.show', $a) }}" class="btn btn-sm btn-outline-primary" title="Detail"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada persetujuan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $approvals->links() }}
    </div>
</div>
@endsection