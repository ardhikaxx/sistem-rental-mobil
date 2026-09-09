@extends('layouts.app')
@section('title', 'Kalender Booking')

@section('breadcrumb')
<li class="breadcrumb-item active">Kalender</li>
@endsection

@section('content')
<div class="page-header">
    <h4>Kalender Booking</h4>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-info-circle me-2"></i>Keterangan</div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-info me-2" style="width:14px;height:14px;"></span>
                    <small>Menunggu Pembayaran</small>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-primary me-2" style="width:14px;height:14px;"></span>
                    <small>Dibooking</small>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-warning me-2" style="width:14px;height:14px;"></span>
                    <small>Berlangsung</small>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-success me-2" style="width:14px;height:14px;"></span>
                    <small>Selesai</small>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-danger me-2" style="width:14px;height:14px;"></span>
                    <small>Dibatalkan</small>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header"><i class="fas fa-calendar-day me-2"></i>Booking Hari Ini</div>
            <div class="card-body">
                @forelse($todayBookings ?? [] as $b)
                <div class="mb-2 p-2 bg-light rounded">
                    <a href="{{ route('employee.bookings.show', $b) }}"><strong>{{ $b->booking_code }}</strong></a>
                    <div class="small text-muted">{{ $b->vehicle->license_plate }} | {{ $b->customer->name }}</div>
                    <span class="badge {{ $b->status_badge_class }}">{{ $b->status_label }}</span>
                </div>
                @empty
                <p class="text-muted">Tidak ada booking hari ini</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">
<style>
.fc-toolbar-title { font-size: 1.2em !important; }
.fc-button { font-size: 0.85em !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/locale/id.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'id',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: '{{ route("employee.calendar.events") }}',
        eventBackgroundColor: function(arg) {
            const colors = {
                'pending_payment': '#0dcaf0',
                'booked': '#0d6efd',
                'in_progress': '#ffc107',
                'completed': '#198754',
                'cancelled': '#dc3545'
            };
            return colors[arg.event.extendedProps.status] || '#6c757d';
        },
        eventBorderColor: function(arg) {
            const colors = {
                'pending_payment': '#0dcaf0',
                'booked': '#0d6efd',
                'in_progress': '#ffc107',
                'completed': '#198754',
                'cancelled': '#dc3545'
            };
            return colors[arg.event.extendedProps.status] || '#6c757d';
        },
        eventClick: function(info) {
            if (info.event.url) {
                info.jsEvent.preventDefault();
                window.location.href = info.event.url;
            }
        },
        eventDidMount: function(info) {
            const tooltip = info.event.extendedProps.tooltip || info.event.title;
            info.el.setAttribute('title', tooltip);
        }
    });
    calendar.render();
});
</script>
@endpush
