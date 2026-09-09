<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lupa PIN - Rental Mobil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f6fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            padding: 24px 16px;
            margin: 0;
        }
        .login-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 420px;
            padding: 40px;
            margin: auto;
        }
        .login-logo { text-align: center; margin-bottom: 32px; }
        .login-logo .icon { width: 64px; height: 64px; background: #1a2332; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; color: #fff; font-size: 28px; margin-bottom: 16px; }
        .login-logo h4 { font-weight: 700; color: #1a2332; margin-bottom: 4px; }
        .login-logo p { color: #6c757d; font-size: 14px; }
        .form-label { font-weight: 600; color: #333; font-size: 14px; }
        .form-control { border-radius: 8px; padding: 12px 14px; border: 1px solid #dee2e6; font-size: 15px; }
        .form-control:focus { border-color: #1a2332; box-shadow: 0 0 0 3px rgba(26,35,50,0.1); }
        .btn-login { background: #1a2332; border: none; border-radius: 8px; padding: 12px; font-weight: 600; font-size: 15px; width: 100%; color: #fff; }
        .btn-login:hover { background: #2c3e50; color: #fff; }
        .back-link { text-align: center; margin-top: 16px; }
        .back-link a { color: #6c757d; font-size: 14px; text-decoration: none; }
        .back-link a:hover { color: #1a2332; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
            <div class="icon"><i class="fas fa-key"></i></div>
            <h4>Lupa PIN</h4>
            <p>Masukkan nomor telepon terdaftar untuk mereset PIN</p>
        </div>
        <form method="POST" action="{{ route('forgot.pin.verify') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" name="phone" class="form-control" required placeholder="08xxxxxxxxxx" value="{{ old('phone') }}">
            </div>
            <button type="submit" class="btn btn-login"><i class="fas fa-check me-2"></i>Konfirmasi</button>
        </form>
        <div class="back-link">
            <a href="{{ route('login') }}"><i class="fas fa-arrow-left me-1"></i>Kembali ke Login</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('swal_error'))
            Swal.fire({ icon: 'error', title: 'Gagal', text: '{{ session("swal_error") }}', confirmButtonColor: '#1a2332' });
        @endif
    </script>
</body>
</html>
