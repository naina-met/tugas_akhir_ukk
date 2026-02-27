<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    // 1. Tampilkan Halaman Welcome Khusus User
    public function showWelcome()
    {
        return view('user.welcome');
    }

    // 2. Tampilkan Form Login User
    public function showLoginForm()
    {
        return view('user.auth.login');
    }

    // 3. Proses Login User
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            
            // PENTING: Arahkan ke dashboard user
            return redirect()->intended('/user/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // 4. Tampilkan Form Register User
    public function showRegisterForm()
    {
        return view('user.auth.register');
    }

    // 5. Proses Register User (Otomatis jadi 'user')
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required', // Sesuaikan dengan kolom database kamu (nama / name)
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6'
        ]);

        User::create([
            'nama' => $request->nama, // Jika di database pakai 'name', ganti jadi 'name'
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // OTOMATIS JADI USER
            'status' => 1,
        ]);

        return redirect()->route('user.login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    // 6. Logout User
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.welcome');
    }
}