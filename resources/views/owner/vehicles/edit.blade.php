@extends('layouts.app')
@section('title', 'Edit Kendaraan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('owner.vehicles.index') }}">Armada</a></li>
<li class="breadcrumb-item active">Edit {{ $vehicle->license_plate }}</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Edit Kendaraan</h4>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('owner.vehicles.update', $vehicle) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">No. Plat <span class="text-danger">*</span></label>
                    <input type="text" name="license_plate" class="form-control" value="{{ old('license_plate', $vehicle->license_plate) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Merk <span class="text-danger">*</span></label>
                    <input type="text" name="brand" class="form-control" value="{{ old('brand', $vehicle->brand) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Model <span class="text-danger">*</span></label>
                    <input type="text" name="model" class="form-control" value="{{ old('model', $vehicle->model) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tahun <span class="text-danger">*</span></label>
                    <input type="number" name="year" class="form-control" value="{{ old('year', $vehicle->year) }}" min="1990" max="{{ date('Y')+1 }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Warna <span class="text-danger">*</span></label>
                    <input type="text" name="color" class="form-control" value="{{ old('color', $vehicle->color) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Transmisi <span class="text-danger">*</span></label>
                    <select name="transmission" class="form-select" required>
                        <option value="manual" {{ old('transmission', $vehicle->transmission)==='manual'?'selected':'' }}>Manual</option>
                        <option value="automatic" {{ old('transmission', $vehicle->transmission)==='automatic'?'selected':'' }}>Automatic</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Bahan Bakar <span class="text-danger">*</span></label>
                    <select name="fuel_type" class="form-select" required>
                        <option value="gasoline" {{ old('fuel_type', $vehicle->fuel_type)==='gasoline'?'selected':'' }}>Bensin</option>
                        <option value="diesel" {{ old('fuel_type', $vehicle->fuel_type)==='diesel'?'selected':'' }}>Diesel</option>
                        <option value="electric" {{ old('fuel_type', $vehicle->fuel_type)==='electric'?'selected':'' }}>Listrik</option>
                        <option value="hybrid" {{ old('fuel_type', $vehicle->fuel_type)==='hybrid'?'selected':'' }}>Hybrid</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Penumpang</label>
                    <input type="number" name="passenger_count" class="form-control" value="{{ old('passenger_count', $vehicle->passenger_count) }}" min="1" max="20" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tarif/Hari (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="daily_rate" class="form-control" value="{{ old('daily_rate', $vehicle->daily_rate) }}" min="0" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tarif/Minggu (Rp)</label>
                    <input type="number" name="weekly_rate" class="form-control" value="{{ old('weekly_rate', $vehicle->weekly_rate) }}" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tarif/Bulan (Rp)</label>
                    <input type="number" name="monthly_rate" class="form-control" value="{{ old('monthly_rate', $vehicle->monthly_rate) }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. Rangka</label>
                    <input type="text" name="chassis_number" class="form-control" value="{{ old('chassis_number', $vehicle->chassis_number) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. Mesin</label>
                    <input type="text" name="engine_number" class="form-control" value="{{ old('engine_number', $vehicle->engine_number) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Pembelian</label>
                    <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date', $vehicle->purchase_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Pajak/STNK</label>
                    <input type="date" name="tax_expiry_date" class="form-control" value="{{ old('tax_expiry_date', $vehicle->tax_expiry_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Asuransi</label>
                    <input type="date" name="insurance_expiry_date" class="form-control" value="{{ old('insurance_expiry_date', $vehicle->insurance_expiry_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Foto Kendaraan</label>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                    @if($vehicle->photo)<small class="text-muted">Foto saat ini ada. Upload baru akan menggantikan.</small>@endif
                </div>
                <div class="col-md-6">
                    <label class="form-label">Odometer (km)</label>
                    <input type="number" name="current_odometer" class="form-control" value="{{ old('current_odometer', $vehicle->current_odometer) }}" min="0">
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $vehicle->notes) }}</textarea>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan Perubahan</button>
                <a href="{{ route('owner.vehicles.show', $vehicle) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
