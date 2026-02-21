<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminApproval
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya check jika user sudah login
        if (Auth::check()) {
            $user = Auth::user();

            // 1. Check status user (harus aktif)
            if (!$user->status) {
                Auth::logout();
                return redirect('/login')->with('error', 'Akun Anda telah dinonaktifkan.');
            }

            // 2. Check approval untuk Admin role (Superadmin tidak perlu)
            if ($user->role === 'Admin' && !$user->approved) {
                Auth::logout();
                return redirect('/login')->with('error', 'Akun admin Anda belum disetujui oleh superadmin. Silakan hubungi superadmin.');
            }
        }

        return $next($request);
    }
}
