@extends('layouts.app')
@section('title', 'Detail Maintenance')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('owner.maintenance.index') }}">Maintenance</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
@php
    $maintenance = $maintenance ?? $record;
@endphp
<div class="page-header">
    <h4>Detail Maintenance <span class="badge {{ $maintenance->status_badge_class }} ms-2">{{ $maintenance->status_label }}</span></h4>
    <div class="d-flex gap-2">
        <a href="{{ route('owner.maintenance.edit', $maintenance) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit me-1"></i>Edit</a>
        <form method="POST" action="{{ route('owner.maintenance.destroy', $maintenance) }}" class="d-inline">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus record maintenance ini?')"><i class="fas fa-trash me-1"></i>Hapus</button>
        </form>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-wrench me-2"></i>Informasi Maintenance</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="160">Kendaraan</th><td><a href="{{ route('owner.vehicles.show', $maintenance->vehicle) }}">{{ $maintenance->vehicle?->license_plate ?? '-' }} - {{ $maintenance->vehicle?->brand }} {{ $maintenance->vehicle?->model }}</a></td></tr>
                    <tr><th>Tipe</th><td><span class="badge bg-info">{{ ucfirst($maintenance->type) }}</span></td></tr>
                    <tr><th>Tanggal Terjadwal</th><td>{{ $maintenance->scheduled_date?->format('d M Y') ?? '-' }}</td></tr>
                    <tr><th>Tanggal Selesai</th><td>{{ $maintenance->completed_date?->format('d M Y') ?? '-' }}</td></tr>
                    <tr><th>Estimasi Biaya</th><td>Rp {{ number_format($maintenance->estimated_cost ?? 0, 0, ',', '.') }}</td></tr>
                    <tr><th>Biaya Aktual</th><td><strong>Rp {{ number_format($maintenance->actual_cost ?? 0, 0, ',', '.') }}</strong></td></tr>
                    <tr><th>Odometer</th><td>{{ $maintenance->odometer_reading ? number_format($maintenance->odometer_reading) . ' km' : '-' }}</td></tr>
                    <tr><th>Deskripsi</th><td>{{ $maintenance->description ?? '-' }}</td></tr>
                    <tr><th>Catatan</th><td>{{ $maintenance->notes ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-car me-2"></i>Info Kendaraan</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="150">No. Plat</th><td>{{ $maintenance->vehicle->license_plate }}</td></tr>
                    <tr><th>Merk/Model</th><td>{{ $maintenance->vehicle->brand }} {{ $maintenance->vehicle->model }}</td></tr>
                    <tr><th>Tahun</th><td>{{ $maintenance->vehicle->year }}</td></tr>
                    <tr><th>Status</th><td><span class="badge {{ $maintenance->vehicle->status_badge_class }}">{{ $maintenance->vehicle->status_label }}</span></td></tr>
                    <tr><th>Odometer</th><td>{{ number_format($maintenance->vehicle->current_odometer) }} km</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection