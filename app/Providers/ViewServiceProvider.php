<?php

namespace App\Providers;

use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Bagikan data notifikasi peminjaman ke view navigation
        View::composer('layouts.navigation', function ($view) {
            $user = Auth::user();
            // Cek apakah user login dan memiliki role 'admin' atau 'superadmin'
            if ($user && in_array(strtolower($user->role), ['admin', 'superadmin'])) {
                $pendingLoans = Peminjaman::where('status', 'pending')
                    ->with(['user', 'item'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                $view->with('pendingLoans', $pendingLoans);
            } else {
                $view->with('pendingLoans', collect()); // Kirim koleksi kosong jika bukan admin
            }
        });
    }
}