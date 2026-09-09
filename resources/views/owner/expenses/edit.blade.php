@extends('layouts.app')
@section('title', 'Edit Pengeluaran')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('owner.expenses.index') }}">Pengeluaran</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Edit Pengeluaran</h4>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('owner.expenses.update', $expense) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="category" class="form-select" required>
                        <option value="">Pilih Kategori</option>
                        @foreach(['maintenance'=>'Maintenance','fuel'=>'BBM','insurance'=>'Asuransi','tax'=>'Pajak','repair'=>'Perbaikan','tire'=>'Ban','rental'=>'Sewa Tempat','salary'=>'Gaji','other'=>'Lainnya'] as $val => $label)
                        <option value="{{ $val }}" {{ old('category', $expense->category)===$val?'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="amount" class="form-control" value="{{ old('amount', $expense->amount) }}" min="0" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kendaraan</label>
                    <select name="vehicle_id" class="form-select">
                        <option value="">Pilih Kendaraan (Opsional)</option>
                        @foreach($vehicles as $v)
                        <option value="{{ $v->id }}" {{ old('vehicle_id', $expense->vehicle_id)===$v->id?'selected':'' }}>{{ $v->license_plate }} - {{ $v->brand }} {{ $v->model }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="2" required>{{ old('description', $expense->description) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bukti/Invoice</label>
                    <input type="file" name="receipt" class="form-control" accept="image/*,.pdf">
                    @if($expense->receipt)<small class="text-muted">Bukti saat ini ada. Upload baru akan menggantikan.</small>@endif
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $expense->notes) }}</textarea>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan Perubahan</button>
                <a href="{{ route('owner.expenses.show', $expense) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection