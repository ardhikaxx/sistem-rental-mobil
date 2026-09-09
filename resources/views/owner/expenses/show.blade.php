@extends('layouts.app')
@section('title', 'Detail Pengeluaran')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('owner.expenses.index') }}">Pengeluaran</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Detail Pengeluaran <span class="badge {{ $expense->status_badge_class }} ms-2">{{ $expense->status_label }}</span></h4>
    <div class="d-flex gap-2">
        <a href="{{ route('owner.expenses.edit', $expense) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit me-1"></i>Edit</a>
        <form method="POST" action="{{ route('owner.expenses.destroy', $expense) }}" class="d-inline">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus pengeluaran ini?')"><i class="fas fa-trash me-1"></i>Hapus</button>
        </form>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-receipt me-2"></i>Informasi Pengeluaran</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="150">Tanggal</th><td>{{ $expense->expense_date->format('d M Y') }}</td></tr>
                    <tr><th>Kategori</th><td><span class="badge bg-secondary">{{ $expense->category }}</span></td></tr>
                    <tr><th>Jumlah</th><td><strong class="text-danger">Rp {{ number_format($expense->amount, 0, ',', '.') }}</strong></td></tr>
                    <tr><th>Deskripsi</th><td>{{ $expense->description }}</td></tr>
                    <tr><th>Kendaraan</th><td>{{ $expense->vehicle ? $expense->vehicle->license_plate . ' - ' . $expense->vehicle->brand . ' ' . $expense->vehicle->model : '-' }}</td></tr>
                    <tr><th>Status</th><td><span class="badge {{ $expense->status_badge_class }}">{{ $expense->status_label }}</span></td></tr>
                    <tr><th>Catatan</th><td>{{ $expense->notes ?? '-' }}</td></tr>
                    <tr><th>Dibuat</th><td>{{ $expense->created_at->format('d M Y H:i') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-file me-2"></i>Bukti/Invoice</div>
            <div class="card-body">
                @if($expense->receipt)
                <a href="{{ asset('storage/' . $expense->receipt) }}" target="_blank" class="btn btn-outline-primary"><i class="fas fa-external-link-alt me-1"></i>Lihat Bukti</a>
                @else
                <p class="text-muted">Tidak ada bukti terlampir</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection