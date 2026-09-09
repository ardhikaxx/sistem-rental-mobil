@extends('layouts.app')
@section('title', 'Tambah Pelanggan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('employee.customers.index') }}">Pelanggan</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="page-header"><h4>Tambah Pelanggan</h4></div>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('employee.customers.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Nama Lengkap <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
                <div class="col-md-6"><label class="form-label">Telepon <span class="text-danger">*</span></label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required></div>
                <div class="col-12"><label class="form-label">Alamat</label><textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea></div>
                <div class="col-md-4"><label class="form-label">Jenis Identitas</label><input type="text" name="identity_type" class="form-control" value="{{ old('identity_type') }}" placeholder="KTP/SIM"></div>
                <div class="col-md-4"><label class="form-label">No. Identitas</label><input type="text" name="identity_number" class="form-control" value="{{ old('identity_number') }}"></div>
                <div class="col-md-4"><label class="form-label">No. SIM</label><input type="text" name="sim_number" class="form-control" value="{{ old('sim_number') }}"></div>
                <div class="col-md-6"><label class="form-label">Kontak Darurat (Nama)</label><input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name') }}"></div>
                <div class="col-md-6"><label class="form-label">Kontak Darurat (Telepon)</label><input type="text" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone') }}"></div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                <a href="{{ route('employee.customers.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
