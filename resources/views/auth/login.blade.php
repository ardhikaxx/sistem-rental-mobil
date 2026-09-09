<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Rental Mobil</title>
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
        .login-logo {
            text-align: center;
            margin-bottom: 32px;
        }
        .login-logo .icon {
            width: 64px;
            height: 64px;
            background: #1a2332;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 28px;
            margin-bottom: 16px;
        }
        .login-logo h4 {
            font-weight: 700;
            color: #1a2332;
            margin-bottom: 4px;
        }
        .login-logo p {
            color: #6c757d;
            font-size: 14px;
        }
        .form-label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 14px;
            border: 1px solid #dee2e6;
            font-size: 15px;
        }
        .form-control:focus {
            border-color: #1a2332;
            box-shadow: 0 0 0 3px rgba(26,35,50,0.1);
        }
        .pin-input-group {
            position: relative;
        }
        .pin-input-group .form-control {
            padding-right: 48px;
            letter-spacing: 8px;
            font-size: 18px;
            font-weight: 600;
            text-align: center;
        }
        .pin-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 4px;
        }
        .pin-toggle:hover {
            color: #1a2332;
        }
        .btn-login {
            background: #1a2332;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            font-size: 15px;
            width: 100%;
            color: #fff;
        }
        .btn-login:hover {
            background: #2c3e50;
            color: #fff;
        }
        .forgot-link {
            text-align: center;
            margin-top: 16px;
        }
        .forgot-link a {
            color: #6c757d;
            font-size: 14px;
            text-decoration: none;
        }
        .forgot-link a:hover {
            color: #1a2332;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
            <div class="icon">
                <i class="fas fa-car"></i>
            </div>
            <h4>Sistem Rental Mobil</h4>
            <p>Masuk ke akun Anda</p>
        </div>

        <form method="POST" action="{{ route('login.post') }}" id="loginForm">
            @csrf
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" value="{{ old('username') }}" required autofocus placeholder="Masukkan username">
            </div>

            <div class="mb-3">
                <label class="form-label">PIN</label>
                <div class="pin-input-group">
                    <input type="password" name="pin" id="pinInput" class="form-control" maxlength="4" pattern="[0-9]{4}" inputmode="numeric" required placeholder="••••">
                    <button type="button" class="pin-toggle" onclick="togglePin()" tabindex="-1">
                        <i class="fas fa-eye" id="pinEye"></i>
                    </button>
                </div>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember" style="font-size:14px;">Ingat saya</label>
            </div>

            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Masuk
            </button>
        </form>

        <div class="forgot-link">
            <a href="{{ route('forgot.pin') }}"><i class="fas fa-key me-1"></i>Lupa PIN?</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function togglePin() {
            const pinInput = document.getElementById('pinInput');
            const pinEye = document.getElementById('pinEye');
            if (pinInput.type === 'password') {
                pinInput.type = 'text';
                pinEye.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                pinInput.type = 'password';
                pinEye.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        document.getElementById('pinInput').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        @if(session('swal_error'))
            Swal.fire({ icon: 'error', title: 'Gagal', text: '{{ session("swal_error") }}', confirmButtonColor: '#1a2332' });
        @endif

        @if(session('swal_success'))
            Swal.fire({ icon: 'success', title: 'Berhasil', text: '{{ session("swal_success") }}', confirmButtonColor: '#1a2332' });
        @endif

        @if($errors->any())
            Swal.fire({ icon: 'error', title: 'Validasi Gagal', text: '{{ $errors->first() }}', confirmButtonColor: '#1a2332' });
        @endif
    </script>
</body>
</html>
