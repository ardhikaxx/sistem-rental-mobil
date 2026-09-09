@extends('layouts.app')
@section('title', 'Edit Maintenance')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('owner.maintenance.index') }}">Maintenance</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
@php
    $maintenance = $maintenance ?? $record;
@endphp
<div class="page-header">
    <h4>Edit Maintenance</h4>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('owner.maintenance.update', $maintenance) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Kendaraan <span class="text-danger">*</span></label>
                    <select name="vehicle_id" class="form-select" required>
                        <option value="">Pilih Kendaraan</option>
                        @foreach($vehicles as $v)
                        <option value="{{ $v->id }}" {{ old('vehicle_id', $maintenance->vehicle_id)===$v->id?'selected':'' }}>{{ $v->license_plate }} - {{ $v->brand }} {{ $v->model }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipe Maintenance <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        <option value="">Pilih Tipe</option>
                        @foreach(['service'=>'Servis Berkala','repair'=>'Perbaikan','tire'=>'Ganti Ban','oil'=>'Ganti Oli','inspection'=>'Inspeksi','other'=>'Lainnya'] as $val => $label)
                        <option value="{{ $val }}" {{ old('type', $maintenance->type)===$val?'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Terjadwal <span class="text-danger">*</span></label>
                    <input type="date" name="scheduled_date" class="form-control" value="{{ old('scheduled_date', $maintenance->scheduled_date?->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="completed_date" class="form-control" value="{{ old('completed_date', $maintenance->completed_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Estimasi Biaya (Rp)</label>
                    <input type="number" name="estimated_cost" class="form-control" value="{{ old('estimated_cost', $maintenance->estimated_cost) }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Biaya Aktual (Rp)</label>
                    <input type="number" name="actual_cost" class="form-control" value="{{ old('actual_cost', $maintenance->actual_cost) }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Odometer Saat Ini (km)</label>
                    <input type="number" name="odometer_reading" class="form-control" value="{{ old('odometer_reading', $maintenance->odometer_reading) }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach(['scheduled'=>'Terjadwal','in_progress'=>'Berlangsung','completed'=>'Selesai','cancelled'=>'Dibatalkan'] as $val => $label)
                        <option value="{{ $val }}" {{ old('status', $maintenance->status)===$val?'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi Pekerjaan <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="3" required>{{ old('description', $maintenance->description) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $maintenance->notes) }}</textarea>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan Perubahan</button>
                <a href="{{ route('owner.maintenance.show', $maintenance) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection