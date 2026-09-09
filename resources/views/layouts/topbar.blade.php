<nav class="topbar">
    <div class="topbar-left">
        <button class="btn btn-link sidebar-toggle d-lg-none" data-toggle-sidebar>
            <i class="fas fa-bars"></i>
        </button>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ auth()->user()->isOwner() ? route('owner.dashboard') : route('employee.dashboard') }}">Home</a></li>
                @yield('breadcrumb')
            </ol>
        </nav>
    </div>
    <div class="topbar-right">
        <div class="dropdown">
            <button class="btn btn-link dropdown-toggle topbar-user-btn" data-bs-toggle="dropdown">
                <span class="topbar-user-name">{{ auth()->user()->name }}</span>
                <i class="fas fa-chevron-down ms-1"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><span class="dropdown-item-text text-muted small">{{ auth()->user()->role === 'owner' ? 'Owner/Admin' : 'Karyawan' }}</span></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
