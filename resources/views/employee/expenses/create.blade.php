@extends('layouts.app')
@section('title', 'Tambah Pengeluaran')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('employee.expenses.index') }}">Pengeluaran</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Tambah Pengeluaran</h4>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('employee.expenses.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="category" class="form-select" required>
                        <option value="">Pilih Kategori</option>
                        <option value="maintenance" {{ old('category')==='maintenance'?'selected':'' }}>Maintenance</option>
                        <option value="fuel" {{ old('category')==='fuel'?'selected':'' }}>BBM</option>
                        <option value="insurance" {{ old('category')==='insurance'?'selected':'' }}>Asuransi</option>
                        <option value="tax" {{ old('category')==='tax'?'selected':'' }}>Pajak</option>
                        <option value="repair" {{ old('category')==='repair'?'selected':'' }}>Perbaikan</option>
                        <option value="tire" {{ old('category')==='tire'?'selected':'' }}>Ban</option>
                        <option value="rental" {{ old('category')==='rental'?'selected':'' }}>Sewa Tempat</option>
                        <option value="salary" {{ old('category')==='salary'?'selected':'' }}>Gaji</option>
                        <option value="other" {{ old('category')==='other'?'selected':'' }}>Lainnya</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="amount" class="form-control" value="{{ old('amount') }}" min="0" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kendaraan</label>
                    <select name="vehicle_id" class="form-select">
                        <option value="">Pilih Kendaraan (Opsional)</option>
                        @foreach($vehicles as $v)
                        <option value="{{ $v->id }}" {{ old('vehicle_id')===$v->id?'selected':'' }}>{{ $v->license_plate }} - {{ $v->brand }} {{ $v->model }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="2" required>{{ old('description') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bukti/Invoice</label>
                    <input type="file" name="receipt" class="form-control" accept="image/*,.pdf">
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                <a href="{{ route('employee.expenses.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
