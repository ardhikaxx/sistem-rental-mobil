<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Rental Mobil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <div class="d-flex" id="app-wrapper">
        @include('layouts.sidebar')

        <div class="main-content" id="mainContent">
            @include('layouts.topbar')

            <div class="content-wrapper">
                @if(session('swal_success'))
                    <div class="d-none" id="swalSuccess" data-message="{{ session('swal_success') }}"></div>
                @endif
                @if(session('swal_error'))
                    <div class="d-none" id="swalError" data-message="{{ session('swal_error') }}"></div>
                @endif
                @if(session('swal_info'))
                    <div class="d-none" id="swalInfo" data-message="{{ session('swal_info') }}"></div>
                @endif

                @yield('content')
            </div>

            @include('layouts.footer')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
    <script>
        document.querySelectorAll('.sidebar-link').forEach(function(link) {
            if (link.href === window.location.href || window.location.href.startsWith(link.href + '/')) {
                link.classList.add('active');
            }
        });

        document.querySelectorAll('[data-toggle-sidebar]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('app-wrapper').classList.toggle('sidebar-open');
            });
        });

        function showSwal(type, message) {
            Swal.fire({ icon: type, title: type === 'success' ? 'Berhasil' : type === 'error' ? 'Gagal' : 'Info', text: message, confirmButtonColor: '#1a2332', timer: type === 'success' ? 2000 : undefined });
        }

        var el;
        if (el = document.getElementById('swalSuccess')) showSwal('success', el.dataset.message);
        if (el = document.getElementById('swalError')) showSwal('error', el.dataset.message);
        if (el = document.getElementById('swalInfo')) showSwal('info', el.dataset.message);
    </script>
</body>
</html>
