@extends('layouts.app')
@section('title', 'Surat Jalan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('employee.checkin-checkout') }}">Check-in / Check-out</a></li>
<li class="breadcrumb-item active">Surat Jalan</li>
@endsection

@section('content')
<div class="d-print-none mb-3">
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print me-1"></i>Cetak</button>
        <a href="{{ route('employee.checkin-checkout') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="text-center mb-4">
            <h5 class="fw-bold">SURAT JALAN</h5>
            <div class="text-muted">Sistem Rental Mobil</div>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <table class="table table-borderless mb-0">
                    <tr><th width="120" class="text-muted">No. Booking</th><td><strong>{{ $booking->booking_code }}</strong></td></tr>
                    <tr><th class="text-muted">Tanggal Mulai</th><td>{{ $booking->start_date->format('d M Y') }}</td></tr>
                    <tr><th class="text-muted">Tanggal Selesai</th><td>{{ $booking->end_date->format('d M Y') }}</td></tr>
                </table>
            </div>
            <div class="col-6">
                <table class="table table-borderless mb-0">
                    <tr><th width="120" class="text-muted">Pelanggan</th><td>{{ $booking->customer->name }}</td></tr>
                    <tr><th class="text-muted">Telepon</th><td>{{ $booking->customer->phone }}</td></tr>
                    <tr><th class="text-muted">Alamat</th><td>{{ $booking->customer->address ?? '-' }}</td></tr>
                </table>
            </div>
        </div>

        <hr>

        <div class="row mb-4">
            <div class="col-6">
                <h6 class="fw-bold">Informasi Kendaraan</h6>
                <table class="table table-borderless mb-0">
                    <tr><th width="120" class="text-muted">Merek/Model</th><td>{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</td></tr>
                    <tr><th class="text-muted">No. Plat</th><td><strong>{{ $booking->vehicle->license_plate }}</strong></td></tr>
                    <tr><th class="text-muted">Tahun</th><td>{{ $booking->vehicle->year }}</td></tr>
                    <tr><th class="text-muted">Transmisi</th><td>{{ ucfirst($booking->vehicle->transmission) }}</td></tr>
                </table>
            </div>
            <div class="col-6">
                <h6 class="fw-bold">Kondisi Kendaraan</h6>
                <table class="table table-borderless mb-0">
                    <tr><th width="120" class="text-muted">Odometer</th><td>{{ $booking->inspection_checkin->odometer ?? '-' }} km</td></tr>
                    <tr><th class="text-muted">Bahan Bakar</th><td>{{ ucfirst(str_replace('_', ' ', $booking->inspection_checkin->fuel_level ?? '-')) }}</td></tr>
                    <tr><th class="text-muted">Kondisi Eksterior</th><td>{{ Str::limit($booking->inspection_checkin->exterior_condition ?? '-', 50) }}</td></tr>
                    <tr><th class="text-muted">Kondisi Interior</th><td>{{ Str::limit($booking->inspection_checkin->interior_condition ?? '-', 50) }}</td></tr>
                </table>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-6">
                <div class="mt-4">
                    <div class="text-muted small">Penerima Kendaraan</div>
                    <div style="border-top:1px solid #000;width:200px;margin-top:50px;padding-top:5px;">{{ $booking->customer->name }}</div>
                </div>
            </div>
            <div class="col-6">
                <div class="mt-4">
                    <div class="text-muted small">Penanggung Jawab</div>
                    <div style="border-top:1px solid #000;width:200px;margin-top:50px;padding-top:5px;">{{ auth()->user()->name }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .d-print-none { display: none !important; }
    .sidebar, .topbar, .footer { display: none !important; }
    .main-content { margin-left: 0 !important; padding: 0 !important; }
    .content-wrapper { padding: 0 !important; }
    .card { border: none !important; box-shadow: none !important; }
    .breadcrumb { display: none !important; }
    body { font-size: 12px; }
}
</style>
@endpush
@endsection
