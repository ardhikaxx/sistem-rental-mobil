<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleEmployee
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        if (! auth()->user()->isEmployee()) {
            abort(403, 'Akses ditolak. Hanya karyawan yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
