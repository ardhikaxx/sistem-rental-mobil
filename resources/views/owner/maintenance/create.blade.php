@extends('layouts.app')
@section('title', 'Tambah Maintenance')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('owner.maintenance.index') }}">Maintenance</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Tambah Maintenance</h4>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('owner.maintenance.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Kendaraan <span class="text-danger">*</span></label>
                    <select name="vehicle_id" class="form-select" required>
                        <option value="">Pilih Kendaraan</option>
                        @foreach($vehicles as $v)
                        <option value="{{ $v->id }}" {{ old('vehicle_id')===$v->id?'selected':'' }}>{{ $v->license_plate }} - {{ $v->brand }} {{ $v->model }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipe Maintenance <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        <option value="">Pilih Tipe</option>
                        <option value="service" {{ old('type')==='service'?'selected':'' }}>Servis Berkala</option>
                        <option value="repair" {{ old('type')==='repair'?'selected':'' }}>Perbaikan</option>
                        <option value="tire" {{ old('type')==='tire'?'selected':'' }}>Ganti Ban</option>
                        <option value="oil" {{ old('type')==='oil'?'selected':'' }}>Ganti Oli</option>
                        <option value="inspection" {{ old('type')==='inspection'?'selected':'' }}>Inspeksi</option>
                        <option value="other" {{ old('type')==='other'?'selected':'' }}>Lainnya</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Terjadwal <span class="text-danger">*</span></label>
                    <input type="date" name="scheduled_date" class="form-control" value="{{ old('scheduled_date', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="completed_date" class="form-control" value="{{ old('completed_date') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Estimasi Biaya (Rp)</label>
                    <input type="number" name="estimated_cost" class="form-control" value="{{ old('estimated_cost') }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Biaya Aktual (Rp)</label>
                    <input type="number" name="actual_cost" class="form-control" value="{{ old('actual_cost') }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Odometer Saat Ini (km)</label>
                    <input type="number" name="odometer_reading" class="form-control" value="{{ old('odometer_reading') }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="scheduled" {{ old('status')==='scheduled'?'selected':'' }}>Terjadwal</option>
                        <option value="in_progress" {{ old('status')==='in_progress'?'selected':'' }}>Berlangsung</option>
                        <option value="completed" {{ old('status')==='completed'?'selected':'' }}>Selesai</option>
                        <option value="cancelled" {{ old('status')==='cancelled'?'selected':'' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi Pekerjaan <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                <a href="{{ route('owner.maintenance.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection