@extends('layouts.app')
@section('title', 'Detail Kendaraan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('owner.vehicles.index') }}">Armada</a></li>
<li class="breadcrumb-item active">{{ $vehicle->license_plate }}</li>
@endsection

@section('content')
<div class="page-header">
    <h4>{{ $vehicle->brand }} {{ $vehicle->model }} <span class="badge {{ $vehicle->status_badge_class }} ms-2">{{ $vehicle->status_label }}</span></h4>
    <div class="d-flex gap-2">
        <a href="{{ route('owner.vehicles.edit', $vehicle) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit me-1"></i>Edit</a>
        <form method="POST" action="{{ route('owner.vehicles.destroy', $vehicle) }}" class="d-inline">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus kendaraan ini?')"><i class="fas fa-trash me-1"></i>Hapus</button>
        </form>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-info-circle me-2"></i>Informasi Kendaraan</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="150">No. Plat</th><td><strong>{{ $vehicle->license_plate }}</strong></td></tr>
                    <tr><th>Merk</th><td>{{ $vehicle->brand }}</td></tr>
                    <tr><th>Model</th><td>{{ $vehicle->model }}</td></tr>
                    <tr><th>Tahun</th><td>{{ $vehicle->year }}</td></tr>
                    <tr><th>Warna</th><td>{{ $vehicle->color }}</td></tr>
                    <tr><th>Transmisi</th><td>{{ ucfirst($vehicle->transmission) }}</td></tr>
                    <tr><th>Bahan Bakar</th><td>{{ ucfirst($vehicle->fuel_type) }}</td></tr>
                    <tr><th>Penumpang</th><td>{{ $vehicle->passenger_count }}</td></tr>
                    <tr><th>Odometer</th><td>{{ number_format($vehicle->current_odometer) }} km</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-tag me-2"></i>Informasi Tarif</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="150">Tarif/Hari</th><td><strong>Rp {{ number_format($vehicle->daily_rate, 0, ',', '.') }}</strong></td></tr>
                    @if($vehicle->weekly_rate)<tr><th>Tarif/Minggu</th><td>Rp {{ number_format($vehicle->weekly_rate, 0, ',', '.') }}</td></tr>@endif
                    @if($vehicle->monthly_rate)<tr><th>Tarif/Bulan</th><td>Rp {{ number_format($vehicle->monthly_rate, 0, ',', '.') }}</td></tr>@endif
                    <tr><th>Pajak/STNK</th><td>{{ $vehicle->tax_expiry_date ? $vehicle->tax_expiry_date->format('d M Y') : '-' }}</td></tr>
                    <tr><th>Asuransi</th><td>{{ $vehicle->insurance_expiry_date ? $vehicle->insurance_expiry_date->format('d M Y') : '-' }}</td></tr>
                </table>

                <div class="mt-3">
                    <strong>Update Status:</strong>
                    <form method="POST" action="{{ route('owner.vehicles.update-status', $vehicle) }}" class="d-flex gap-2 mt-2">
                        @csrf
                        <select name="status" class="form-select form-select-sm" style="width:auto;">
                            <option value="available" {{ $vehicle->status==='available'?'selected':'' }}>Tersedia</option>
                            <option value="maintenance" {{ $vehicle->status==='maintenance'?'selected':'' }}>Maintenance</option>
                            <option value="unavailable" {{ $vehicle->status==='unavailable'?'selected':'' }}>Tidak Tersedia</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header"><i class="fas fa-history me-2"></i>Riwayat Booking</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr><th>Kode</th><th>Pelanggan</th><th>Periode</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($vehicle->bookings->take(10) as $b)
                    <tr>
                        <td><a href="{{ route('owner.bookings.show', $b) }}">{{ $b->booking_code }}</a></td>
                        <td>{{ $b->customer->name }}</td>
                        <td>{{ $b->start_date->format('d M Y') }} - {{ $b->end_date->format('d M Y') }}</td>
                        <td><span class="badge {{ $b->status_badge_class }}">{{ $b->status_label }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted">Belum ada booking</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
