@extends('layouts.app')
@section('title', 'Check-in Kendaraan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('employee.checkin-checkout') }}">Check-in / Check-out</a></li>
<li class="breadcrumb-item active">Check-in {{ $booking->booking_code }}</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Check-in {{ $booking->booking_code }}</h4>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-file-invoice me-2"></i>Info Booking</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="130">Kode</th><td><strong>{{ $booking->booking_code }}</strong></td></tr>
                    <tr><th>Pelanggan</th><td>{{ $booking->customer->name }}</td></tr>
                    <tr><th>Kendaraan</th><td>{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} ({{ $booking->vehicle->license_plate }})</td></tr>
                    <tr><th>Periode</th><td>{{ $booking->start_date->format('d M Y') }} - {{ $booking->end_date->format('d M Y') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('employee.checkin-checkout.process-checkin', $booking) }}" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><i class="fas fa-clipboard-check me-2"></i>Data Check-in</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Odometer (km) <span class="text-danger">*</span></label>
                            <input type="number" name="odometer" class="form-control" value="{{ old('odometer') }}" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Level Bahan Bakar <span class="text-danger">*</span></label>
                            <select name="fuel_level" class="form-select" required>
                                <option value="">Pilih</option>
                                <option value="empty" {{ old('fuel_level')==='empty'?'selected':'' }}>Kosong (0%)</option>
                                <option value="quarter" {{ old('fuel_level')==='quarter'?'selected':'' }}>1/4</option>
                                <option value="half" {{ old('fuel_level')==='half'?'selected':'' }}>1/2</option>
                                <option value="three_quarter" {{ old('fuel_level')==='three_quarter'?'selected':'' }}>3/4</option>
                                <option value="full" {{ old('fuel_level')==='full'?'selected':'' }}>Penuh (100%)</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Kondisi Eksterior <span class="text-danger">*</span></label>
                            <textarea name="exterior_condition" class="form-control" rows="3" placeholder="Deskripsikan kondisi eksterior kendaraan..." required>{{ old('exterior_condition') }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Kondisi Interior <span class="text-danger">*</span></label>
                            <textarea name="interior_condition" class="form-control" rows="3" placeholder="Deskripsikan kondisi interior kendaraan..." required>{{ old('interior_condition') }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Kondisi Peralatan</label>
                            <textarea name="equipment_condition" class="form-control" rows="2" placeholder="Serep, dongkrak, toolkit, dll...">{{ old('equipment_condition') }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Catatan Kerusakan Sebelumnya</label>
                            <textarea name="previous_damage_notes" class="form-control" rows="2" placeholder="Catatan kerusakan yang sudah ada sebelumnya...">{{ old('previous_damage_notes') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Foto Kendaraan</label>
                            <div class="row g-2" id="photoUploads">
                                <div class="col-md-4">
                                    <select name="photos[0][category]" class="form-select form-select-sm mb-1">
                                        <option value="exterior_front">Depan</option>
                                        <option value="exterior_rear">Belakang</option>
                                        <option value="exterior_left">Kiri</option>
                                        <option value="exterior_right">Kanan</option>
                                        <option value="interior">Interior</option>
                                        <option value="odometer">Odometer</option>
                                        <option value="damage">Kerusakan</option>
                                    </select>
                                    <input type="file" name="photos[0][file]" class="form-control form-control-sm" accept="image/*">
                                </div>
                                <div class="col-md-4">
                                    <select name="photos[1][category]" class="form-select form-select-sm mb-1">
                                        <option value="exterior_front">Depan</option>
                                        <option value="exterior_rear">Belakang</option>
                                        <option value="exterior_left">Kiri</option>
                                        <option value="exterior_right">Kanan</option>
                                        <option value="interior">Interior</option>
                                        <option value="odometer">Odometer</option>
                                        <option value="damage">Kerusakan</option>
                                    </select>
                                    <input type="file" name="photos[1][file]" class="form-control form-control-sm" accept="image/*">
                                </div>
                                <div class="col-md-4">
                                    <select name="photos[2][category]" class="form-select form-select-sm mb-1">
                                        <option value="exterior_front">Depan</option>
                                        <option value="exterior_rear">Belakang</option>
                                        <option value="exterior_left">Kiri</option>
                                        <option value="exterior_right">Kanan</option>
                                        <option value="interior">Interior</option>
                                        <option value="odometer">Odometer</option>
                                        <option value="damage">Kerusakan</option>
                                    </select>
                                    <input type="file" name="photos[2][file]" class="form-control form-control-sm" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><i class="fas fa-info-circle me-2"></i>Panduan</div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Foto odometer wajib jelas</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Foto semua sisi kendaraan</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Catat semua kerusakan yang ada</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Periksa kelengkapan alat</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-3">
        <button type="submit" class="btn btn-success"><i class="fas fa-check me-1"></i>Proses Check-in</button>
        <a href="{{ route('employee.checkin-checkout') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
@endsection
