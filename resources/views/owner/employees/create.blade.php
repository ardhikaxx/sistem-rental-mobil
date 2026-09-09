@extends('layouts.app')
@section('title', 'Tambah Karyawan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('owner.employees.index') }}">Karyawan</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Tambah Karyawan</h4>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('owner.employees.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telepon <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Posisi <span class="text-danger">*</span></label>
                    <select name="position" class="form-select" required>
                        <option value="">Pilih Posisi</option>
                        <option value="admin" {{ old('position')==='admin'?'selected':'' }}>Admin</option>
                        <option value="driver" {{ old('position')==='driver'?'selected':'' }}>Driver</option>
                        <option value="mechanic" {{ old('position')==='mechanic'?'selected':'' }}>Mekanik</option>
                        <option value="sales" {{ old('position')==='sales'?'selected':'' }}>Sales</option>
                        <option value="other" {{ old('position')==='other'?'selected':'' }}>Lainnya</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. Identitas</label>
                    <input type="text" name="identity_number" class="form-control" value="{{ old('identity_number') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Gaji Pokok (Rp)</label>
                    <input type="number" name="salary" class="form-control" value="{{ old('salary') }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="1" {{ old('is_active', '1')=='1'?'selected':'' }}>Aktif</option>
                        <option value="0" {{ old('is_active')=='0'?'selected':'' }}>Non-aktif</option>
                    </select>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                <a href="{{ route('owner.employees.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection