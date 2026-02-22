<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show login view
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba login
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        // 3. CEK APPROVAL STATUS UNTUK ADMIN ROLE (Superadmin tidak perlu approval)
        $user = Auth::user();
        if ($user && $user->role === 'Admin') {
            // Admin HARUS disetujui sebelum login
            if (!$user->approved) {
                Auth::logout();

                throw ValidationException::withMessages([
                    'email' => 'Akun admin Anda belum disetujui oleh superadmin.',
                ]);
            }

            // Jika Admin approved, otomatis aktifkan status (bisa login ulang setelah logout)
            if ($user instanceof User) {
                $user->update(['status' => true]);
            }
        }

        // 4. CEK STATUS USER - Harus aktif (status = true)
        // Gunakan $user yang sudah diambil sebelumnya untuk konsistensi
        if (!$user || !$user->status) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'Akun Anda nonaktif. Silakan hubungi admin.',
            ]);
        }

        // 5. Regenerate session
        $request->session()->regenerate();

        // 6. Redirect
        return redirect()->intended('/dashboard');
    }

    /**
     * Logout
     */
    public function destroy(Request $request)
    {
        // Jika user adalah Admin, set status jadi non-aktif saat logout
        if (Auth::check()) {
            $user = Auth::user();
            if ($user && $user->role === 'Admin' && $user instanceof User) {
                $user->update(['status' => false]);
            }
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
