@extends('layouts.app')
@section('title', 'Detail Persetujuan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('owner.approvals.index') }}">Persetujuan</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Detail Persetujuan <span class="badge {{ $approval->status_badge_class }} ms-2">{{ $approval->status_label }}</span></h4>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-clipboard-check me-2"></i>Informasi Persetujuan</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="150">Tipe</th><td><span class="badge bg-info">{{ ucfirst($approval->type) }}</span></td></tr>
                    <tr><th>Deskripsi</th><td>{{ $approval->description }}</td></tr>
                    <tr><th>Diajukan Oleh</th><td>{{ $approval->employee->name ?? $approval->user->name ?? '-' }}</td></tr>
                    <tr><th>Tanggal Pengajuan</th><td>{{ $approval->created_at->format('d M Y H:i') }}</td></tr>
                    @if($approval->approved_at)
                    <tr><th>Tanggal Disetujui</th><td>{{ $approval->approved_at->format('d M Y H:i') }}</td></tr>
                    @endif
                    @if($approval->notes)
                    <tr><th>Catatan</th><td>{{ $approval->notes }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        @if($approval->status === 'pending')
        <div class="card">
            <div class="card-header"><i class="fas fa-gavel me-2"></i>Aksi</div>
            <div class="card-body">
                <form method="POST" action="{{ route('owner.approvals.approve', $approval) }}" class="mb-2">
                    @csrf @method('POST')
                    <div class="mb-2">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Catatan persetujuan..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Setujui permintaan ini?')"><i class="fas fa-check me-1"></i>Setujui</button>
                </form>
                <hr>
                <form method="POST" action="{{ route('owner.approvals.reject', $approval) }}">
                    @csrf @method('POST')
                    <div class="mb-2">
                        <label class="form-label">Alasan Penolakan</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Alasan penolakan..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Tolak permintaan ini?')"><i class="fas fa-times me-1"></i>Tolak</button>
                </form>
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-header"><i class="fas fa-info-circle me-2"></i>Riwayat Keputusan</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th>Keputusan</th><td><span class="badge {{ $approval->status==='approved'?'bg-success':'bg-danger' }}">{{ $approval->status==='approved'?'Disetujui':'Ditolak' }}</span></td></tr>
                    @if($approval->notes)
                    <tr><th>Catatan</th><td>{{ $approval->notes }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection