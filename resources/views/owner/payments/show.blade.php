@extends('layouts.app')
@section('title', 'Detail Pembayaran')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('owner.payments.index') }}">Pembayaran</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Detail Pembayaran</h4>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-money-bill me-2"></i>Informasi Pembayaran</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="150">Tanggal Bayar</th><td>{{ $payment->created_at ? $payment->created_at->format('d M Y H:i') : '-' }}</td></tr>
                    <tr><th>Jumlah</th><td><strong class="text-success">Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></td></tr>
                    <tr><th>Metode</th><td>{{ $payment->method_label }}</td></tr>
                    <tr><th>Tipe</th><td><span class="badge bg-info">{{ $payment->type_label }}</span></td></tr>
                    <tr><th>Status</th><td><span class="badge {{ $payment->status_badge_class }}">{{ $payment->status_label }}</span></td></tr>
                    <tr><th>No. Transaksi</th><td>{{ $payment->transaction_code }}</td></tr>
                    @if($payment->bank_name)
                    <tr><th>Bank</th><td>{{ $payment->bank_name }} ({{ $payment->account_number ?? '-' }})</td></tr>
                    @endif
                    <tr><th>Catatan</th><td>{{ $payment->notes ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-file-invoice me-2"></i>Informasi Booking</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="150">Kode Booking</th><td><a href="{{ route('owner.bookings.show', $payment->booking) }}">{{ $payment->booking->booking_code }}</a></td></tr>
                    <tr><th>Pelanggan</th><td>{{ $payment->booking->customer->name ?? '-' }}</td></tr>
                    <tr><th>Kendaraan</th><td>{{ $payment->booking->vehicle->brand ?? '' }} {{ $payment->booking->vehicle->model ?? '' }} ({{ $payment->booking->vehicle->license_plate ?? '-' }})</td></tr>
                    <tr><th>Periode</th><td>{{ $payment->booking->start_date ? $payment->booking->start_date->format('d M Y') : '-' }} - {{ $payment->booking->end_date ? $payment->booking->end_date->format('d M Y') : '-' }}</td></tr>
                    <tr><th>Total Biaya</th><td>Rp {{ number_format($payment->booking->total_amount, 0, ',', '.') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection