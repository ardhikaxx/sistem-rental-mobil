<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\PinResetToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (auth()->check()) {
            return $this->redirectByRole();
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'pin' => 'required|string|size:4',
        ]);

        $user = User::where('username', $request->username)->first();

        if (! $user) {
            return back()->withInput($request->only('username'))->with('swal_error', 'Username tidak ditemukan.');
        }

        if (! $user->is_active) {
            return back()->withInput($request->only('username'))->with('swal_error', 'Akun Anda telah dinonaktifkan. Hubungi admin.');
        }

        if (! Hash::check($request->pin, $user->pin)) {
            return back()->withInput($request->only('username'))->with('swal_error', 'PIN salah. Silakan coba lagi.');
        }

        auth()->login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        AuditLog::log('login', 'auth', 'User berhasil login: '.$user->name);

        return redirect()->route($user->isOwner() ? 'owner.dashboard' : 'employee.dashboard')
            ->with('swal_success', 'Selamat datang, '.$user->name.'!');
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        AuditLog::log('logout', 'auth', 'User logout: '.$user->name);

        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('swal_success', 'Berhasil logout.');
    }

    public function showForgotPin()
    {
        return view('auth.forgot-pin');
    }

    public function verifyPhone(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)->where('is_active', true)->first();

        if (! $user) {
            return back()->with('swal_error', 'Nomor telepon tidak ditemukan atau akun tidak aktif.');
        }

        $token = PinResetToken::create([
            'user_id' => $user->id,
            'phone' => $user->phone,
            'expires_at' => now()->addMinutes(15),
        ]);

        return redirect()->route('reset.pin.form', ['token' => $token->token]);
    }

    public function showResetPinForm($token)
    {
        $resetToken = PinResetToken::where('token', $token)->first();

        if (! $resetToken || ! $resetToken->isValid()) {
            return redirect()->route('login')->with('swal_error', 'Token tidak valid atau sudah expired.');
        }

        return view('auth.reset-pin', ['token' => $token]);
    }

    public function resetPin(Request $request, $token)
    {
        $resetToken = PinResetToken::where('token', $token)->first();

        if (! $resetToken || ! $resetToken->isValid()) {
            return redirect()->route('login')->with('swal_error', 'Token tidak valid atau sudah expired.');
        }

        $request->validate([
            'pin' => 'required|string|size:4|digits:4',
            'pin_confirmation' => 'required|string|same:pin',
        ], [
            'pin.required' => 'PIN baru wajib diisi.',
            'pin.size' => 'PIN harus tepat 4 digit.',
            'pin.digits' => 'PIN harus berupa angka.',
            'pin_confirmation.same' => 'Konfirmasi PIN tidak cocok.',
        ]);

        $user = $resetToken->user;
        $user->update(['pin' => Hash::make($request->pin)]);

        $resetToken->update(['used' => true]);

        AuditLog::log('pin_reset', 'auth', 'PIN berhasil direset untuk user: '.$user->name);

        return redirect()->route('login')->with('swal_success', 'PIN berhasil diubah. Silakan login dengan PIN baru.');
    }

    private function redirectByRole()
    {
        $user = auth()->user();

        return redirect()->route($user->isOwner() ? 'owner.dashboard' : 'employee.dashboard');
    }
}
