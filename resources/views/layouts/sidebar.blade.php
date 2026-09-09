<nav class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <i class="fas fa-car"></i>
            <span>Rental Mobil</span>
        </div>
        <button class="sidebar-close d-lg-none" onclick="document.getElementById('app-wrapper').classList.remove('sidebar-open')">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="sidebar-user">
        <div class="sidebar-user-avatar">
            <i class="fas fa-user"></i>
        </div>
        <div class="sidebar-user-info">
            <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
            <div class="sidebar-user-role">{{ auth()->user()->isOwner() ? 'Owner/Admin' : 'Karyawan' }}</div>
        </div>
    </div>

    <div class="sidebar-menu">
        <ul class="nav flex-column">
            @if(auth()->user()->isOwner())
                <li class="sidebar-menu-item">
                    <a href="{{ route('owner.dashboard') }}" class="sidebar-link">
                        <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('owner.calendar') }}" class="sidebar-link">
                        <i class="fas fa-calendar-alt"></i><span>Kalender Armada</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('owner.bookings.index') }}" class="sidebar-link">
                        <i class="fas fa-file-invoice"></i><span>Booking</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('owner.customers.index') }}" class="sidebar-link">
                        <i class="fas fa-users"></i><span>Pelanggan</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('owner.vehicles.index') }}" class="sidebar-link">
                        <i class="fas fa-car-side"></i><span>Armada</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('owner.payments.index') }}" class="sidebar-link">
                        <i class="fas fa-money-bill-wave"></i><span>Pembayaran</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('owner.expenses.index') }}" class="sidebar-link">
                        <i class="fas fa-receipt"></i><span>Pengeluaran</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('owner.maintenance.index') }}" class="sidebar-link">
                        <i class="fas fa-tools"></i><span>Maintenance</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('owner.employees.index') }}" class="sidebar-link">
                        <i class="fas fa-user-tie"></i><span>Karyawan</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('owner.approvals.index') }}" class="sidebar-link">
                        <i class="fas fa-check-double"></i><span>Approval</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('owner.reports.index') }}" class="sidebar-link">
                        <i class="fas fa-chart-bar"></i><span>Laporan Keuangan</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('owner.audit-logs.index') }}" class="sidebar-link">
                        <i class="fas fa-history"></i><span>Audit Log</span>
                    </a>
                </li>
            @else
                <li class="sidebar-menu-item">
                    <a href="{{ route('employee.dashboard') }}" class="sidebar-link">
                        <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('employee.calendar') }}" class="sidebar-link">
                        <i class="fas fa-calendar-alt"></i><span>Kalender Armada</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('employee.bookings.index') }}" class="sidebar-link">
                        <i class="fas fa-file-invoice"></i><span>Booking</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('employee.customers.index') }}" class="sidebar-link">
                        <i class="fas fa-users"></i><span>Pelanggan</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('employee.checkin-checkout') }}" class="sidebar-link">
                        <i class="fas fa-exchange-alt"></i><span>Check-in / Check-out</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('employee.payments.index') }}" class="sidebar-link">
                        <i class="fas fa-money-bill-wave"></i><span>Kasir</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('employee.expenses.index') }}" class="sidebar-link">
                        <i class="fas fa-receipt"></i><span>Pengeluaran</span>
                    </a>
                </li>
            @endif

            <li class="sidebar-menu-item sidebar-menu-divider"></li>
            <li class="sidebar-menu-item">
                <form method="POST" action="{{ route('logout') }}" id="logoutForm" class="d-inline">
                    @csrf
                    <a href="#" class="sidebar-link" onclick="event.preventDefault();confirmLogout();">
                        <i class="fas fa-sign-out-alt"></i><span>Logout</span>
                    </a>
                </form>
            </li>
        </ul>
    </div>
</nav>

<script>
function confirmLogout() {
    Swal.fire({
        title: 'Konfirmasi Logout',
        text: 'Apakah Anda yakin ingin keluar?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#1a2332',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logoutForm').submit();
        }
    });
}
</script>
