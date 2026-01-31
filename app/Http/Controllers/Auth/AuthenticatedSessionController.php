<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // 3. CEK STATUS USER
        if (Auth::user()->status == 0) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'Akun Anda nonaktif. Silakan hubungi admin.',
            ]);
        }

        // 4. Regenerate session
        $request->session()->regenerate();

        // 5. Redirect
        return redirect()->intended('/dashboard');
    }

    /**
     * Logout
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
