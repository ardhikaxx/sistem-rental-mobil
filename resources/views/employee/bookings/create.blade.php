@extends('layouts.app')
@section('title', 'Tambah Booking')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('employee.bookings.index') }}">Booking</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="page-header"><h4>Tambah Booking</h4></div>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('employee.bookings.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pelanggan <span class="text-danger">*</span></label>
                    <select name="customer_id" class="form-select" required>
                        <option value="">Pilih Pelanggan</option>
                        @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ old('customer_id')===$c->id ? 'selected' : '' }}>{{ $c->name }} - {{ $c->phone }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kendaraan <span class="text-danger">*</span></label>
                    <select name="vehicle_id" class="form-select" required>
                        <option value="">Pilih Kendaraan</option>
                        @foreach($vehicles as $v)
                        <option value="{{ $v->id }}" {{ old('vehicle_id')===$v->id ? 'selected' : '' }}>{{ $v->brand }} {{ $v->model }} ({{ $v->license_plate }}) - Rp {{ number_format($v->daily_rate, 0, ',', '.') }}/hari</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Biaya Tambahan (Rp)</label>
                    <input type="number" name="additional_fees" class="form-control" value="{{ old('additional_fees', 0) }}" min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Diskon (Rp)</label>
                    <input type="number" name="discount" class="form-control" value="{{ old('discount', 0) }}" min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label">DP / Uang Muka (Rp)</label>
                    <input type="number" name="dp_amount" class="form-control" value="{{ old('dp_amount', 0) }}" min="0">
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                <a href="{{ route('employee.bookings.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
