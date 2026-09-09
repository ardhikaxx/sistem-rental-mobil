@extends('layouts.app')
@section('title', 'Tambah Kendaraan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('owner.vehicles.index') }}">Armada</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Tambah Kendaraan</h4>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('owner.vehicles.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">No. Plat <span class="text-danger">*</span></label>
                    <input type="text" name="license_plate" class="form-control" value="{{ old('license_plate') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Merk <span class="text-danger">*</span></label>
                    <input type="text" name="brand" class="form-control" value="{{ old('brand') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Model <span class="text-danger">*</span></label>
                    <input type="text" name="model" class="form-control" value="{{ old('model') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tahun <span class="text-danger">*</span></label>
                    <input type="number" name="year" class="form-control" value="{{ old('year', date('Y')) }}" min="1990" max="{{ date('Y')+1 }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Warna <span class="text-danger">*</span></label>
                    <input type="text" name="color" class="form-control" value="{{ old('color') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Transmisi <span class="text-danger">*</span></label>
                    <select name="transmission" class="form-select" required>
                        <option value="manual" {{ old('transmission')==='manual'?'selected':'' }}>Manual</option>
                        <option value="automatic" {{ old('transmission')==='automatic'?'selected':'' }}>Automatic</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Bahan Bakar <span class="text-danger">*</span></label>
                    <select name="fuel_type" class="form-select" required>
                        <option value="gasoline">Bensin</option>
                        <option value="diesel">Diesel</option>
                        <option value="electric">Listrik</option>
                        <option value="hybrid">Hybrid</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jumlah Penumpang <span class="text-danger">*</span></label>
                    <input type="number" name="passenger_count" class="form-control" value="{{ old('passenger_count', 5) }}" min="1" max="20" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tarif/Hari (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="daily_rate" class="form-control" value="{{ old('daily_rate') }}" min="0" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tarif/Minggu (Rp)</label>
                    <input type="number" name="weekly_rate" class="form-control" value="{{ old('weekly_rate') }}" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tarif/Bulan (Rp)</label>
                    <input type="number" name="monthly_rate" class="form-control" value="{{ old('monthly_rate') }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. Rangka</label>
                    <input type="text" name="chassis_number" class="form-control" value="{{ old('chassis_number') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. Mesin</label>
                    <input type="text" name="engine_number" class="form-control" value="{{ old('engine_number') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Pembelian</label>
                    <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Pajak/STNK</label>
                    <input type="date" name="tax_expiry_date" class="form-control" value="{{ old('tax_expiry_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Asuransi</label>
                    <input type="date" name="insurance_expiry_date" class="form-control" value="{{ old('insurance_expiry_date') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Foto Kendaraan</label>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Odometer Saat Ini (km)</label>
                    <input type="number" name="current_odometer" class="form-control" value="{{ old('current_odometer', 0) }}" min="0">
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                <a href="{{ route('owner.vehicles.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
