@extends('layouts.app')
@section('title', 'Detail Pelanggan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('owner.customers.index') }}">Pelanggan</a></li>
<li class="breadcrumb-item active">{{ $customer->name }}</li>
@endsection

@section('content')
<div class="page-header">
    <h4>{{ $customer->name }} <span class="badge {{ $customer->verification_badge_class }} ms-2">{{ $customer->verification_status_label }}</span></h4>
    <div class="d-flex gap-2">
        @if($customer->verification_status === 'unverified')
        <form method="POST" action="{{ route('owner.customers.verify', $customer) }}" class="d-inline">
            @csrf <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check me-1"></i>Verifikasi</button>
        </form>
        @endif
        <a href="{{ route('owner.customers.edit', $customer) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit me-1"></i>Edit</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-user me-2"></i>Data Diri</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="160">Nama</th><td>{{ $customer->name }}</td></tr>
                    <tr><th>Telepon</th><td>{{ $customer->phone }}</td></tr>
                    <tr><th>Alamat</th><td>{{ $customer->address ?? '-' }}</td></tr>
                    <tr><th>Jenis Identitas</th><td>{{ $customer->identity_type ?? '-' }}</td></tr>
                    <tr><th>No. Identitas</th><td>{{ $customer->identity_number ?? '-' }}</td></tr>
                    <tr><th>No. SIM</th><td>{{ $customer->sim_number ?? '-' }}</td></tr>
                    <tr><th>Kontak Darurat</th><td>{{ $customer->emergency_contact_name ?? '-' }} {{ $customer->emergency_contact_phone ? '('. $customer->emergency_contact_phone .')' : '' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-file-alt me-2"></i>Dokumen</div>
            <div class="card-body">
                @forelse($customer->documents as $doc)
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                    <div><strong>{{ $doc->document_type }}</strong> {{ $doc->document_number ? "- {$doc->document_number}" : '' }}</div>
                    <span class="badge {{ $doc->status==='verified'?'bg-success':($doc->status==='rejected'?'bg-danger':'bg-secondary') }}">{{ ucfirst($doc->status) }}</span>
                </div>
                @empty
                <p class="text-muted">Belum ada dokumen</p>
                @endforelse
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header"><i class="fas fa-file-invoice me-2"></i>Riwayat Booking</div>
            <div class="card-body">
                @forelse($customer->bookings->take(5) as $b)
                <div class="d-flex justify-content-between mb-2">
                    <a href="{{ route('owner.bookings.show', $b) }}">{{ $b->booking_code }}</a>
                    <span class="badge {{ $b->status_badge_class }}">{{ $b->status_label }}</span>
                </div>
                @empty
                <p class="text-muted">Belum ada booking</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
